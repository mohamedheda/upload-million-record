<?php

namespace App\Http\Controllers;

use App\Jobs\CustomerCSVJob;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\File;

class CustomerController extends Controller
{
    public function index()
    {
        return view('upload-csv');
    }

    public function upload()
    {
        $batch = Bus::batch([])->dispatch();
        if (request()->has('csv')) {
            $file = file(request()->csv);
            unset($file[0]);
            $chunks = array_chunk($file, 1000);
            $columns = $this->columns();
            foreach ($chunks as $index => $chunk) {
                $data = array_map('str_getcsv', $chunk);
                $batch->add(new CustomerCSVJob($data, $columns));
            }
            return "CSVs File uploaded successfully";
        }
        return "Please upload file .";
    }

    private function columns()
    {
        return [
            'customer_id',
            'first_name',
            'last_name',
            'company',
            'city',
            'country',
            'phone_1',
            'phone_2',
            'email',
            'subscription_date',
            'website',
        ];
    }
}
