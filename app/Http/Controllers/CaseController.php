<?php

namespace App\Http\Controllers;

use App\Models\AutopsyCase;
use App\Models\AutopsyExamination;
use App\Models\CaseDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CaseController extends Controller
{
    private function statusSteps()
    {
        return [
            'registered',
            'assigned',
            'in_progress',
            'completed',
            'verified',
            'closed',
        ];
    }

    private function statusLabel($status)
    {
        return match ($status) {
            'registered' => 'Registered',
            'assigned' => 'Assigned',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'verified' => 'Verified',
            'closed' => 'Closed',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    public function index(Request $request)
    {
        $query = AutopsyCase::with(['assignedDoctor', 'registrationOfficer'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('case_number', 'like', "%{$search}%")
                    ->orWhere('victim_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $cases = $query->paginate(5)->withQueryString();

        return view('cases.index', [
            'cases' => $cases,
            'totalCases' => AutopsyCase::count(),
            'registeredCases' => AutopsyCase::where('status', 'registered')->count(),
            'inProgressCases' => AutopsyCase::where('status', 'in_progress')->count(),
            'completedCases' => AutopsyCase::where('status', 'completed')->count(),
        ]);
    }

    public function create()
    {
        $doctors = User::doctors()->orderBy('name')->get();

        return view('cases.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_number' => 'required|string|max:50|unique:autopsy_cases,case_number',
            'victim_name' => 'required|string|max:255',
            'gender' => 'required|string|max:20',
            'age' => 'nullable|integer|min:0|max:150',
            'date_received' => 'required|date',
            'case_type' => 'required|string|max:100',
            'assigned_doctor_id' => 'required|exists:users,id',
            'referring_institution' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
        ]);

        $validated['status'] = 'assigned';
        $validated['registration_officer_id'] = session('user.id');

        AutopsyCase::create($validated);

        return redirect()
            ->route('cases.index')
            ->with('success', 'Case berhasil ditambahkan dan langsung assigned ke dokter.');
    }

    public function show($id)
    {
        $case = AutopsyCase::with([
            'assignedDoctor',
            'registrationOfficer',
            'documents',
            'examination',
        ])->findOrFail($id);

        $statusSteps = $this->statusSteps();
        $currentIndex = array_search($case->status, $statusSteps);

        if ($currentIndex === false) {
            $currentIndex = 0;
        }

        return view('cases.show', compact('case', 'statusSteps', 'currentIndex'));
    }

    public function edit($id)
    {
        $case = AutopsyCase::with([
            'assignedDoctor',
            'registrationOfficer',
            'documents',
            'examination',
        ])->findOrFail($id);

        $doctors = User::doctors()->orderBy('name')->get();

        $statusSteps = $this->statusSteps();
        $currentIndex = array_search($case->status, $statusSteps);

        if ($currentIndex === false) {
            $currentIndex = 0;
        }

        return view('cases.edit', compact(
            'case',
            'doctors',
            'statusSteps',
            'currentIndex'
        ));
    }

    public function update(Request $request, $id)
    {
        $case = AutopsyCase::findOrFail($id);

        $validated = $request->validate([
            'case_number' => 'required|string|max:50|unique:autopsy_cases,case_number,' . $case->id,
            'victim_name' => 'required|string|max:255',
            'gender' => 'required|string|max:20',
            'age' => 'nullable|integer|min:0|max:150',
            'date_received' => 'required|date',
            'case_type' => 'required|string|max:100',
            'assigned_doctor_id' => 'required|exists:users,id',
            'referring_institution' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'status' => 'required|in:registered,assigned,in_progress,completed,verified,closed',

            'document_name' => 'nullable|string|max:255',
            'document_type' => 'nullable|string|max:100',
            'document_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $case->update([
            'case_number' => $validated['case_number'],
            'victim_name' => $validated['victim_name'],
            'gender' => $validated['gender'],
            'age' => $validated['age'] ?? null,
            'date_received' => $validated['date_received'],
            'case_type' => $validated['case_type'],
            'assigned_doctor_id' => $validated['assigned_doctor_id'],
            'referring_institution' => $validated['referring_institution'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ]);

        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $path = $file->store('case-documents', 'public');

            CaseDocument::create([
                'case_id' => $case->id,
                'document_name' => $request->document_name ?: $file->getClientOriginalName(),
                'document_type' => $request->document_type ?: $file->getClientOriginalExtension(),
                'file_path' => $path,
                'uploaded_by' => session('user.id'),
            ]);
        }

        return redirect()
            ->route('cases.show', $case->id)
            ->with('success', 'Case berhasil diperbarui.');
    }

    public function verifyCase(Request $request, $id)
    {
        $case = AutopsyCase::with('assignedDoctor')->findOrFail($id);

        $request->validate([
            'case_number_confirmation' => 'required|string',
            'doctor_password' => 'required|string',
        ]);

        if ($case->status !== 'completed') {
            return back()->with('error', 'Case hanya bisa diverifikasi setelah status Completed.');
        }

        if ($request->case_number_confirmation !== $case->case_number) {
            return back()->with('error', 'Nomor kasus tidak sesuai.');
        }

        $doctor = User::find(session('user.id'));

        if (!$doctor) {
            return back()->with('error', 'Sesi user tidak ditemukan. Silakan login ulang.');
        }

        if ($doctor->role !== 'dokter' && $doctor->role !== 'doctor') {
            return back()->with('error', 'Hanya dokter yang dapat melakukan verifikasi case.');
        }

        if ((int) $case->assigned_doctor_id !== (int) $doctor->id) {
            return back()->with('error', 'Dokter ini bukan dokter yang ditugaskan pada case tersebut.');
        }

        if (!Hash::check($request->doctor_password, $doctor->password)) {
            return back()->with('error', 'Password dokter tidak sesuai.');
        }

        $case->update([
            'status' => 'verified',
        ]);

        return redirect()
            ->route('cases.show', $case->id)
            ->with('success', 'Case berhasil diverifikasi oleh dokter.');
    }

    public function closeCase(Request $request, $id)
    {
        $case = AutopsyCase::with('assignedDoctor')->findOrFail($id);

        $request->validate([
            'case_number_confirmation' => 'required|string',
            'doctor_password' => 'required|string',
        ]);

        if ($case->status !== 'verified') {
            return back()->with('error', 'Case hanya bisa ditutup setelah status Verified.');
        }

        if ($request->case_number_confirmation !== $case->case_number) {
            return back()->with('error', 'Nomor kasus tidak sesuai.');
        }

        $doctor = User::find(session('user.id'));

        if (!$doctor) {
            return back()->with('error', 'Sesi user tidak ditemukan. Silakan login ulang.');
        }

        if ($doctor->role !== 'dokter' && $doctor->role !== 'doctor') {
            return back()->with('error', 'Hanya dokter yang dapat menutup case.');
        }

        if ((int) $case->assigned_doctor_id !== (int) $doctor->id) {
            return back()->with('error', 'Dokter ini bukan dokter yang ditugaskan pada case tersebut.');
        }

        if (!Hash::check($request->doctor_password, $doctor->password)) {
            return back()->with('error', 'Password dokter tidak sesuai.');
        }

        $case->update([
            'status' => 'closed',
        ]);

        return redirect()
            ->route('cases.show', $case->id)
            ->with('success', 'Case berhasil ditutup.');
    }

    public function examinationIndex()
    {
        $cases = AutopsyCase::with(['assignedDoctor', 'registrationOfficer', 'examination'])
            ->latest()
            ->paginate(6);

        return view('examination.index', compact('cases'));
    }

    public function examination($id)
    {
        $case = AutopsyCase::with(['assignedDoctor', 'registrationOfficer', 'examination'])
            ->findOrFail($id);

        $examination = $case->examination;

        return view('examination.examination', compact('case', 'examination'));
    }

    public function saveExamination(Request $request, $id)
    {
        $case = AutopsyCase::findOrFail($id);

        $request->validate([
            'body_length' => 'nullable|string|max:50',
            'estimated_weight' => 'nullable|string|max:50',
            'rigor_mortis' => 'nullable|string|max:100',
            'external_description' => 'nullable|string',
            'external_injury' => 'nullable|string',

            'heart_weight' => 'nullable|string|max:50',
            'cardiovascular_description' => 'nullable|string',
            'right_lung_weight' => 'nullable|string|max:50',
            'left_lung_weight' => 'nullable|string|max:50',
            'respiratory_description' => 'nullable|string',
            'internal_observation' => 'nullable|string',

            'finding_1' => 'nullable|string',
            'finding_2' => 'nullable|string',

            'immediate_cause' => 'nullable|string',
            'underlying_cause' => 'nullable|string',
            'significant_conditions' => 'nullable|string',
            'manner_of_death' => 'nullable|string|max:100',
            'toxicology_pending' => 'nullable',

            'notes' => 'nullable|string',
            'status' => 'required|in:Draft,Submitted',
        ]);

        $externalExamination =
            "Body Length: " . ($request->body_length ?: '-') . " cm\n" .
            "Estimated Weight: " . ($request->estimated_weight ?: '-') . " kg\n" .
            "Rigor Mortis: " . ($request->rigor_mortis ?: '-') . "\n\n" .
            "General Description:\n" . ($request->external_description ?: '-') . "\n\n" .
            "Evidence of External Injury:\n" . ($request->external_injury ?: '-');

        $internalExamination =
            "Heart Weight: " . ($request->heart_weight ?: '-') . " g\n\n" .
            "Cardiovascular Description:\n" . ($request->cardiovascular_description ?: '-') . "\n\n" .
            "Right Lung Weight: " . ($request->right_lung_weight ?: '-') . " g\n" .
            "Left Lung Weight: " . ($request->left_lung_weight ?: '-') . " g\n\n" .
            "Respiratory Description:\n" . ($request->respiratory_description ?: '-') . "\n\n" .
            "Abdominal Organs & Other Internal Observations:\n" . ($request->internal_observation ?: '-');

        $findings =
            "1. " . ($request->finding_1 ?: '-') . "\n" .
            "2. " . ($request->finding_2 ?: '-');

        $causeOfDeath =
            "Immediate Cause:\n" . ($request->immediate_cause ?: '-') . "\n\n" .
            "Due To / Consequence Of:\n" . ($request->underlying_cause ?: '-') . "\n\n" .
            "Other Significant Conditions:\n" . ($request->significant_conditions ?: '-') . "\n\n" .
            "Manner of Death: " . ($request->manner_of_death ?: '-') . "\n" .
            "Toxicology Pending: " . ($request->has('toxicology_pending') ? 'Yes' : 'No');

        AutopsyExamination::updateOrCreate(
            ['case_id' => $case->id],
            [
                'case_id' => $case->id,
                'external_examination' => $externalExamination,
                'internal_examination' => $internalExamination,
                'findings' => $findings,
                'cause_of_death' => $causeOfDeath,
                'notes' => $request->notes,
                'status' => $request->status,
                'submitted_at' => $request->status === 'Submitted' ? now() : null,
            ]
        );

        $case->update([
            'status' => $request->status === 'Submitted'
                ? 'completed'
                : 'in_progress',
        ]);

        return redirect()
            ->route('cases.examination', $case->id)
            ->with(
                'success',
                $request->status === 'Submitted'
                    ? 'Examination report berhasil disubmit.'
                    : 'Draft examination berhasil disimpan.'
            );
    }
}
