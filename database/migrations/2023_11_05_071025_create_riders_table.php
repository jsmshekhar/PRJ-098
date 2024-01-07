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
            $table->string('slug', 20)->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email', 191)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('alternate_phone', 50)->nullable();
            $table->string('parent_phone', 50)->nullable();
            $table->string('sibling_phone', 50)->nullable();
            $table->string('owner_phone', 50)->nullable();
            $table->string('password')->nullable();
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('state_name', 100)->nullable();
            $table->string('university', 255)->nullable();
            $table->string('pincode', 25)->nullable();
            $table->text('photo')->nullable();
            $table->string('company_name')->nullable();
            $table->text('company_address')->nullable();
            $table->text('api_token')->nullable();
            $table->tinyInteger('profile_type')->default(1)->comment('1 => Corporate, 2 => Individual, 3 => Student, 4 => Vender');
            $table->tinyInteger('kyc_status')->default(2)->comment('1 => Verified, 2 => Pending, 3 => Red Flag');
            $table->tinyInteger('kyc_step')->default(0)->comment('Current Kys Steps');
            $table->tinyInteger('status_id')->default(1)->comment('1 => Active, 2 => Pending, 3 => Inactive, 4 => Deleted');
            $table->dateTime('is_step_selfie_done')->nullable()->useCurrentOnUpdate();
            $table->dateTime('is_personal_detail_done')->nullable()->useCurrentOnUpdate();
            $table->dateTime('is_id_proof_done')->nullable()->useCurrentOnUpdate();
            $table->dateTime('is_bank_detail_done')->nullable()->useCurrentOnUpdate();
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
