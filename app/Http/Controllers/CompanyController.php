<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    public $industries = ['Technology', 'Finance', 'Healthcare', 'Education', 'Retail', 'Manufacturing', 'Other']; // this variable to make industries list in company create and edit

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Active
        $query = Company::latest();

        // Archived
        if($request->input('archived') == 'true') {
            $query->onlyTrashed();  // use it in archived mode when use softDeletes()
        }

        $companies = $query->paginate(10)->onEachSide(1); // this is to get the last hob category will added it in database and make it paginate by one side or one button
        return view('company.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $industries = $this->industries;
        return view('company.create', compact('industries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCompanyRequest $request)
    {
        // Create Company Owner
        $companyOwner = User::create([
            'name' => $request->owner_name,
            'email' => $request->owner_email,
            'password' => Hash::make($request->owner_password),
            'role' => 'company_owner',
        ]);
        // validation if the owner email is already in use
        if(!$companyOwner) {
            return redirect()->back()->with('error', 'Failed to create company owner');
        }
        // Create Company
        Company::create([
            'name' => $request->name,
            'address' => $request->address,
            'industry' => $request->industry,
            'website' => $request->website,
            'owner_id' => $companyOwner->id
        ]);
        return redirect()->route('company.index')->with('success', 'Company Created Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id = null)
    {
        if($id) {
            $company = Company::findOrFail($id);
        } else {
            $company = Company::where('owner_id', Auth::user()->id)->first();
        }
        return view('company.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id = null)
    {
        if($id) {
            $company = Company::findOrFail($id);
        } else {
            $company = Company::where('owner_id', Auth::user()->id)->first();
        }
        $industries = $this->industries;
        return view('company.edit', compact('company', 'industries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, string $id = null)
    {
        if($id) {
            $company = Company::findOrFail($id);
        } else {
            $company = Company::where('owner_id', Auth::user()->id)->first();
        }
        $company->update([
            'name' => $request->name,
            'address' => $request->address,
            'industry' => $request->industry,
            'website' => $request->website,
        ]);
        // Update Owner
        if(!empty($request->owner_password)) {
            $company->owner->update([
                'name' => $request->owner_name,
                'password' => $request->owner_password
        ]);
        } else {
            $company->owner->update([ 'name' => $request->owner_name]);
        }
        // This condition is added to identify the incoming route type
        if($request->query('redirectToList') == 'false') {
            return redirect()->route('company.show', $id)->with('success', 'Company Updated Successfully!');
        }
        if(Auth::user()->role === 'company_owner') {
            return redirect()->route('my-company.show')->with('success', 'Company Updated Successfully!');
        }
            return redirect()->route('company.index')->with('success', 'Company Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $company = Company::findOrFail($id);
        foreach ($company->Jobvacancy as $job) {
            $job->jobApplications()->delete(); // to delete all job_applications when the company deleted
        }
        $company->Jobvacancy()->delete(); // to delete all job_vacancy when the company deleted
        $company->delete();
        return redirect()->route('company.index')->with('success', 'Company Deleted Successfully!');
    }
    public function restore(string $id)
    {
        $company = Company::withTrashed()->findOrFail($id);
        $company->Jobvacancy()->withTrashed()->restore(); // to restore job_vacancy
        foreach ($company->Jobvacancy()->withTrashed()->get() as $job) {
            $job->jobApplications()->withTrashed()->restore(); // to restore job_applications
        }
        $company->restore();
        return redirect()->route('company.index', ['archived'=>'true'])->with('success', 'Company Restored Successfully!');
    }
}