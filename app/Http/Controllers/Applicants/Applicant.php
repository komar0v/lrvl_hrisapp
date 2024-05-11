<?php

namespace App\Http\Controllers\Applicants;

use Illuminate\Http\Request;
use App\Services\ApplicantService;
use App\Http\Controllers\Controller;
use App\Models\Applicants\Applicant AS M_Applicant;
use App\Models\Vacancy\Job AS M_Job;

class Applicant extends Controller
{
    protected $applicantService;

    public function __construct(ApplicantService $applicantService)
    {
        $this->applicantService = $applicantService;
    }
    
    public function downloadApplicantResume($applicantId){
        $applicant= M_Applicant::find($applicantId);
        if (!$applicant) {
            return response()->json(["message" => "Applicant Id Not Found"], 404);
        }
        
        $resumeContent = $applicant->resume_or_cv;

        $fileName = $applicant->applicant_name . '_Resume.pdf';

        return response($resumeContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    public function manualProcessApplicant($applicantId){
        $applicant= M_Applicant::findApplicant($applicantId);
        if (!$applicant) {
            return response()->json(["message" => "Applicant Id Not Found"], 404);
        }

        $job= M_Job::find($applicant->job_id);

        $applicantData=[
            'requirements'=>$job['requirements'],
            'cities'=>$job['cities'],
            'divisions'=>$job['divisions'],
            'resume_or_cv'=>$applicant->resume_or_cv,
        ];

        $applicantInfos=[
            'applicant_name'=>$applicant->applicant_name,
            'applicant_email'=>$applicant->applicant_email,
            'job_to_apply'=>$job['title']
        ];

        $analizeApplicantResume = $this->applicantService->manualProcessApplicantResume($applicantData);
        $applicantResultReason = $analizeApplicantResume['message'];
        $applicantStatus = $analizeApplicantResume['status'];

        $data=[
            'status'=>'Processed',
            'recommendation'=>$applicantStatus,
            'reason'=>$applicantResultReason
        ];

        $applicantMdl = M_Applicant::find($applicantId);
        $applicantMdl->fill($data);
        $applicantMdl->save();

        return response()->json(["message" => "Application Has Been Processed", "applicant_info"=>$applicantInfos], 200);
    }

    public function showAllApplicants(){
        return response()->json(M_Applicant::getAllAplicants(), 200);
    }

    public function detailsApplicant($applicantId){
        $applicantData = M_Applicant::applicantDetail($applicantId);

        if (!$applicantData) {
            return response()->json(["message" => "Data tidak ditemukan"], 404);
        }
        return response()->json($applicantData, 200);
    }

    
}
