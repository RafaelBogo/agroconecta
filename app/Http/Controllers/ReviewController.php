<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;

class ReviewController extends Controller
{
    public function index()
    {
        $products = \App\Models\Order::where('user_id', auth()->id())
            ->where('status', 'Processando')
            ->with('product')
            ->get()
            ->map(function ($order) {
                return $order->product;
            })
            ->unique('id');

        $reviews = auth()->user()->reviews()->pluck('product_id')->toArray();

        return view('account.myRatings', compact('products', 'reviews'));
    }

    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'product_id' => $productId,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Avaliação enviada com sucesso!');
    }
}
