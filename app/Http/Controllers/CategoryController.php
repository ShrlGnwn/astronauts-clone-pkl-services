<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;

/**
 * CONTOH CRUD LENGKAP — Kategori.
 *
 * Endpoint jadi (didaftarkan lewat Route::apiResource di routes/api.php):
 *
 *   GET    /api/categories          → index()  (list semua)
 *   POST   /api/categories          → store()  (buat baru)
 *   GET    /api/categories/{id}     → show()   (detail 1)
 *   PUT    /api/categories/{id}     → update() (ubah 1)
 *   DELETE /api/categories/{id}     → destroy()(hapus 1)
 *
 * Pelajari polanya — ini pola standar yang sama untuk resource lain
 * (produk, order, dll). Di Postman tinggal ganti method & body.
 */
class CategoryController extends Controller
{
    /**
     * GET /api/categories — list semua kategori (urut nama).
     */
    public function index(): AnonymousResourceCollection
    {
        return CategoryResource::collection(Category::orderBy('name')->get());
    }

    /**
     * POST /api/categories
     * Body JSON: { "slug": "minuman-dingin", "name": "Minuman Dingin", "icon": "https://..." }
     * - validasi gagal → 422 berisi pesan error per field
     * - sukses → 201 + data kategori baru
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'slug' => ['required', 'string', 'max:100', 'unique:categories,slug'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:500'],
        ]);

        $category = Category::create($validated);

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * GET /api/categories/{id} — detail 1 kategori.
     */
    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }

    /**
     * PUT /api/categories/{id}
     * Body JSON: field yang mau diubah (boleh sebagian).
     * Catatan: 'unique:categories,slug' diberi pengecualian {category}
     * supaya boleh update tanpa mengganti slug.
     */
    public function update(Request $request, Category $category): CategoryResource
    {
        $validated = $request->validate([
            'slug' => ['sometimes', 'string', 'max:100', 'unique:categories,slug,'.$category->id],
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:500'],
        ]);

        $category->update($validated);

        return new CategoryResource($category);
    }

    /**
     * DELETE /api/categories/{id} — hapus kategori.
     * Sukses → 200 dengan pesan. (Tidak mengembalikan data.)
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json(['message' => 'Kategori berhasil dihapus']);
    }
}
