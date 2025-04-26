<?php

namespace App\Livewire;

use App\Models\Product;
use Filament\Widgets\Widget;

class LatestProductsWidget extends Widget
{
    protected static string $view = 'livewire.latest-products-widget';

    protected function getViewData(): array
    {
        return [
            'products' => Product::query()
                ->latest()
                ->take(3)
                ->get()
        ];
    }
}