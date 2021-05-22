<?php

namespace App\Http\Controllers;


use App\Jobs\ProcessJson;
use App\Models\Account;
use App\Models\Creditcard;
use App\Post;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function store(Request $request)
    {

        return back()->with('message', 'Your file is submitted Successfully');
    }

    public function upload(Request $request)
    {
        /**
        $data = json_encode($request);
        error_log($data);
        print($data);
        Client::create($data);
        **/


        $jsonFile = file_get_contents($request->file('file'));
        //debug($jsonFile)
        $jsonArray = json_decode($jsonFile, true);

        ProcessJson::dispatch($request->file('file'));


        return response()->json(['success' => 'json']);

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

    function fetch_image()
    {

    }

    function delete_image(Request $request)
    {

    }
}