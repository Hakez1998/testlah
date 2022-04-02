<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestStepExecutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_step_executions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_case_execution_id')->unsigned();        
            $table->foreign('test_case_execution_id')->references('id')->on('test_case_executions')->onDelete('cascade');
            $table->longText("title")->nullable();
            $table->longText("expected_result")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_step_executions');
    }
}
