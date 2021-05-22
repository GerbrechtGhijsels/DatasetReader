<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:json {filepath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process Json to database';

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
        \App\Jobs\ProcessJson::dispatch($this->argument('filepath'));
    }
}
