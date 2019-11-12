<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSavingsFixedAmountMonthlyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('savings_fixed_amount_monthly', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('author_id');
            $table->double('fixed_amount');
            $table->timestamp('reminder_time');
            $table->char('is_reminder_checked', 1)->default(config('app.default_no'));
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
        Schema::dropIfExists('savings_fixed_amount_monthly');
    }
}
