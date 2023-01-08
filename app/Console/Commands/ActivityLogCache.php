<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log as Logger;
use Session;
use App\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class ActivityLogCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:activity-log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activity logs';

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
     * @return mixed
     */
    public function handle()
    {
        Logger::info('activity log is Working in ActivityLogCache');
        $cron_job =  DB::table('dashboard_cache')->where('value', 'activity_log')->first();
        if ($cron_job->value == 'activity_log' && $cron_job->status == 1) {

            $activity_logs = Log::latest()->take(3)->get();

            $output = "";
            $i = 1;

            if ($activity_logs) {
                foreach ($activity_logs as $key => $log) {
                    $user = $log->user;
                    $output .= '<tr  role="row" >' .

                        '<td>' .  $i++  . '</td>' .
                        '<td  id="user" >' .  $user->name . '</td>' .
                        '<td id="ip_address" >' .   $log->ip_address  . '</td>' .
                        '<td  id="location">' .  $log->city_name . ', ' . $log->region_name . '<br>' . $log->country_name . ',' . $log->zip_code . '</td>' .
                        '<td  id="date" >' . $log->created_at->diffForHumans()   . '</td>' .

                        '</tr>';
                }
                Logger::info('remember starts');

                $minutes = 5;
                $output = \Cache::remember('activity_log', $minutes, function () use ($output) {
                    return $output;
                });



                $cron_job->total_run = $cron_job->total_run + 1;
                DB::table('dashboard_cache')->where('value', $cron_job->value)->update([
                    'status' => 0,
                    'total_run' => $cron_job->total_run
                ]);
                DB::table('dashboard_cache')->where('value', 'linechart-monthly')->update([
                    'status' => 1
                ]);

                Logger::info('activity log done');
            }
        }
    }
}
