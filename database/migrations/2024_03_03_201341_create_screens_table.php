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
        Schema::create('screens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 100);
            $table->string('serial')->nullable();
            $table->integer('brand_id');
            $table->string('model', 100);
            $table->integer('engineer_receive_id');
            $table->integer('engineer_maintenance_id');
            $table->integer('warehouse_id');
            $table->decimal('repair_amount')->nullable();
            $table->integer('status')->default(0)->comment('0->receive
1->repaired
2->delivered
3->cant repair
4->ready to sale

');
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
        Schema::dropIfExists('screens');
    }
};
