<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class CacheItemList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cache-item-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache items list to improve website performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to cache items...');
        
        // Get items from database
        $data = DB::table('items')->get();
        
        // Store in cache for 30 minutes
        Cache::put('cached_items', $data, now()->addMinutes(30));
        
        $this->info('Successfully cached ' . count($data) . ' items!');
    }
}
