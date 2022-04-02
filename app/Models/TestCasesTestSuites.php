<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCasesTestSuites extends Model
{
    use HasFactory;
    protected $fillable = ['test_suite_id', 'test_case_id'];
}
