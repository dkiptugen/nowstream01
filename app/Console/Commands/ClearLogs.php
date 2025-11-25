<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        exec('truncate -s 0 ' . storage_path('logs/laravel.log'));
	    exec('truncate -s 0 ' . storage_path('logs/worker.log'));
	    $this->info('Logs have been cleared');
    }
}
