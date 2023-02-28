<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGRNSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('good_receive_notes', function (Blueprint $table) {
            $table->id();
            $table->text('remarks');
            $table->unsignedBigInteger('vendor_id')->nullable(); 
            $table->unsignedBigInteger('customer_id')->nullable(); 
            $table->text('corriour_by');
            $table->text('po_no');
            $table->string('stock');
            $table->longText('items');
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
        Schema::dropIfExists('good_receive_notes');
    }
}
