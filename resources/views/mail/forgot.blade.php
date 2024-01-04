@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
@endpush

@push('scripts')
    <script type="module" src={{ url('js/app.js') }} defer></script>
@endpush

@section('content')
    <div class = "container" style="min-width: 33vh; min-height:25vh;">
        <div class = "row p-4">
            <div class ="col-12 text-center" style = "margin-top: 5%;">
                <h1>You have requested to reset your password</h1>
            </div>
            <div class = "col-12 text-center" style = "margin-top: 3%;">
                <h2>We simply send you your old password. A unique link to reset your password has been generated for you. To reset your password, click the following link and follow the instructions.</h2>
            </div>
            <div class ="col-12 d-flex justify-content-center" style = "margin-top: 5%;">
                <a href="{{ route('pass.reset', ['token' => $mailData['token']]) }}" style="padding: 10px; background-color: #3490dc; color: #ffffff; text-decoration: none; border-radius: 5px; display: inline-block;" target="_blank">Reset Password</a>
            </div>

@endsection