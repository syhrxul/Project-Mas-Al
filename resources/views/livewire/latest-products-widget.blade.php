<x-filament::section>
    <div class="space-y-4">
        <div class="flex items-center gap-x-3">
            <x-filament::icon
                icon="heroicon-o-shopping-bag"
                class="h-5 w-5 text-primary-500"
            />
            <h2 class="text-lg font-medium">Produk Terbaru</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($products as $product)
                <div class="rounded-lg border p-4 space-y-2">
                    <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                <x-filament::icon
                                    icon="heroicon-o-photo"
                                    class="h-8 w-8 text-gray-400"
                                />
                            </div>
                        @endif
                    </div>
                    <h3 class="text-base font-medium">{{ $product->name }}</h3>
                </div>
            @endforeach

            @if($products->isEmpty())
                <p class="text-sm text-gray-500">Belum ada produk.</p>
            @endif
        </div>
    </div>
</x-filament::section>