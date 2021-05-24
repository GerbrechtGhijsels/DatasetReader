<?php

namespace App\Jobs;

use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Imtigger\LaravelJobStatus\JobStatus;
use Imtigger\LaravelJobStatus\Trackable;
use LimitIterator;

class ProcessJson implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    ///Users/gerbrechtghijsels/Projects/DatasetReader/JSONTest/test.json
    ///Users/gerbrechtghijsels/Projects/DatasetReader/challenge.json
    protected $filepath;
    protected $id;

    /**
     * Create a new job instance.
     *
     * @param $filePath
     */
    public function __construct( $filepath)
    {
        $this->prepareStatus();
        $this->filepath = $filepath;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $this->getJobStatusId();
        $jobStatus = JobStatus::whereKey($this->getJobStatusId())->firstOrFail();

        $jsonFile = file_get_contents($this->filepath);
        $jsonArray = json_decode($jsonFile, true);

        $this->setProgressMax(count($jsonArray));

        for($index=$jobStatus->progress_now; $index < count($jsonArray); $index++)
        {

            $creditcardJson = $this->array_remove($jsonArray[$index],'credit_card');
            $account = Account::create($jsonArray[$index]);

            //$this->setProgressNow($index);
            $this->setInput(['id'=>$account->getKey()]);

            $account->creditcard()->create($creditcardJson);
            $this->id = $account->getKey();
            $this->setProgressNow($index+1);
        }

        Storage::delete($this->filepath);
    }

    /**
     * Removes an item from the array and returns its value.
     *
     * @param array $arr The input array
     * @param $key The key pointing to the desired value
     * @return The value mapped to $key or null if none
     */
    function array_remove(array &$arr, $key) {
        if (array_key_exists($key, $arr)) {
            $val = $arr[$key];
            unset($arr[$key]);

            return $val;
        }

        return null;
    }
}
