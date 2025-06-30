<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        // $this->call(TesdaCourseTypesSeeder::class);
        // $this->call(TesdaStudinfoSeeder::class);
        // $this->call(TesdaEnrolledStudSeeder::class);
        // $this->call(TesdaCourseSeriesSeeder::class);
        // $this->call(TesdaBatchesSeeder::class);


        $defaultTerms = [
            ['description' => 'Prelim', 'term_frequency' => 1, 'grading_perc' => 25, 'deleted' => 0, 'quarter' => 1 ] ,
            ['description' => 'Midterm', 'term_frequency' => 1, 'grading_perc' => 25, 'deleted' => 0, 'quarter' => 2 ],
            ['description' => 'Prefinal', 'term_frequency' => 1, 'grading_perc' => 25, 'deleted' => 0, 'quarter' => 3 ],
            ['description' => 'Final', 'term_frequency' => 1, 'grading_perc' => 25, 'deleted' => 0, 'quarter' => 4 ]
        ];

        $statusguide = [
            ['id' => 0, 'description' => 'Not Submitted'],
            ['id' => 1, 'description' => 'Submitted'],
            ['id' => 2, 'description' => 'Approved'],
            ['id' => 3, 'description' => 'Dean Approved'],
            ['id' => 4, 'description' => 'Chairman Approved'],
            ['id' => 5, 'description' => 'Posted'],
            ['id' => 6, 'description' => 'Pending'],
            ['id' => 7, 'description' => 'INC'],
            ['id' => 8, 'description' => 'Dropped'],

        ];

        $signatory = [
            ['id' => 1, 'description' => 'Expenses & Disbursement Signatories'],
            ['id' => 2, 'description' => 'General Ledger Signatories'],
            ['id' => 3, 'description' => 'Subsidiary Ledger Signatories'],
            ['id' => 4, 'description' => 'Trial Balance Signatoriess'],
            ['id' => 5, 'description' => 'Financial Statement Signatories'],
        ];

        $depreciation = [
            ['id' => 1, 'depreciation_desc' => 'Straight Line Method', 'isActive' => 1],
            ['id' => 2, 'depreciation_desc' => 'Double Declining Balance Method', 'isActive' => 0],
        ];

        DB::table('college_termgrading')->truncate();
        DB::table('college_termgrading')->insert($defaultTerms);
        DB::table('college_status_guide')->truncate();
        DB::table('college_status_guide')->insert($statusguide);
        DB::table('bk_signatory_grp')->truncate();
        DB::table('bk_signatory_grp')->insert($signatory);
        DB::table('bk_depreciation')->insert($depreciation);

    }

}
