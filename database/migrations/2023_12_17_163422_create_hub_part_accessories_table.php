<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHubPartAccessoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hub_part_accessories', function (Blueprint $table) {
            $table->bigIncrements('hub_part_accessories_id');
            $table->string('slug', 20)->nullable();

            $table->unsignedBigInteger('hub_id')->nullable();

            $table->unsignedBigInteger('accessories_id')->nullable();
            $table->integer('accessories_category_id')->nullable();
            $table->string('accessories_title', 20)->nullable();
            $table->string('accessories_price', 20)->nullable();
            $table->string('assigned_price', 50)->nullable();
            $table->string('requested_qty', 20)->nullable();
            $table->string('assigned_qty', 20)->nullable();

            $table->text('requested_remark')->nullable();
            $table->text('assigned_remark')->nullable();

            $table->timestamp('requested_date')->nullable()->useCurrent();
            $table->timestamp('assign_date')->nullable();

            $table->tinyInteger('status_id')->default(1)->comment('1 => Raised, 2 => Shipped, 3 => Completed, 4 => Rejected');
            $table->tinyInteger('view_status')->default(0);
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
        Schema::dropIfExists('hub_part_accessories');
    }
}
