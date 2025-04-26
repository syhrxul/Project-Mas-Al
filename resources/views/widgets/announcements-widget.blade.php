<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-4">
            <div class="flex items-center gap-x-3">
                <x-filament::icon
                    icon="heroicon-o-megaphone"
                    class="h-5 w-5 text-primary-500"
                />
                <h2 class="text-lg font-medium">Announcements</h2>
            </div>

            @foreach($this->getAnnouncements() as $announcement)
                <div class="rounded-lg border p-4 space-y-2">
                    <div class="flex justify-between items-start">
                        <h3 class="text-base font-medium">{{ $announcement->title }}</h3>
                        <span class="text-sm text-gray-500">
                            {{ $announcement->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <div class="prose prose-sm max-w-none">
                        {!! $announcement->content !!}
                    </div>
                </div>
            @endforeach

            @if($this->getAnnouncements()->isEmpty())
                <p class="text-sm text-gray-500">No announcements at the moment.</p>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>