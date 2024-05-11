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
        'recommendation',
        'reason',
    ];

    protected function findApplicant($applicantId){
        return DB::table($this->table)->where('id', $applicantId)->first();
    }

    protected function applicantDetail($applicantId){
        return DB::table($this->table)
        ->select('applicants_tbl.id', 'applicants_tbl.applicant_name', 'applicants_tbl.applicant_email', 'applicants_tbl.status', 'applicants_tbl.recommendation', 'applicants_tbl.reason', 'jobs_tbl.title')
        ->join('jobs_tbl', 'applicants_tbl.job_id', '=', 'jobs_tbl.id')
        ->where('applicants_tbl.id', $applicantId)
        ->first();
    }

    protected function getAllAplicants(){
        return DB::table($this->table)
        ->select('id','job_id','applicant_name', 'applicant_email', 'status', 'created_at')->get();
    }
}
