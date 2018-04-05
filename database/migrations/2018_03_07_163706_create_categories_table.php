<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->text('desc')->nullable();
            $table->string('keywords')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('name')->nullable();
            $table->string('text')->nullable();
            /*$table->integer('parent')->nullable();
            $table->integer('level');
            $table->integer('lft');
            $table->integer('rgt');
            $table->integer('weight');*/
            $table->nestedSet();
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
        Schema::dropIfExists('categories');
    }
}
