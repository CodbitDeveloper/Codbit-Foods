<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('deliveries', function (Blueprint $table) {
            $table->integer('order_id')->unsigned();
            $table->integer('dispatch_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->primary(['order_id', 'dispatch_id']);

            $table->foreign('order_id')->references('id')->on('orders')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('dispatch_id')->references('id')->on('dispatches')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('deliveries');
    }
}
