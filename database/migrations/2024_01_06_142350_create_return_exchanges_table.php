<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_exchanges', function (Blueprint $table) {
            $table->bigIncrements('return_exchange_id');
            $table->string('slug', 20)->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('hub_id')->nullable();
            $table->integer('rider_id')->nullable();

            $table->integer('mapped_vehicle_id')->nullable();

            $table->string('refund_ammount')->nullable();
            $table->dateTime('assigned_date')->nullable();
            $table->dateTime('refund_date')->nullable();
            $table->text('note')->nullable();

            $table->tinyInteger('request_for')->default(0)->comment('1 => Return, 2 => Exchange');
            $table->tinyInteger('status_id')->default(2)->comment('1 => Resolved, 2 => Pending');
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
        Schema::dropIfExists('return_exchanges');
    }
}
