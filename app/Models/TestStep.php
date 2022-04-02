<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestStep extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'test_case_id', 'expected_result'];

    public function testResult()
    {
        return $this->hasMany(TestResult::class, 'test_step_id');
    }
}
