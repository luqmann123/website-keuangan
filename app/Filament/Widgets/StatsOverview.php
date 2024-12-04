<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use App\Models\Transaksi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;
    protected function getStats(): array
    {
    
        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
        Carbon::parse($this->filters['startDate']) :
        null;

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
        Carbon::parse($this->filters['endDate']) :
        now();

        $pemasukan   = Transaksi::incomes()
                     ->whereBetween('date_transaksi', [$startDate, $endDate])
                     ->sum('amount');

        $pengeluaran = Transaksi::expenses()->get()
                     ->whereBetween('date_transaksi', [$startDate, $endDate])
                     ->sum('amount');

        return [
            Stat::make('Total Pemasukan', 'Rp. ' . number_format($pemasukan, 0, ',', '.')),
            Stat::make('Total Pengeluaran', 'Rp. ' . number_format($pengeluaran, 0, ',', '.')),
            Stat::make('Selisih', 'Rp. ' . number_format($pemasukan - $pengeluaran, 0, ',', '.')),
        ];
                    
    }
}
