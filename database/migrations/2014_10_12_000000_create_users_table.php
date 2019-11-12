<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('extension_name');
            $table->string('birth_date');
            $table->string('email', 155)->unique();
            $table->string('password')->nullable();
            $table->char('type', 1)->default(config('app.default_user_type'));
            $table->char('status', 1)->default(config('app.default_user_status'));
            $table->string('owwa_id', 20)->nullable();
            $table->string('contact_no', 20)->nullable();
            $table->char('gender',1);
            $table->char('is_deleted', 1)->default(config('app.is_deleted_no'));
            $table->rememberToken();
            $table->timestamps();
            $table->unique(['owwa_id', 'id']);
            $table->index(['status', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
