<?php

use Illuminate\Database\Seeder;

class TesdaEnrolledStudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear the table to avoid redundancy
        DB::table('tesda_enrolledstud')->truncate();

        // Get sample data from tesda_studinfo
        $students = DB::table('tesda_studinfo')->select('id as studid')->limit(5)->get();

        // Create seed data
        $enrolledStudents = [];
        foreach ($students as $student) {
            $enrolledStudents[] = [
                'studid' => $student->studid,
                'syid' => 1, // Example: Academic Year ID
                'levelid' => 1, // Example: Level ID
                'sectionid' => 1, // Example: Section ID
                'courseid' => 2, // Example: Section ID
                'batchid' => 1, // Example: Section ID
                'teacherid' => 1, // Example: Teacher ID
                'schemeid' => 1, // Example: Payment Scheme ID
                'dateenrolled' => now(),
                'createdby' => 1, // Example: Admin User
                'studstatus' => array_rand(array_flip(array_unique(range(1, 7)))), // No repetition in Status
                'promotionstatus' => 0, // Default Promotion Status
                'remarks' => 'Sample enrollment data', // Example Remarks
            ];
        }

        // Insert into the table
        DB::table('tesda_enrolledstud')->insertOrIgnore($enrolledStudents);
    }
}
