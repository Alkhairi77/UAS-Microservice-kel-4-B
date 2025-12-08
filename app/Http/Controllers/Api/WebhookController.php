<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handleVendorUpdate(Request $request)
    {
        // Handle webhook dari vendor eksternal
        $data = $request->all();
        
        if ($data['status'] === 'completed') {
            $pengelolaan = \App\Models\PengelolaanLimbah::where('nomor_manifest', $data['manifest_number'])->first();
            
            if ($pengelolaan) {
                $pengelolaan->update(['status' => 'selesai']);
                
                NotificationHelper::notifyUser(
                    $pengelolaan->perusahaan->user,
                    'Pengelolaan Selesai (Vendor)',
                    "Vendor telah menyelesaikan pengelolaan limbah dengan manifest {$data['manifest_number']}",
                    'success',
                    route('pengelolaan-limbah.show', $pengelolaan)
                );
            }
        }
        
        return response()->json(['status' => 'success']);
    }
    
    public function handlePaymentUpdate(Request $request)
    {
        // Handle payment webhook
        $data = $request->all();
        
        if ($data['status'] === 'paid') {
            NotificationHelper::notifyUser(
                \App\Models\User::find($data['user_id']),
                'Pembayaran Berhasil',
                "Pembayaran untuk invoice #{$data['invoice_number']} telah berhasil diproses",
                'success'
            );
        }
        
        return response()->json(['status' => 'success']);
    }
}