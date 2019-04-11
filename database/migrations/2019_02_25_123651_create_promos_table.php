<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('promos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->string('image');
            // The max uses this voucher has
            $table->integer('max_uses')->unsigned()->nullable();
            // How many times a customer can use this voucher.
            $table->integer('max_uses_customer')->unsigned()->nullable();
            $table->integer('promo_amount');
            $table->boolean('is_active')->default(true);

            /*$table->boolean('is_all_item')->default(false);
            $table->boolean('is_all_user')->default(false);*/

            //Minimum amount a customer needs to meet to enjoy promo
            $table->integer('min_amount')->nullable();
            //Maximum amount the restaurant is willing to give to a customer
            $table->integer('max_allowed_amount')->nullable();
            // Whether or not the voucher is a percentage or a fixed price. 
            $table->boolean('is_fixed')->default(true);
            // When the promo begins
            $table->timestamp('starts_at')->nullable();
            // When the promo ends
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Create table for associating promos to items(Many To Many)
        Schema::connection('mysql2')->create('item_promo', function(Blueprint $table) {
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('promo_id');
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('promo_id')->references('id')->on('promos')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->primary(['item_id', 'promo_id']);
        }); 

    // Create table for associating promos to users(Many To Many)
        Schema::connection('mysql2')->create('customer_promo', function(Blueprint $table) {
            $table->unsignedInteger('promo_id');
            $table->unsignedInteger('customer_id');
            $table->boolean('is_used')->default(false);
            $table->timestamps();

            $table->foreign('promo_id')->references('id')->on('promos')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['promo_id', 'customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('promos');
        Schema::connection('mysql2')->dropIfExists('item_promo');
        Schema::connection('mysql2')->dropIfExists('customer_promo');
    }
}
