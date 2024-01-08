<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_files', function (Blueprint $table) {
            $table->bigIncrements('media_file_id');
            $table->string('slug', 20)->nullable();
            $table->integer('ref_id')->nullable();
            $table->integer('ref_table_id')->nullable();
            $table->string('file_name')->nullable();
            $table->tinyInteger('file_type')->default(1)->comment('1 => Image, 2 => Docs');
            $table->tinyInteger('module_type')->default(1)->comment('1 => Assigned, 2 => Return, 3 => Exchange');
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
        Schema::dropIfExists('media_files');
    }
}
