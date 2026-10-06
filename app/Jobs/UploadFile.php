<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DB;

class UploadFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $files;
    public $field;

    public $table;
    public $id;

    /**
     * Create a new job instance.
     */
    public function __construct($file, $field, $table, $id)
    {
        $this->file = $file;
        $this->field = $field;

        $this->table = $table;
        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
        $filename = uniqid() . '.' . $this->file->getClientOriginalExtension();
        $this->file->move(public_path('uploads/services'), $filename);

        DB::table($this->table)->where('id',$this->id)->update([$this->field => $filename]);
        
    }
}
