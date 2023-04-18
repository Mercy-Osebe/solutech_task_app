<?php

namespace Database\Seeders;

use App\Enums\TaskStatusesEnum;
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
   
        foreach (
            [
               TaskStatusesEnum::OPEN,
                TaskStatusesEnum::IN_PROGRESS,
                TaskStatusesEnum::COMPLETED,
                TaskStatusesEnum::ON_HOLD,
                TaskStatusesEnum::CANCELLED,
            ]
            as $status
        ) {
            DB::table('status')->insert([
                'name' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
     }
}
