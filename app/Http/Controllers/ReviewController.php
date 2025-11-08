<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Review;

class ReviewController extends Controller
{
    /**
     * Lista os produtos que o usuário comprou e pode (ou não) avaliar.
     */
    public function index()
    {
        $userId = Auth::id();

        // Produtos comprados por este usuário (tabela orders contém product_id e status)
        $products = Product::whereIn('products.id', function ($q) use ($userId) {
                $q->select('orders.product_id')
                  ->from('orders')
                  ->where('orders.user_id', $userId);
            })
            ->with('user')
            ->orderBy('name')
            ->get();

        // Produtos que o usuário já avaliou
        $reviews = Review::where('user_id', $userId)
            ->pluck('product_id')
            ->all();

        // Produtos elegíveis para avaliação (pedido marcado como Retirado)
        $eligibleIds = $this->eligibleProductIds($userId);

        return view('account.myRatings', [
            'products'    => $products,
            'reviews'     => $reviews,
            'eligibleIds' => $eligibleIds,
        ]);
    }

    /**
     * Salva a avaliação de um produto.
     */
    public function store(Request $request, $productId)
    {
        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $userId    = Auth::id();
        $productId = (int) $productId;

        // Só permite avaliar se houver pedido "Retirado" para este produto
        $eligibleIds = $this->eligibleProductIds($userId);
        abort_unless(in_array($productId, $eligibleIds, true), 403, 'A avaliação só é liberada após marcar o produto como retirado.');

        // Evita avaliação duplicada
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

    /**
     * Retorna os IDs de produtos elegíveis para avaliação (pedidos "Retirado").
     */
    private function eligibleProductIds(int $userId): array
    {
        return DB::table('orders')
            ->where('user_id', $userId)
            ->where('status', 'Retirado')
            ->pluck('product_id')
            ->unique()
            ->values()
            ->all();
    }
}
