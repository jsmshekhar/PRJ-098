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
        Schema::dropIfExists('products');
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->string('slug', 12)->nullable();
            $table->string('title')->nullable();
            $table->string('speed')->nullable();
            $table->integer('rent_cycle')->nullable()->comment('15-Days, 30-Days');
            $table->string('per_day_rent')->nullable();
            $table->string('total_rent')->nullable();
            $table->integer('bettery_type')->nullable()->comment('1-Swappable, 2-Fixed');
            $table->string('km_per_charge')->nullable()->comment('Single charge Run Time');;
            $table->unsignedBigInteger('hub_id')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('is_display_on_app')->default(2)->comment('1 => Yes, 2 => No');
            $table->string('image')->nullable();
            $table->string('chassis_number')->nullable();
            $table->string('ev_number')->nullable();
            $table->string('gps_emei_number')->nullable();
            $table->integer('ev_category_id')->nullable();
            $table->integer('ev_type_id')->nullable();
            $table->integer('profile_category')->nullable()->comment('1 => CORPORATE, 2 => INDIVIDUAL, 3 => STUDENT, 4 => VENDER');
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
