<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('riders', function (Blueprint $table) {
            $table->bigIncrements('rider_id');
            $table->string('slug', 12)->nullable();
            $table->string('name')->nullable();
            $table->string('email', 191)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->string('phone', 191)->unique();
            $table->string('password')->nullable();
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->text('photo')->nullable();
            $table->integer('subscription_days')->nullable();
            $table->timestamp('joining_date')->nullable()->useCurrent();
            $table->timestamp('subscription_validity')->nullable();
            $table->text('api_token')->nullable();
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
        Schema::dropIfExists('riders');
    }
}
