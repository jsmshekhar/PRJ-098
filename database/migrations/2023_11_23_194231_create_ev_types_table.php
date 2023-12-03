<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ev_types', function (Blueprint $table) {
            $table->bigIncrements('ev_type_id');
            $table->string('slug', 12)->nullable();
            $table->integer('ev_category')->nullable()->comment('1 => Two Wheeler, 2 => Three Wheeler');
            $table->string('ev_type_name')->nullable();
            $table->float('rs_perday')->nullable();
            $table->integer('range')->nullable();
            $table->string('carrying_capecity')->nullable();
            $table->string('speed')->nullable();
            $table->string('battery_capacity')->nullable();
            $table->string('registration_driving_licence')->nullable();
            $table->string('battary_swapping')->nullable();
            $table->tinyInteger('status_id')->default(1)->comment('1 => Active, 2 => Inactive');
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
        Schema::dropIfExists('ev_types');
    }
}
