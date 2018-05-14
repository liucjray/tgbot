<?php

use Illuminate\Database\Seeder;

class StockTargetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stock_targets')->insert([
            [
                'name' => null,
                'code' => '0050',
                'created_at' => date('Y-m-d 00:00:00'),
            ],
            [
                'name' => 'CHT',
                'code' => '2412',
                'created_at' => date('Y-m-d 00:00:00'),
            ]
        ]);
    }
}
