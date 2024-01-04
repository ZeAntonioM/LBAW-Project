@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript" src={{ url('js/app.js') }} defer></script>
@endpush

@section('content')
    <div class = "container-fluid d-flex justify-content-center ">
        <div class = "row">
            <div class = "col-12 d-flex justify-content-center p-4 card">
            <div class ="col-12" style = "margin-top: 5%;">
                <h1>Forgot your password</h1>
            </div>
            <div class = "col-12" style = "margin-top: 3%;">
                <h2>Please enter the email address you'd like your password reset information sent to</h2>
            </div>
            <div class ="col-12" style = "margin-top: 5%;">
            <form method="POST" class ="row" action="{{ route('pass.email')}}">
                @csrf()
                <div class = "form-group col-12 w-100">
                    <label for="name">Enter email address</label>
                    <input type="text" id = "email" class="p-3 inputemail"name="email" placeholder="myemail@company.com" value="{{ old('email') }}">
                    @if ($errors->has('email'))
                    <span class="error">
                        {{ $errors->first('email') }}
                    </span>
                @endif
                </div>
                <div class = "col-12"  style = "margin-top: 3%;" >
                    <button class = "w-100"><p>Request reset link</p></button>
                </div>
                </form>
            </div>
            
            <div class="col-12 text-center goback" >
                <a href="{{ route('login') }}">Go back to login</a>
            </div>
            @if(session('message'))
                <div class="alert alert-success alert-dismissible fade show" style="margin-top: 3%;" role="alert">
                    {{ session('message') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" style="margin-top: 3%;" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            </div>
    </div>
    </div>

@endsection