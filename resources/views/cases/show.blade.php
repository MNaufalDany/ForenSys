@extends('layouts.app')

@section('title', 'Case Detail')

@section('content')
@php
    $status = strtolower(trim($case->status));

    $statusBadgeClass = match ($status) {
        'registered' => 'bg-slate-100 text-slate-800',
        'assigned' => 'bg-blue-100 text-blue-800',
        'in_progress' => 'bg-amber-100 text-amber-800',
        'completed' => 'bg-green-100 text-green-800',
        'verified' => 'bg-indigo-100 text-indigo-800',
        'closed' => 'bg-red-100 text-red-800',
        default => 'bg-slate-100 text-slate-800',
    };

    $statusLabel = match ($status) {
        'registered' => 'Registered',
        'assigned' => 'Assigned',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'verified' => 'Verified',
        'closed' => 'Closed',
        default => ucwords(str_replace('_', ' ', $status)),
    };

    $statusSteps = [
        'registered' => 'Registered',
        'assigned' => 'Assigned',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'verified' => 'Verified',
        'closed' => 'Closed',
    ];

    $statusKeys = array_keys($statusSteps);
    $currentIndex = array_search($status, $statusKeys);

    if ($currentIndex === false) {
        $currentIndex = 0;
    }

    $examination = $case->examination;

    $examFields = [
        'external_examination',
        'internal_examination',
        'findings',
        'cause_of_death',
        'notes',
    ];

    $filledFields = 0;

    if ($examination) {
        foreach ($examFields as $field) {
            $value = $examination->{$field} ?? null;

            if (!empty($value) && trim($value) !== '-' && trim($value) !== '') {
                $filledFields++;
            }
        }
    }

    $baseProgress = match ($status) {
        'registered' => 15,
        'assigned' => 25,
        'in_progress' => 35,
        'completed' => 85,
        'verified' => 95,
        'closed' => 100,
        default => 0,
    };

    $examProgress = count($examFields) > 0
        ? intval(($filledFields / count($examFields)) * 60)
        : 0;

    if (in_array($status, ['completed', 'verified', 'closed'])) {
        $progress = $baseProgress;
    } else {
        $progress = min(84, $baseProgress + $examProgress);
    }

    $progressNote = $examination
        ? $filledFields . ' of ' . count($examFields) . ' examination sections completed.'
        : 'No examination document has been filled yet.';
@endphp

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white border border-[#c5c5d3] rounded-xl p-5">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-[#757682]">
                    Case Detail
                </p>

                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#00236f] mt-1">
                    Case #{{ $case->case_number }}
                </h2>

                <p class="text-sm text-[#444651] mt-1">
                    {{ ucwords(str_replace('_', ' ', $case->case_type)) }} Investigation
                </p>
            </div>

            <span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-bold {{ $statusBadgeClass }}">
                {{ $statusLabel }}
            </span>
        </div>

        {{-- STATUS STEPS --}}
        <div class="mt-6 overflow-x-auto">
            <div class="min-w-[760px] flex items-center">
                @foreach($statusSteps as $stepKey => $stepLabel)
                    @php
                        $index = array_search($stepKey, $statusKeys);
                    @endphp

                    <div class="flex flex-col items-center gap-2 shrink-0">
                        @if($index < $currentIndex)
                            <div class="w-8 h-8 rounded-full bg-[#00236f] text-white flex items-center justify-center">
                                <span class="material-symbols-outlined text-sm">check</span>
                            </div>
                            <span class="text-xs font-bold text-[#00236f]">{{ $stepLabel }}</span>
                        @elseif($index == $currentIndex)
                            <div class="w-8 h-8 rounded-full border-2 border-[#00236f] flex items-center justify-center">
                                <span class="w-2 h-2 rounded-full bg-[#00236f]"></span>
                            </div>
                            <span class="text-xs font-bold text-[#00236f]">{{ $stepLabel }}</span>
                        @else
                            <div class="w-8 h-8 rounded-full border-2 border-[#c5c5d3]"></div>
                            <span class="text-xs font-semibold text-[#757682]">{{ $stepLabel }}</span>
                        @endif
                    </div>

                    @if(!$loop->last)
                        <div class="h-0.5 flex-1 mx-3 {{ $index < $currentIndex ? 'bg-[#00236f]' : 'bg-[#d2d4df]' }}"></div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
            <p class="font-bold mb-2">Ada data yang belum valid:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        {{-- LEFT CONTENT --}}
        <div class="lg:col-span-8 space-y-5">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- VICTIM INFORMATION --}}
                <div class="bg-white border border-[#c5c5d3] rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-[#f4f3fa] px-4 py-3 border-b border-[#d2d4df]">
                        <h3 class="font-bold text-[#1a1b21]">Victim Information</h3>
                    </div>

                    <div class="p-4 space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-lg bg-[#d2d4df] flex items-center justify-center text-xs">
                                img
                            </div>

                            <div>
                                <p class="font-bold">{{ $case->victim_name }}</p>
                                <p class="text-sm text-[#757682]">
                                    Age: {{ $case->age ?? '-' }} | ID: {{ $case->case_number }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-xs text-[#757682] uppercase tracking-wide">Gender</p>
                                <p>{{ ucfirst($case->gender) }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-[#757682] uppercase tracking-wide">Blood Type</p>
                                <p>Unknown</p>
                            </div>

                            <div class="col-span-2">
                                <p class="text-xs text-[#757682] uppercase tracking-wide">Known Medical Conditions</p>
                                <p>Not recorded</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CASE DETAILS --}}
                <div class="bg-white border border-[#c5c5d3] rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-[#f4f3fa] px-4 py-3 border-b border-[#d2d4df] flex justify-between items-center">
                        <h3 class="font-bold text-[#1a1b21]">Case Details</h3>
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-bold">
                            URGENT
                        </span>
                    </div>

                    <div class="p-4 space-y-4 text-sm">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-[#757682] uppercase tracking-wide">Date Received</p>
                                <p>{{ $case->date_received ? \Carbon\Carbon::parse($case->date_received)->format('d M Y') : '-' }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-[#757682] uppercase tracking-wide">Location</p>
                                <p>{{ $case->referring_institution ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-[#757682] uppercase tracking-wide">Case Type</p>
                                <p>{{ ucwords(str_replace('_', ' ', $case->case_type)) }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-[#757682] uppercase tracking-wide">Officer</p>
                                <p>{{ $case->registrationOfficer?->name ?? '-' }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs text-[#757682] uppercase tracking-wide">Preliminary Findings</p>
                            <p class="mt-1 text-[#444651]">
                                {{ $case->description ?? 'No preliminary findings recorded.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ATTACHED DOCUMENTS --}}
            <div class="bg-white border border-[#c5c5d3] rounded-xl overflow-hidden shadow-sm">
                <div class="bg-[#f4f3fa] px-4 py-3 border-b border-[#d2d4df]">
                    <h3 class="font-bold text-[#1a1b21]">Attached Documents</h3>
                </div>

                <div class="divide-y divide-[#d2d4df]">
                    @forelse($case->documents as $document)
                        <div class="p-4 flex items-center justify-between hover:bg-[#f8f9ff]">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-red-100 text-red-700 flex items-center justify-center">
                                    <span class="material-symbols-outlined">description</span>
                                </div>

                                <div>
                                    <p class="text-sm font-bold">{{ $document->document_name }}</p>
                                    <p class="text-xs text-[#757682]">
                                        {{ strtoupper($document->document_type ?? 'FILE') }}
                                        • Uploaded {{ $document->created_at ? $document->created_at->format('d M Y') : '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-1">
                                <a href="{{ asset('storage/' . $document->file_path) }}"
                                   target="_blank"
                                   class="p-2 text-[#757682] hover:text-[#00236f]">
                                    <span class="material-symbols-outlined">visibility</span>
                                </a>

                                <a href="{{ asset('storage/' . $document->file_path) }}"
                                   download
                                   class="p-2 text-[#757682] hover:text-[#00236f]">
                                    <span class="material-symbols-outlined">download</span>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-sm text-[#757682]">
                            No documents uploaded.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- CASE TIMELINE --}}
            <div class="bg-white border border-[#c5c5d3] rounded-xl overflow-hidden shadow-sm">
                <div class="bg-[#f4f3fa] px-4 py-3 border-b border-[#d2d4df]">
                    <h3 class="font-bold text-[#1a1b21]">Case Timeline</h3>
                </div>

                <div class="p-6 space-y-8 relative before:content-[''] before:absolute before:left-8 before:top-8 before:bottom-8 before:w-px before:bg-[#d2d4df]">
                    <div class="relative flex gap-5">
                        <div class="z-10 w-4 h-4 rounded-full bg-[#00236f] ring-4 ring-white shrink-0 mt-1"></div>
                        <div>
                            <p class="text-xs text-[#757682] uppercase">
                                {{ $case->updated_at ? $case->updated_at->format('d M Y • H:i') : '-' }}
                            </p>
                            <p class="text-sm font-bold text-[#00236f]">
                                Current Status: {{ $statusLabel }}
                            </p>
                            <p class="text-sm text-[#444651] mt-1">
                                Case status is currently marked as {{ $statusLabel }}.
                            </p>
                        </div>
                    </div>

                    @if($case->assignedDoctor)
                        <div class="relative flex gap-5">
                            <div class="z-10 w-4 h-4 rounded-full bg-[#757682] ring-4 ring-white shrink-0 mt-1"></div>
                            <div>
                                <p class="text-xs text-[#757682] uppercase">
                                    {{ $case->created_at ? $case->created_at->format('d M Y • H:i') : '-' }}
                                </p>
                                <p class="text-sm font-bold">Case Assigned</p>
                                <p class="text-sm text-[#444651] mt-1">
                                    Case assigned to {{ $case->assignedDoctor->name }}.
                                </p>
                            </div>
                        </div>
                    @endif

                    <div class="relative flex gap-5">
                        <div class="z-10 w-4 h-4 rounded-full bg-[#757682] ring-4 ring-white shrink-0 mt-1"></div>
                        <div>
                            <p class="text-xs text-[#757682] uppercase">
                                {{ $case->created_at ? $case->created_at->format('d M Y • H:i') : '-' }}
                            </p>
                            <p class="text-sm font-bold">Case Registered</p>
                            <p class="text-sm text-[#444651] mt-1">
                                Initial case record created by {{ $case->registrationOfficer?->name ?? 'registration officer' }}.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT SIDEBAR --}}
        <div class="lg:col-span-4 space-y-5">

            {{-- ASSIGNED DOCTOR --}}
            <div class="bg-white border border-[#c5c5d3] rounded-xl overflow-hidden shadow-sm">
                <div class="bg-[#f4f3fa] px-4 py-3 border-b border-[#d2d4df]">
                    <h3 class="font-bold text-[#1a1b21]">Assigned Doctor</h3>
                </div>

                <div class="p-4">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-[#d2d4df] flex items-center justify-center text-xs">
                            img
                        </div>

                        <div>
                            <p class="text-sm font-bold">
                                {{ $case->assignedDoctor?->name ?? 'Not Assigned' }}
                            </p>
                            <p class="text-xs text-[#757682]">
                                {{ $case->assignedDoctor?->role ?? 'Forensic Doctor' }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <button type="button"
                                onclick="openVerifyModal()"
                                {{ $status !== 'completed' ? 'disabled' : '' }}
                                class="w-full py-2 rounded-lg text-sm font-bold flex items-center justify-center gap-2
                                {{ $status === 'completed'
                                    ? 'bg-[#00236f] text-white hover:bg-[#001a52]'
                                    : 'bg-[#d2d4df] text-[#757682] cursor-not-allowed' }}">
                            <span class="material-symbols-outlined text-sm">verified</span>
                            Verify Case
                        </button>

                        <button type="button"
                                onclick="openCloseModal()"
                                {{ $status !== 'verified' ? 'disabled' : '' }}
                                class="w-full py-2 rounded-lg text-sm font-bold flex items-center justify-center gap-2
                                {{ $status === 'verified'
                                    ? 'bg-red-700 text-white hover:bg-red-800'
                                    : 'bg-[#d2d4df] text-[#757682] cursor-not-allowed' }}">
                            <span class="material-symbols-outlined text-sm">lock</span>
                            Close This Case
                        </button>

                        <p class="text-xs text-[#757682] leading-relaxed">
                            Verification is available after the examination report is completed. Closing is available after verification.
                        </p>
                    </div>
                </div>
            </div>

            {{-- LOCATION MAP --}}
            <div class="bg-white border border-[#c5c5d3] rounded-xl overflow-hidden shadow-sm">
                <div class="bg-[#f4f3fa] px-4 py-3 border-b border-[#d2d4df]">
                    <h3 class="font-bold text-[#1a1b21]">Location Map</h3>
                </div>

                <div class="h-44 bg-[#c5c5d3] relative flex items-center justify-center">
                    <span class="material-symbols-outlined text-[#00236f] text-4xl">location_on</span>
                    <div class="absolute bottom-3 left-3 bg-white px-2 py-1 rounded text-[10px] font-bold text-[#00236f]">
                        {{ $case->referring_institution ?? 'Location not recorded' }}
                    </div>
                </div>

                <div class="p-4">
                    <p class="text-xs text-[#757682] uppercase tracking-wide">Arrival Time</p>
                    <p class="text-sm">
                        {{ $case->date_received ? \Carbon\Carbon::parse($case->date_received)->format('d M Y') : '-' }}
                    </p>
                </div>
            </div>

            {{-- FORECAST --}}
            <div class="rounded-xl bg-[#00236f] text-white p-5 shadow-md relative overflow-hidden">
                <div class="relative z-10">
                    <h4 class="text-xs font-bold uppercase tracking-wide text-white/70">
                        Case Completion Forecast
                    </h4>

                    <div class="flex items-end gap-2 mt-5">
                        <span class="text-4xl font-extrabold">{{ $progress }}%</span>
                        <span class="text-xs text-white/80 mb-1">Estimated Progress</span>
                    </div>

                    <div class="w-full bg-white/20 h-2 rounded-full overflow-hidden mt-4">
                        <div class="bg-white h-full transition-all duration-300" style="width: {{ $progress }}%;"></div>
                    </div>

                    <p class="text-xs text-white/80 italic mt-4">
                        {{ $progressNote }}
                    </p>

                    <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
                        <div class="bg-white/10 rounded-lg p-2">
                            <p class="text-white/60">Status</p>
                            <p class="font-bold">{{ $statusLabel }}</p>
                        </div>

                        <div class="bg-white/10 rounded-lg p-2">
                            <p class="text-white/60">Examination</p>
                            <p class="font-bold">{{ $filledFields }}/{{ count($examFields) }}</p>
                        </div>
                    </div>
                </div>

                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            </div>

        </div>
    </div>
</div>

{{-- VERIFY MODAL --}}
<div id="verifyModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-xl border border-[#c5c5d3] overflow-hidden">
        <div class="bg-[#f4f3fa] px-5 py-4 border-b border-[#d2d4df] flex items-center justify-between">
            <h3 class="font-bold text-[#1a1b21]">Verify Case</h3>
            <button type="button" onclick="closeVerifyModal()" class="text-[#757682] hover:text-red-700">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form method="POST" action="{{ route('cases.verify', $case->id) }}" class="p-5 space-y-4">
            @csrf
            @method('PUT')

            <p class="text-sm text-[#444651]">
                Masukkan nomor kasus dan password dokter untuk memverifikasi case ini.
            </p>

            <div>
                <label class="text-xs text-[#757682] uppercase tracking-wide">Case Number</label>
                <input type="text"
                       name="case_number_confirmation"
                       placeholder="Example: {{ $case->case_number }}"
                       required
                       class="mt-1 w-full h-10 px-3 rounded-lg border border-[#c5c5d3] text-sm outline-none focus:ring-2 focus:ring-[#00236f]">
            </div>

            <div>
                <label class="text-xs text-[#757682] uppercase tracking-wide">Doctor Password</label>
                <input type="password"
                       name="doctor_password"
                       required
                       class="mt-1 w-full h-10 px-3 rounded-lg border border-[#c5c5d3] text-sm outline-none focus:ring-2 focus:ring-[#00236f]">
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button"
                        onclick="closeVerifyModal()"
                        class="px-4 py-2 rounded-lg border border-[#c5c5d3] text-sm font-bold text-[#444651] hover:bg-[#f4f3fa]">
                    Cancel
                </button>

                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-[#00236f] text-white text-sm font-bold hover:bg-[#001a52]">
                    Confirm Verify
                </button>
            </div>
        </form>
    </div>
</div>

{{-- CLOSE MODAL --}}
<div id="closeModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-xl border border-[#c5c5d3] overflow-hidden">
        <div class="bg-[#f4f3fa] px-5 py-4 border-b border-[#d2d4df] flex items-center justify-between">
            <h3 class="font-bold text-[#1a1b21]">Close Case</h3>
            <button type="button" onclick="closeCloseModal()" class="text-[#757682] hover:text-red-700">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form method="POST" action="{{ route('cases.close', $case->id) }}" class="p-5 space-y-4">
            @csrf
            @method('PUT')

            <p class="text-sm text-[#444651]">
                Masukkan nomor kasus dan password dokter untuk menutup case ini.
            </p>

            <div>
                <label class="text-xs text-[#757682] uppercase tracking-wide">Case Number</label>
                <input type="text"
                       name="case_number_confirmation"
                       placeholder="Example: {{ $case->case_number }}"
                       required
                       class="mt-1 w-full h-10 px-3 rounded-lg border border-[#c5c5d3] text-sm outline-none focus:ring-2 focus:ring-[#00236f]">
            </div>

            <div>
                <label class="text-xs text-[#757682] uppercase tracking-wide">Doctor Password</label>
                <input type="password"
                       name="doctor_password"
                       required
                       class="mt-1 w-full h-10 px-3 rounded-lg border border-[#c5c5d3] text-sm outline-none focus:ring-2 focus:ring-[#00236f]">
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button"
                        onclick="closeCloseModal()"
                        class="px-4 py-2 rounded-lg border border-[#c5c5d3] text-sm font-bold text-[#444651] hover:bg-[#f4f3fa]">
                    Cancel
                </button>

                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-red-700 text-white text-sm font-bold hover:bg-red-800">
                    Confirm Close
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openVerifyModal() {
        document.getElementById('verifyModal').classList.remove('hidden');
        document.getElementById('verifyModal').classList.add('flex');
    }

    function closeVerifyModal() {
        document.getElementById('verifyModal').classList.add('hidden');
        document.getElementById('verifyModal').classList.remove('flex');
    }

    function openCloseModal() {
        document.getElementById('closeModal').classList.remove('hidden');
        document.getElementById('closeModal').classList.add('flex');
    }

    function closeCloseModal() {
        document.getElementById('closeModal').classList.add('hidden');
        document.getElementById('closeModal').classList.remove('flex');
    }
</script>
@endsection
