<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Review;
use App\Models\Product;
use App\Models\OrderItem;

class ReviewController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $products = Product::whereIn('products.id', function ($q) use ($userId) {
                $q->select('order_items.product_id')
                  ->from('orders')
                  ->join('order_items', 'order_items.order_id', '=', 'orders.id')
                  ->where('orders.user_id', $userId);
            })
            ->with('user')
            ->orderBy('name')
            ->get();

        $reviews = Review::where('user_id', $userId)
            ->pluck('product_id')
            ->all();

        $eligibleIds = $this->eligibleProductIds($userId);

        return view('account.myRatings', [
            'products' => $products,
            'reviews' => $reviews,
            'eligibleIds'  => $eligibleIds,
        ]);
    }

    public function store(Request $request, $productId)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();
        $productId = (int) $productId;

        $eligibleIds = $this->eligibleProductIds($userId);
        abort_unless(in_array($productId, $eligibleIds, true), 403, 'A avaliação só é liberada após marcar o produto como retirado.');

        $already = Review::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();
        abort_if($already, 422, 'Você já avaliou este produto.');

        Review::create([
            'product_id' => $productId,
            'user_id'=> $userId,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        return back()->with('success', 'Avaliação enviada com sucesso!');
    }
    private function eligibleProductIds(int $userId): array
    {
        $hasPickedUpAt = Schema::hasColumn('order_items', 'picked_up_at');
        $hasOrderItemStatus = Schema::hasColumn('order_items', 'status');

        $q = OrderItem::query()
            ->select('product_id')
            ->whereHas('order', fn ($oq) => $oq->where('user_id', $userId));

        if ($hasPickedUpAt) {
            $q->whereNotNull('picked_up_at');
        } elseif ($hasOrderItemStatus) {
            $q->where('status', 'Retirado');
        } else {
            return [];
        }

        return $q->distinct()->pluck('product_id')->all();
    }
}
