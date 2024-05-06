<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Models\Applicants\Applicant;

class ApplicantService
{
    public function addNewApplicant($applicantData)
    {
        return Applicant::create($applicantData);
    }

    public function manualProcessApplicantResume($applicantData){
        $client = new Client();
        $apiEndpoint=env('AI_API_URL').'/resume-insight';

        $payload = [
            'multipart' => [
                [
                    'name' => 'requirements',
                    'contents' => $applicantData['requirements'],
                ],
                [
                    'name' => 'resume',
                    'contents' => $applicantData['resume_or_cv'],
                    'filename' => 'resume.pdf',
                ],
            ],
        ];

        $response = $client->post($apiEndpoint, $payload);

        return json_decode($response->getBody()->getContents(), true);

        // // Check if the API request was successful
        // if ($response->getStatusCode() === 200) {
        //     // Update the applicant's status to 'Processed'
        //     $applicant->status = 'Processed';
        //     $applicant->save();
        //     \Log::info("Processed applicant {$applicant->id}");
        // } else {
        //     // Log or handle API request failure
        //     \Log::error("Failed to process applicant {$applicant->id}");
        // }
        
    }
}
