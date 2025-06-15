<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'input_format',
        'output_format',
        'constraints',
        'sample_input',
        'sample_output',
        'explanation',
        'difficulty',
        'created_by',
    ];

    public function testCases()
    {
        return $this->hasMany(TestCase::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}