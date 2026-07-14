<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display user's wishlist.
     */
    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with('product') // Load relasi product
            ->latest()
            ->get();

        return view('user.wishlist.index', compact('wishlists'));
    }

    /**
     * Toggle product in wishlist.
     */
    public function toggle(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $userId = Auth::id();

        // Check if already in wishlist
        $wishlist = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            // Remove from wishlist
            $wishlist->delete();
            return response()->json([
                'success' => true,
                'message' => 'Produk dihapus dari wishlist',
                'action' => 'removed'
            ]);
        } else {
            // Add to wishlist
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Produk ditambahkan ke wishlist',
                'action' => 'added'
            ]);
        }
    }

    /**
     * Remove product from wishlist.
     */
    public function destroy($id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->findOrFail($id);
        
        $wishlist->delete();

        return redirect()->route('user.wishlist.index')
            ->with('success', 'Produk dihapus dari wishlist');
    }
}