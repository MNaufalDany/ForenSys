@extends('layouts.app')

@section('title', 'Case Management')

@php
if (! function_exists('statusBadgeClass')) {
    function statusBadgeClass($status) {
        return match ($status) {
            'registered', 'Registered' => 'bg-blue-100 text-blue-700',
            'assigned', 'Assigned' => 'bg-purple-100 text-purple-700',
            'in_progress', 'In Progress' => 'bg-yellow-100 text-yellow-700',
            'completed', 'Completed' => 'bg-green-100 text-green-700',
            'verified', 'Verified' => 'bg-indigo-100 text-indigo-700',
            'closed', 'Closed' => 'bg-gray-100 text-gray-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}
@endphp

@section('content')
<div class="space-y-6">

    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-[#00236f]">Case Management</h2>
            <p class="text-sm text-[#444651] mt-1">
                Manage and track forensic autopsy cases across the facility.
            </p>
        </div>

        <a href="{{ route('cases.create') }}"
           class="inline-flex items-center justify-center gap-2 bg-[#00236f] text-white px-5 py-3 rounded-lg text-sm font-bold tracking-wider hover:bg-[#001a52]">
            <span class="material-symbols-outlined">add_circle</span>
            ADD NEW CASE
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white border border-[#c5c5d3] rounded-xl p-5">
            <p class="text-xs font-semibold tracking-widest uppercase text-[#444651]">Total Cases</p>
            <h3 class="text-3xl font-extrabold text-[#00236f] mt-3">{{ $totalCases }}</h3>
        </div>

        <div class="bg-white border border-[#c5c5d3] rounded-xl p-5">
            <p class="text-xs font-semibold tracking-widest uppercase text-[#444651]">Registered</p>
            <h3 class="text-3xl font-extrabold text-[#00236f] mt-3">{{ $registeredCases }}</h3>
        </div>

        <div class="bg-white border border-[#c5c5d3] rounded-xl p-5">
            <p class="text-xs font-semibold tracking-widest uppercase text-[#444651]">In Progress</p>
            <h3 class="text-3xl font-extrabold text-[#00236f] mt-3">{{ $inProgressCases }}</h3>
        </div>

        <div class="bg-white border border-[#c5c5d3] rounded-xl p-5">
            <p class="text-xs font-semibold tracking-widest uppercase text-[#444651]">Completed</p>
            <h3 class="text-3xl font-extrabold text-[#00236f] mt-3">{{ $completedCases }}</h3>
        </div>
    </div>

    <div class="bg-white border border-[#c5c5d3] rounded-xl overflow-hidden">
        <div class="p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-[#c5c5d3]">
            <form method="GET" action="{{ route('cases.index') }}" class="flex flex-col sm:flex-row gap-2">
                <input
                    name="search"
                    type="text"
                    value="{{ request('search') }}"
                    placeholder="Search case or victim name..."
                    class="h-10 px-4 rounded-lg border border-[#c5c5d3] text-sm outline-none focus:ring-2 focus:ring-[#00236f]"
                >

                <select
                    name="status"
                    class="h-10 px-4 rounded-lg border border-[#c5c5d3] text-sm outline-none focus:ring-2 focus:ring-[#00236f]"
                >
                    <option value="all">All Status</option>
                    <option value="Registered" {{ request('status') === 'Registered' ? 'selected' : '' }}>Registered</option>
                    <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Closed" {{ request('status') === 'Closed' ? 'selected' : '' }}>Closed</option>
                </select>

                <button class="h-10 px-4 rounded-lg bg-[#00236f] text-white text-sm font-bold">
                    Filter
                </button>

                @if(request()->filled('search') || request('status'))
                    <a href="{{ route('cases.index') }}"
                       class="h-10 px-4 rounded-lg border border-[#c5c5d3] text-sm font-bold flex items-center justify-center hover:bg-[#f4f3fa]">
                        Reset
                    </a>
                @endif
            </form>

            <p class="text-sm text-[#444651]">
                Showing {{ $cases->firstItem() ?? 0 }}-{{ $cases->lastItem() ?? 0 }} of {{ $cases->total() }} cases
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-[#f4f3fa] text-[#444651] uppercase text-[11px] tracking-widest">
                    <tr>
                        <th class="px-4 py-3 whitespace-nowrap">Case Number</th>
                        <th class="px-4 py-3 whitespace-nowrap">Victim Name</th>
                        <th class="px-4 py-3 whitespace-nowrap">Date Received</th>
                        <th class="px-4 py-3 whitespace-nowrap">Assigned Doctor</th>
                        <th class="px-4 py-3 whitespace-nowrap">Status</th>
                        <th class="px-4 py-3 text-right whitespace-nowrap">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#d2d4df]">
                    @forelse($cases as $case)
                        <tr class="hover:bg-[#f8f9ff] transition">
                            <td class="px-4 py-4 font-bold text-[#00236f] whitespace-nowrap">
                                {{ $case->case_number }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                {{ $case->victim_name ?? 'Unknown Subject' }}
                            </td>

                            <td class="px-4 py-4 text-[#444651] whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($case->date_received)->format('d M Y') }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                {{ $case->assignedDoctor->name ?? '-' }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ statusBadgeClass($case->status) }}">
                                    {{ $case->status }}
                                </span>
                            </td>

                            <td class="px-4 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex gap-1">
                                    <a href="{{ route('cases.show', $case->id) }}"
                                       class="p-1.5 rounded text-[#444651] hover:bg-[#dce1ff] hover:text-[#00236f]">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </a>

                                    <a href="{{ route('cases.edit', $case->id) }}"
   class="p-1.5 rounded text-[#444651] hover:bg-[#dce1ff] hover:text-[#00236f]">
    <span class="material-symbols-outlined text-lg">edit</span>
</a>

                                    <button type="button"
                                            class="p-1.5 rounded text-[#444651] hover:bg-red-100 hover:text-red-700">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-[#757682]">
                                No cases found.
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
