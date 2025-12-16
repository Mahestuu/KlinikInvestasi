<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Models\Kbli;
use App\Models\Pbumku;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;


class Dashboard extends BaseDashboard
{
    public function getPbumkuOptions()
    {
        return Cache::remember('pbumku_options', 300, function () {
            return Pbumku::with('dinas')->get()->mapWithKeys(function ($pbumku) {
                return [$pbumku->pbumku_id => $pbumku->nama . ' (' . ($pbumku->dinas->nama ?? 'Tidak ada dinas') . ')'];
            });
        });
    }

    public function getKbliOptions()
    {
        return Cache::remember('kbli_options', 300, function () {
            return Kbli::with(['dinas', 'kategoriKbli'])->get()->mapWithKeys(function ($kbli) {
                return [$kbli->kbli_id => $kbli->kode . ' - ' . $kbli->nama . ' (' . ($kbli->dinas->nama ?? 'Tidak ada dinas') . ')'];
            });
        });
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_pdf_pbumku')
                ->label('Export PDF PBUMKU')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->form([
                    Select::make('mode')
                        ->label('Mode Export')
                        ->options([
                            'all' => 'Semua PBUMKU',
                            'single' => 'Satu PBUMKU',
                            'multiple' => 'Beberapa PBUMKU',
                        ])
                        ->default('all')
                        ->required()
                        ->live(),
                    Select::make('pbumku_id')
                        ->label('Pilih PBUMKU')
                        ->options($this->getPbumkuOptions())
                        ->searchable()
                        ->placeholder('Cari dan pilih PBUMKU...')
                        ->visible(fn($get) => $get('mode') === 'single')
                        ->required(fn($get) => $get('mode') === 'single'),
                    CheckboxList::make('pbumku_ids')
                        ->label('Pilih PBUMKU (Gunakan search untuk mencari)')
                        ->options($this->getPbumkuOptions())
                        ->searchable()
                        ->visible(fn($get) => $get('mode') === 'multiple')
                        ->required(fn($get) => $get('mode') === 'multiple')
                        ->columns(1)
                        ->gridDirection('row')
                        ->bulkToggleable()
                        ->live()
                        ->afterStateUpdated(function ($state, $set) {
                            $count = is_array($state) ? count($state) : 0;
                            $set('selected_count_info', $count > 0
                                ? "✅ {$count} PBUMKU dipilih untuk di-export"
                                : "ℹ️ Pilih PBUMKU yang ingin di-export");
                        }),
                    TextInput::make('selected_count_info')
                        ->label('')
                        ->default('ℹ️ Pilih PBUMKU yang ingin di-export')
                        ->dehydrated(false)
                        ->disabled()
                        ->visible(fn($get) => $get('mode') === 'multiple')
                        ->extraAttributes(['class' => 'text-sm font-medium text-gray-600'])
                        ->live(onBlur: false),
                ])
                ->action(function (array $data): void {
                    $mode = $data['mode'];
                    $pbumkuIds = $mode === 'all' ? [] : ($mode === 'single' ? [$data['pbumku_id']] : $data['pbumku_ids']);
                    Notification::make()
                        ->title('PDF sedang di-generate')
                        ->success()
                        ->send();
                    redirect()->route('pbumku.pdf.export', ['pbumku_ids' => $pbumkuIds]);
                }),
            // Action Export PDF KBLI dengan fitur lengkap
            Action::make('export_pdf_kbli')
                ->label('Export PDF KBLI')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->form([
                    Select::make('mode')
                        ->label('Mode Export')
                        ->options([
                            'all' => 'Semua KBLI',
                            'single' => 'Satu KBLI',
                            'multiple' => 'Beberapa KBLI',
                        ])
                        ->default('all')
                        ->required()
                        ->live(),
                    Select::make('kbli_id')
                        ->label('Pilih KBLI')
                        ->options($this->getKbliOptions())
                        ->searchable()
                        ->placeholder('Cari dan pilih KBLI...')
                        ->visible(fn($get) => $get('mode') === 'single')
                        ->required(fn($get) => $get('mode') === 'single'),
                    CheckboxList::make('kbli_ids')
                        ->label('Pilih KBLI (Gunakan search untuk mencari)')
                        ->options($this->getKbliOptions())
                        ->searchable()
                        ->visible(fn($get) => $get('mode') === 'multiple')
                        ->required(fn($get) => $get('mode') === 'multiple')
                        ->columns(1)
                        ->gridDirection('row')
                        ->bulkToggleable()
                        ->live()
                        ->afterStateUpdated(function ($state, $set) {
                            $count = is_array($state) ? count($state) : 0;
                            $set('selected_kbli_count_info', $count > 0
                                ? "✅ {$count} KBLI dipilih untuk di-export"
                                : "ℹ️ Pilih KBLI yang ingin di-export");
                        }),
                    TextInput::make('selected_kbli_count_info')
                        ->label('')
                        ->default('ℹ️ Pilih KBLI yang ingin di-export')
                        ->dehydrated(false)
                        ->disabled()
                        ->visible(fn($get) => $get('mode') === 'multiple')
                        ->extraAttributes(['class' => 'text-sm font-medium text-gray-600'])
                        ->live(onBlur: false),
                ])
                ->action(function (array $data) {
                    try {
                        $mode = $data['mode'];
                        $kbliIds = $mode === 'all' ? 'all' : ($mode === 'single' ? $data['kbli_id'] : $data['kbli_ids']);

                        // Jika array, convert ke format query string
                        if (is_array($kbliIds)) {
                            $kbliIds = implode(',', $kbliIds);
                        }

                        // Buat URL untuk export PDF dengan parameter - pastikan include locale
                        $locale = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale();
                        // Gunakan route helper dengan locale yang benar
                        $baseUrl = route('kbli.export-pdf', [], false);
                        $url = url($locale . $baseUrl . '?kbli_id=' . urlencode($kbliIds));

                        \Illuminate\Support\Facades\Log::info('Export PDF URL', ['url' => $url, 'kbli_ids' => $kbliIds]);

                        // Gunakan redirect untuk trigger download
                        // Browser akan handle download otomatis
                        return redirect($url);
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error')
                            ->body('Gagal generate PDF: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->successNotificationTitle('PDF sedang di-generate...'),
            // Action Import PDF KBLI
            Action::make('import_pdf_kbli')
                ->label('Import PDF KBLI')
                ->icon('heroicon-o-document-arrow-up')
                ->color('warning')
                ->form([
                    FileUpload::make('pdf_file')
                        ->label('Upload File PDF')
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(10240) // 10MB
                        ->required()
                        ->disk('public')
                        ->directory('imports/kbli')
                        ->visibility('private')
                        ->helperText('Upload file PDF yang berisi data KBLI. Maksimal 10MB. Format harus berisi kode KBLI, nama, persyaratan, dan sub persyaratan.')
                        ->columnSpanFull(),
                ])
                ->action(function (array $data): void {
                    try {
                        if (empty($data['pdf_file'])) {
                            Notification::make()
                                ->title('Error')
                                ->body('File PDF tidak ditemukan')
                                ->danger()
                                ->send();
                            return;
                        }

                        // Get full path from storage
                        $filePath = Storage::disk('public')->path($data['pdf_file']);

                        if (!file_exists($filePath)) {
                            Notification::make()
                                ->title('Error')
                                ->body('File PDF tidak dapat diakses')
                                ->danger()
                                ->send();
                            return;
                        }

                        $controller = new \App\Http\Controllers\ImportKbliFromPdfController();
                        $request = new \Illuminate\Http\Request(['pdf_file' => $filePath]);
                        $response = $controller->import($request);
                        $result = json_decode($response->getContent(), true);

                        // Hapus file setelah import
                        if (Storage::disk('public')->exists($data['pdf_file'])) {
                            Storage::disk('public')->delete($data['pdf_file']);
                        }

                        if ($result['success'] ?? false) {
                            Notification::make()
                                ->title('Import Berhasil')
                                ->body($result['message'] . '. Total: ' . ($result['imported_count'] ?? 0) . ' KBLI')
                                ->success()
                                ->send();

                            // Clear cache
                            Cache::forget('kbli_options');
                        } else {
                            Notification::make()
                                ->title('Import Gagal')
                                ->body($result['message'] ?? 'Terjadi kesalahan saat mengimpor PDF')
                                ->danger()
                                ->send();
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error')
                            ->body('Terjadi kesalahan: ' . $e->getMessage())
                            ->danger()
                            ->send();
                        Log::error('Import PDF Error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                    }
                })
                ->modalHeading('Import KBLI dari PDF')
                ->modalSubmitActionLabel('Import')
                ->modalCancelActionLabel('Batal')
                ->modalWidth('md'),
            // Action Import PDF PBUMKU
            Action::make('import_pdf_pbumku')
                ->label('Import PDF PBUMKU')
                ->icon('heroicon-o-document-arrow-up')
                ->color('warning')
                ->form([
                    FileUpload::make('pdf_file')
                        ->label('Upload File PDF')
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(10240) // 10MB
                        ->required()
                        ->disk('public')
                        ->directory('imports/pbumku')
                        ->visibility('private')
                        ->helperText('Upload file PDF yang berisi data PBUMKU. Maksimal 10MB. Format harus berisi nama PBUMKU, persyaratan, dan sub persyaratan.')
                        ->columnSpanFull(),
                ])
                ->action(function (array $data): void {
                    try {
                        if (empty($data['pdf_file'])) {
                            Notification::make()
                                ->title('Error')
                                ->body('File PDF tidak ditemukan')
                                ->danger()
                                ->send();
                            return;
                        }

                        // Get full path from storage
                        $filePath = Storage::disk('public')->path($data['pdf_file']);

                        if (!file_exists($filePath)) {
                            Notification::make()
                                ->title('Error')
                                ->body('File PDF tidak dapat diakses')
                                ->danger()
                                ->send();
                            return;
                        }

                        // Notifikasi bahwa proses import sedang berjalan
                        Notification::make()
                            ->title('Sedang Memproses...')
                            ->body('File PDF sedang diproses. Mohon tunggu sebentar.')
                            ->info()
                            ->send();

                        $controller = new \App\Http\Controllers\ImportPbumkuFromPdfController();
                        $request = new \Illuminate\Http\Request(['pdf_file' => $filePath]);
                        $response = $controller->import($request);
                        $result = json_decode($response->getContent(), true);

                        // Hapus file setelah import
                        if (Storage::disk('public')->exists($data['pdf_file'])) {
                            Storage::disk('public')->delete($data['pdf_file']);
                        }

                        if ($result['success'] ?? false) {
                            Notification::make()
                                ->title('Import Berhasil')
                                ->body($result['message'] . '. Total: ' . ($result['imported_count'] ?? 0) . ' PBUMKU')
                                ->success()
                                ->send();

                            // Clear cache
                            Cache::forget('pbumku_options');
                        } else {
                            Notification::make()
                                ->title('Import Gagal')
                                ->body($result['message'] ?? 'Terjadi kesalahan saat mengimpor PDF')
                                ->danger()
                                ->send();
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error')
                            ->body('Terjadi kesalahan: ' . $e->getMessage())
                            ->danger()
                            ->send();
                        Log::error('Import PDF PBUMKU Error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                    }
                })
                ->modalHeading('Import PBUMKU dari PDF')
                ->modalSubmitActionLabel('Import')
                ->modalCancelActionLabel('Batal')
                ->modalWidth('md'),

        ];
    }
}
