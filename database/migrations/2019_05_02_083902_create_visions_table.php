<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('author_id');
            $table->double('target_amount');
            $table->integer('target_cat_id');
            $table->integer('vision_cat_others_id')->default(0);
            $table->integer('target_date');
            $table->text('description');
            $table->string('family_code');
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
        Schema::dropIfExists('visions');
    }
}
