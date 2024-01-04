@extends('layouts.app')

@push('styles')

    <link href="{{ asset('css/static/projects.css') }}" rel="stylesheet">

    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
@endpush

@section('content')
    <section class="home-content">
        <section class="opening">
            <h1 class="shine">Welcome to ProjPlanner</h1>
        </section>

        <section class="home-center">
            <p>Your Ultimate Project Planning Platform</p>
            <p>At ProjPlanner, we understand that every project is unique, and planning is the key to success. Whether
                you're a seasoned project manager or embarking on your first venture, we've got you covered.</p>
        </section>
        <section class="get-started">
            <h2 class="shine">Get Started</h2>
            <p>Get started today by signing up for free and take your first step towards turning your ideas into reality.
            </p>
            <a href="{{ route('login') }}"><button>Sign In</button></a>
            <a href="{{ route('register') }}"><button>Register</button></a>
        </section>
    </section>
@endsection
