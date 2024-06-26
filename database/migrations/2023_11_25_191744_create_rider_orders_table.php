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
            $table->bigInteger('rider_id')->nullable()->comment('Customer Id');
            $table->tinyInteger('transaction_mode')->default(0)->comment('1 => Card, 2 => Wallet, 3 => UPI, 4 => COD');
            $table->string('slug', 20)->nullable();

            $table->unsignedBigInteger('vehicle_id')->nullable()->comment('Product Primary Key');
            $table->string('product_price')->nullable();
            $table->string('product_name')->nullable();

            $table->unsignedBigInteger('mapped_vehicle_id')->nullable()->comment('Product Primary Key');
            $table->string('mapped_product_price')->nullable();
            $table->string('mapped_product_name')->nullable();
            $table->string('mapped_ev_range')->nullable();

            $table->string('cluster_manager')->nullable()->comment('Incase of customer is vendor');
            $table->string('tl_name')->nullable()->comment('Incase of customer is vendor');
            $table->string('client_name')->nullable()->comment('Incase of customer is vendor');
            $table->text('client_address')->nullable()->comment('Incase of customer is vendor');


            $table->string('accessories_id')->nullable()->comment('Multiples');
            $table->string('accessories_items')->nullable()->comment('Store accessories json');

            $table->timestamp('order_date')->nullable()->useCurrent();
            $table->timestamp('assigned_date')->nullable();
            $table->integer('subscription_days')->nullable();
            $table->timestamp('subscription_validity')->nullable();

            $table->string('ordered_ammount')->nullable();
            $table->string('security_ammount')->nullable();
            $table->text('requested_payload')->nullable();

            $table->string('monthely_running_distance')->nullable();

            $table->bigInteger('hub_id')->nullable()->comment('Hub Id');
            $table->tinyInteger('status_id')->default(2)->comment('1 => Assigned, 2 => Pending, 3 => Rejected, 4 => Returned');
            $table->tinyInteger('payment_status')->default(2)->comment('1 => Succes, 2 => Pending, 3 => Failed, 4 => Rejected, 5-Overdues');
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
