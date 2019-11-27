<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TodosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 全ての行を削除してリセットする
        DB::table('todos')->truncate();
        DB::table('todos')->insert([
            [
                'title'         => 'Laravel Lessonを終わらせる',
                'created_at'    => Carbon::create(2018, 1, 1), 
                'updated_at'    => Carbon::create(2018, 1, 4), 
            ],
            [
                'title'         => 'レビューに向けて理解を深める',
                'created_at'    => Carbon::create(2018, 2, 1), 
                'updated_at'    => Carbon::create(2018, 2, 5), 
            ],
        ]);
    }
}
