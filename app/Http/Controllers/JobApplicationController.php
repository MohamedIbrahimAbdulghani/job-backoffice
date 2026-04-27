<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateJobApplicationRequest;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Active
        $query = JobApplication::latest();

        // this to connect between Job_Application and Company by Job_Vacancy
        if(Auth::user()->role === 'company_owner') {
            $query->whereHas('jobVacancy', function($query) {
                // $query->where('company_id', Auth::user()->company->id);
                $query->withTrashed() ->where('company_id', Auth::user()->company->id);
            });
        }

        // Archived
        if($request->input('archived') == 'true') {
            $query->onlyTrashed();  // use it in archived mode when use softDeletes()
        }

        $job_applications = $query->paginate(10)->onEachSide(1); // this is to get the last hob category will added it in database and make it paginate by one side or one button
        return view('job_application.index', compact('job_applications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $job_application = JobApplication::with([
            'user',
            'resume',
            'jobVacancy' => fn($q) => $q->withTrashed(),
            'jobVacancy.company' => fn($q) => $q->withTrashed(),
        ])->findOrFail($id);

        return view('job_application.show', compact('job_application'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $job_application = JobApplication::findOrFail($id);
        return view('job_application.edit', compact('job_application'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobApplicationRequest $request, string $id)
    {
        $job_application = JobApplication::findOrFail($id);
        $job_application->update([
            'status' => $request->status
        ]);
        // This condition is added to identify the incoming route type
        if($request->query('redirectToList') == 'false') {
            return redirect()->route('job_application.show', $id)->with('success', 'Job Application Updated Successfully!');
        }
        return redirect()->route('job_application.index')->with('success', 'Job Application Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $job_application = JobApplication::findOrFail($id);
        $job_application->delete();
        return redirect()->route('job_application.index')->with('success', 'Job Application Deleted Successfully!');
    }

    public function restore(string $id)
    {
        $job_application = JobApplication::withTrashed()->findOrFail($id);
        $job_application->restore();
        return redirect()->route('job_application.index')->with('success', 'Job Application Restored Successfully!');
    }
}
