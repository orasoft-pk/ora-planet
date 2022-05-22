<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQtyCheckKeyInVendorordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendororders', function (Blueprint $table) {
            $table->boolean('qty_check')->default(0);
            $table->string('qty_remarks')->nullable();
            $table->boolean('qlty_check')->default(0);
            $table->string('qlty_remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendororders', function (Blueprint $table) {
            //
        });
    }
}
