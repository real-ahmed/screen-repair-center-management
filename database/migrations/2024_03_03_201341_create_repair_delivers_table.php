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
        Schema::create('repair_delivers', function (Blueprint $table) {
            $table->bigInteger('repair_id')->nullable();
            $table->string('reference_number')->nullable();
            $table->bigIncrements('id');
            $table->decimal('total_amount');
            $table->decimal('received_amount')->default(0);
            $table->integer('status')->default(0);
            $table->string('note')->nullable();
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
        Schema::dropIfExists('repair_delivers');
    }
};
