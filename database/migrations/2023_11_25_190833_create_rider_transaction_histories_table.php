<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiderTransactionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rider_transaction_histories', function (Blueprint $table) {
            $table->bigIncrements('rider_transaction_id');
            $table->bigInteger('rider_id')->nullable();
            $table->string('slug', 12)->nullable();
            $table->string('transaction_id',151)->nullable();
            $table->string('transaction_ammount',50)->nullable();
            $table->tinyInteger('transaction_type')->nullable()->comment('1 => Credited, 2 => Debited');
            $table->tinyInteger('transaction_mode')->nullable()->comment('1 => Card, 2 => Wallet, 3 => UPI');
            $table->tinyInteger('status_id')->nullable()->comment('1 => Succes, 2 => Pending, 3 => Failed, 4 => Rejected');
            $table->string('transaction_notes')->nullable();
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
        Schema::dropIfExists('rider_transaction_histories');
    }
}
