<?php

namespace App\Console\Commands;

use App\Http\Controllers\CronController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImmobilizedVehicleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vehicle:immobilized';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'immobilized vehicle if payment not done at payment date';

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

        $cron = new CronController();
        $cron->immobilizedVehicles();

    }
}
