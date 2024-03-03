<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('site_name', 50);
            $table->string('invoice_name');
            $table->string('address');
            $table->string('phone', 12);
            $table->string('sac_phone', 12)->nullable();
            $table->string('money_sign', 30);
            $table->text('invoice_policy');
            $table->string('insurance_term');
            $table->string('default_new_user_pass');
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
        Schema::dropIfExists('general_settings');
    }
};
