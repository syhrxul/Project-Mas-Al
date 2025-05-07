<x-filament-panels::page>
    <x-filament::section>
        {{ $this->form }}

        <div class="mt-4">
            <div class="text-xl font-bold mb-4">
                Data Penyewaan Bulan {{ Carbon\Carbon::parse($selectedMonth)->format('F Y') }}
            </div>

            <div class="space-y-4">
                @foreach($this->getViewData()['rentals']->groupBy('product_id') as $productId => $rentals)
                    <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg">
                        <div class="font-medium">{{ $rentals->first()->product->name }}</div>
                        <div class="text-sm text-gray-500">
                            Jumlah Disewa: {{ $rentals->sum('quantity') }} unit
                        </div>
                        <div class="text-sm text-gray-500">
                            Total Pendapatan: Rp {{ number_format($rentals->sum('total_price'), 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach

                <div class="mt-4 p-4 bg-primary-50 dark:bg-primary-900 rounded-lg">
                    <div class="text-lg font-bold">
                        Total Pendapatan Bulan Ini: Rp {{ number_format($this->getViewData()['totalIncome'], 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-panels::page>