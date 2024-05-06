<?php

namespace App\Models\Vacancy;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'jobs_tbl';

    protected $fillable = [
        'title',
        'description',
        'requirements',
    ];

    protected function getAllJobs()
    {
        return DB::table($this->table)->get();
    }
}
