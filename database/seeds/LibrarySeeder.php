<?php

use Illuminate\Database\Seeder;

class LibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('libraries')->insert([
            'library_name' => 'ST. PETER\'S COLLEGE LIBRARY',
            'library_email' => 'spcmoi@gmail.com',
        ]);
        DB::table('libraries')->insert([
            'library_name' => 'SHS ',
            'library_email' => 'spcmoi@gmail.com',
        ]);

        DB::table('library_categories')->insert([
            'category_name' => 'Book',
            'category_reference' => 'hard reference',
        ]);

        DB::table('library_categories')->insert([
            'category_name' => 'Magazines',
            'category_reference' => 'hard reference',
        ]);

        DB::table('library_genres')->insert([
            'genre_name' => 'History',
        ]);

        DB::table('library_genres')->insert([
            'genre_name' => 'Fiction',
        ]);


        DB::table('library_books')->insert([
            'book_isbn' => '978-0-123-45678-9',
            'book_title' => 'Sample Book 1',
            'book_author' => 'John Doe',
            'book_publisher' => 'Shakes Ass',
            'book_img' => 'books/9780718155209.jpg',
            'book_qty' => 7,
            'book_price' => 350.00,
            'book_genre' => 1,
            'library_branch' => 1,
            'book_category' => 1,
            'book_available' => 5,
            'book_status' => 0,
            'book_deleted' => 0,
        ]);

        DB::table('library_books')->insert([
            'book_isbn' => '978-0-123-45677-8',
            'book_title' => 'Affection',
            'book_author' => 'Kristin Rouse',
            'book_publisher' => 'Unknown',
            'book_img' => 'books/affection.png',
            'book_qty' => 7,
            'book_price' => 375.50,
            'book_genre' => 1,
            'library_branch' => 1,
            'book_category' => 1,
            'book_available' => 20,
            'book_status' => 0,
            'book_deleted' => 0,
        ]);

        DB::table('library_status')->insert([
            'status_name' => 'Issued',
        ]);
        DB::table('library_status')->insert([
            'status_name' => 'Borrowed',
        ]);
        DB::table('library_status')->insert([
            'status_name' => 'Returned',
        ]);
        DB::table('library_status')->insert([
            'status_name' => 'Lost',
        ]);


        DB::table('library_circulation')->insert([
            'circulation_book_id' => 1,
            'circulation_members_id' => 3,
            'circulation_penalty' => 0,
            'circulation_due_date' => '',
            'circulation_date_borrowed' => '',
            'circulation_date_returned' => '',
            'circulation_status' => 2,
        ]);

    }
}
