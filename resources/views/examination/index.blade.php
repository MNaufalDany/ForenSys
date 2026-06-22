@extends('layouts.app')

@section('title', 'Examination')

@php
    $activePage = 'examination';
    $sidebarVariant = 'default';

    function statusBadgeClass($status) {
        return match ($status) {
            'Registered' => 'bg-slate-100 text-slate-800',
            'In Progress' => 'bg-amber-100 text-amber-800',
            'Completed' => 'bg-green-100 text-green-800',
            'Closed' => 'bg-red-100 text-red-800',
            default => 'bg-slate-100 text-slate-800',
        };
    }
@endphp

@section('content')
<div class="space-y-6">

    <div>
        <h2 class="text-3xl font-extrabold text-[#00236f]">Examination</h2>
        <p class="text-sm text-[#444651] mt-1">
            Select a registered case to begin or continue the post-mortem examination.
        </p>
    </div>

    <div class="bg-white border border-[#c5c5d3] rounded-xl overflow-hidden">
        <div class="p-4 border-b border-[#c5c5d3] flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-[#1a1b21]">Available Cases</h3>
                <p class="text-xs text-[#757682] mt-1">
                    Choose one case to open the examination form.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-[#f4f3fa] text-[#444651] uppercase text-[11px] tracking-widest">
                    <tr>
                        <th class="px-4 py-3">Case Number</th>
                        <th class="px-4 py-3">Victim Name</th>
                        <th class="px-4 py-3">Assigned Doctor</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#d2d4df]">
                    @forelse ($cases as $case)
                        <tr class="hover:bg-[#f8f9ff] transition">
                            <td class="px-4 py-4 font-bold text-[#00236f]">
                                {{ $case->case_number }}
                            </td>

                            <td class="px-4 py-4">
                                {{ $case->victim_name ?? 'Unknown Subject' }}
                            </td>

                            <td class="px-4 py-4">
                                {{ $case->assignedDoctor->name ?? '-' }}
                            </td>

                            <td class="px-4 py-4">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ statusBadgeClass($case->status) }}">
                                    {{ $case->status }}
                                </span>
                            </td>

                            <td class="px-4 py-4 text-right">
                                <a href="{{ route('cases.examination', $case->id) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#00236f] text-white text-xs font-bold hover:bg-[#001a52]">
                                    <span class="material-symbols-outlined text-base">stethoscope</span>
                                    Open Examination
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-[#757682]">
                                No cases available for examination.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($cases->hasPages())
            <div class="p-4 border-t border-[#c5c5d3]">
                {{ $cases->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
