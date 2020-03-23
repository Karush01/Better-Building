<?php

use Illuminate\Database\Seeder;
use App\Sku;

class SkuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $first_sku = new Sku();
        $first_sku->name = 'החלפת מינורות';
        $first_sku->description = 'כל שבועיים';
        $first_sku->сyclic_days = 14;
        $first_sku->save();

        $sku2 = new Sku();
        $sku2->name = 'ניקוי בריכה';
        $sku2->description = 'ניקוי בריכה';
        $sku2->сyclic_days = 14;
        $sku2->save();
    }
}
