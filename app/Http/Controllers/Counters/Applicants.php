<?php

namespace App\Http\Controllers\Counters;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Applicants\Applicant AS M_Applicant;

class Applicants extends Controller
{
    public function countApplicants(){
        $applicantCounter = M_Applicant::countApplicants();

        $data=[
            'applicant_total'=>$applicantCounter->applicant_total,
            'applicant_pending'=>$applicantCounter->applicant_pending,
            'applicant_processed'=>$applicantCounter->applicant_processed
        ];

        return response()->json($data, 200);
    }

    public function countApplicantResults(){
        $applicantResultsCounter = M_Applicant::countApplicantResults();

        $data=[
            'applicant_rejected'=>$applicantResultsCounter->applicant_rejected,
            'applicant_processed'=>$applicantResultsCounter->applicant_processed,
        ];

        return response()->json($data, 200);
    }
}
