<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiderNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rider_notifications', function (Blueprint $table) {
            $table->bigIncrements('rider_notification_id');
            $table->string('slug', 20)->nullable();
            $table->integer('rider_id')->nullable();
            $table->bigInteger('notification_id')->nullable();

            $table->string('title')->nullable();
            $table->text('description')->nullable();

            $table->tinyInteger('status_id')->default(1)->comment('1 => Sent, 2 => Pending');
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
        Schema::dropIfExists('rider_notifications');
    }
}
