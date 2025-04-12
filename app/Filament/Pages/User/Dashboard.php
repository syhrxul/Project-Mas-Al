<?php

namespace App\Filament\Pages\User;

use App\Models\Category;
use App\Models\Product;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Contracts\View\View;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.user.dashboard';

    protected static ?int $navigationSort = -2;

    public function getCategories()
    {
        return Category::where('is_active', true)->withCount('products')->get();
    }

    public function getFeaturedProducts()
    {
        return Product::where('is_active', true)->latest()->take(6)->get();
    }

    protected function getHeaderActions(): array
    {
        return [
            // Header actions have been removed
        ];
    }
}