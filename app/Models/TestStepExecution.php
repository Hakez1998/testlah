<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestStepExecution extends Model
{
    use HasFactory;

    protected $fillable = ['test_case_execution_id', 'title', 'expected_result'];

    public function testResults()
    {
        return $this->hasMany(TestResults::class, 'test_step_execution_id');
    }
}
