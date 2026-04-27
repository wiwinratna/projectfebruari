<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserEducationHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EducationHistoryController extends Controller
{
    /**
     * List all education records for the current user (JSON).
     */
    public function index()
    {
        $userId = session('customer_id');
        $records = UserEducationHistory::where('user_id', $userId)
            ->orderBy('sort_order')
            ->orderByDesc('graduation_year')
            ->get()
            ->map(fn($r) => $this->formatRecord($r));

        return response()->json(['success' => true, 'data' => $records]);
    }

    /**
     * Store a new education record.
     */
    public function store(Request $request)
    {
        $userId = session('customer_id');

        $validated = $request->validate([
            'education_level'   => ['required', Rule::in(array_keys(UserEducationHistory::educationLevels()))],
            'institution_name'  => 'required|string|max:200',
            'field_of_study'    => 'nullable|string|max:150',
            'graduation_year'   => 'nullable|integer|min:1950|max:' . (date('Y') + 10),
            'is_still_studying' => 'boolean',
            'proof_document'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $record = new UserEducationHistory();
        $record->user_id = $userId;
        $record->education_level   = $validated['education_level'];
        $record->institution_name  = $validated['institution_name'];
        $record->field_of_study    = $validated['field_of_study'] ?? null;
        $record->graduation_year   = $request->boolean('is_still_studying') ? null : ($validated['graduation_year'] ?? null);
        $record->is_still_studying = $request->boolean('is_still_studying');
        $record->sort_order        = UserEducationHistory::where('user_id', $userId)->max('sort_order') + 1;

        if ($request->hasFile('proof_document')) {
            $file = $request->file('proof_document');
            $path = $file->store('education_proofs', 'public');
            $record->proof_document = $path;
            $record->proof_document_original_name = $file->getClientOriginalName();
        }

        $record->save();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat pendidikan berhasil ditambahkan.',
            'data'    => $this->formatRecord($record),
        ]);
    }

    /**
     * Update an existing education record.
     */
    public function update(Request $request, UserEducationHistory $education)
    {
        $this->authorizeRecord($education);

        $validated = $request->validate([
            'education_level'   => ['required', Rule::in(array_keys(UserEducationHistory::educationLevels()))],
            'institution_name'  => 'required|string|max:200',
            'field_of_study'    => 'nullable|string|max:150',
            'graduation_year'   => 'nullable|integer|min:1950|max:' . (date('Y') + 10),
            'is_still_studying' => 'boolean',
            'proof_document'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $education->education_level   = $validated['education_level'];
        $education->institution_name  = $validated['institution_name'];
        $education->field_of_study    = $validated['field_of_study'] ?? null;
        $education->is_still_studying = $request->boolean('is_still_studying');
        $education->graduation_year   = $education->is_still_studying ? null : ($validated['graduation_year'] ?? null);

        if ($request->hasFile('proof_document')) {
            // Delete old file
            if ($education->proof_document && Storage::disk('public')->exists($education->proof_document)) {
                Storage::disk('public')->delete($education->proof_document);
            }
            $file = $request->file('proof_document');
            $path = $file->store('education_proofs', 'public');
            $education->proof_document = $path;
            $education->proof_document_original_name = $file->getClientOriginalName();
        }

        $education->save();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat pendidikan berhasil diperbarui.',
            'data'    => $this->formatRecord($education),
        ]);
    }

    /**
     * Delete an education record.
     */
    public function destroy(UserEducationHistory $education)
    {
        $this->authorizeRecord($education);

        // Delete proof document if exists
        if ($education->proof_document && Storage::disk('public')->exists($education->proof_document)) {
            Storage::disk('public')->delete($education->proof_document);
        }

        $education->delete();

        return response()->json(['success' => true, 'message' => 'Riwayat pendidikan berhasil dihapus.']);
    }

    /**
     * Remove the proof document from an education record.
     */
    public function removeProof(UserEducationHistory $education)
    {
        $this->authorizeRecord($education);

        if ($education->proof_document && Storage::disk('public')->exists($education->proof_document)) {
            Storage::disk('public')->delete($education->proof_document);
        }

        $education->proof_document = null;
        $education->proof_document_original_name = null;
        $education->save();

        return response()->json(['success' => true, 'message' => 'Bukti pendidikan berhasil dihapus.']);
    }

    /**
     * Ensure the record belongs to the current user.
     */
    private function authorizeRecord(UserEducationHistory $education): void
    {
        if ((int) $education->user_id !== (int) session('customer_id')) {
            abort(403, 'Unauthorized');
        }
    }

    /**
     * Format a record for JSON response.
     */
    private function formatRecord(UserEducationHistory $record): array
    {
        $levels = UserEducationHistory::educationLevels();

        return [
            'id'                          => $record->id,
            'education_level'             => $record->education_level,
            'education_level_label'       => $levels[$record->education_level] ?? $record->education_level,
            'institution_name'            => $record->institution_name,
            'field_of_study'              => $record->field_of_study,
            'graduation_year'             => $record->graduation_year,
            'is_still_studying'           => $record->is_still_studying,
            'proof_document'              => $record->proof_document,
            'proof_document_original_name' => $record->proof_document_original_name,
            'proof_document_url'          => $record->proof_document
                ? asset('storage/' . ltrim($record->proof_document, '/'))
                : null,
            'sort_order'                  => $record->sort_order,
        ];
    }
}
