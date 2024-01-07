<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiderOrderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rider_order_payments', function (Blueprint $table) {
            $table->bigIncrements('rider_order_payment_id');
            $table->string('slug', 20)->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('rider_id')->nullable();
            $table->integer('mapped_vehicle_id')->nullable();
            $table->dateTime('from_date')->nullable();
            $table->dateTime('to_date')->nullable();

            $table->tinyInteger('status_id')->default(1)->comment('1 => Mobilized, 2 => Immobilized');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rider_order_payments');
    }
}
