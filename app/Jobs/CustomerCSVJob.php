<?php

namespace App\Jobs;

use App\Models\Customer;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CustomerCSVJob implements ShouldQueue
{
    use Batchable,Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $data;
    public $columns;
    public function __construct($data, $columns)
    {
        $this->data=$data;
        $this->columns=$columns;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->data as $index => $item) {
            logger($item);
            unset($item[0]);
            $record = array_combine($this->columns, $item);
            Customer::create($record);
        }
    }
}
