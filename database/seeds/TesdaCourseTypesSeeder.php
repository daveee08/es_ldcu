<?php

use Illuminate\Database\Seeder;

class TesdaCourseTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tesda_courses')->truncate();
        DB::table('tesda_course_type')->truncate();

        DB::table('tesda_course_type')->insert([
            ['description' => 'NC I', 'createddatetime' => now(), 'updateddatetime' => now()],
            ['description' => 'NC II', 'createddatetime' => now(), 'updateddatetime' => now()],
            ['description' => 'NC III', 'createddatetime' => now(), 'updateddatetime' => now()],
            ['description' => 'NC IV', 'createddatetime' => now(), 'updateddatetime' => now()],
            // Add more course types here as needed
        ]);

        DB::table('tesda_courses')->insert([
            [
                'course_code' => 'CG-NCII',
                'course_name' => 'Caregiving NC II',
                'course_type' => 2, // Assuming "NC II" has ID = 1
                'course_duration' => 6,
                'deleted' => 0,
                'createddatetime' => now(),
                'updateddatetime' => now(),
            ],
            [
                'course_code' => 'FB-NCII',
                'course_name' => 'Food and Beverage NC II',
                'course_type' => 2, // Assuming "NC II" has ID = 1
                'course_duration' => 5,
                'deleted' => 0,
                'createddatetime' => now(),
                'updateddatetime' => now(),
            ],
            [
                'course_code' => 'HC-NCII',
                'course_name' => 'Housekeeping NC II',
                'course_type' => 2, // Assuming "NC II" has ID = 1
                'course_duration' => 6,
                'deleted' => 0,
                'createddatetime' => now(),
                'updateddatetime' => now(),
            ],
            [
                'course_code' => 'BA-NCII',
                'course_name' => 'Bookkeeping NC II',
                'course_type' => 2, // Assuming "NC II" has ID = 1
                'course_duration' => 4,
                'deleted' => 0,
                'createddatetime' => now(),
                'updateddatetime' => now(),
            ],
            [
                'course_code' => 'EL-NCIII',
                'course_name' => 'Electrical Installation and Maintenance NC III',
                'course_type' => 3, // Assuming "NC II" has ID = 1
                'course_duration' => 6,
                'deleted' => 0,
                'createddatetime' => now(),
                'updateddatetime' => now(),
            ],
        ]);
    }
}
