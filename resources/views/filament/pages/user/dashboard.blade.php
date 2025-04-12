<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <x-filament::section>
            <x-slot name="heading">Welcome, {{ auth()->user()->name }}</x-slot>
            <p>This is your user dashboard. You can manage your account and view your orders here.</p>
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

        <x-filament::section>
            <x-slot name="heading">Quick Actions</x-slot>
            <div class="space-y-4">
                <a href="#" class="block w-full p-2 bg-primary-500 hover:bg-primary-600 text-white text-center rounded-lg transition">
                    Browse Products
                </a>
                <a href="#" class="block w-full p-2 bg-gray-100 hover:bg-gray-200 text-center rounded-lg transition">
                    View Orders
                </a>
                <a href="#" class="block w-full p-2 bg-gray-100 hover:bg-gray-200 text-center rounded-lg transition">
                    Edit Profile
                </a>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>