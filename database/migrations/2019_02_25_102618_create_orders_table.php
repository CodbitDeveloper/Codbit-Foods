<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->double('total_price');
            $table->string('status')->default('Pending');
            $table->string('address');
            $table->boolean('to_be_delivered')->default(false);
            $table->boolean('has_paid')->default(false);
            $table->integer('payment_type_id')->unsigned();
            $table->text('extra_note')->nullable();
            $table->boolean('is_delivered')->default(false);
            $table->integer('branch_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')->references('id')->on('customers')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('payment_type_id')->references('id')->on('payment_types')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')
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
        Schema::connection('mysql2')->dropIfExists('orders');
    }
}
