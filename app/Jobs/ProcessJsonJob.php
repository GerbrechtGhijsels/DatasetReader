<?php

namespace App\Jobs;

use App\Helpers\FileHelper;
use App\Models\Account;
use Faker\Core\File;
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

class ProcessJsonJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    ///Users/gerbrechtghijsels/Projects/DatasetReader/JSONTest/test.json
    ///Users/gerbrechtghijsels/Projects/DatasetReader/challenge.json
    protected $filepath;
    protected $id;
    protected $filters;

    public $timeout = 20;

    /**
     * Create a new job instance.
     *
     * @param $filePath
     */
    public function __construct( $filepath, $filters)
    {
        $this->prepareStatus();
        $this->filepath = $filepath;
        $this->filters = $filters;

        //Increase timeout according to the json file size.
        $this->timeout = 2 * count($this->getJsonArrayFromPath($this->filepath));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $jobStatus = JobStatus::whereKey($this->getJobStatusId())->firstOrFail();

        $jsonArray = $this->getJsonArrayFromPath($this->filepath);

        $this->setProgressMax(count($jsonArray));

        for($index = $jobStatus->progress_now; $index < count($jsonArray); $index++)
        {
            $creditcardJson = $this->array_remove($jsonArray[$index],'credit_card');

            if($this->validate($jsonArray[$index]))
            {
                $account = Account::create($jsonArray[$index]);
                $account->creditcard()->create($creditcardJson);
            }

            $this->setProgressNow($index+1);
        }
        // Delete json file locally after the json processing is done.
        FileHelper::deleteFile($this->filepath);
    }


    /**
     * Validates the input array based on the given filters
     *
     * @param array $data The input array
     * @return bool return true or false based on if the validation succeeds
     */
    public function validate($data)
    {
        $v = Validator::make($data, $this->filters);

        if ($v->fails())
        {
            return false;
        }
        return true;
    }

    /**
     * Get the Json array from a filepath
     *
     * @param string $filepath The filepath to the json array
     * @return array the json array
     */
    function getJsonArrayFromPath($filepath) {
        $jsonFile = file_get_contents($filepath);
        return json_decode($jsonFile, true);
    }

    /**
     * Removes an item from the array and returns its value.
     *
     * @param array $arr The input array
     * @param string $key The key pointing to the desired value
     * @return array the value mapped to $key or null if none
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
