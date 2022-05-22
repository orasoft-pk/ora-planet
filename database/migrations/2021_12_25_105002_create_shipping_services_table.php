<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('cost')->default(0);
            $table->string('distance')->default(0);
            $table->string('distance_unit')->nullable();
            $table->string('logo')->nullable();
            $table->text('text')->nullable();
            $table->text('json')->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('shipping_services');
    }
}
