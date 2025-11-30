<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    private function get(): array
    {
        return session()->get('wishlist', []); // array of product ids
    }

    private function save(array $ids): void
    {
        session()->put('wishlist', array_values(array_unique($ids)));
        session()->save();
    }

    public function index()
    {
        $ids = $this->get();
        $products = Product::with('vendor')->whereIn('id', $ids)->get();
        return response()->json([
            'ids' => $ids,
            'items' => $products->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'image' => $p->getMainImage(),
                    'price' => (float) $p->price,
                    'original_price' => $p->original_price ? (float) $p->original_price : null,
                    'vendor' => $p->vendor ? ['id' => $p->vendor->id, 'name' => $p->vendor->name] : null,
                ];
            })->values(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['product_id' => 'required|integer|exists:products,id']);
        $ids = $this->get();
        $ids[] = (int) $data['product_id'];
        $this->save($ids);
        return $this->index();
    }

    public function destroy(int $productId)
    {
        $ids = array_filter($this->get(), fn ($id) => (int) $id !== (int) $productId);
        $this->save($ids);
        return $this->index();
    }
}
