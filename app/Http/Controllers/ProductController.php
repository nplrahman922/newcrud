<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Products/Index', [
            'products' => Product::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Products/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048', // Maks 2MB
            'video' => 'nullable|mimetypes:video/mp4,video/avi|max:20480', // Maks 20MB
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('videos', 'public');
        }

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'photo_path' => $photoPath,
            'video_path' => $videoPath,
        ]);

        return redirect()->route('products.index');
    }

    public function edit(Product $product)
    {
        return Inertia::render('Products/Edit', [
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product)
    {
         $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'video' => 'nullable|mimetypes:video/mp4,video/avi|max:20480',
        ]);

        $photoPath = $product->photo_path;
        if ($request->hasFile('photo')) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        $videoPath = $product->video_path;
        if ($request->hasFile('video')) {
            if ($videoPath) {
                Storage::disk('public')->delete($videoPath);
            }
            $videoPath = $request->file('video')->store('videos', 'public');
        }

        $product->update([
             'name' => $request->name,
             'description' => $request->description,
             'photo_path' => $photoPath,
             'video_path' => $videoPath,
        ]);


        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        if ($product->photo_path) {
            Storage::disk('public')->delete($product->photo_path);
        }
        if ($product->video_path) {
            Storage::disk('public')->delete($product->video_path);
        }

        $product->delete();

        return redirect()->route('products.index');
    }
}