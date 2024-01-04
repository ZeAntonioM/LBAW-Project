@extends('layouts.app')

@section('content')

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    @endpush

    <section class="authentication login">

        <section class="formContainer">
            <header>
                <h2><span class="shine">Empower</span> Your Day, <span class="shine">Unleash</span> the work </h2>
                <h2>Login to Your Account</h2>
            </header>


        <form method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}

            @if (isset($userEmail))
                <input id="email" type="email" placeholder="Insert your email" name="email" value="{{ $userEmail }}" required readonly>
            @else
                <input id="email" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
            @endif
            @if ($errors->has('email'))
                <span class="error">
          {{ $errors->first('email') }}
        </span>
            @endif

            <input id="password" type="password" name="password" placeholder="Password" required>
            @if ($errors->has('password'))
                <span class="error">
            {{ $errors->first('password') }}
        </span>
            @endif

            <label>
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
            </label>

            <button type="submit">
                Login
            </button>

            @if (session('success'))
                <p class="success">
                    {{ session('success') }}
                </p>
            @endif

            @isset($project)
                <input type="hidden" name="project" value="{{ $project }}">
            @else
                <input type="hidden" name="project" value="{{ old('project') }}">
            @endisset
        </form>
        <div class ="d-flex justify-content-center forgot">
                    <a href="{{ route('pass.request')}} "> Forgot your password? </a>
        </div>
        @if(session('message'))
                <div class=" d-flex justify-content-center alert alert-success alert-dismissible fade show" style="margin-top: 3%;" role="alert">
                    {{ session('message') }}
                </div>
            @endif
            @if(session('delete_user_success'))
                <div class=" d-flex justify-content-center alert alert-success alert-dismissible fade show" style="margin-top: 3%;" role="alert">
                    {{ session('delete_user_success') }}
                </div>
            @endif
        </section>
        <section class="container">
            <h2>New Here?</h2>
            <p>Sign up and discover the best project manager tool.</p>
            <a class="button" href="{{ route('register') }}">Register</a>
        </section>
    </section>

@endsection