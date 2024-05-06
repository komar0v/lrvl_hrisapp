<?php

namespace App\Models\Applicants;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Applicant extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'applicants_tbl';

    protected $fillable = [
        'job_id',
        'applicant_name',
        'applicant_email',
        'resume_or_cv',
        'status',
        'reason',
    ];

    protected function findApplicant($applicantId){
        return DB::table($this->table)->where('id', $applicantId)->first();
    }

    protected function getAllAplicants(){
        return DB::table($this->table)
        ->select('id','job_id','applicant_name', 'applicant_email', 'status', 'reason')->get();
    }
}
