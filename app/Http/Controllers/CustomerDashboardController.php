<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\WorkerOpening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserCertificate;


class CustomerDashboardController extends Controller
{
    public function index()
    {
        $customerId = session('customer_id');
        
        // Get customer's applications
        $applications = Application::with(['opening.event', 'opening.jobCategory'])
            ->where('user_id', $customerId)
            ->latest()
            ->take(5)
            ->get();

        // Get recommended jobs (open jobs that customer hasn't applied to)
        $recommendedJobs = WorkerOpening::with(['event.city', 'jobCategory'])
            ->where('status', 'open')
            ->whereDoesntHave('applications', function($query) use ($customerId) {
                $query->where('user_id', $customerId);
            })
            ->take(6)
            ->get();

        // Get statistics
        $totalApplications = Application::where('user_id', $customerId)->count();
        $pendingApplications = Application::where('user_id', $customerId)->where('status', 'pending')->count();
        $approvedApplications = Application::where('user_id', $customerId)->where('status', 'approved')->count();
        $rejectedApplications = Application::where('user_id', $customerId)->where('status', 'rejected')->count();

        return view('menu.customer.dashboard', compact(
            'applications', 
            'recommendedJobs', 
            'totalApplications',
            'pendingApplications', 
            'approvedApplications', 
            'rejectedApplications'
        ));
    }

    public function profile()
    {
        $customerId = session('customer_id');
        $user = \App\Models\User::with(['profile','certificates'])->findOrFail($customerId);

        return view('menu.customer.profile', compact('user'));
    }

    public function settings()
    {
        $customerId = session('customer_id');
        $user = \App\Models\User::with('profile')->find($customerId);

        return view('menu.customer.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $customerId = session('customer_id');
        $user = \App\Models\User::find($customerId);
        
        // Validate and update email only if provided (e.g. from Personal Data tab)
        if ($request->has('email') || $request->has('name')) {
            $rules = [];
            if ($request->has('email')) {
                $rules['email'] = 'required|email|unique:users,email,'.$customerId;
                $rules['username'] = 'required|string|max:255|unique:users,username,'.$customerId;
            }
            if ($request->has('name')) {
                $rules['name'] = 'required|string|max:255';
            }
            
            $request->validate($rules);

            // Update user fields
            $updateData = [];
            if ($request->has('email')) {
                $updateData['email'] = $request->email;
                $updateData['username'] = $request->username;
                // Update session username
                session(['customer_username' => $request->username]);
            }
            if ($request->has('name')) {
                $updateData['name'] = $request->name;
                // Update session name if stored there
                session(['customer_name' => $request->name]);
            }

            $user->update($updateData);
        }

        // Get or create user profile
        $userProfile = $user->profile()->first();
        if (!$userProfile) {
            $userProfile = new \App\Models\UserProfile();
            $userProfile->user_id = $customerId;
        }

        // Handle different types of updates based on request data
        $profileData = [];

        // Handle summary update (from basic info tab)
        if ($request->has('summary')) {
            $request->validate([
                'summary' => 'nullable|string|max:2000',
            ]);
            $profileData['summary'] = $request->summary;
        }

        // Handle personal data updates (from personal data tab)
        if ($request->has('professional_headline') || $request->has('phone') || $request->has('date_of_birth') || $request->has('address')) {
            $request->validate([
                'professional_headline' => 'nullable|string|max:100',
                'phone' => 'nullable|string|max:20',
                'date_of_birth' => 'nullable|date',
                'address' => 'nullable|string|max:500',
            ]);

            $profileData['professional_headline'] = $request->professional_headline;
            if ($request->filled('phone')) $profileData['phone'] = $request->phone;
            if ($request->filled('date_of_birth')) $profileData['date_of_birth'] = $request->date_of_birth;
            if ($request->filled('address')) $profileData['address'] = $request->address;
        }

        // Handle Education & Preferences
        if ($request->has('last_education')) {
            $request->validate([
                'last_education' => 'nullable|string|max:50',
                'field_of_study' => 'nullable|string|max:100',
                'university' => 'nullable|string|max:100',
                'graduation_year' => 'nullable|integer|min:1900|max:'.(date('Y')+10),
                'skills' => 'nullable|string|max:1000',
                'languages' => 'nullable|string|max:500',
            ]);

            $profileData['last_education'] = $request->last_education;
            $profileData['field_of_study'] = $request->field_of_study;
            $profileData['university'] = $request->university;
            $profileData['graduation_year'] = $request->graduation_year;
            $profileData['skills'] = $request->skills;
            $profileData['languages'] = $request->languages;
        }

        // Save profile data if any exists
        if (!empty($profileData)) {
            $userProfile->fill($profileData);
            $userProfile->save();
        }

        // Handle CV upload
        if ($request->hasFile('cv_file')) {
            $request->validate([
                'cv_file' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
            ]);
            
            // Remove old CV if exists
            if ($userProfile->cv_file && \Storage::exists($userProfile->cv_file)) {
                \Storage::delete($userProfile->cv_file);
            }
            
            // Store new CV
            $cvPath = $request->file('cv_file')->store('cv_files', 'public');
            $userProfile->cv_file = $cvPath;
            $userProfile->cv_updated_at = now();
            $userProfile->save();
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui!');
    }

    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $customerId = session('customer_id');
        $user = \App\Models\User::find($customerId);
        
        // Get or create user profile
        $userProfile = $user->profile()->first();
        if (!$userProfile) {
            $userProfile = new \App\Models\UserProfile();
            $userProfile->user_id = $customerId;
        }

        // Delete old photo if exists
        if ($userProfile->profile_photo && \Storage::exists('public/' . $userProfile->profile_photo)) {
            \Storage::delete('public/' . $userProfile->profile_photo);
        }

        // Store new photo
        // Note: The cropper sends a blob, which Laravel treats as a file upload
        $path = $request->file('profile_photo')->store('profile_photos', 'public');
        
        $userProfile->profile_photo = $path;
        $userProfile->save();

        // Update session
        session(['customer_profile_photo' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Profile photo updated successfully!'
        ]);
    }

    public function removeProfilePhoto()
    {
        $customerId = session('customer_id');
        $user = \App\Models\User::find($customerId);
        
        $userProfile = $user->profile()->first();
        if ($userProfile && $userProfile->profile_photo && \Storage::exists($userProfile->profile_photo)) {
            \Storage::delete($userProfile->profile_photo);
        }
        
        if ($userProfile) {
            $userProfile->profile_photo = null;
            $userProfile->save();
            
            // Update session
            session()->forget('customer_profile_photo');
        }

        return redirect()->back()->with('success', 'Foto profil berhasil dihapus!');
    }

    public function updateProfile(Request $request)
    {
        $customerId = session('customer_id');
        
        // Determine which fields to validate based on the request
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$customerId,
            'username' => 'required|string|max:255|unique:users,username,'.$customerId,
        ];

        // Add conditional validation rules for additional fields
        if ($request->has('phone')) {
            $rules['phone'] = 'nullable|string|max:20';
        }
        
        if ($request->has('date_of_birth')) {
            $rules['date_of_birth'] = 'nullable|date';
        }
        
        if ($request->has('address')) {
            $rules['address'] = 'nullable|string|max:500';
        }
        
        // Social media fields
        $socialFields = ['linkedin', 'instagram', 'twitter', 'github', 'website'];
        foreach ($socialFields as $field) {
            if ($request->has($field)) {
                $rules[$field] = 'nullable|url';
            }
        }

        $validated = $request->validate($rules);

        $user = \App\Models\User::find($customerId);
        
        // Handle CV file upload
        if ($request->hasFile('cv_file')) {
            $request->validate([
                'cv_file' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
            ]);
            
            // Remove old CV if exists
            if ($user->cv_file && \Storage::exists($user->cv_file)) {
                \Storage::delete($user->cv_file);
            }
            
            // Store new CV
            $cvPath = $request->file('cv_file')->store('cv_files', 'private');
            $validated['cv_file'] = $cvPath;
            $validated['cv_updated_at'] = now();
        }

        $user->update($validated);

        // Update session data
        session([
            'customer_username' => $user->username,
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function uploadCv(Request $request)
    {
        $request->validate([
            'cv_file' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
        ]);

        $customerId = session('customer_id');
        $user = \App\Models\User::find($customerId);
        $userProfile = $user->profile ?? new \App\Models\UserProfile(['user_id' => $user->id]);

        // Remove old CV if exists
        if ($userProfile->cv_file && \Storage::exists($userProfile->cv_file)) {
            \Storage::delete($userProfile->cv_file);
        }

        // Store new CV
        $cvPath = $request->file('cv_file')->store('cv_files', 'public');
        $userProfile->cv_file = $cvPath;
        $userProfile->cv_updated_at = now();
        $userProfile->save();

        return response()->json([
            'success' => true,
            'message' => 'CV uploaded successfully!',
            'cv_url' => asset('storage/' . $cvPath),
            'filename' => basename($cvPath)
        ]);
    }

    public function updateSocialMedia(Request $request)
    {
        try {
            $customerId = session('customer_id');
            if (!$customerId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $user = \App\Models\User::find($customerId);
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }

            // Validate inputs
            $socialFields = ['linkedin', 'instagram', 'twitter', 'tiktok', 'website'];
            $rules = [];
            foreach ($socialFields as $field) {
                $rules[$field] = 'nullable|url|max:255';
            }
            
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Validation error', 
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get or create user profile
            $userProfile = $user->profile;
            if (!$userProfile) {
                $userProfile = new \App\Models\UserProfile();
                $userProfile->user_id = $customerId;
            }

            $updatedCount = 0;
            foreach ($socialFields as $field) {
                if ($request->has($field)) {
                    // Handle empty strings as null
                    $value = $request->input($field);
                    if ($value === null || $value === '') {
                        $value = null;
                    }
                    
                    $userProfile->{$field} = $value;
                    $updatedCount++;
                }
            }

            if ($updatedCount > 0) {
                $userProfile->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Social media links updated successfully!'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'No changes made.'
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Social media update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeCV()
    {
        $customerId = session('customer_id');
        $user = \App\Models\User::find($customerId);
        
        if ($user->cv_file && \Storage::exists($user->cv_file)) {
            \Storage::delete($user->cv_file);
        }
        
        $user->update([
            'cv_file' => null,
            'cv_updated_at' => null
        ]);

        return redirect()->back()->with('success', 'CV removed successfully!');
    }

    public function applications()
    {
        $customerId = session('customer_id');
        
        $applications = Application::with(['opening.event', 'opening.jobCategory'])
            ->where('user_id', $customerId)
            ->latest()
            ->paginate(10);
return view('menu.customer.applications', compact('applications'));
}

public function saveJob(Request $request, WorkerOpening $job)
{
$customerId = session('customer_id');

// Check if already saved
$alreadySaved = \DB::table('saved_jobs')
    ->where('user_id', $customerId)
    ->where('worker_opening_id', $job->id)
    ->exists();

if ($alreadySaved) {
    return response()->json(['success' => false, 'message' => 'Job is already saved']);
}

// Save the job
\DB::table('saved_jobs')->insert([
    'user_id' => $customerId,
    'worker_opening_id' => $job->id,
    'created_at' => now(),
    'updated_at' => now()
]);

return response()->json(['success' => true, 'message' => 'Job saved successfully']);
}

public function unsaveJob(Request $request, WorkerOpening $job)
{
$customerId = session('customer_id');

\DB::table('saved_jobs')
    ->where('user_id', $customerId)
    ->where('worker_opening_id', $job->id)
    ->delete();

return response()->json(['success' => true, 'message' => 'Job removed from saved jobs']);
}

public function savedJobs()
{
$customerId = session('customer_id');

    $user = \App\Models\User::find($customerId);

    $savedJobs = $user->savedJobs()
        ->with(['event.city', 'jobCategory'])
        ->orderByPivot('created_at', 'desc')
        ->paginate(10);

return view('menu.customer.saved-jobs', compact('savedJobs'));
}
public function uploadCertificates(Request $request)
{
    $customerId = session('customer_id');

    $request->validate([
        'certificates' => 'required|array|min:1',
        'certificates.*.title' => 'required|string|max:150',
        'certificates.*.event_date' => 'required|date',
        'certificates.*.stage' => 'required|in:province,national,asean_sea,asia,world',
        'certificates.*.file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'certificates.*.title' => 'required|string|max:150',
        'certificates.*.event_date' => 'required|date',
    ]);

    foreach ($request->certificates as $item) {
        $file = $item['file'];
        $path = $file->store('certificates', 'public');

        \App\Models\UserCertificate::create([
            'user_id' => $customerId,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'title' => $item['title'],
            'event_date' => $item['event_date'],
            'stage' => $item['stage'],
            'title' => $item['title'],
            'event_date' => $item['event_date'],
        ]);
    }

    return response()->json(['success' => true]);
}
public function certificateDetail(\App\Models\UserCertificate $certificate)
{
    if ($certificate->user_id !== session('customer_id')) {
        abort(403);
    }

    return response()->json([
        'title' => $certificate->title,
        'event_date' => $certificate->event_date,
        'stage' => strtoupper(str_replace('_',' ', $certificate->stage)),
        'file_url' => asset('storage/'.$certificate->file_path),
        'file_name' => $certificate->original_name,
    ]);
}
public function certificateDelete(\App\Models\UserCertificate $certificate)
{
    if ($certificate->user_id !== session('customer_id')) {
        abort(403);
    }

    if (\Storage::disk('public')->exists($certificate->file_path)) {
        \Storage::disk('public')->delete($certificate->file_path);
    }

    $certificate->delete();

    return response()->json(['success' => true]);
}
}
