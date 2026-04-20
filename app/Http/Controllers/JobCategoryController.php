<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateJobCategoryRequest;
use App\Http\Requests\UpdateJobCategoryRequest;
use App\Models\JobCategory;
use Illuminate\Http\Request;

class JobCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Active
        $query = JobCategory::latest();

        // Archived
        if($request->input('archived') == 'true') {
            $query->onlyTrashed();  // use it in archived mode when use softDeletes()
        }

        $categories = $query->paginate(10)->onEachSide(1); // this is to get the last hob category will added it in database and make it paginate by one side or one button
        return view('job_category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('job_category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateJobCategoryRequest $request)
    {
        JobCategory::create([
            'name' => $request->name
        ]);
        return redirect()->route('job_category.index')->with('success', 'Job Category Created Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = JobCategory::findOrFail($id);
        return view('job_category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobCategoryRequest $request, string $id)
    {
        $category = JobCategory::findOrFail($id);
        $category->update([
            'name' => $request->name
        ]);
        return redirect()->route('job_category.index')->with('success', 'Job Category Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = JobCategory::findOrFail($id);
        foreach ($category->Jobvacancy as $job) {
            $job->jobApplications()->delete(); // to delete job_application when deleted job_category
        }

        $category->Jobvacancy()->delete(); // to delete job_vacancy when deleted job_category

        $category->delete();

        return redirect()->route('job_category.index')->with('success', 'Job Category Deleted Successfully!');
    }
    public function restore(string $id)
    {
        $category = JobCategory::withTrashed()->findOrFail($id);

        $category->Jobvacancy()->withTrashed()->restore(); // to restore job_vacancy when deleted job_category

        foreach ($category->Jobvacancy()->withTrashed()->get() as $job) {
            $job->jobApplications()->withTrashed()->restore(); // to restore job_application when deleted job_category
        }

        $category->restore();
        return redirect()->route('job_category.index', ['archived'=>'true'])->with('success', 'Job Category Restored Successfully!');
    }
}
