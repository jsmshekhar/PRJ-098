<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;

class ScheduleNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:schedule-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Scheduled Notification';

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
        Notification::sendScheduleNotifications();
    }
}
