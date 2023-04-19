<?php

namespace Database\Seeders;

use App\Enums\TaskStatusesEnum;
use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // seed the status data via the status model
   
        foreach (
                Status::$statuses as $status
        ) {
            DB::table('status')->insert([
                'name' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
       
        
     }
}
