<?php

namespace App\Console\Commands;

use App\Models\Machine;
use App\Models\User;
use App\Notifications\MaintenanceRequiredNotificaton;
use Illuminate\Console\Command;

class SearchForMaintenances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:search-for-maintenances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search for maintenances from all registered machines';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $machines = Machine::all();

        foreach ($machines as $machine) {
            $lastMaintenanceDate = $machine->maintenances()->max('created_at');

            $daysSinceLastMaintenance = is_null($lastMaintenanceDate) ?
                now()->diffInDays($machine->created_at) :
                now()->diffInDays($lastMaintenanceDate);

            if ($daysSinceLastMaintenance >= $machine->days_next_maintenance) {
                $this->sendMaintanenceNotification($machine);
            }
        }

        $this->info('Searching completed successfully');
    }

    private function sendMaintanenceNotification(Machine $machine)
    {
        // $users = User::where('active', 1)->get();
        // foreach ($users as $user) {
        //     // notify to all active users
        //     $user->notify(new MaintenanceRequiredNotificaton($machine));
        // }

        $this->info('Notified for machine: ' . $machine->name);
    }
}
