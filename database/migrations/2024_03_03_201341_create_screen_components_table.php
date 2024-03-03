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
        Schema::create('screen_components', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('brand_id')->nullable();
            $table->integer('category_id');
            $table->integer('subcategory_id')->nullable();
            $table->string('code');
            $table->decimal('selling_price');
            $table->integer('auto_request_quantity')->nullable();
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
        Schema::dropIfExists('screen_components');
    }
};
