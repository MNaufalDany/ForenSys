@extends('layouts.app')

@section('title', 'Add New Case')

@php
    $activePage = 'cases';
    $sidebarVariant = 'default';
    $navbarVariant = 'default';
    $contentWidth = 'default';
@endphp

@section('content')
<div class="space-y-6">

    <div>
        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-[#757682]">
            <a href="{{ route('cases.index') }}" class="hover:text-[#00236f]">Case Management</a>
            <span>/</span>
            <span class="text-[#1a1b21]">New Case</span>
        </div>

        <h2 class="text-2xl sm:text-3xl font-extrabold text-[#00236f] mt-2">
            Case Intake Registration
        </h2>

        <p class="text-sm text-[#444651] mt-1">
            Initialize a new forensic file. Fields marked with * are required.
        </p>
    </div>

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
            <p class="font-bold mb-2">Please check the form again.</p>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="newCaseForm" action="{{ route('cases.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

            <div class="lg:col-span-8">
                <div class="bg-white border border-[#c5c5d3] rounded-xl p-5 sm:p-6 shadow-sm">
                    <div class="flex items-center gap-3 pb-4 mb-5 border-b border-[#d2d4df]">
                        <span class="material-symbols-outlined text-[#00236f]">person_add</span>
                        <h3 class="text-lg font-bold text-[#1a1b21]">Victim Identification</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-[#444651]">
                                Case Number <span class="text-red-600">*</span>
                            </label>
                            <input
                                type="text"
                                name="case_number"
                                value="{{ old('case_number') }}"
                                placeholder="e.g. FS-2026-001"
                                class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#00236f]"
                                required
                            >
                            <p class="mt-1 text-[11px] text-[#757682]">
                                Unique forensic identifier.
                            </p>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-[#444651]">
                                Victim Name
                            </label>
                            <input
                                type="text"
                                name="victim_name"
                                value="{{ old('victim_name') }}"
                                placeholder="Full name or Unknown Subject"
                                class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#00236f]"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label class="text-sm font-semibold text-[#444651]">Gender</label>
                            <select
                                name="gender"
                                class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#00236f]"
                            >
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="unknown" {{ old('gender') === 'unknown' ? 'selected' : '' }}>Unknown</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-[#444651]">Age</label>
                            <input
                                type="number"
                                name="age"
                                value="{{ old('age') }}"
                                placeholder="Years"
                                min="0"
                                max="120"
                                class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#00236f]"
                            >
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-[#444651]">
                                Date Received <span class="text-red-600">*</span>
                            </label>
                            <input
                                type="date"
                                name="date_received"
                                value="{{ old('date_received') }}"
                                class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#00236f]"
                                required
                            >
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="text-sm font-semibold text-[#444651]">
                            Description / Initial Findings
                        </label>

                        <textarea
                            id="descriptionInput"
                            name="description"
                            rows="5"
                            maxlength="2000"
                            placeholder="Provide a short summary of the initial condition, scene context, or visible findings..."
                            class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#00236f] resize-none"
                        >{{ old('description') }}</textarea>

                        <div class="flex justify-between mt-1 text-[11px] text-[#757682]">
                            <span>Professional narrative format.</span>
                            <span id="descriptionCounter">0 / 2000 characters</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-5">
                <div class="bg-white border border-[#c5c5d3] rounded-xl p-5 sm:p-6 shadow-sm">
                    <div class="flex items-center gap-3 pb-4 mb-5 border-b border-[#d2d4df]">
                        <span class="material-symbols-outlined text-[#00236f]">assignment_ind</span>
                        <h3 class="text-lg font-bold text-[#1a1b21]">Classification</h3>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-semibold text-[#444651]">
                                Case Type <span class="text-red-600">*</span>
                            </label>
                            <select
                                name="case_type"
                                class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#00236f]"
                                required
                            >
                                <option value="criminal" {{ old('case_type') === 'criminal' ? 'selected' : '' }}>Criminal</option>
                                <option value="accident" {{ old('case_type') === 'accident' ? 'selected' : '' }}>Accident</option>
                                <option value="unknown_death" {{ old('case_type') === 'unknown_death' ? 'selected' : '' }}>Unknown Death</option>
                                <option value="other" {{ old('case_type') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-[#444651]">
                                Assigned Doctor <span class="text-red-600">*</span>
                            </label>
                            <select
                                name="assigned_doctor_id"
                                class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#00236f]"
                                required
                            >
                                <option value="">Select Doctor</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('assigned_doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-[#444651]">Referring Institution</label>
                            <input
                                type="text"
                                name="referring_institution"
                                value="{{ old('referring_institution') }}"
                                placeholder="e.g. Polrestabes Surabaya"
                                class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#00236f]"
                            >
                        </div>

                        <div class="rounded-lg border border-[#b6c4ff] bg-[#f4f3fa] p-4">
                            <p class="flex gap-2 text-xs font-medium text-[#264191]">
                                <span class="material-symbols-outlined text-sm">info</span>
                                Filing this case will create an initial status: Registered.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="hidden lg:block rounded-xl bg-[#00236f] p-5 text-white shadow-md relative overflow-hidden">
                    <div class="relative z-10">
                        <h4 class="text-base font-bold">Case Status Flow</h4>
                        <p class="text-sm opacity-80 mt-2">
                            Each case will move through a controlled forensic workflow.
                        </p>

                        <div class="space-y-3 mt-5 text-xs font-semibold">
                            <div class="flex items-center gap-3">
                                <span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">1</span>
                                Registered
                            </div>
                            <div class="flex items-center gap-3 opacity-70">
                                <span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">2</span>
                                Assigned
                            </div>
                            <div class="flex items-center gap-3 opacity-70">
                                <span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">3</span>
                                In Progress
                            </div>
                        </div>
                    </div>

                    <span class="material-symbols-outlined absolute -right-8 -bottom-8 text-[150px] opacity-10">
                        lock
                    </span>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-5 border-t border-[#d2d4df]">
            <a
                href="{{ route('cases.index') }}"
                class="w-full sm:w-auto text-center px-8 py-3 rounded-lg border border-[#757682] text-[#505f76] font-bold hover:bg-white"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-10 py-3 rounded-lg bg-[#00236f] text-white font-bold shadow-md hover:bg-[#001a52]"
            >
                <span class="material-symbols-outlined text-base">save</span>
                Save Case File
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const descriptionInput = document.getElementById('descriptionInput');
    const descriptionCounter = document.getElementById('descriptionCounter');

    if (descriptionInput && descriptionCounter) {
        const updateCounter = () => {
            descriptionCounter.textContent = `${descriptionInput.value.length} / 2000 characters`;
        };

        updateCounter();
        descriptionInput.addEventListener('input', updateCounter);
    }
</script>
@endpush
