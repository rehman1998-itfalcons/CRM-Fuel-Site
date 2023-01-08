<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\SyncPaymentStatus::class,

        Commands\SyncItems::class,
        Commands\syncContacts::class,
        Commands\SyncCustomerCOA::class,
        Commands\SyncSuppliers::class,
        Commands\SyncSupplierCOA::class,
        Commands\SyncSales::class,
        Commands\SyncPurchases::class,
        Commands\EmailSending::class,
        Commands\SyncSalesRecordCache::class,
        Commands\PurchaseCache::class,
        Commands\ActivityLogCache::class,
        Commands\supplierchart::class,
        Commands\linechart::class,
        Commands\Slinechart::class,
        Commands\lineChartMonthly::class,
        Commands\sidechart::class,
        Commands\monthltyReport::class,

        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //dashboard Cache
        $schedule->command('command:activity-log')->everyMinute();
        $schedule->command('command:linechart-monthly')->everyMinute();
        $schedule->command('command:Slinechart')->everyMinute();
        $schedule->command('command:linechart')->everyMinute();
        $schedule->command('command:supplierchart')->everyMinute();
        $schedule->command('command:sidechart')->everyMinute();
        $schedule->command('command:monthltyReport')->everyMinute();



        // $schedule->command('command:sync-items')->everyMinute();
        // $schedule->command('command:sync-contacts-to-myob')->everyMinute();
        //  $schedule->command('command:Sync-customer-chartOfAccount')->everyMinute();
        //  $schedule->command('command:Sync-supplier-companies')->everyMinute();
        //  $schedule->command('command:Sync-supplier-COA')->everyMinute();
        //  $schedule->command('command:sync-sales-records-cache-to-myob')->everyMinute();
        //  $schedule->command('command:Sync-sales-to-myob')->everyMinute();
        //  $schedule->command('command:sync-purchase-invoices-cache-to-myob')->everyMinute();
        //  $schedule->command('command:Sync-purchases-to-myob')->everyMinute();





        //   $schedule->command('command:sending-report-email')->everyMinute();

        //outdated
        // $schedule->command('command:Sync-payment-status-from-myob-against-sale-invoices')->everyMinute();

        $schedule->command('inspire')
                 ->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
