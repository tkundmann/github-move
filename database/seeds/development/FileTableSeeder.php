<?php

namespace Database\Seeds\Development;

use App\Data\Models\Page;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $page1 = Page::where('code', '=', 'page_1')->first();

        DB::table('file')->insert([
            [
                'filename' => 'file1.txt',
                'name' => 'Example File #1',
                'url' => 'http://localhost/file1.txt',
                'size' => 543543,
                'page_id' => $page1->id,
                'created_at' => Carbon::now()
            ],
            [
                'filename' => 'file2.txt',
                'name' => 'Example File #2',
                'url' => 'http://localhost/file2.txt',
                'size' => 343543,
                'page_id' => $page1->id,
                'created_at' => Carbon::now()
            ],
            [
                'filename' => 'file3.txt',
                'name' => 'Example File #3',
                'url' => 'http://localhost/file3.txt',
                'size' => 243543,
                'page_id' => $page1->id,
                'created_at' => Carbon::now()
            ]
        ]);

    }
}