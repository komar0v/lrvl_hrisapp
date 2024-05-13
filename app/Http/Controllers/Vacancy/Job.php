<?php

namespace App\Http\Controllers\Vacancy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vacancy\Job AS M_Job;

class Job extends Controller
{
    public function createNewJobOpenings(Request $req){
        $requestData=$req->validate([
            'title' => 'required|string',
            'description' => 'required',
            'requirements' => 'required',
            'cities' => 'required',
            'divisions' => 'required',
        ]);

        $data=[
            'title'=>$requestData['title'],
            'description'=>$requestData['description'],
            'requirements'=>$requestData['requirements'],
            'cities'=>$requestData['cities'],
            'divisions'=>$requestData['divisions']
        ];

        $job = M_Job::create($data);

        return response()->json(['message' => "Openings Job $job->title created"], 201);
    }

    public function showAllJobs(){
        return response()->json(M_Job::getAllJobs(), 200);
    }

    public function jobDetails($jobId){
        $job= M_Job::find($jobId);
        if (!$job) {
            return response()->json(["message" => "Job Id Not Found"], 404);
        }
        return response()->json($job, 200);
    }

    public function updateJob(Request $request, $jobId){
        $requestData = $request->validate([
            'title' => 'required|string',
            'description' => 'required',
            'cities' => 'required',
            'divisions' => 'required',
            'requirements' => 'required',
        ]);

        $data=[
            'title' => $requestData['title'],
            'description' => $requestData['description'],
            'cities' => $requestData['cities'],
            'divisions' => $requestData['divisions'],
            'requirements' => $requestData['requirements'],
        ];

        $job= M_Job::find($jobId);
        if (!$job) {
            return response()->json(["message" => "Job Id Not Found"], 404);
        }

        $job->fill($data);
        $job->save();

        return response()->json(["message" => "Job data updated", "job_title" => $data['title']], 200);
    }

}
