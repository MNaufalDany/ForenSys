@extends('layouts.app')

@section('title', 'Case Examination')

@php
$activePage = 'examination';
$sidebarVariant = 'default';
@endphp

@section('content')

<div class="space-y-6">

```
@if(session('success'))
    <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
        <p class="font-bold mb-2">Please check the following errors:</p>
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('cases.examination.save', $case->id) }}" method="POST">
    @csrf

    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold tracking-wide text-[#757682]">
                <a href="{{ route('examination.index') }}" class="hover:text-[#00236f]">Examination</a>
                <span>›</span>
                <span class="text-[#00236f]">Case #{{ $case->case_number }}</span>
            </div>

            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#1a1b21] mt-2">
                Post-Mortem Examination Report
            </h2>

            <p class="text-sm text-[#444651] mt-1">
                Medical Examiner: {{ $case->assignedDoctor->name ?? '-' }}
                |
                Registered: {{ \Carbon\Carbon::parse($case->date_received)->format('d M Y') }}
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <button type="submit" name="status" value="Draft"
                class="px-5 py-3 rounded-lg border border-[#757682] text-[#444651] text-sm font-bold hover:bg-white">
                Save Draft
            </button>

            <button type="submit" name="status" value="Submitted"
                class="px-5 py-3 rounded-lg bg-[#00236f] text-white text-sm font-bold shadow-md hover:bg-[#001a52]">
                Submit Report
            </button>
        </div>
    </div>

    <div class="bg-white border border-[#c5c5d3] rounded-xl shadow-sm overflow-hidden mt-6">
        <div class="flex overflow-x-auto border-b border-[#c5c5d3]">
            <button type="button" onclick="switchTab('external')" id="tab-external"
                class="tab-button active-tab px-5 py-4 text-xs font-bold tracking-widest whitespace-nowrap">
                EXTERNAL EXAMINATION
            </button>

            <button type="button" onclick="switchTab('internal')" id="tab-internal"
                class="tab-button px-5 py-4 text-xs font-bold tracking-widest text-[#444651] whitespace-nowrap">
                INTERNAL EXAMINATION
            </button>

            <button type="button" onclick="switchTab('findings')" id="tab-findings"
                class="tab-button px-5 py-4 text-xs font-bold tracking-widest text-[#444651] whitespace-nowrap">
                KEY FINDINGS
            </button>

            <button type="button" onclick="switchTab('cause')" id="tab-cause"
                class="tab-button px-5 py-4 text-xs font-bold tracking-widest text-[#444651] whitespace-nowrap">
                CAUSE OF DEATH
            </button>

            <button type="button" onclick="switchTab('notes')" id="tab-notes"
                class="tab-button px-5 py-4 text-xs font-bold tracking-widest text-[#444651] whitespace-nowrap">
                ADDITIONAL NOTES
            </button>
        </div>

        <div class="p-5 sm:p-6">

            <section id="section-external" class="tab-section">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                    <div class="lg:col-span-8 space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold tracking-widest text-[#444651]">BODY LENGTH (CM)</label>
                                <input type="number" name="body_length" placeholder="0.0" value="{{ old('body_length') }}"
                                    class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] px-4 py-3 text-sm focus:ring-2 focus:ring-[#00236f] outline-none">
                            </div>

                            <div>
                                <label class="text-xs font-bold tracking-widest text-[#444651]">ESTIMATED WEIGHT (KG)</label>
                                <input type="number" name="estimated_weight" placeholder="0.0" value="{{ old('estimated_weight') }}"
                                    class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] px-4 py-3 text-sm focus:ring-2 focus:ring-[#00236f] outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold tracking-widest text-[#444651]">GENERAL DESCRIPTION & IDENTIFYING MARKS</label>
                            <textarea name="external_description" rows="5"
                                placeholder="Describe clothing, scars, tattoos, and general state of remains..."
                                class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] px-4 py-3 text-sm resize-none focus:ring-2 focus:ring-[#00236f] outline-none">{{ old('external_description') }}</textarea>
                        </div>

                        <div>
                            <label class="text-xs font-bold tracking-widest text-[#444651]">EVIDENCE OF INJURY (EXTERNAL)</label>
                            <textarea name="external_injury" rows="4"
                                placeholder="Detail any contusions, abrasions, or lacerations..."
                                class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] px-4 py-3 text-sm resize-none focus:ring-2 focus:ring-[#00236f] outline-none">{{ old('external_injury') }}</textarea>
                        </div>
                    </div>

                    <div class="lg:col-span-4 space-y-5">
                        <div class="rounded-xl border border-[#c5c5d3] bg-[#f4f3fa] p-4">
                            <h4 class="text-xs font-bold tracking-widest text-[#00236f] mb-3">REFERENCE DIAGRAM</h4>

                            <div class="aspect-[3/4] rounded-lg border border-dashed border-[#757682] bg-[#e9e7ef] flex items-center justify-center">
                                <span class="material-symbols-outlined text-6xl text-[#757682]">accessibility_new</span>
                            </div>

                            <p class="text-xs text-center text-[#757682] mt-2">
                                Click to annotate anatomical markers.
                            </p>
                        </div>

                        <div>
                            <label class="text-xs font-bold tracking-widest text-[#444651]">RIGOR MORTIS</label>
                            <select name="rigor_mortis"
                                class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] px-4 py-3 text-sm focus:ring-2 focus:ring-[#00236f] outline-none">
                                <option value="Absent" {{ old('rigor_mortis') === 'Absent' ? 'selected' : '' }}>Absent</option>
                                <option value="Minimal" {{ old('rigor_mortis') === 'Minimal' ? 'selected' : '' }}>Minimal</option>
                                <option value="Moderate" {{ old('rigor_mortis') === 'Moderate' ? 'selected' : '' }}>Moderate</option>
                                <option value="Generalized" {{ old('rigor_mortis') === 'Generalized' ? 'selected' : '' }}>Generalized</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <section id="section-internal" class="tab-section hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-4">
                        <h3 class="text-lg font-bold text-[#00236f] border-b border-[#d2d4df] pb-2">
                            Cardiovascular System
                        </h3>

                        <div>
                            <label class="text-xs font-bold tracking-widest text-[#444651]">HEART WEIGHT (G)</label>
                            <input type="number" name="heart_weight" value="{{ old('heart_weight') }}"
                                class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] px-4 py-3 text-sm focus:ring-2 focus:ring-[#00236f] outline-none">
                        </div>

                        <div>
                            <label class="text-xs font-bold tracking-widest text-[#444651]">CARDIOVASCULAR DESCRIPTION</label>
                            <textarea name="cardiovascular_description" rows="4"
                                placeholder="Describe heart condition, vessels, and abnormalities..."
                                class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] px-4 py-3 text-sm resize-none focus:ring-2 focus:ring-[#00236f] outline-none">{{ old('cardiovascular_description') }}</textarea>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-lg font-bold text-[#00236f] border-b border-[#d2d4df] pb-2">
                            Respiratory System
                        </h3>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold tracking-widest text-[#444651]">RIGHT LUNG (G)</label>
                                <input type="number" name="right_lung_weight" value="{{ old('right_lung_weight') }}"
                                    class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] px-4 py-3 text-sm focus:ring-2 focus:ring-[#00236f] outline-none">
                            </div>

                            <div>
                                <label class="text-xs font-bold tracking-widest text-[#444651]">LEFT LUNG (G)</label>
                                <input type="number" name="left_lung_weight" value="{{ old('left_lung_weight') }}"
                                    class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] px-4 py-3 text-sm focus:ring-2 focus:ring-[#00236f] outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold tracking-widest text-[#444651]">RESPIRATORY DESCRIPTION</label>
                            <textarea name="respiratory_description" rows="4"
                                placeholder="Describe lungs, airway condition, and visible abnormalities..."
                                class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] px-4 py-3 text-sm resize-none focus:ring-2 focus:ring-[#00236f] outline-none">{{ old('respiratory_description') }}</textarea>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-bold tracking-widest text-[#444651]">ABDOMINAL ORGANS & OTHER INTERNAL OBSERVATIONS</label>
                        <textarea name="internal_observation" rows="5"
                            placeholder="Describe abdominal organs, internal injuries, and other relevant findings..."
                            class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] px-4 py-3 text-sm resize-none focus:ring-2 focus:ring-[#00236f] outline-none">{{ old('internal_observation') }}</textarea>
                    </div>
                </div>
            </section>

            <section id="section-findings" class="tab-section hidden">
                <div class="space-y-5">
                    <div class="rounded-lg border border-[#b6c4ff] bg-[#f4f3fa] p-4">
                        <p class="flex gap-2 text-sm text-[#264191]">
                            <span class="material-symbols-outlined text-base">info</span>
                            List key pathological findings in order of clinical significance.
                        </p>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center gap-3 rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] p-3">
                            <span class="w-7 h-7 rounded-full bg-[#00236f] text-white flex items-center justify-center text-xs font-bold">1</span>
                            <input type="text" name="finding_1" value="{{ old('finding_1') }}" placeholder="Add primary finding..."
                                class="flex-1 bg-transparent border-0 focus:ring-0 text-sm">
                        </div>

                        <div class="flex items-center gap-3 rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] p-3">
                            <span class="w-7 h-7 rounded-full bg-[#00236f] text-white flex items-center justify-center text-xs font-bold">2</span>
                            <input type="text" name="finding_2" value="{{ old('finding_2') }}" placeholder="Add secondary finding..."
                                class="flex-1 bg-transparent border-0 focus:ring-0 text-sm">
                        </div>
                    </div>
                </div>
            </section>

            <section id="section-cause" class="tab-section hidden">
                <div class="max-w-3xl space-y-5">
                    <div>
                        <h3 class="text-lg font-bold text-[#00236f]">Opinion on Cause of Death</h3>
                        <p class="text-sm text-[#444651] mt-1">
                            This section will be used for final report documentation. Please be precise.
                        </p>
                    </div>

                    <div class="rounded-xl border border-[#c5c5d3] bg-[#f4f3fa] p-5 space-y-4">
                        <div>
                            <label class="text-xs font-bold tracking-widest text-[#444651]">IMMEDIATE CAUSE</label>
                            <textarea name="immediate_cause" rows="2"
                                class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-white px-4 py-3 text-sm resize-none focus:ring-2 focus:ring-[#00236f] outline-none">{{ old('immediate_cause') }}</textarea>
                        </div>

                        <div>
                            <label class="text-xs font-bold tracking-widest text-[#444651]">DUE TO / CONSEQUENCE OF</label>
                            <textarea name="underlying_cause" rows="2"
                                class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-white px-4 py-3 text-sm resize-none focus:ring-2 focus:ring-[#00236f] outline-none">{{ old('underlying_cause') }}</textarea>
                        </div>

                        <div>
                            <label class="text-xs font-bold tracking-widest text-[#444651]">OTHER SIGNIFICANT CONDITIONS</label>
                            <textarea name="significant_conditions" rows="2"
                                class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-white px-4 py-3 text-sm resize-none focus:ring-2 focus:ring-[#00236f] outline-none">{{ old('significant_conditions') }}</textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold tracking-widest text-[#444651]">MANNER OF DEATH</label>
                            <select name="manner_of_death"
                                class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] px-4 py-3 text-sm focus:ring-2 focus:ring-[#00236f] outline-none">
                                <option value="Natural" {{ old('manner_of_death') === 'Natural' ? 'selected' : '' }}>Natural</option>
                                <option value="Accident" {{ old('manner_of_death') === 'Accident' ? 'selected' : '' }}>Accident</option>
                                <option value="Suicide" {{ old('manner_of_death') === 'Suicide' ? 'selected' : '' }}>Suicide</option>
                                <option value="Homicide" {{ old('manner_of_death') === 'Homicide' ? 'selected' : '' }}>Homicide</option>
                                <option value="Pending Investigation" {{ old('manner_of_death') === 'Pending Investigation' ? 'selected' : '' }}>Pending Investigation</option>
                                <option value="Could not be determined" {{ old('manner_of_death') === 'Could not be determined' ? 'selected' : '' }}>Could not be determined</option>
                            </select>
                        </div>

                        <label class="flex items-center gap-3 pt-7">
                            <input type="checkbox" name="toxicology_pending"
                                class="w-5 h-5 rounded border-[#c5c5d3] text-[#00236f] focus:ring-[#00236f]"
                                {{ old('toxicology_pending') ? 'checked' : '' }}>
                            <span class="text-sm font-semibold text-[#444651]">Toxicology pending</span>
                        </label>
                    </div>
                </div>
            </section>

            <section id="section-notes" class="tab-section hidden">
                <div class="space-y-5">
                    <div>
                        <label class="text-xs font-bold tracking-widest text-[#444651]">
                            SUPPLEMENTARY COMMENTS & INTERNAL NOTES
                        </label>
                        <textarea name="notes" rows="10"
                            placeholder="Administrative notes, laboratory requests, or internal coordination details..."
                            class="mt-2 w-full rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] px-4 py-3 text-sm resize-none focus:ring-2 focus:ring-[#00236f] outline-none">{{ old('notes', $examination->notes ?? '') }}</textarea>
                    </div>

                    @if($examination)
                        <div class="rounded-lg border border-[#c5c5d3] bg-[#f4f3fa] p-4">
                            <p class="text-xs font-bold tracking-widest text-[#00236f] mb-2">
                                SAVED EXAMINATION STATUS
                            </p>
                            <p class="text-sm text-[#444651]">
                                Status: <span class="font-bold">{{ $examination->status }}</span>
                                @if($examination->submitted_at)
                                    | Submitted: {{ \Carbon\Carbon::parse($examination->submitted_at)->format('d M Y H:i') }}
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </section>

        </div>
    </div>
</form>
```

</div>
@endsection

@push('scripts')

<style>
    .active-tab {
        color: #00236f;
        border-bottom: 2px solid #00236f;
        background: #ffffff;
    }
</style>

<script>
    function switchTab(tabName) {
        document.querySelectorAll('.tab-section').forEach(section => {
            section.classList.add('hidden');
        });

        document.querySelectorAll('.tab-button').forEach(tab => {
            tab.classList.remove('active-tab');
            tab.classList.add('text-[#444651]');
        });

        document.getElementById('section-' + tabName).classList.remove('hidden');

        const activeTab = document.getElementById('tab-' + tabName);
        activeTab.classList.add('active-tab');
        activeTab.classList.remove('text-[#444651]');
    }
</script>

@endpush
