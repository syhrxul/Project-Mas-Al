<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <x-filament::section>
            <x-slot name="heading">Welcome, {{ auth()->user()->name }}</x-slot>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Active Rentals:</span>
                    <span class="text-lg font-semibold">{{ auth()->user()->rentals()->where('status', 'active')->count() }}</span>
                </div>
                
                @if(auth()->user()->rentals()->where('status', 'active')->count() > 0)
                    <div class="mt-4">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Currently Rented Items:</h3>
                        <div class="space-y-3">
                            @foreach(auth()->user()->rentals()->where('status', 'active')->with('product')->get() as $rental)
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium">{{ $rental->product->title }}</span>
                                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">{{ $rental->rental_type }}</span>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-500 flex justify-between">
                                        <span>Start: {{ $rental->start_date->format('M d, Y H:i') }}</span>
                                        <span class="font-medium text-red-600">Due: {{ $rental->end_date->format('M d, Y H:i') }}</span>
                                    </div>
                                    <div class="mt-1 text-xs">
                                        @if($rental->end_date->isPast())
                                            <span class="text-red-600 font-medium">Overdue by {{ $rental->end_date->diffForHumans(['parts' => 1]) }}</span>
                                        @else
                                            <span class="text-green-600">Due in {{ $rental->end_date->diffForHumans(['parts' => 1]) }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 text-sm">You don't have any active rentals.</p>
                @endif
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Account Information</x-slot>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-500">Name:</span>
                    <span>{{ auth()->user()->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Email:</span>
                    <span>{{ auth()->user()->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Joined:</span>
                    <span>{{ auth()->user()->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </x-filament::section>


    </div>
</x-filament-panels::page>