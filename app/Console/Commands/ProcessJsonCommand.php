<?php

namespace App\Console\Commands;

use App\Jobs\ProcessJsonJob;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Helpers\FileHelper;

class ProcessJsonCommand extends Command
{
    /**
     * The name and signature of the console command.
     * Only works locally!
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

        $filename = FileHelper::uploadFileWithPath($this->argument('filepath'));

        $filters = [ 'date_of_birth' => 'nullable|olderThan:18|youngerThan:65'];
        ProcessJsonJob::dispatch(Storage::path('files/'.$filename), $filters);
    }
}
