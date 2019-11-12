<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFamilyMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('family_members', function (Blueprint $table) {
            // columns
            $table->increments('id');
            $table->integer('family_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->char('status', 1)->default(config('app.default_family_member_status'));
            $table->timestamps();

            // indexes
            $table->foreign('family_id')
                ->references('id')
                ->on('families');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('family_members');
    }
}
