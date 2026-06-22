@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#00236f]">
                User Management
            </h2>
            <p class="text-sm text-[#444651] mt-1">
                Manage forensic staff access and system permissions.
            </p>
        </div>

        <a href="#"
           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-[#00236f] text-white text-sm font-bold shadow-md hover:bg-[#001a52]">
            <span class="material-symbols-outlined">person_add</span>
            Add User
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white border border-[#c5c5d3] rounded-xl p-5">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#757682]">Total Users</p>
            <h3 class="text-3xl font-extrabold text-[#00236f] mt-2">124</h3>
            <p class="text-xs text-green-600 font-semibold mt-2">+4 this month</p>
        </div>

        <div class="bg-white border border-[#c5c5d3] rounded-xl p-5">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#757682]">Doctors</p>
            <h3 class="text-3xl font-extrabold text-[#00236f] mt-2">42</h3>
            <p class="text-xs text-[#757682] font-semibold mt-2">Forensic pathologists</p>
        </div>

        <div class="bg-white border border-[#c5c5d3] rounded-xl p-5">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#757682]">Active Now</p>
            <h3 class="text-3xl font-extrabold text-[#00236f] mt-2">18</h3>
            <p class="text-xs text-green-600 font-semibold mt-2">Live monitoring</p>
        </div>

        <div class="bg-white border border-[#c5c5d3] rounded-xl p-5">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#757682]">Pending Access</p>
            <h3 class="text-3xl font-extrabold text-amber-800 mt-2">7</h3>
            <p class="text-xs text-amber-800 font-semibold mt-2">Requires approval</p>
        </div>
    </div>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 bg-[#f4f3fa] rounded-xl p-3">
        <div class="flex flex-col sm:flex-row gap-3">
            <select class="h-10 rounded-lg border border-[#c5c5d3] bg-white px-4 text-sm outline-none focus:ring-2 focus:ring-[#00236f]">
                <option>All Roles</option>
                <option>Admin</option>
                <option>Doctor</option>
                <option>Registration</option>
            </select>

            <select class="h-10 rounded-lg border border-[#c5c5d3] bg-white px-4 text-sm outline-none focus:ring-2 focus:ring-[#00236f]">
                <option>All Status</option>
                <option>Active</option>
                <option>Inactive</option>
            </select>
        </div>

        <div class="flex items-center gap-2">
            <button class="p-2 border border-[#c5c5d3] rounded-lg hover:bg-white">
                <span class="material-symbols-outlined">filter_list</span>
            </button>

            <button class="p-2 border border-[#c5c5d3] rounded-lg hover:bg-white">
                <span class="material-symbols-outlined">download</span>
            </button>
        </div>
    </div>

    <div class="bg-white border border-[#c5c5d3] rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-[#f4f3fa] text-[#757682] uppercase text-xs tracking-widest">
                    <tr>
                        <th class="px-4 py-4 whitespace-nowrap">User</th>
                        <th class="px-4 py-4 whitespace-nowrap">Email</th>
                        <th class="px-4 py-4 whitespace-nowrap">Role</th>
                        <th class="px-4 py-4 whitespace-nowrap">Status</th>
                        <th class="px-4 py-4 text-right whitespace-nowrap">Actions</th>
                    </tr>
                </thead>

                <tbody id="userTable" class="divide-y divide-[#d2d4df]">
                    @php
                        $users = [
                            ['Rendra Wijaya', 'System Administrator', 'admin@forensys.test', 'RW', 'Admin', 'Active', 'admin'],
                            ['Maya Salsabila', 'Registration Officer', 'registrasi@forensys.test', 'MS', 'Registration', 'Active', 'registrasi'],
                            ['dr. Aditya Pratama', 'Forensic Doctor', 'dokter@forensys.test', 'AP', 'Doctor', 'Active', 'dokter'],
                            ['Dina Maharani', 'Intake Clerk', 'dina.maharani@forensys.test', 'DM', 'Registration', 'Inactive', 'registrasi'],
                        ];
                    @endphp

                    @foreach($users as $user)
                        @php
                            $roleClass = match($user[6]) {
                                'admin' => 'bg-[#00236f] text-white',
                                'dokter' => 'bg-[#d0e1fb] text-[#00236f]',
                                'registrasi' => 'bg-[#e9e7ef] text-[#444651]',
                                default => 'bg-gray-100 text-gray-800'
                            };

                            $avatarClass = match($user[6]) {
                                'admin' => 'bg-[#00236f] text-white',
                                'dokter' => 'bg-[#d0e1fb] text-[#00236f]',
                                'registrasi' => 'bg-orange-100 text-orange-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        @endphp

                        <tr class="hover:bg-[#f8f9ff] transition">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full {{ $avatarClass }} flex items-center justify-center font-bold text-sm">
                                        {{ $user[3] }}
                                    </div>

                                    <div>
                                        <p class="font-bold text-[#1a1b21]">{{ $user[0] }}</p>
                                        <p class="text-xs text-[#757682]">{{ $user[1] }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-4 text-[#444651] whitespace-nowrap">
                                {{ $user[2] }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $roleClass }}">
                                    {{ $user[4] }}
                                </span>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($user[5] === 'Active')
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                        <span class="font-semibold text-[#1a1b21]">Active</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-[#757682]"></span>
                                        <span class="font-semibold text-[#757682]">Inactive</span>
                                    </div>
                                @endif
                            </td>

                            <td class="px-4 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex gap-1">
                                    <button class="p-2 text-[#444651] hover:bg-[#dce1ff] hover:text-[#00236f] rounded-lg" title="Edit">
                                        <span class="material-symbols-outlined">edit</span>
                                    </button>

                                    <button class="p-2 text-[#444651] hover:bg-red-100 hover:text-red-700 rounded-lg" title="Delete">
                                        <span class="material-symbols-outlined">delete</span>
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
                Showing 1 to 4 of 124 users
            </p>

            <div class="flex items-center gap-1">
                <button disabled class="p-2 border border-[#c5c5d3] rounded-lg opacity-40">
                    <span class="material-symbols-outlined text-base">chevron_left</span>
                </button>

                <button class="w-9 h-9 rounded-lg bg-[#00236f] text-white text-sm font-bold">1</button>
                <button class="w-9 h-9 rounded-lg hover:bg-[#f4f3fa] text-sm font-bold">2</button>
                <button class="w-9 h-9 rounded-lg hover:bg-[#f4f3fa] text-sm font-bold">3</button>

                <span class="px-2 text-[#757682]">...</span>

                <button class="w-9 h-9 rounded-lg hover:bg-[#f4f3fa] text-sm font-bold">31</button>

                <button class="p-2 border border-[#c5c5d3] rounded-lg hover:bg-[#f4f3fa]">
                    <span class="material-symbols-outlined text-base">chevron_right</span>
                </button>
            </div>
        </div>
    </div>

    <footer class="pt-5 border-t border-[#d2d4df] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs text-[#757682]">
        <p>System Version: Forensic Core v1.0-stable</p>

        <div class="flex items-center gap-4">
            <a href="#" class="hover:text-[#00236f]">Privacy Policy</a>
            <a href="#" class="hover:text-[#00236f]">Terms of Service</a>
        </div>
    </footer>

</div>
@endsection
