@extends('layouts.app')

@push('styles')
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <link href="{{ asset('js/admin/create_user.js') }}" defer>
@endpush

@section('content')
<section class="authentication register">

    <section class="formContainer">
    <header>
        <h2>Create User</h2>
    </header>
    <form method="POST" action="{{ route('admin_user_create') }}">
        @csrf

            <input id="name" type="text" placeholder="Insert user's name" name="name" value="{{ old('name') }}" required autofocus>
            @if($errors->has('name'))
                <span class="error">
                    {{ $errors->first('name') }}
                </span>
            @endif


            <input id="email" type="email" placeholder="Insert user's email" name="email" value="{{ old('email') }}" required>
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

            <input id="password-confirm" type="password" placeholder="Confirm user's password" name="password_confirmation" required>
            @if($errors->has('password_confirmation'))
                <span class="error">
                    {{ $errors->first('password_confirmation') }}
                </span>
            @endif

            <select class="is_admin" name="is_admin">
                <option value="1">Admin</option>
                <option value="0" selected>User</option>
            </select>
            @if($errors->has('is_admin'))
                <span class="error">
                    {{ $errors->first('is_admin') }}
                </span>
            @endif

            <button type="submit">
                Create
            </button>


    </form>
    </section>

</section>

@endsection