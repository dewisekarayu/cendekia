@extends('layouts.portal')

@section('title', 'Profil Pengguna')
@section('activeMenu', 'Settings')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="bg-blue-900 rounded-xl px-8 py-6 text-white mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Profil Pengguna</h1>
                <p class="text-blue-200 text-sm mt-1">Perbarui informasi akun dan preferensi Anda.</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="flex items-center gap-3 bg-white/10 px-3 py-1 rounded-full">
                    <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center text-sm font-semibold">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                    <div class="text-sm">{{ auth()->user()->name }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Profile & Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow">
                <div class="h-28 bg-blue-800 rounded-t-lg"></div>
                <div class="px-6 pb-6 -mt-5">
                    <div class="flex items-end gap-6">
                        <div class="w-28 h-28 rounded-lg overflow-hidden border-4 border-white shadow-lg bg-gray-100">
                            <img src="{{ asset('images/profil.jpg') }}" alt="avatar" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 mt-15">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold text-black-">{{ auth()->user()->name }}</h2>
                                    <div class="text-sm text-black-700">NIM: {{ auth()->user()->nim ?? '210401xxxx' }} • <span class="text-blue-600">Aktif</span></div>
                                </div>
                                <div>
                                    <a href="#" class="inline-flex items-center px-3 py-1.5 bg-blue-700 text-white rounded-md shadow">Edit Profil</a>
                                </div>
                            </div>
                            <p class="mt-3 text-sm text-gray-600">Halo {{ auth()->user()->name }}, perbarui informasi profil kamu di sini.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-lg shadow p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-700">Info Kontak</h3>
                    </div>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5h12M9 3v2m-6 4h18M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z"/></svg>
                            <div>
                                <div class="text-gray-800">Email Institusi</div>
                                <div class="text-xs">{{ auth()->user()->email ?? 'ahmad.fauzi@student.com' }}</div>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2 8.5C2 6.57 3.57 5 5.5 5h13c1.93 0 3.5 1.57 3.5 3.5v7c0 1.93-1.57 3.5-3.5 3.5H5.5C3.57 19 2 17.43 2 15.5v-7z"/></svg>
                            <div>
                                <div class="text-gray-800">Nomor WhatsApp</div>
                                <div class="text-xs">{{ auth()->user()->phone ?? '+62 812-3456-7890' }}</div>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 2-8 4.5V21h16v-2.5c0-2.5-3.582-4.5-8-4.5z"/></svg>
                            <div>
                                <div class="text-gray-800">Email Personal</div>
                                <div class="text-xs">{{ auth()->user()->personal_email ?? 'fauzi.ahmad@gmail.com' }}</div>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="bg-white rounded-lg shadow p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-700">Status Akademik</h3>
                    </div>
                    <div class="text-sm text-gray-600 mb-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs text-gray-500">Fakultas</div>
                                <div class="text-sm font-medium text-gray-800">Teknik</div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-gray-500">Semester</div>
                                <div class="text-sm font-medium text-gray-800">Semester 6</div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 mb-2">Progress Kelulusan (112 / 144 SKS)</div>
                        <div class="w-full bg-gray-100 rounded-full h-3">
                            <div class="h-3 rounded-full bg-blue-700" style="width:78%"></div>
                        </div>
                        <div class="text-xs text-right text-blue-700 mt-2">78%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Notifications -->
        <div class="space-y-4">
            <div class="bg-white rounded-lg shadow p-5">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-semibold text-gray-800">Notification</h3>
                    <div class="text-sm text-gray-500">Today</div>
                </div>

                <div class="space-y-3">
                    <div class="border-l-4 border-blue-200 pl-3 py-3 bg-blue-50 rounded-md">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-sm font-medium text-gray-800">New Grade Released: Advanced Web Development</div>
                                <div class="text-xs text-gray-600">Your project assignment \"UI/UX React Integration\" has been graded. You received an A-.</div>
                            </div>
                            <div class="text-xs text-gray-400">10:45 AM</div>
                        </div>
                        <div class="mt-3 flex gap-2">
                            <button class="text-sm px-3 py-1 bg-blue-700 text-white rounded">View Grades</button>
                            <button class="text-sm px-3 py-1 bg-white border rounded">Dismiss</button>
                        </div>
                    </div>

                    <div class="border-l-4 border-green-200 pl-3 py-3 bg-green-50 rounded-md">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-sm font-medium text-gray-800">Deadline Approaching: Database Systems Quiz</div>
                                <div class="text-xs text-gray-600">Remember to submit your weekly quiz before 11:59 PM tonight.</div>
                            </div>
                            <div class="text-xs text-gray-400">08:00 AM</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 border-t pt-4 text-sm text-gray-500">Yesterday</div>
                <div class="mt-3 space-y-2 text-sm text-gray-700">
                    <div class="p-3 bg-gray-50 rounded">Course Announcement: Algorithm & Data Structure — New lecture video for Chapter 7.</div>
                    <div class="p-3 bg-gray-50 rounded">New Discussion Reply — Siti Aminah replied to your thread.</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-5 text-center text-sm text-gray-600">Show all notifications</div>
        </div>
    </div>
</div>

@endsection
