<?php

namespace App\Http\Controllers;

use App\Models\JenisLimbah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class JenisLimbahController extends Controller
{
    /**
     * Display a listing of jenis limbah.
     */
    public function index(Request $request)
    {
        $correlationId = $request->attributes->get('correlation_id');

        Log::info('Fetching jenis limbah list', [
            'correlation_id' => $correlationId,
            'filters' => $request->only(['kategori', 'status', 'search']),
        ]);

        try {
            $query = JenisLimbah::query();

            // Filter by kategori
            if ($request->has('kategori')) {
                $query->where('kategori', $request->kategori);
            }

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Search by nama or kode
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_limbah', 'like', "%{$search}%")
                      ->orWhere('kode_limbah', 'like', "%{$search}%");
                });
            }

            $perPage = $request->input('per_page', 15);
            $jenisLimbahs = $query->orderBy('kode_limbah')->paginate($perPage);

            Log::info('Jenis limbah list fetched successfully', [
                'correlation_id' => $correlationId,
                'count' => $jenisLimbahs->count(),
                'total' => $jenisLimbahs->total(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jenis limbah retrieved successfully',
                'data' => $jenisLimbahs->items(),
                'pagination' => [
                    'current_page' => $jenisLimbahs->currentPage(),
                    'per_page' => $jenisLimbahs->perPage(),
                    'total' => $jenisLimbahs->total(),
                    'last_page' => $jenisLimbahs->lastPage(),
                ],
                'correlation_id' => $correlationId,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching jenis limbah list', [
                'correlation_id' => $correlationId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve jenis limbah',
                'error' => $e->getMessage(),
                'correlation_id' => $correlationId,
            ], 500);
        }
    }

    /**
     * Store a newly created jenis limbah.
     */
    public function store(Request $request)
    {
        $correlationId = $request->attributes->get('correlation_id');

        Log::info('Creating new jenis limbah', [
            'correlation_id' => $correlationId,
            'data' => $request->except(['_token']),
        ]);

        $validator = Validator::make($request->all(), [
            'kode_limbah' => 'required|string|max:20|unique:jenis_limbahs,kode_limbah',
            'nama_limbah' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'kategori' => ['required', Rule::in(['B3', 'Non-B3', 'Organik', 'Anorganik'])],
            'satuan_default' => 'required|string|max:20',
            'batas_aman' => 'nullable|numeric|min:0',
            'status' => ['nullable', Rule::in(['aktif', 'non-aktif'])],
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed for jenis limbah creation', [
                'correlation_id' => $correlationId,
                'errors' => $validator->errors()->toArray(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                'correlation_id' => $correlationId,
            ], 422);
        }

        try {
            $jenisLimbah = JenisLimbah::create($request->all());

            Log::info('Jenis limbah created successfully', [
                'correlation_id' => $correlationId,
                'jenis_limbah_id' => $jenisLimbah->id,
                'kode_limbah' => $jenisLimbah->kode_limbah,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jenis limbah created successfully',
                'data' => $jenisLimbah,
                'correlation_id' => $correlationId,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating jenis limbah', [
                'correlation_id' => $correlationId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create jenis limbah',
                'error' => $e->getMessage(),
                'correlation_id' => $correlationId,
            ], 500);
        }
    }

    /**
     * Display the specified jenis limbah.
     */
    public function show(Request $request, string $id)
    {
        $correlationId = $request->attributes->get('correlation_id');

        Log::info('Fetching jenis limbah detail', [
            'correlation_id' => $correlationId,
            'id' => $id,
        ]);

        try {
            $jenisLimbah = JenisLimbah::findOrFail($id);

            Log::info('Jenis limbah detail fetched successfully', [
                'correlation_id' => $correlationId,
                'jenis_limbah_id' => $jenisLimbah->id,
                'kode_limbah' => $jenisLimbah->kode_limbah,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jenis limbah retrieved successfully',
                'data' => $jenisLimbah,
                'correlation_id' => $correlationId,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Jenis limbah not found', [
                'correlation_id' => $correlationId,
                'id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Jenis limbah not found',
                'correlation_id' => $correlationId,
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching jenis limbah detail', [
                'correlation_id' => $correlationId,
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve jenis limbah',
                'error' => $e->getMessage(),
                'correlation_id' => $correlationId,
            ], 500);
        }
    }

    /**
     * Update the specified jenis limbah.
     */
    public function update(Request $request, string $id)
    {
        $correlationId = $request->attributes->get('correlation_id');

        Log::info('Updating jenis limbah', [
            'correlation_id' => $correlationId,
            'id' => $id,
            'data' => $request->except(['_token', '_method']),
        ]);

        try {
            $jenisLimbah = JenisLimbah::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'kode_limbah' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('jenis_limbahs', 'kode_limbah')->ignore($jenisLimbah->id),
                ],
                'nama_limbah' => 'required|string|max:100',
                'deskripsi' => 'nullable|string',
                'kategori' => ['required', Rule::in(['B3', 'Non-B3', 'Organik', 'Anorganik'])],
                'satuan_default' => 'required|string|max:20',
                'batas_aman' => 'nullable|numeric|min:0',
                'status' => ['nullable', Rule::in(['aktif', 'non-aktif'])],
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed for jenis limbah update', [
                    'correlation_id' => $correlationId,
                    'id' => $id,
                    'errors' => $validator->errors()->toArray(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'correlation_id' => $correlationId,
                ], 422);
            }

            $jenisLimbah->update($request->all());

            Log::info('Jenis limbah updated successfully', [
                'correlation_id' => $correlationId,
                'jenis_limbah_id' => $jenisLimbah->id,
                'kode_limbah' => $jenisLimbah->kode_limbah,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jenis limbah updated successfully',
                'data' => $jenisLimbah->fresh(),
                'correlation_id' => $correlationId,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Jenis limbah not found for update', [
                'correlation_id' => $correlationId,
                'id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Jenis limbah not found',
                'correlation_id' => $correlationId,
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error updating jenis limbah', [
                'correlation_id' => $correlationId,
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update jenis limbah',
                'error' => $e->getMessage(),
                'correlation_id' => $correlationId,
            ], 500);
        }
    }

    /**
     * Remove the specified jenis limbah.
     */
    public function destroy(Request $request, string $id)
    {
        $correlationId = $request->attributes->get('correlation_id');

        Log::info('Deleting jenis limbah', [
            'correlation_id' => $correlationId,
            'id' => $id,
        ]);

        try {
            $jenisLimbah = JenisLimbah::findOrFail($id);
            $kodeLimbah = $jenisLimbah->kode_limbah;

            $jenisLimbah->delete();

            Log::info('Jenis limbah deleted successfully', [
                'correlation_id' => $correlationId,
                'jenis_limbah_id' => $id,
                'kode_limbah' => $kodeLimbah,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jenis limbah deleted successfully',
                'correlation_id' => $correlationId,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Jenis limbah not found for deletion', [
                'correlation_id' => $correlationId,
                'id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Jenis limbah not found',
                'correlation_id' => $correlationId,
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting jenis limbah', [
                'correlation_id' => $correlationId,
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete jenis limbah',
                'error' => $e->getMessage(),
                'correlation_id' => $correlationId,
            ], 500);
        }
    }
}
