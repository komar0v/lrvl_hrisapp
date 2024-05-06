<?php

namespace App\Http\Controllers\Vacancy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ApplicantService;
use App\Models\Vacancy\Job AS M_Job;
use Illuminate\Support\Facades\Storage;

class ApplyJob extends Controller
{
    protected $applicantService;

    public function __construct(ApplicantService $applicantService)
    {
        $this->applicantService = $applicantService;
    }

    public function applyJob(Request $req, $jobId){
        $requestData=$req->validate([
            'applicant_name' => 'required',
            'applicant_email' => 'required|email',
            'resume_or_cv' => 'required|file|mimes:pdf'
        ]);

        $applicantData=[
            'job_id'=>$jobId,
            'applicant_name'=>$requestData['applicant_name'],
            'applicant_email'=>$requestData['applicant_email'],
            'resume_or_cv'=>$this->convertToBlob($requestData['resume_or_cv']),
        ];

        $job = M_Job::find($jobId);
        if (!$job) {
            return response()->json(["message" => "Job Id Not Found"], 404);
        }
        
        $createApplicant = $this->applicantService->addNewApplicant($applicantData);
        return response()->json(['message' => 'Job application submited', 'applicant_email' => $createApplicant->applicant_email]);
    }

    private function convertToBlob($file){
        $filePath = $file->getRealPath();
        return file_get_contents($filePath);
    }
}
