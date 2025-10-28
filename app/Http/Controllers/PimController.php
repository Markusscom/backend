<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Website;
use App\Models\Product;

class PIMController extends Controller
{
    // --- WEBSITES CRUD ---

    // List all websites
    public function websites() {
        return json_encode(Website::with('products')->get());
    }

    // Create website
    public function createWebsite(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string',
            'url' => 'required|url|unique:websites,url'
        ]);

        $website = Website::create($validated);

        return json_encode([
            'message' => 'Website created',
            'website' => $website
        ]);
    }

    // Update website
    public function updateWebsite(Request $request, $id) {
        $website = Website::find($id);
        if (!$website) return json_encode(['errors' => 'Website not found']);

        $validated = $request->validate([
            'name' => 'nullable|string',
            'url' => 'nullable|url|unique:websites,url,' . $id
        ]);

        $website->update($validated);

        return json_encode([
            'message' => 'Website updated',
            'website' => $website
        ]);
    }

    // Delete website
    public function deleteWebsite($id) {
        $website = Website::find($id);
        if (!$website) return json_encode(['errors' => 'Website not found']);

        $website->delete();

        return json_encode(['message' => 'Website deleted']);
    }

    // --- PRODUCTS CRUD ---

    // List products for a website
    public function products($website_id) {
        $website = Website::find($website_id);
        if (!$website) return json_encode(['errors' => 'Website not found']);

        return json_encode($website->products);
    }

    // Create product for a website
    public function createProduct(Request $request, $website_id) {
        $website = Website::find($website_id);
        if (!$website) return json_encode(['errors' => 'Website not found']);

        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer'
        ]);

        $product = $website->products()->create($validated);

        return json_encode([
            'message' => 'Product created',
            'product' => $product
        ]);
    }

    // Update product
    public function updateProduct(Request $request, $id) {
        $product = Product::find($id);
        if (!$product) return json_encode(['errors' => 'Product not found']);

        $validated = $request->validate([
            'name' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'stock' => 'nullable|integer'
        ]);

        $product->update($validated);

        return json_encode([
            'message' => 'Product updated',
            'product' => $product
        ]);
    }

    // Delete product
    public function deleteProduct($id) {
        $product = Product::find($id);
        if (!$product) return json_encode(['errors' => 'Product not found']);

        $product->delete();

        return json_encode(['message' => 'Product deleted']);
    }
}
