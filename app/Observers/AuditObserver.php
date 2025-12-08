<?php

namespace App\Observers;

use App\Helpers\NotificationHelper;

class AuditObserver
{
    public function created($model)
    {
        $this->logActivity('created', $model);
    }
    
    public function updated($model)
    {
        $this->logActivity('updated', $model);
    }
    
    public function deleted($model)
    {
        $this->logActivity('deleted', $model);
    }
    
    private function logActivity($action, $model)
    {
        $modelName = class_basename($model);
        $user = auth()->user();
        
        if (!$user) return;
        
        // Notifikasi untuk aktivitas sensitif
        $sensitiveModels = ['User', 'Perusahaan', 'JenisLimbah'];
        
        if (in_array($modelName, $sensitiveModels) && $user->role === 'admin') {
            NotificationHelper::notifyAdmins(
                'Aktivitas Sistem',
                "{$user->name} telah {$action} data {$modelName}",
                'info'
            );
        }
    }
}