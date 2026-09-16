@extends(auth()->check() && auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.portal')

@section('title', 'Profil & Pengaturan Akun')
@section('activeMenu', 'Pengaturan')

@section('content')
<div class="space-y-6 max-w-4xl">
    
    {{-- Header --}}
    <div>
        <h1 class="page-title mb-1 text-2xl sm:text-3xl font-extrabold text-[#002B6B] dark:text-white">
            Profil & Pengaturan Akun
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 m-0">
            Perbarui informasi akun, kata sandi, dan preferensi keamanan Anda.
        </p>
    </div>

    <div class="space-y-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700 shadow-sm p-5 sm:p-7">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700 shadow-sm p-5 sm:p-7">
            @include('profile.partials.update-password-form')
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-red-200/80 dark:border-red-900/50 shadow-sm p-5 sm:p-7">
            @include('profile.partials.delete-user-form')
        </div>
    </div>

</div>
@endsection