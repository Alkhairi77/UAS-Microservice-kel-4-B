<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\NotificationHelper;
use Carbon\Carbon;

class SystemMaintenance extends Command
{
    protected $signature = 'system:maintenance {action} {--message=} {--schedule=}';
    protected $description = 'Send system maintenance notifications';

    public function handle()
    {
        $action = $this->argument('action');
        
        switch ($action) {
            case 'notify':
                $this->notifyMaintenance();
                break;
            case 'start':
                $this->startMaintenance();
                break;
            case 'end':
                $this->endMaintenance();
                break;
            default:
                $this->error('Invalid action. Use: notify, start, or end');
        }
    }

    private function notifyMaintenance()
    {
        $message = $this->option('message') ?: 'Sistem akan menjalani maintenance rutin';
        $schedule = $this->option('schedule') ?: Carbon::now()->addHours(2)->format('d/m/Y H:i');
        
        NotificationHelper::systemMaintenance($message, $schedule);
        $this->info('Maintenance notification sent to all users');
    }

    private function startMaintenance()
    {
        NotificationHelper::systemMaintenance('Sistem sedang dalam maintenance. Beberapa fitur mungkin tidak tersedia.');
        $this->info('Maintenance start notification sent');
    }

    private function endMaintenance()
    {
        NotificationHelper::systemMaintenance('Maintenance selesai. Semua sistem sudah normal kembali.');
        $this->info('Maintenance end notification sent');
    }
}