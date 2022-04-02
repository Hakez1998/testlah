<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSuite extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'project_id'];

    public function testCases()
    {
        return $this->belongsToMany(TestCase::class, 'test_cases_test_suites', 'test_suite_id',  'test_case_id');
    }

    public function projects()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

}
