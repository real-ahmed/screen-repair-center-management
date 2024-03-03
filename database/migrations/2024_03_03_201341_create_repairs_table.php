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
        Schema::create('repairs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_id');
            $table->timestamp('receive_date')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('expected_delivery_date')->useCurrent();
            $table->timestamps();
            $table->integer('receptionist_id');
            $table->string('reference_number', 100);
            $table->decimal('paid')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repairs');
    }
};
