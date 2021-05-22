<?php

namespace App\Jobs;

use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessJson implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    ///Users/gerbrechtghijsels/Projects/DatasetReader/JSONTest/test.json
    protected $filepath;

    /**
     * Create a new job instance.
     *
     * @param $filePath
     */
    public function __construct( $filepath)
    {
        $this->filepath = $filepath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $jsonFile = file_get_contents($this->filepath);
        $jsonArray = json_decode($jsonFile, true);


        foreach ($jsonArray as $item)
        {
            $creditcardJson = $this->array_remove($item,'credit_card');
            $account = Account::create($item);

            $account->creditcard()->create($creditcardJson);

        }
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
