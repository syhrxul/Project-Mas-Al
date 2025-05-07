<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-4">
            <div class="text-xl font-bold">
                Status Penyewaan Anda
            </div>

            @php
                $rentals = $this->getRentals();
            @endphp

            @if($rentals->isEmpty())
                <div class="text-center py-4">
                    <p class="text-gray-500 mb-4">Wah kamu belum ada barang sewaan, yuk sewa sekarang</p>
                    <x-filament::button
                        color="primary"
                        tag="a"
                        href="{{ route('filament.user.resources.products.index') }}"
                    >
                        Lihat Produk
                    </x-filament::button>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($rentals as $rental)
                        <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg">
                            <div class="font-medium">{{ $rental->product->title }}</div>
                            <div class="text-sm text-gray-500">
                                Status: 
                                <span class="@if($rental->status === 'confirmed') text-green-500 @else text-blue-500 @endif">
                                    {{ $rental->status === 'confirmed' ? 'Sedang Disewa' : 'Selesai' }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-500">
                                Tanggal Selesai: {{ \Carbon\Carbon::parse($rental->end_datetime)->format('d M Y') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>