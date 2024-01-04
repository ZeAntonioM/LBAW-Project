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
                <h1>You have been invited to a project!</h1>
            </div>
            <div class = "col-12 text-center" style = "margin-top: 3%;">
                <h2>Click the button below to create a new account and join the project.</h2>
            </div>
            <div class ="col-12 d-flex justify-content-center" style = "margin-top: 5%;">
                <a href="{{ route('accept.invite', ['token' => $mailData['token']]) }}" style="padding: 10px; background-color: #3490dc; color: #ffffff; text-decoration: none; border-radius: 5px; display: inline-block;" target="_blank">Join Project</a>
            </div>

@endsection