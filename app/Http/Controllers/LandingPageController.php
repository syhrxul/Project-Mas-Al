<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class LandingPageController extends Controller
{
    public function index()
    {
        // Get featured products for the landing page
        $featuredProducts = Product::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
            
        // Get categories for the landing page
        $categories = Category::all();
        
        return view('landing', compact('featuredProducts', 'categories'));
    }
}