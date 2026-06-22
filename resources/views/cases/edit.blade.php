@extends('layouts.app')

@section('title', 'Edit Case')

@section('content')
<form method="POST" action="{{ route('cases.update', $case->id) }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

    {{-- HEADER --}}
    <div class="bg-white border border-[#c5c5d3] rounded-xl p-5">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-[#757682]">
                    Edit Case
                </p>

                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#00236f] mt-1">
                    Case #{{ $case->case_number }}
                </h2>

                <p class="text-sm text-[#444651] mt-1">
                    Update forensic case information and upload supporting documents.
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('cases.show', $case->id) }}"
                   class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-[#c5c5d3] text-sm font-bold text-[#444651] hover:bg-[#f4f3fa]">
                    Cancel
                </a>

                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-[#00236f] text-white text-sm font-bold hover:bg-[#001a52]">
                    <span class="material-symbols-outlined text-sm">save</span>
                    Save Changes
                </button>
            </div>
        </div>

        {{-- STATUS STEPS --}}
        <div class="mt-6 overflow-x-auto">
            <div class="min-w-[760px] flex items-center">
                @foreach($statusSteps as $index => $step)
                    <div class="flex flex-col items-center gap-2 shrink-0">
                        @if($index < $currentIndex)
                            <div class="w-8 h-8 rounded-full bg-[#00236f] text-white flex items-center justify-center">
                                <span class="material-symbols-outlined text-sm">check</span>
                            </div>
                            <span class="text-xs font-bold text-[#00236f]">{{ $step }}</span>
                        @elseif($index == $currentIndex)
                            <div class="w-8 h-8 rounded-full border-2 border-[#00236f] flex items-center justify-center">
                                <span class="w-2 h-2 rounded-full bg-[#00236f]"></span>
                            </div>
                            <span class="text-xs font-bold text-[#00236f]">{{ $step }}</span>
                        @else
                            <div class="w-8 h-8 rounded-full border-2 border-[#c5c5d3]"></div>
                            <span class="text-xs font-semibold text-[#757682]">{{ $step }}</span>
                        @endif
                    </div>

                    @if(!$loop->last)
                        <div class="h-0.5 flex-1 mx-3 {{ $index < $currentIndex ? 'bg-[#00236f]' : 'bg-[#d2d4df]' }}"></div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- ERROR --}}
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

        {{-- LEFT --}}
        <div class="lg:col-span-8 space-y-5">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- VICTIM INFORMATION --}}
                <div class="bg-white border border-[#c5c5d3] rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-[#f4f3fa] px-4 py-3 border-b border-[#d2d4df] flex items-center justify-between">
                        <h3 class="font-bold text-[#1a1b21]">Victim Information</h3>
                        <span class="material-symbols-outlined text-[#00236f] text-sm">edit</span>
                    </div>

                    <div class="p-4 space-y-4">
                        <div>
                            <label class="text-xs text-[#757682] uppercase tracking-wide">Victim Name</label>
                            <input type="text"
                                   name="victim_name"
                                   value="{{ old('victim_name', $case->victim_name) }}"
                                   class="mt-1 w-full h-10 px-3 rounded-lg border border-[#c5c5d3] text-sm outline-none focus:ring-2 focus:ring-[#00236f]">
                        </div>

                        <div>
                            <label class="text-xs text-[#757682] uppercase tracking-wide">Case Number</label>
                            <input type="text"
                                   name="case_number"
                                   value="{{ old('case_number', $case->case_number) }}"
                                   class="mt-1 w-full h-10 px-3 rounded-lg border border-[#c5c5d3] text-sm outline-none focus:ring-2 focus:ring-[#00236f]">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-[#757682] uppercase tracking-wide">Gender</label>
                                <select name="gender"
                                        class="mt-1 w-full h-10 px-3 rounded-lg border border-[#c5c5d3] text-sm outline-none focus:ring-2 focus:ring-[#00236f]">
                                    <option value="male" {{ old('gender', $case->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $case->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="unknown" {{ old('gender', $case->gender) == 'unknown' ? 'selected' : '' }}>Unknown</option>
                                </select>
                            </div>

                            <div>
                                <label class="text-xs text-[#757682] uppercase tracking-wide">Age</label>
                                <input type="number"
                                       name="age"
                                       value="{{ old('age', $case->age) }}"
                                       class="mt-1 w-full h-10 px-3 rounded-lg border border-[#c5c5d3] text-sm outline-none focus:ring-2 focus:ring-[#00236f]">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CASE DETAILS --}}
                <div class="bg-white border border-[#c5c5d3] rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-[#f4f3fa] px-4 py-3 border-b border-[#d2d4df] flex items-center justify-between">
                        <h3 class="font-bold text-[#1a1b21]">Case Details</h3>
                        <span class="material-symbols-outlined text-[#00236f] text-sm">edit</span>
                    </div>

                    <div class="p-4 space-y-4 text-sm">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-[#757682] uppercase tracking-wide">Date Received</label>
                                <input type="date"
                                       name="date_received"
                                       value="{{ old('date_received', \Carbon\Carbon::parse($case->date_received)->format('Y-m-d')) }}"
                                       class="mt-1 w-full h-10 px-3 rounded-lg border border-[#c5c5d3] text-sm outline-none focus:ring-2 focus:ring-[#00236f]">
                            </div>

                            <div>
                                <label class="text-xs text-[#757682] uppercase tracking-wide">Status</label>
                                <select name="status"
                                        class="mt-1 w-full h-10 px-3 rounded-lg border border-[#c5c5d3] text-sm outline-none focus:ring-2 focus:ring-[#00236f]">
                                    @foreach($statusSteps as $status)
                                        <option value="{{ $status }}" {{ old('status', $case->status) == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-xs text-[#757682] uppercase tracking-wide">Case Type</label>
                                <select name="case_type"
                                        class="mt-1 w-full h-10 px-3 rounded-lg border border-[#c5c5d3] text-sm outline-none focus:ring-2 focus:ring-[#00236f]">
                                    <option value="criminal" {{ old('case_type', $case->case_type) == 'criminal' ? 'selected' : '' }}>Criminal</option>
                                    <option value="accident" {{ old('case_type', $case->case_type) == 'accident' ? 'selected' : '' }}>Accident</option>
                                    <option value="unknown_death" {{ old('case_type', $case->case_type) == 'unknown_death' ? 'selected' : '' }}>Unknown Death</option>
                                    <option value="other" {{ old('case_type', $case->case_type) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div>
                                <label class="text-xs text-[#757682] uppercase tracking-wide">Referring Institution</label>
                                <input type="text"
                                       name="referring_institution"
                                       value="{{ old('referring_institution', $case->referring_institution) }}"
                                       class="mt-1 w-full h-10 px-3 rounded-lg border border-[#c5c5d3] text-sm outline-none focus:ring-2 focus:ring-[#00236f]">
                            </div>
                        </div>

                        <div>
                            <label class="text-xs text-[#757682] uppercase tracking-wide">Preliminary Findings</label>
                            <textarea name="description"
                                      rows="4"
                                      class="mt-1 w-full px-3 py-2 rounded-lg border border-[#c5c5d3] text-sm outline-none focus:ring-2 focus:ring-[#00236f]">{{ old('description', $case->description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DOCUMENTS --}}
            <div class="bg-white border border-[#c5c5d3] rounded-xl overflow-hidden shadow-sm">
                <div class="bg-[#f4f3fa] px-4 py-3 border-b border-[#d2d4df] flex items-center justify-between">
                    <h3 class="font-bold text-[#1a1b21]">Attached Documents</h3>
                    <span class="text-xs font-bold text-[#757682]">Upload supporting file</span>
                </div>

                {{-- UPLOAD DOCUMENT --}}
                <div class="p-4 border-b border-[#d2d4df] bg-white space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-[#757682] uppercase tracking-wide">Document Name</label>
                            <input type="text"
                                   name="document_name"
                                   value="{{ old('document_name') }}"
                                   placeholder="Example: Police Report"
                                   class="mt-1 w-full h-10 px-3 rounded-lg border border-[#c5c5d3] text-sm outline-none focus:ring-2 focus:ring-[#00236f]">
                        </div>

                        <div>
                            <label class="text-xs text-[#757682] uppercase tracking-wide">Document Type</label>
                            <input type="text"
                                   name="document_type"
                                   value="{{ old('document_type') }}"
                                   placeholder="Example: PDF, Photo, Medical Record"
                                   class="mt-1 w-full h-10 px-3 rounded-lg border border-[#c5c5d3] text-sm outline-none focus:ring-2 focus:ring-[#00236f]">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs text-[#757682] uppercase tracking-wide">Upload File</label>
                        <input type="file"
                               name="document_file"
                               class="mt-1 w-full px-3 py-2 rounded-lg border border-[#c5c5d3] text-sm outline-none focus:ring-2 focus:ring-[#00236f] bg-white">
                        <p class="text-xs text-[#757682] mt-1">
                            Allowed files: PDF, DOC, DOCX, JPG, JPEG, PNG. Max 5 MB.
                        </p>
                    </div>
                </div>

                {{-- DOCUMENT LIST --}}
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
                                    </p>
                                </div>
                            </div>

                            <a href="{{ asset('storage/' . $document->file_path) }}"
                               target="_blank"
                               class="p-2 text-[#757682] hover:text-[#00236f]">
                                <span class="material-symbols-outlined">visibility</span>
                            </a>
                        </div>
                    @empty
                        <div class="p-6 text-center text-sm text-[#757682]">
                            No documents uploaded.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- RIGHT --}}
        <div class="lg:col-span-4 space-y-5">

            {{-- ASSIGNED DOCTOR --}}
            <div class="bg-white border border-[#c5c5d3] rounded-xl overflow-hidden shadow-sm">
                <div class="bg-[#f4f3fa] px-4 py-3 border-b border-[#d2d4df] flex items-center justify-between">
                    <h3 class="font-bold text-[#1a1b21]">Assigned Doctor</h3>
                    <span class="material-symbols-outlined text-[#00236f] text-sm">edit</span>
                </div>

                <div class="p-4">
                    <label class="text-xs text-[#757682] uppercase tracking-wide">Doctor</label>
                    <select name="assigned_doctor_id"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-[#c5c5d3] text-sm outline-none focus:ring-2 focus:ring-[#00236f]">
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('assigned_doctor_id', $case->assigned_doctor_id) == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- SUMMARY --}}
            <div class="rounded-xl bg-[#00236f] text-white p-5 shadow-md relative overflow-hidden">
                <div class="relative z-10">
                    <h4 class="text-xs font-bold uppercase tracking-wide text-white/70">
                        Editing Mode
                    </h4>

                    <div class="flex items-end gap-2 mt-5">
                        <span class="text-4xl font-extrabold">
                            <span class="material-symbols-outlined text-4xl">edit</span>
                        </span>
                        <span class="text-xs text-white/80 mb-1">Case data can be updated</span>
                    </div>

                    <p class="text-xs text-white/80 italic mt-4">
                        Press Save Changes to apply updates and upload document to this forensic case.
                    </p>
                </div>

                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            </div>

        </div>
    </div>
</form>
@endsection
