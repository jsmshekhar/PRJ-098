<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiderTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rider_tokens', function (Blueprint $table) {
            $table->bigIncrements('rider_token_id');
            $table->string('slug', 20)->nullable();
            $table->integer('rider_id')->nullable();
            $table->tinyInteger('device_type')->nullable()->comment('1 => Android, 2 => iOS');

            $table->text('device_token')->nullable();
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
        Schema::dropIfExists('rider_tokens');
    }
}
