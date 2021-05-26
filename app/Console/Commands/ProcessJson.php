<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

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

        $filename = time().basename($this->argument('filepath'));

        Storage::disk('local')->putFileAs(
            'files/',
            new File($this->argument('filepath')),
            $filename
        );

        $filters = [ 'date_of_birth' => 'nullable|olderThan:18|youngerThan:65'];
        \App\Jobs\ProcessJson::dispatch(Storage::path('files/'.$filename), $filters)->onQueue('processing');
    }
}
