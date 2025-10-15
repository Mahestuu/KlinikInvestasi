<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use Filament\Notifications\Notification;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Models\Kbli;
use App\Models\Pbumku;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;


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
                        ->reactive(),
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
                            $set('selected_count', $count);
                        }),
                    \Filament\Forms\Components\Placeholder::make('selected_count_info')
                        ->label('')
                        ->content(function ($get) {
                            $count = $get('selected_count') ?? 0;
                            if ($count > 0) {
                                return "✅ {$count} PBUMKU dipilih untuk di-export";
                            }
                            return "ℹ️ Pilih PBUMKU yang ingin di-export";
                        })
                        ->visible(fn($get) => $get('mode') === 'multiple'),
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
                ->color('success')
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
                        ->reactive(),
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
                            $set('selected_kbli_count', $count);
                        }),
                    \Filament\Forms\Components\Placeholder::make('selected_kbli_count_info')
                        ->label('')
                        ->content(function ($get) {
                            $count = $get('selected_kbli_count') ?? 0;
                            if ($count > 0) {
                                return "✅ {$count} KBLI dipilih untuk di-export";
                            }
                            return "ℹ️ Pilih KBLI yang ingin di-export";
                        })
                        ->visible(fn($get) => $get('mode') === 'multiple'),
                ])
                ->action(function (array $data): void {
                    $mode = $data['mode'];
                    $kbliIds = $mode === 'all' ? 'all' : ($mode === 'single' ? $data['kbli_id'] : $data['kbli_ids']);
                    Notification::make()
                        ->title('PDF sedang di-generate')
                        ->success()
                        ->send();
                    redirect()->route('kbli.export-pdf', ['kbli_id' => $kbliIds]);
                }),
        ];
    }
}
