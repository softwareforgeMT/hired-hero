<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlacementProfile;
use App\Models\User;
use App\Models\ResumeSubscription;
use App\Models\JobMatch;
use Illuminate\Http\Request;
use Session;
use Storage;

class PlacementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display all placement profiles
     */
    public function index()
    {
        $placements = PlacementProfile::with('user')->orderByDesc('id')->paginate(15);
        return view('admin.placements.index', compact('placements'));
    }

    /**
     * Show a specific placement profile
     */
    public function show($id)
    {
        $placement = PlacementProfile::with('user', 'jobMatches')->findOrFail($id);
        $user = $placement->user;
        $subscriptions = ResumeSubscription::where('user_id', $user->id)->orderByDesc('id')->get();
        
        return view('admin.placements.show', compact('placement', 'user', 'subscriptions'));
    }

    /**
     * Show edit form for placement profile
     */
    public function edit($id)
    {
        $placement = PlacementProfile::findOrFail($id);
        $user = $placement->user;
        
        return view('admin.placements.edit', compact('placement', 'user'));
    }

    /**
     * Update placement profile
     */
    public function update(Request $request, $id)
    {
        $placement = PlacementProfile::findOrFail($id);

        $request->validate([
            'job_type' => 'nullable|string',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'work_permit_status' => 'nullable|string',
            'job_level' => 'nullable|string',
            'years_experience' => 'nullable|integer|min:0',
        ]);

        $data = $request->only([
            'job_type',
            'salary_min',
            'salary_max',
            'country',
            'city',
            'work_permit_status',
            'job_level',
            'years_experience'
        ]);

        $placement->update($data);

        Session::flash('message', 'Placement profile updated successfully!');
        Session::flash('alert-class', 'alert-success');
        
        return redirect()->route('admin.placements.show', $placement->id);
    }

    /**
     * Display resumes for a user
     */
    public function resumes($placementId)
    {
        $placement = PlacementProfile::findOrFail($placementId);
        $user = $placement->user;
        
        return view('admin.placements.resumes', compact('placement', 'user'));
    }

    /**
     * Download a resume from private storage
     */
    public function downloadResume($placementId)
    {
        $placement = PlacementProfile::findOrFail($placementId);
        
        if (!$placement->resume_path || !Storage::disk('private')->exists($placement->resume_path)) {
            Session::flash('message', 'Resume file not found!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }

        return Storage::disk('private')->download($placement->resume_path);
    }

    /**
     * Delete a resume
     */
    public function deleteResume(Request $request, $placementId)
    {
        $placement = PlacementProfile::findOrFail($placementId);
        
        $request->validate([
            'file_path' => 'required|string',
        ]);

        // Delete from private storage
        if (Storage::disk('private')->exists($placement->resume_path)) {
            Storage::disk('private')->delete($placement->resume_path);
        }

        // Clear the resume data
        $placement->update([
            'resume_path' => null,
            'resume_data' => null,
            'has_resume' => false,
        ]);

        Session::flash('message', 'Resume deleted successfully!');
        Session::flash('alert-class', 'alert-success');
        
        return redirect()->back();
    }

    /**
     * Display job matches for a placement profile
     */
    public function jobMatches($placementId)
    {
        $placement = PlacementProfile::with('jobMatches')->findOrFail($placementId);
        $jobMatches = $placement->jobMatches()->orderByDesc('created_at')->paginate(10);
        
        return view('admin.placements.job-matches', compact('placement', 'jobMatches'));
    }

    /**
     * Datatables for AJAX requests
     */
    public function datatables(Request $request)
    {
        $skip = $request->start ?? 0;
        $limit = $request->length ?? 10;
        $search = $request->search['value'] ?? '';

        $query = PlacementProfile::with('user')
            ->when($search, function ($q) use ($search) {
                return $q->whereHas('user', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                });
            });

        $total = $query->count();
        $data = $query->orderByDesc('id')->skip($skip)->take($limit)->get();

        return response()->json([
            'draw' => $request->draw ?? 1,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data->map(function ($item, $index) use ($skip) {
                return [
                    'DT_RowIndex' => $skip + $index + 1,
                    'id' => $item->id,
                    'user_name' => $item->user->name ?? 'Deleted User',
                    'user_email' => $item->user->email ?? 'N/A',
                    'job_type' => $item->job_type ?? 'N/A',
                    'country' => $item->country ?? 'N/A',
                    'current_step' => $item->current_step,
                    'is_completed' => $item->is_completed ? '<span class="badge bg-success">Completed</span>' : '<span class="badge bg-warning">Pending</span>',
                    'created_at' => $item->created_at->format('Y-m-d H:i'),
                    'actions' => '<a href="' . route('admin.placements.show', $item->id) . '" class="btn btn-sm btn-primary">View</a>
                                  <a href="' . route('admin.placements.edit', $item->id) . '" class="btn btn-sm btn-warning">Edit</a>'
                ];
            })
        ]);
    }
}
