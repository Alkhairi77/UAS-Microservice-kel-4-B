<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait HasBulkActions
{
    /**
     * Handle bulk actions for models
     */
    protected function handleBulkAction(Request $request, $modelClass, $allowedActions = [])
    {
        $request->validate([
            'action' => 'required|in:' . implode(',', $allowedActions),
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'exists:' . (new $modelClass)->getTable() . ',id'
        ]);

        try {
            DB::beginTransaction();

            $items = $modelClass::whereIn('id', $request->selected_items);
            
            // Filter by company if user is not admin
            if (auth()->user()->isPerusahaan()) {
                $items = $items->where('perusahaan_id', auth()->user()->perusahaan->id);
            }
            
            $items = $items->get();

            if ($items->count() !== count($request->selected_items)) {
                return back()->with('error', 'Beberapa item tidak ditemukan atau bukan milik Anda.');
            }

            $successCount = 0;
            $errorCount = 0;

            foreach ($items as $item) {
                try {
                    $this->executeBulkAction($request->action, $item);
                    $successCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    \Log::error('Bulk action error: ' . $e->getMessage());
                }
            }

            DB::commit();

            $message = "{$successCount} item berhasil diproses.";
            if ($errorCount > 0) {
                $message .= " {$errorCount} item gagal diproses.";
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal melakukan bulk action: ' . $e->getMessage());
        }
    }

    /**
     * Execute specific bulk action - to be implemented in each controller
     */
    abstract protected function executeBulkAction($action, $item);
}