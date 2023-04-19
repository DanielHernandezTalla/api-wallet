<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('section_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->string('description')->nullable();
            $table->float('amount')->unsigned()->nullable();
            $table->string('type')->nullable();
            $table->timestamps();

            $table->foreign('section_id')->references('id')->on('sections');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movements');
    }
}
