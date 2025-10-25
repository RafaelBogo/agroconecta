<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- faltava
use App\Models\Review;
use App\Models\Product;

class ReviewController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Produtos comprados pelo usuário (via order_items) — apenas pedidos concluídos
        $products = Product::whereIn('products.id', function ($q) use ($userId) {
                $q->select('order_items.product_id')
                  ->from('orders')
                  ->join('order_items', 'order_items.order_id', '=', 'orders.id')
                  ->where('orders.user_id', $userId)
                  ->where('orders.status', 'Concluido');
            })
            ->with('user')
            ->orderBy('name')
            ->get();

        // IDs de produtos já avaliados pelo usuário
        $reviews = Review::where('user_id', $userId)
            ->pluck('product_id')
            ->all();

        return view('account.myRatings', compact('products', 'reviews'));
    }

    public function store(Request $request, $productId)
    {
        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'product_id' => (int) $productId,
            'user_id'    => Auth::id(),
            'rating'     => $data['rating'],
            'comment'    => $data['comment'] ?? null,
        ]);

        return back()->with('success', 'Avaliação enviada com sucesso!');
    }
}
