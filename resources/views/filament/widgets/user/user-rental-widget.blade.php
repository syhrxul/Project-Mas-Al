<x-filament-widgets::widget class="mt-0">
    <x-filament::section class="mt-0">
        <div class="space-y-2">
            <div class="text-xl font-bold">
                Status Penyewaan Aktif
            </div>

            @php
                $rentals = $this->getRentals();
            @endphp

            @if($rentals->isEmpty())
                <div class="text-center py-2">
                    <p class="text-gray-500 mb-2">Wah kamu belum ada barang sewaan, yuk sewa sekarang</p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach($rentals as $rental)
                        <div class="bg-gray-100 dark:bg-gray-800 p-3 rounded-lg">
                            <div class="font-medium">{{ $rental->product->title }}</div>
                            <div class="text-sm text-gray-500">
                                Status: <span class="text-green-500">Sedang Disewa</span>
                            </div>
                            <div class="text-sm text-gray-500">
                                Berakhir pada: {{ \Carbon\Carbon::parse($rental->end_datetime)->format('d M Y') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>