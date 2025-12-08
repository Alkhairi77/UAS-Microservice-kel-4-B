<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

trait HasFilters
{
    /**
     * Apply filters to query
     */
    protected function applyFilters(Builder $query, Request $request, array $filters = [])
    {
        // Search filter
        if ($request->filled('search') && isset($filters['search'])) {
            $search = $request->search;
            $query->where(function ($q) use ($search, $filters) {
                $this->applySearchFilter($q, $search, $filters['search']);
            });
        }

        // Date range filter
        if (isset($filters['date_field'])) {
            if ($request->filled('tanggal_dari')) {
                $query->where($filters['date_field'], '>=', $request->tanggal_dari);
            }
            if ($request->filled('tanggal_sampai')) {
                $query->where($filters['date_field'], '<=', $request->tanggal_sampai);
            }
        }

        // Status filter
        if (isset($filters['status_field']) && $request->filled('status')) {
            $query->where($filters['status_field'], $request->status);
        }

        // Perusahaan filter (untuk admin)
        if ($request->filled('perusahaan_id') && auth()->user()->isAdmin()) {
            $query->where('perusahaan_id', $request->perusahaan_id);
        }

        return $query;
    }

    /**
     * Apply search filter to query
     */
    protected function applySearchFilter(Builder $query, string $search, array $searchFields)
    {
        foreach ($searchFields as $field) {
            if (str_contains($field, '.')) {
                // Relationship field
                [$relation, $column] = explode('.', $field);
                $query->orWhereHas($relation, function ($subQ) use ($search, $column) {
                    $subQ->where($column, 'like', "%{$search}%");
                });
            } else {
                // Direct field
                $query->orWhere($field, 'like', "%{$search}%");
            }
        }
    }
}