<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateJobVacancyRequest;
use App\Http\Requests\UpdateJobVacancyRequest;
use App\Models\Company;
use App\Models\JobCategory;
use Illuminate\Http\Request;

use App\Models\JobVacancy;
use Illuminate\Support\Facades\Auth;

class JobVacancyController extends Controller
{
    public $type = ['full-time', 'contract', 'remote', 'hybrid']; // this variable to make types list in job_vacancy create and edit

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Active
        $query = JobVacancy::latest();

        if(Auth::user()->role === 'company_owner') {
            $query->where('company_id', Auth::user()->company->id);
        }
        // // Archived
        if($request->input('archived') == 'true') {
            $query->onlyTrashed();  // use it in archived mode when use softDeletes()
        }

        $job_vacancies = $query->paginate(10)->onEachSide(1); // this is to get the last hob category will added it in database and make it paginate by one side or one button
        return view('job_vacancy.index', compact('job_vacancies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::all();
        $job_categories = JobCategory::all();
        $types = $this->type;
        return view('job_vacancy.create', compact('companies', 'job_categories', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateJobVacancyRequest $request)
    {
        $job_vacancy = JobVacancy::create([
            'title'=>$request->title,
            'description' => $request->description,
            'location'=>$request->location,
            'type'=>$request->type,
            'salary'=>$request->salary,
            'company_id'=>$request->company,
            'category_id'=>$request->job_category,
        ]);
        return redirect()->route('job_vacancy.index')->with('success', 'Job Vacancy Created Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $job_vacancies = JobVacancy::findOrFail($id);
        return view('job_vacancy.show', compact('job_vacancies'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $job_vacancy = JobVacancy::findOrFail($id);
        $companies = Company::all();
        $job_categories = JobCategory::all();
        $types = $this->type;
        return view('job_vacancy.edit', compact('job_vacancy', 'companies', 'job_categories', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobVacancyRequest $request, string $id)
    {
        JobVacancy::findOrFail($id)->update([
            'title'=>$request->title,
            'description' => $request->description,
            'location'=>$request->location,
            'type'=>$request->type,
            'salary'=>$request->salary,
            'company_id'=>$request->company,
            'category_id'=>$request->job_category,
        ]);
        // This condition is added to identify the incoming route type
        if($request->query('redirectToList') == 'false') {
            return redirect()->route('job_vacancy.show', $id)->with('success', 'Job Vacancy Updated Successfully!');
        }
        return redirect()->route('job_vacancy.index')->with('success', 'Job Vacancy Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $job_vacancy = JobVacancy::findOrFail($id);
        $job_vacancy->delete();
        return redirect()->route('job_vacancy.index')->with('success', 'Job Vacancy Deleted Successfully!');
    }

    public function restore(string $id)
    {
        $job_vacancy = JobVacancy::withTrashed()->findOrFail($id);
        $job_vacancy->restore();
        return redirect()->route('job_vacancy.index')->with('success', ['archived'=>'true'])->with('success', 'Job Vacancy Restored Successfully!');
    }
}
