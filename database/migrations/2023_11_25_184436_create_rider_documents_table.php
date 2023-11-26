<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiderDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rider_documents', function (Blueprint $table) {
            $table->bigIncrements('rider_document_id');
            $table->bigInteger('rider_id')->nullable();
            $table->string('slug', 12)->nullable();
            $table->string('name')->nullable();
            $table->string('front_pic')->nullable();
            $table->string('back_pic')->nullable();
            $table->tinyInteger('document_type')->nullable()->comment('1 => Aadhar Card, 2 => Credit Score, 3 => Driving License, 4 => Electicity Bill, 5 => Pan Card, 6 => Passpost,  7 => Voter Id');

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
        Schema::dropIfExists('rider_documents');
    }
}
