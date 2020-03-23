<?php

use Illuminate\Database\Seeder;

class CyclicDaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('skus')->where('сyclic_days', '7')->update(['сyclic_days' => '+1 week']);
        DB::table('skus')->where('сyclic_days', '14')->update(['сyclic_days' => '+2 week']);
        DB::table('skus')->where('сyclic_days', '30')->update(['сyclic_days' => '+1 month']);
        DB::table('skus')->where('сyclic_days', '90')->update(['сyclic_days' => '+3 month']);
        DB::table('skus')->where('сyclic_days', '180')->update(['сyclic_days' => '+6 month']);
        DB::table('skus')->where('сyclic_days', '360')->update(['сyclic_days' => '+1 year']);

        DB::table('durations')->where('сyclic_days', '7')->update(['сyclic_days' => '+1 week']);
        DB::table('durations')->where('сyclic_days', '14')->update(['сyclic_days' => '+2 week']);
        DB::table('durations')->where('сyclic_days', '30')->update(['сyclic_days' => '+1 month']);
        DB::table('durations')->where('сyclic_days', '90')->update(['сyclic_days' => '+3 month']);
        DB::table('durations')->where('сyclic_days', '180')->update(['сyclic_days' => '+6 month']);
        DB::table('durations')->where('сyclic_days', '360')->update(['сyclic_days' => '+1 year']);


        DB::table('tasks')->where('сyclic_days', '7')->update(['сyclic_days' => '+1 week']);
        DB::table('tasks')->where('сyclic_days', '14')->update(['сyclic_days' => '+2 week']);
        DB::table('tasks')->where('сyclic_days', '30')->update(['сyclic_days' => '+1 month']);
        DB::table('tasks')->where('сyclic_days', '90')->update(['сyclic_days' => '+3 month']);
        DB::table('tasks')->where('сyclic_days', '180')->update(['сyclic_days' => '+6 month']);
        DB::table('tasks')->where('сyclic_days', '360')->update(['сyclic_days' => '+1 year']);

    }
}
