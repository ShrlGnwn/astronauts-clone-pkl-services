<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * CONTOH CRUD BEST PRACTICE DI DASHBOARD (AJAX).
 *
 * Resource yang sama dengan /api/categories (tabel + CategoryResource),
 * tapi endpoint khusus dashboard (login + admin) yang balikin JSON biar
 * bisa dipanggil pakai fetch() dari halaman Blade.
 *
 *   GET    /dashboard/categories          → index()  (render halaman + list awal)
 *   GET    /dashboard/categories/data     → data()   (JSON DataTables server-side)
 *   POST   /dashboard/categories          → store()  (buat, 201)
 *   PUT    /dashboard/categories/{id}     → update() (ubah)
 *   DELETE /dashboard/categories/{id}     → destroy()(hapus)
 *
 * Pola yang dipelajari:
 * 1. Validasi DI SERVER — aturannya sama persis dengan CategoryController API.
 *    Jangan pernah percaya validasi di JavaScript saja.
 * 2. Response JSON konsisten: sukses {message, data}, gagal validasi 422
 *    {message, errors:{field:[...]}}.
 * 3. Resource dipakai ulang → bentuk data API & dashboard dijamin sama.
 */
class CategoryController extends Controller
{
    public function index(): View
    {
        // List diisi DataTable lewat AJAX ke data() — halaman cukup render shell.
        return view('dashboard.categories');
    }

    /**
     * Endpoint DataTables server-side (pagination + search di DB).
     * Urut default: created_at desc (terbaru di atas).
     * Param: draw, start, length, search[value].
     */
    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        if ($length < 1) {
            $length = 10;
        }
        // Batasi biar gak kebablasan request length
        $length = min($length, 100);

        $recordsTotal = Category::count();

        $query = Category::query();
        $search = trim((string) $request->input('search.value', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('slug', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        $recordsFiltered = (clone $query)->count();

        $rows = $query
            ->orderByDesc('created_at')
            ->skip($start)
            ->take($length)
            ->get();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            // resolve() biar gak kebungkus {data:[...]} lagi (DataTables butuh array flat)
            'data' => CategoryResource::collection($rows)->resolve(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateCategory($request);

        $category = Category::create($validated);

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan.',
            'data' => new CategoryResource($category),
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $category = Category::find($id);
        if (! $category) {
            return response()->json(['message' => 'Kategori tidak ditemukan.'], 404);
        }

        $validated = $this->validateCategory($request, $id);

        $category->update($validated);

        return response()->json([
            'message' => 'Kategori berhasil diperbarui.',
            'data' => new CategoryResource($category),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $category = Category::find($id);
        if (! $category) {
            return response()->json(['message' => 'Kategori tidak ditemukan.'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Kategori berhasil dihapus.']);
    }

    /**
     * Validasi dipisah biar store & update pakai aturan yang sama.
     * Trik {id}: pas update, slug milik kategori itu sendiri tetap boleh
     * (unique:categories,slug,{id}) — persis seperti contoh API.
     */
    private function validateCategory(Request $request, ?int $ignoreId = null): array
    {
        $uniqueSlug = 'unique:categories,slug'.($ignoreId ? ','.$ignoreId : '');

        return $request->validate([
            'slug' => ['required', 'string', 'max:100', $uniqueSlug],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:500'],
        ]);
    }
}
