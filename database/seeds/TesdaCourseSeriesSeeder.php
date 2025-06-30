<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TesdaCourseSeriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tesda_course_series')->truncate();
        DB::table('tesda_course_series')->insert([
            [
                'id' => 1,
                'course_id' => 1,
                'description' => 'SY 2018 - 2019',
                'active' => 1,
                'deleted' => 0,
                'createdby' => 30,
                'createddatetime' => Carbon::parse('2025-01-22 08:43:33'),
                'deletedby' => null,
                'deleteddatetime' => null,
                'updatedby' => 30,
                'updateddatetime' => Carbon::parse('2025-01-27 15:34:12'),
            ],
            [
                'id' => 2,
                'course_id' => 1,
                'description' => 'SY 2020 - 2021',
                'active' => 0,
                'deleted' => 0,
                'createdby' => 30,
                'createddatetime' => Carbon::parse('2025-01-22 08:44:22'),
                'deletedby' => null,
                'deleteddatetime' => null,
                'updatedby' => 30,
                'updateddatetime' => Carbon::parse('2025-01-27 15:34:12'),
            ],
        ]);
    }
}
