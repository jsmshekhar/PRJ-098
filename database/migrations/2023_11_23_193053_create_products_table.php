<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->string('slug', 12)->nullable();
            $table->string('title')->nullable();
            $table->string('speed')->nullable();
            $table->integer('rent_per_day')->nullable();
            $table->unsignedBigInteger('product_category_id')->nullable();
            $table->integer('ev_type_id')->nullable();
            $table->string('ev_number')->nullable();
            $table->integer('ev_category')->nullable();
            $table->string('profile_category', 100)->nullable();
            $table->integer('hub_id')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('products');
    }
}
