<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;

class AutomaticNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:automatic-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Automatic Notification';

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
        Notification::sendAutomatcNotifications();
    }
}
