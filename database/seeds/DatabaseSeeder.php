<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    // seedコマンドを実行するとここのrunメソッドが実行される。そのためここで各テーブルを呼ぶ処理を書く必要がある。
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(TodosTableSeeder::class);
    }
}
