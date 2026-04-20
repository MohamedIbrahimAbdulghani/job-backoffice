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

        $totalJobs = JobVacancy::whereNull('deleted_at')->count();

        $totalApplications = JobApplication::whereNull('deleted_at')->count();

        $mostAppliedJobs = JobVacancy::withCount('jobapplications as totalCountJobApplication')
        ->whereNull('deleted_at')
        ->orderByDesc('totalCountJobApplication')
        ->limit(5)
        ->get();

        $conversionJobRates = JobVacancy::withCount('jobapplications as totalCountJobApplication')
        ->whereNull('deleted_at')
        ->groupBy('job_vacancies.id')
        ->havingRaw('(select count(*) from "job_applications" where "job_vacancies"."id" = "job_applications"."job_vacancy_id" and "job_applications"."deleted_at" is null) > 0')
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

        return [
            'activeUsers' => $activeUsers,
            'totalJobs' => $totalJobs,
            'totalApplications' => $totalApplications,
            'mostAppliedJobs' => $mostAppliedJobs,
            'conversionJobRates' => $conversionJobRates,
        ];
    }

    private function companyOwnerDashboard() {
        $company = Auth::user()->company;

        $activeUsers = User::whereNotNull('last_login_at')
        ->where('last_login_at', '>=', now()->subDays(30))
        ->where('role', 'job_seeker')
        ->whereHas('jobapplications', function($query) use ($company) {
            $query->whereIn('job_vacancy_id', $company->Jobvacancy()->pluck('id'));
        })
        ->count();

        $totalJobs = $company->Jobvacancy->count();

        $totalApplications = JobApplication::whereIn('job_vacancy_id', $company->Jobvacancy()->pluck('id'))->count();

        $mostAppliedJobs = JobVacancy::withCount('jobapplications as totalCountJobApplication')
        ->whereIn('id', $company->Jobvacancy()->pluck('id'))
        ->orderByDesc('totalCountJobApplication')
        ->limit(5)
        ->get();

        $conversionJobRates = JobVacancy::withCount('jobapplications as totalCountJobApplication')
        ->whereIn('id', $company->Jobvacancy()->pluck('id'))
        ->groupBy('job_vacancies.id')
        ->havingRaw('(select count(*) from "job_applications" where "job_vacancies"."id" = "job_applications"."job_vacancy_id" and "job_applications"."deleted_at" is null) > 0')
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

        return [
            'activeUsers' => $activeUsers,
            'totalJobs' => $totalJobs,
            'totalApplications' => $totalApplications,
            'mostAppliedJobs' => $mostAppliedJobs,
            'conversionJobRates' => $conversionJobRates,
        ];
    }
}
