<?php

namespace App\Filament\Pages\Admin;

use App\Models\Rental;
use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Carbon\Carbon;

class MonthlyReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Laporan Bulanan';
    protected static ?string $title = 'Laporan Bulanan AL Management';
    protected static ?string $navigationGroup = 'Management';
    protected static ?int $navigationSort = 2;

    public ?string $selectedMonth = null;

    public function mount()
    {
        $this->selectedMonth = now()->format('Y-m');
    }

    protected function getMonthOptions(): array
    {
        $options = [];
        $currentDate = Carbon::now();
        $startDate = Carbon::now()->subMonths(11);

        while ($startDate->lte($currentDate)) {
            $key = $startDate->format('Y-m');
            $label = $startDate->format('F Y');
            $options[$key] = $label;
            $startDate->addMonth();
        }

        return $options;
    }

    public function getViewData(): array
    {
        $month = Carbon::parse($this->selectedMonth);
        
        $rentals = Rental::query()
            ->where('status', 'confirmed')
            ->whereYear('created_at', $month->year)
            ->whereMonth('created_at', $month->month)
            ->with('product')
            ->get();

        $totalIncome = $rentals->sum('total_price');
        
        return [
            'rentals' => $rentals,
            'totalIncome' => $totalIncome,
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('selectedMonth')
                ->label('Pilih Bulan')
                ->options($this->getMonthOptions())
                ->default(now()->format('Y-m'))
                ->reactive()
                ->required(),
        ];
    }

    protected static string $view = 'filament.pages.admin.monthly-report';
}