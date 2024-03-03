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
        Schema::create('repair_screen_components', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('screen_id');
            $table->unsignedBigInteger('screen_component_id');
            $table->decimal('price', 6);
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
        Schema::dropIfExists('repair_screen_components');
    }
};
