<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobCategory;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Seed the user admin
        User::firstOrCreate([ // firstOrCreate([], []) => this is function to check or to make validation about user if user is found in database not make create or don't run this function
            'email' => 'admin@admin.com',
        ], [
            'name' => 'Admin',
            'password' => Hash::make('admin'),
            'role' => 'admin',
            'email_verified_at' => now()
        ]);

        // ************************************************************************************************

        // to include json file from data/job_data.json in database folder
        $data = json_decode(file_get_contents(database_path('data/job_data.json')), true);

        // Create Job Categories
        foreach($data['jobCategories'] as $jobCategories) {
            JobCategory::firstOrCreate([
                'name' => $jobCategories,
            ]);
        }
        // ************************************************************************************************

        // Create Companies
        foreach($data['companies'] as $companies) {
            // 1- Create Company Owner because i make relationship between company and user i can't create company in database without owner_company
            $companyOwner = User::firstOrCreate([
                'email' => fake()->unique()->safeEmail()
            ], [
                'name' => fake()->name(),
                'password' => Hash::make('123'),
                'role' => 'company_owner',
                'email_verified_at' => now()
            ]);
            // 2- Create Company
            Company::firstOrCreate([
                'name' => $companies['name']
            ], [
                'address' => $companies['address'],
                'industry' => $companies['industry'],
                'website' => $companies['website'],
                'owner_id' => $companyOwner->id
            ]);
        }
        // ************************************************************************************************

        // Create jobVacancies
        foreach($data['jobVacancies'] as $jobVacancies) {
            // Get Company_name
            $company = Company::where('name', $jobVacancies['company'])->firstOrFail();
            // Get Category_name
            $category = JobCategory::where('name', $jobVacancies['category'])->firstOrFail();
            JobVacancy::firstOrCreate([
                'title' => $jobVacancies['title']
            ], [
                'title' => $jobVacancies['title'],
                'description' => $jobVacancies['description'],
                'location' => $jobVacancies['location'],
                'salary' => $jobVacancies['salary'],
                'type' => $jobVacancies['type'],
                'company_id' => $company->id,
                'category_id' => $category->id,
            ]);
        }
        // ************************************************************************************************

        // Create job_applications
        foreach($data['jobApplications'] as $jobApplications) {
            // Get random job vacancy
            $jobVacancy = jobVacancy::inRandomOrder()->first();

            // Create user (job_seeker)
            $user = User::firstOrCreate([
                'email' => fake()->unique()->safeEmail()
            ], [
                'name' => fake()->name(),
                'password' => Hash::make('123'),
                'role' => 'job_seeker',
                'email_verified_at' => now()
            ]);
            // Create resume
            $resume = Resume::firstOrCreate([
                'user_id' => $user->id
            ], [
                "filename" => $jobApplications['resume']['filename'],
                "fileUrl" => $jobApplications['resume']['fileUrl'],
                "contactDetails" => $jobApplications['resume']['contactDetails'],
                "summary" => $jobApplications['resume']['summary'],
                "education" => $jobApplications['resume']['education'],
                "skills" => $jobApplications['resume']['skills'],
                "experience" => $jobApplications['resume']['experience']
            ]);

            // Create job_application
            JobApplication::create([
                "status" => $jobApplications['status'],
                "aiGeneratedScore" => $jobApplications['aiGeneratedScore'],
                "aiGeneratedFeedback" => $jobApplications['aiGeneratedFeedback'],
                "user_id" => $user->id,
                "resume_id" => $resume->id,
                "job_vacancy_id" => $jobVacancy->id,
            ]);

        }


    }
}
