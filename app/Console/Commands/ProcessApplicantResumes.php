<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ApplicantService;

class ProcessApplicantResumes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'applicants:auto-process-resumes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Process pending Applicant Resumes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $applicantService = new ApplicantService();
        $applicantService->processPendingResumes();
    }
}
