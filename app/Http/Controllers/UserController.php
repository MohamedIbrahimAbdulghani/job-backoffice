<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Active
        $query = User::latest();

        // Archived
        if($request->input('archived') == 'true') {
            $query->onlyTrashed();  // use it in archived mode when use softDeletes()
        }

        $users = $query->paginate(10)->onEachSide(1); // this is to get the last hob category will added it in database and make it paginate by one side or one button
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);
        return redirect()->route('user.index')->with('success', 'User Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
            // لو الـ user عنده company
            if($user->company) {
                //  احذف كل الـ applications
                foreach ($user->company->Jobvacancy as $job) {
                    $job->jobApplications()->delete();
                }
                //  احذف كل الـ jobs
                $user->company->Jobvacancy()->delete();
                //  احذف الـ company
                $user->company->delete();
            }
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User Deleted Successfully!');
    }

    public function restore(string $id)
    {
        $user = User::withTrashed()->findOrFail($id);
            // لو الـ user عنده company محذوفة
            if($user->company()->withTrashed()->exists()) {
                $company = $user->company()->withTrashed()->first();
                //  restore الـ jobs
                $company->Jobvacancy()->withTrashed()->restore();
                //  restore الـ applications
                foreach ($company->Jobvacancy()->withTrashed()->get() as $job) {
                    $job->jobApplications()->withTrashed()->restore();
                }
                //  restore الـ company
                $company->restore();
            }
        $user->restore();
        return redirect()->route('user.index')->with('success', 'User Restored Successfully!');
    }
}