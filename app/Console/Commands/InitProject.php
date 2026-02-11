<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InitProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:project';

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
        $this->info('Initializing the project...');

        // List of commands to run
        $commands = [
            'migrate:fresh',
            'permission:generate',
            'db:seed',
            'region:import',
            'language:import',
            'optimize:clear',

        ];

        // Execute each command
        foreach ($commands as $command) {
            $this->call($command);
        }

        $this->info('Project initialized successfully!');
        return Command::SUCCESS;
    }
}
