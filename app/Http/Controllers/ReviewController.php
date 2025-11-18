<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Review;
use App\Models\Order;

class ReviewController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $productIds = Order::where('user_id', $userId)
            ->with('items')
            ->get()
            ->flatMap(fn ($order) => $order->items->pluck('product_id'))
            ->unique()
            ->values()
            ->all();

        if (empty($productIds)) {
            $products = collect();
        } else {
            $products = Product::whereIn('id', $productIds)
                ->with('user')
                ->orderBy('name')
                ->get();
        }

        $reviews = Review::where('user_id', $userId)
            ->pluck('product_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $eligibleIds = $this->eligibleProductIds($userId);

        return view('account.myRatings', [
            'products'    => $products,
            'reviews'     => $reviews,
            'eligibleIds' => $eligibleIds,
        ]);
    }

    public function store(Request $request, $productId)
    {
        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $userId    = Auth::id();
        $productId = (int) $productId;

        $eligibleIds = $this->eligibleProductIds($userId);
        abort_unless(
            in_array($productId, $eligibleIds, true),
            403,
            'A avaliação só é liberada após marcar o pedido como retirado.'
        );

        $already = Review::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();

        abort_if($already, 422, 'Você já avaliou este produto.');

        Review::create([
            'product_id' => $productId,
            'user_id'    => $userId,
            'rating'     => $data['rating'],
            'comment'    => $data['comment'] ?? null,
        ]);

        return back()->with('success', 'Avaliação enviada com sucesso!');
    }

    private function eligibleProductIds(int $userId): array
    {
        return Order::where('user_id', $userId)
            ->where('status', 'Retirado')
            ->with('items')
            ->get()
            ->flatMap(fn ($order) => $order->items->pluck('product_id'))
            ->unique()
            ->values()
            ->map(fn ($id) => (int) $id)
            ->all();
    }
}
