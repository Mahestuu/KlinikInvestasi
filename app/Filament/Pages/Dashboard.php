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


class Dashboard extends BaseDashboard
{
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
                        ->options(Pbumku::pluck('nama', 'pbumku_id'))
                        ->placeholder('Pilih PBUMKU...')
                        ->visible(fn($get) => $get('mode') === 'single')
                        ->required(fn($get) => $get('mode') === 'single'),
                    CheckboxList::make('pbumku_id')
                        ->label('Pilih PBUMKU')
                        ->options(Pbumku::pluck('nama', 'pbumku_id'))
                        ->visible(fn($get) => $get('mode') === 'multiple')
                        ->required(fn($get) => $get('mode') === 'multiple'),
                ])
                ->action(function (array $data): void {
                    $mode = $data['mode'];
                    $pbumkuId = $mode === 'all' ? 'all' : $data['pbumku_id'];
                    Notification::make()
                        ->title('PDF sedang di-generate')
                        ->success()
                        ->send();
                    redirect()->route('pbumku.export-pdf', ['pbumku_id' => $pbumkuId]);
                }),
            // Action Export PDF KBLI dari iterasi sebelumnya
            Action::make('export_pdf_kbli')
                ->label('Export PDF KBLI')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->form([
                    Select::make('kbli_id')
                        ->label('Pilih KBLI')
                        ->options(Kbli::pluck('nama', 'kbli_id')->prepend('Semua KBLI', 'all'))
                        ->placeholder('Pilih KBLI...')
                        ->default('all')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $kbliId = $data['kbli_id'];
                    Notification::make()
                        ->title('PDF sedang di-generate')
                        ->success()
                        ->send();
                    redirect()->route('kbli.export-pdf', ['kbli_id' => $kbliId]);
                }),
        ];
    }
}
