@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-[#757682]">
                <span>Main</span>
                <span>/</span>
                <span class="text-[#00236f]">Reports</span>
            </div>

            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#1a1b21] mt-2">
                Finalized Autopsy Reports
            </h2>

            <p class="text-sm text-[#444651] mt-1">
                Access and export validated forensic records from the regional database.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <button class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg border border-[#757682] text-[#444651] text-sm font-bold hover:bg-white">
                <span class="material-symbols-outlined text-base">filter_list</span>
                Filter
            </button>

            <button class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg border border-[#757682] text-[#444651] text-sm font-bold hover:bg-white">
                <span class="material-symbols-outlined text-base">file_download</span>
                Export List
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white border border-[#c5c5d3] rounded-xl p-5">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#757682]">Total Reports</p>
            <h3 class="text-3xl font-extrabold text-[#00236f] mt-2">1,248</h3>
            <p class="text-xs text-green-600 font-semibold mt-2">+12% this month</p>
        </div>

        <div class="bg-white border border-[#c5c5d3] rounded-xl p-5">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#757682]">Pending Review</p>
            <h3 class="text-3xl font-extrabold text-amber-800 mt-2">14</h3>
            <p class="text-xs text-[#757682] font-semibold mt-2">Action required</p>
        </div>

        <div class="bg-white border border-[#c5c5d3] rounded-xl p-5">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#757682]">Avg. Completion</p>
            <h3 class="text-3xl font-extrabold text-[#1a1b21] mt-2">
                4.2 <span class="text-sm font-medium text-[#757682]">days</span>
            </h3>
            <p class="text-xs text-green-600 font-semibold mt-2">Within target SLA</p>
        </div>

        <div class="bg-white border border-[#c5c5d3] rounded-xl p-5 relative overflow-hidden">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#757682]">System Integrity</p>
            <h3 class="text-3xl font-extrabold text-[#00236f] mt-2">99.9%</h3>
            <p class="text-xs text-[#00236f] font-semibold mt-2">Secure verified</p>

            <span class="material-symbols-outlined absolute right-4 bottom-2 text-[72px] text-[#00236f] opacity-10">
                verified_user
            </span>
        </div>
    </div>

    <div class="bg-white border border-[#c5c5d3] rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-[#e9e7ef] text-[#444651] uppercase text-xs tracking-widest">
                    <tr>
                        <th class="px-4 py-4 whitespace-nowrap">Case Number</th>
                        <th class="px-4 py-4 whitespace-nowrap">Victim Name</th>
                        <th class="px-4 py-4 whitespace-nowrap">Medical Examiner</th>
                        <th class="px-4 py-4 whitespace-nowrap">Status</th>
                        <th class="px-4 py-4 whitespace-nowrap">Report Date</th>
                        <th class="px-4 py-4 text-right whitespace-nowrap">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#d2d4df]">
                    @php
                        $reports = [
                            ['FS-2026-001', 'Budi Santoso', 'BS', 'dr. Aditya Pratama', 'Finalized', '11 Jun 2026', 'green'],
                            ['FS-2026-002', 'Siti Rahmawati', 'SR', 'dr. Aditya Pratama', 'Finalized', '10 Jun 2026', 'green'],
                            ['FS-2026-003', 'Unknown Subject', 'US', 'dr. Aditya Pratama', 'Review Required', '09 Jun 2026', 'yellow'],
                            ['FS-2026-004', 'Rizky Maulana', 'RM', 'dr. Aditya Pratama', 'Finalized', '08 Jun 2026', 'green'],
                            ['FS-2026-005', 'Dewi Lestari', 'DL', 'dr. Aditya Pratama', 'Finalized', '07 Jun 2026', 'green'],
                        ];
                    @endphp

                    @foreach($reports as $report)
                        @php
                            $statusClass = $report[6] === 'green'
                                ? 'bg-green-100 text-green-800'
                                : 'bg-yellow-100 text-yellow-800';
                        @endphp

                        <tr class="hover:bg-[#f8f9ff] transition">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="font-bold text-[#00236f] bg-[#dce1ff] px-2 py-1 rounded">
                                    {{ $report[0] }}
                                </span>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-[#d0e1fb] rounded-full flex items-center justify-center text-[#00236f] font-bold text-[10px]">
                                        {{ $report[2] }}
                                    </div>

                                    <div>
                                        <p class="font-bold text-[#1a1b21]">{{ $report[1] }}</p>
                                        <p class="text-[11px] text-[#757682]">Autopsy case record</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                {{ $report[3] }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ $statusClass }}">
                                    {{ $report[4] }}
                                </span>
                            </td>

                            <td class="px-4 py-4 text-[#444651] whitespace-nowrap">
                                {{ $report[5] }}
                            </td>

                            <td class="px-4 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex gap-1">
                                    <button class="p-2 text-[#444651] hover:bg-[#dce1ff] hover:text-[#00236f] rounded-lg" title="View Report">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </button>

                                    <button class="p-2 text-[#444651] hover:bg-[#dce1ff] hover:text-[#00236f] rounded-lg" title="Download PDF">
                                        <span class="material-symbols-outlined">picture_as_pdf</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-4 py-4 border-t border-[#d2d4df] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <p class="text-xs text-[#757682]">
                Showing 1 to 5 of 1,248 entries
            </p>

            <div class="flex items-center gap-1">
                <button disabled class="p-2 border border-[#c5c5d3] rounded-lg opacity-40">
                    <span class="material-symbols-outlined text-base">chevron_left</span>
                </button>

                <button class="px-3 py-2 rounded-lg bg-[#00236f] text-white text-sm font-bold">1</button>
                <button class="px-3 py-2 rounded-lg hover:bg-[#f4f3fa] text-sm font-bold">2</button>
                <button class="px-3 py-2 rounded-lg hover:bg-[#f4f3fa] text-sm font-bold">3</button>

                <span class="px-2 text-[#757682]">...</span>

                <button class="px-3 py-2 rounded-lg hover:bg-[#f4f3fa] text-sm font-bold">250</button>

                <button class="p-2 border border-[#c5c5d3] rounded-lg hover:bg-[#f4f3fa]">
                    <span class="material-symbols-outlined text-base">chevron_right</span>
                </button>
            </div>
        </div>
    </div>

    <div class="bg-[#dce1ff] border border-[#b6c4ff] rounded-xl p-5 flex gap-4">
        <span class="material-symbols-outlined text-[#00236f] mt-1">info</span>

        <div>
            <h4 class="text-sm font-bold text-[#00236f]">Compliance Reminder</h4>
            <p class="text-sm text-[#264191] mt-1">
                All downloaded PDF reports are digitally recorded to maintain chain-of-custody protocols.
                Unauthorized distribution of these records violates system policy.
            </p>
        </div>
    </div>

</div>
@endsection
