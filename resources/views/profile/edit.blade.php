@extends('layouts.portal')

@section('title', 'Profil Saya')
@section('activeMenu', '')

@section('content')

    <div class="max-w-2xl space-y-6">

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            @include('profile.partials.update-password-form')
        </div>

        <div class="bg-white rounded-xl border border-red-100 shadow-sm p-6">
            @include('profile.partials.delete-user-form')
        </div>

    </div>

@endsection