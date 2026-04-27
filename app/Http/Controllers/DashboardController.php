<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if(Auth::user()->role === 'admin') {
            $analytics = $this->adminDashboard();
        } else {
            $analytics = $this->companyOwnerDashboard();
        }
        return view('dashboard.index', compact(['analytics']));
    }

    private function adminDashboard() {
        // Last 30 days active users (job-seeker role)
        $activeUsers = User::where('role', 'job_seeker')
        ->where('last_login_at', '>=', Carbon::now()->subDays(30))
        ->count();
        // $activeUsers = User::whereNotNull('last_login_at')
        // ->where('last_login_at', '>=', now()->subDays(30))
        // ->where('role', 'job_seeker')
        // ->count();

        // Total Jobs ( not deleted )
        $totalJobs = JobVacancy::whereNull('deleted_at')
        ->count();

        // Total Applications ( not deleted )
        $totalApplications = JobApplication::whereNull('deleted_at')
        ->count();

        // Most Applied Jobs
        $mostAppliedJobs = JobVacancy::withCount('jobapplications as totalCountJobApplication')
        ->whereNull('deleted_at')
        ->orderByDesc('totalCountJobApplication')
        ->limit(5)
        ->get();

        // Top Converting Job Posts
        $conversionJobRates = JobVacancy::withCount('jobapplications as totalCountJobApplication')
        ->whereNull('deleted_at')
        ->having('totalCountJobApplication', '>', 0)
        ->limit(5)
        ->orderByDesc('totalCountJobApplication')
        ->get()
        ->map(function($job) {
            if($job->view_count > 0) {
                $job->conversionRate = round(( $job->totalCountJobApplication / $job->view_count ) * 100 , 2);
            } else {
                $job->conversionRate = 0;
            }
            return $job;
        });
        $analytics = [
            'activeUsers' => $activeUsers,
            'totalJobs' => $totalJobs,
            'totalApplications' => $totalApplications,
            'mostAppliedJobs' => $mostAppliedJobs,
            'conversionJobRates' => $conversionJobRates,
        ];
        return $analytics;
    }

    private function companyOwnerDashboard() {

        $company = Auth::user()->company;

        // filter active users by applying to jobs of the company
        $activeUsers = User::whereNotNull('last_login_at')
        ->where('last_login_at', '>=', now()->subDays(30))
        ->where('role', 'job_seeker')
        ->whereHas('jobapplications', function($query) use ($company) {
            $query->whereIn('job_vacancy_id', $company->Jobvacancy()->pluck('id'));
        })
        ->count();

        // total jobs of the company
        $totalJobs = $company->Jobvacancy->count();

        // total applications of the company
        $totalApplications = JobApplication::whereIn('job_vacancy_id', $company->Jobvacancy()->pluck('id'))->count();

        // Most Applied Jobs
        $mostAppliedJobs = JobVacancy::withCount('jobapplications as totalCountJobApplication')
        ->whereIn('id', $company->Jobvacancy()->pluck('id'))
        ->orderByDesc('totalCountJobApplication')
        ->limit(5)
        ->get();

        // Top Converting Job Posts
        $conversionJobRates = JobVacancy::withCount('jobapplications as totalCountJobApplication')
        ->whereIn('id', $company->Jobvacancy()->pluck('id'))
        ->having('totalCountJobApplication', '>', 0)
        ->limit(5)
        ->orderByDesc('totalCountJobApplication')
        ->get()
        ->map(function($job) {
            if($job->view_count > 0) {
                $job->conversionRate = round(( $job->totalCountJobApplication / $job->view_count ) * 100 , 2);
            } else {
                $job->conversionRate = 0;
            }
            return $job;
        });

        $analytics = [
            'activeUsers' => $activeUsers,
            'totalJobs' => $totalJobs,
            'totalApplications' => $totalApplications,
            'mostAppliedJobs' => $mostAppliedJobs,
            'conversionJobRates' => $conversionJobRates,
        ];
        return $analytics;
    }
}
