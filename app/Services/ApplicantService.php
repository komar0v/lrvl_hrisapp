<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\Applicants\Applicant;
use App\Models\Vacancy\Job AS M_Job;
use Exception;

use function PHPUnit\Framework\isEmpty;

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
                    'name' => 'cities',
                    'contents' => $applicantData['cities'],
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

    public function processPendingResumes()
    {
        $countPendingResumes = Applicant::where('status', 'Pending')->count();
        if ($countPendingResumes === 0) {
            Log::info('No pending resumes to process.');
        }else{
            Log::info("Starting auto process $countPendingResumes pending resumes.");

            // Get all applicants with status 'Pending'
            // $applicants = Applicant::where('status', 'Pending')->get();

            $applicants = Applicant::where('status', 'Pending')->limit(1)->get();
            Log::info("Processing one resume every one minute.");
            foreach ($applicants as $applicant) {
                // Process each applicant
                $this->autoProcessApplicantResume($applicant);
            }
        }
    }

    private function autoProcessApplicantResume($applicant)
    {
        Log::info("Processing applicant {$applicant->id}");
        $job= M_Job::find($applicant->job_id);
        $client = new Client();
        $apiEndpoint=env('AI_API_URL').'/resume-insight';

        $payload = [
            'multipart' => [
                [
                    'name' => 'requirements',
                    'contents' => $job['requirements'],
                ],
                [
                    'name' => 'cities',
                    'contents' => $job['cities'],
                ],
                [
                    'name' => 'resume',
                    'contents' => $applicant['resume_or_cv'],
                    'filename' => 'resume.pdf',
                ],
            ],
        ];

        try {
            $response = $client->post($apiEndpoint, $payload);
            $responseData = json_decode($response->getBody()->getContents(), true);
            // Log::info(json_decode($response->getBody()->getContents(), true));
        if ($response->getStatusCode() === 200) {
            $applicant->status = 'Processed';
            $applicant->recommendation = $responseData['status'];
            $applicant->reason = $responseData['message'];
            $applicant->save();
            Log::info("Processed applicant {$applicant->id}");
        } else {
            // Log or handle API request failure
            Log::error("Failed to process applicant {$applicant->id}");
        }
        }catch(Exception $e) {
            Log::error('Error processing applicant resumes: ' . $e->getMessage());
        }
        
    }
}
