<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('notification_id');
            $table->string('slug', 12)->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('notification_parameter')->nullable();
            $table->string('notification_type')->nullable();
            $table->integer('notification_user_based')->nullable();
            $table->string('distance_remaining')->nullable();
            $table->string('days_remaining')->nullable();
            $table->integer('penalty_charge')->nullable();
            $table->string('penalty_charge_text')->nullable();
            $table->tinyInteger('is_send_charge')->default(1)->comment('1 => Yes, 2 => No');
            $table->dateTime('schedule_date')->nullable();
            $table->tinyInteger('status_id')->default(1)->comment('1 => Active, 2 => Inactive, 3 => Expired');
            $table->tinyInteger('notification_status')->default(2)->comment('1 => Sent, 2 => Pending, 3 => Queue');
            $table->string('user_slug')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
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
        Schema::dropIfExists('notifications');
    }
}
