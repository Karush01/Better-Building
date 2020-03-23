<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('city');
            $table->string('address');
            $table->string('image')->nullable();
            $table->string('description')->nullable();
            $table->integer('management_company_id');
            $table->integer('tenants_count');
            $table->integer('floors');
            $table->integer('parking_levels');
            $table->string('entrepreneur');
            $table->string('constructor');
            $table->string('committee_members');
            $table->string('building_description');
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buildings');
    }
}
