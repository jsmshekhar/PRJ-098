<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiderWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rider_wallets', function (Blueprint $table) {
            $table->bigIncrements('wallet_id');
            $table->bigInteger('rider_id')->nullable();
            $table->string('slug', 20)->nullable();
            $table->string('ammount')->nullable();
            $table->tinyInteger('status_id')->nullable()->comment('1 => Credited, 2 => Debited, 3 => Failed, 4 => Pending');
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
        Schema::dropIfExists('rider_wallets');
    }
}
