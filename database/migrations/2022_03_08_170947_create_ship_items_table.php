<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ship_items', function (Blueprint $table) {
            $table->id();
            $table->string('track_id')->nullable();
            $table->string('slip_link')->nullable();
            $table->string('order_id')->nullable();
            $table->string('order_number')->nullable();
            $table->string('customer_id')->nullable();
            $table->text('vendor_id')->nullable();
            $table->string('frenchise_id')->nullable();
            $table->string('shipping_service_id')->nullable();
            $table->string('shipping_charges')->nullable();
            $table->text('booked_packet_json')->nullable();
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('ship_items');
    }
}
