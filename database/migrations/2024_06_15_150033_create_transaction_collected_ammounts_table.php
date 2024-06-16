<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionCollectedAmmountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_collected_ammounts', function (Blueprint $table) {
            $table->bigIncrements('transaction_collected_id');
            $table->string('slug', 20)->nullable();
            $table->integer('rider_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('transaction_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('ammount')->nullable();

            $table->tinyInteger('status_id')->default(1)->comment('1 => Active, 2 => Inactive');
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
        Schema::dropIfExists('transaction_collected_ammounts');
    }
}
