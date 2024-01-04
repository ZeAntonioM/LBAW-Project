@extends('layouts.app')
@push('styles')

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush    <link href="{{ asset('css/admin/create_users.css') }}" rel="stylesheet">


@section('content')
    <section class="authentication register">

        <section class="formContainer">
        <header>
            <h2><span class="shine">Empower</span> Your Day, <span class="shine">Unleash</span> the work </h2>
            <h2>Sign-up:</h2>
        </header>
        <form method="POST" action="{{ route('create_account') }}">
            @csrf

                <input id="name" type="text" placeholder="Insert your name" name="name" value="{{ old('name') }}" required autofocus>
                @if($errors->has('name'))
                    <span class="error">
                        {{ $errors->first('name') }}
                    </span>
                @endif

                @if (isset($userEmail))
                    <input id="email" type="email" placeholder="Insert your email" name="email" value="{{ $userEmail }}" required readonly>
                @else
                    <input id="email" type="email" placeholder="Insert your email" name="email" value="{{ old('email') }}" required>
                @endif
                @if($errors->has('email'))
                    <span class="error">
                        {{ $errors->first('email') }}
                    </span>
                @endif

                <input id="password" type="password" name="password" placeholder="Password" required>
                @if($errors->has('password'))
                    <span class="error">
                        {{ $errors->first('password') }}
                    </span>
                @endif

                <input id="password-confirm" type="password" placeholder="Confirm your password" name="password_confirmation" required>
                @if($errors->has('password_confirmation'))
                    <span class="error">
                        {{ $errors->first('password_confirmation') }}
                    </span>
                @endif

                @isset($project)
                    <input type="hidden" name="project" value="{{ $project }}">
                @else
                    <input type="hidden" name="project" value="{{ old('project') }}">
                @endisset

                <button type="submit">
                    Register
                </button>


        </form>
        </section>
    <section class="container">
        <h2>Already have an account?</h2>
        <p>Login into your account and start using the app now!</p>
        <a class="button" href="{{ route('login') }}">Login</a>
    </section>
    </section>
@endsection
