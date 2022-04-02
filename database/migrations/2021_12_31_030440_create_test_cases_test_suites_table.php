<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestCasesTestSuitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_cases_test_suites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_suite_id')->unsigned();
            $table->unsignedBigInteger('test_case_id')->unsigned();        
            $table->foreign('test_suite_id')->references('id')->on('test_suites')->onDelete('cascade');
            $table->foreign('test_case_id')->references('id')->on('test_cases')->onDelete('cascade');
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
        Schema::dropIfExists('test_cases_test_suites');
    }
}
