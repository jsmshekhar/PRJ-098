<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvServiceRequsetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ev_service_requsets', function (Blueprint $table) {
            $table->bigIncrements('requset_id');
            $table->string('slug', 20)->nullable();
            $table->integer('rider_id')->nullable();
            $table->string('name')->nullable();
            $table->string('number')->nullable();
            $table->string('ev_number')->nullable();
            $table->text('description')->nullable();

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
        Schema::dropIfExists('ev_service_requsets');
    }
}
