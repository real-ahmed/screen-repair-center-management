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
        Schema::create('screen_purchase_item_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('screen_id')->nullable()->default(0);
            $table->bigInteger('purchase_item_id')->nullable()->default(0);
            $table->integer('quantity');
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
        Schema::dropIfExists('screen_purchase_item_histories');
    }
};
