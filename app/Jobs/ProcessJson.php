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
use Illuminate\Support\Facades\Validator;
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
    protected $filter;

    public $timeout = 600;

    /**
     * Create a new job instance.
     *
     * @param $filePath
     */
    public function __construct( $filepath, $filter)
    {
        $this->prepareStatus();
        $this->filepath = $filepath;
        $this->filter = $filter;


        $jsonFile = file_get_contents($this->filepath);
        $jsonArray = json_decode($jsonFile, true);
        $this->timeout = 2 * count($jsonArray);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $jobStatus = JobStatus::whereKey($this->getJobStatusId())->firstOrFail();

        $jsonFile = file_get_contents($this->filepath);
        $jsonArray = json_decode($jsonFile, true);

        $this->setProgressMax(count($jsonArray));
        $index = 0;

        if($jobStatus->progress_now > 0 ) {
            $index = $jobStatus->progress_now -1;
        }

        for($index; $index < count($jsonArray); $index++)
        {

            $creditcardJson = $this->array_remove($jsonArray[$index],'credit_card');

            if($this->validate($jsonArray[$index]))
            {
                $account = Account::create($jsonArray[$index]);


                $account->creditcard()->create($creditcardJson);
            }


            $this->setProgressNow($index+1);

            $this->timeout += 3;
        }

        Storage::delete($this->filepath);
    }

    public function validate($data)
    {
        // make a new validator object
        $v = Validator::make($data, $this->filter);

        // check for failure
        if ($v->fails())
        {
            // set errors and return false
            return false;
        }

        // validation pass
        return true;
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

    public function retryAfter()
    {
        return $this->timeout + 3;
    }
}
