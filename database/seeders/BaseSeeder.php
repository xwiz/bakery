<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BaseSeeder extends Seeder {

	public function truncateAndInsert($table, $data)
	{
		Schema::disableForeignKeyConstraints();
        DB::table($table)->where([])->delete();
        DB::table($table)->truncate();
        DB::table($table)->insert($data);
		Schema::enableForeignKeyConstraints();
	}

}