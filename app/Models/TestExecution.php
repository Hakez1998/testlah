<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestExecution extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'user_id', 'email', 'title', 'tester_name', 'invitation_code'];

    public function testCaseExecution()
    {
        return $this->hasMany(TestCaseExecution::class, 'test_execution_id');
    }
}
