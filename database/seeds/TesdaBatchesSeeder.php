<?php

use Illuminate\Database\Seeder;

class TesdaBatchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tesda_batches')->truncate();
        // Get the maximum ID from tesda_courses
        $maxCourseId = DB::table('tesda_courses')->max('id');
        $courseSeries = DB::table('tesda_course_series')->max('id');

        // Define how many records to insert
        $numberOfBatches = 5; // Adjust as needed

        $data = [];
        for ($i = 0; $i < $numberOfBatches; $i++) {
            $data[] = [
                'course_id' => rand(1, $maxCourseId), // Random course_id
                'batch_desc' => 'Batch ' . ($i + 1),
                'batch_series_id' => rand(1, $courseSeries),
                'date_from' => now()->subDays(rand(1, 30))->toDateString(),
                'date_to' => now()->addDays(rand(1, 30))->toDateString(),
                'batch_capacity' => rand(10, 50),
                'semesterID' => rand(1, 2),
                'buildingID' => rand(1, 5),
                'roomID' => rand(1, 10),
                'isactive' => rand(0, 1),
                'deleted' => 0,
                'createdby' => rand(1, 10),
                'createddatetime' => now(),
                'updatedby' => null,
                'updateddatetime' => null,
            ];
        }

        // Insert into the tesda_batches table
        DB::table('tesda_batches')->insert($data);
    }
}
