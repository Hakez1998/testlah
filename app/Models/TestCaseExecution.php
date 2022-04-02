<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCaseExecution extends Model
{
    use HasFactory;

    protected $fillable = ['test_execution_id', 'title'];

    public function testStepExecution()
    {
        return $this->hasMany(TestStepExecution::class, 'test_case_execution_id');
    }
}
