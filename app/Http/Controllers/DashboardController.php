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
        $activeUsers = User::where('role', 'job_seeker')
        ->where('last_login_at', '>=', Carbon::now()->subDays(30))
        ->count();

        $totalJobs = JobVacancy::whereNull('deleted_at')
        ->count();

        $totalApplications = JobApplication::whereNull('deleted_at')
        ->count();

        $mostAppliedJobs = JobVacancy::withCount('jobApplications as totalCountJobApplication')
        ->whereNull('deleted_at')
        ->orderByDesc('totalCountJobApplication')
        ->limit(5)
        ->get();

        $conversionJobRates = JobVacancy::withCount('jobApplications as totalCountJobApplication')
        ->whereNull('deleted_at')
        ->limit(5)
        ->orderByDesc('totalCountJobApplication')
        ->get()
        ->filter(fn($job) => $job->totalCountJobApplication > 0)
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

        $activeUsers = User::whereNotNull('last_login_at')
        ->where('last_login_at', '>=', now()->subDays(30))
        ->where('role', 'job_seeker')
        ->whereHas('jobApplications', function($query) use ($company) {
            $query->whereIn('job_vacancy_id', $company->Jobvacancy()->pluck('id'));
        })
        ->count();

        $totalJobs = $company->Jobvacancy->count();

        $totalApplications = JobApplication::whereIn('job_vacancy_id', $company->Jobvacancy()->pluck('id'))->count();

        $mostAppliedJobs = JobVacancy::withCount('jobApplications as totalCountJobApplication')
        ->whereIn('id', $company->Jobvacancy()->pluck('id'))
        ->orderByDesc('totalCountJobApplication')
        ->limit(5)
        ->get();

        $conversionJobRates = JobVacancy::withCount('jobApplications as totalCountJobApplication')
        ->whereIn('id', $company->Jobvacancy()->pluck('id'))
        ->limit(5)
        ->orderByDesc('totalCountJobApplication')
        ->get()
        ->filter(fn($job) => $job->totalCountJobApplication > 0)
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