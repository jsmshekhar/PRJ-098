<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiderOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rider_orders', function (Blueprint $table) {
            $table->bigIncrements('order_id');
            $table->bigInteger('rider_id')->nullable();
            $table->string('slug', 20)->nullable();
            $table->unsignedBigInteger('vehicle_id')->nullable()->comment('Product Primary Key');

            $table->unsignedBigInteger('product_category')->nullable();
            $table->integer('subscription_days')->nullable();
            $table->timestamp('joining_date')->nullable()->useCurrent();
            $table->timestamp('subscription_validity')->nullable();

            $table->string('product_price')->nullable();
            $table->string('product_name')->nullable();

            $table->tinyInteger('status_id')->default(1)->comment('1 => Active, 2 => Pending, 3 => Inactive, 4 => Deleted');
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
        Schema::dropIfExists('rider_orders');
    }
}
