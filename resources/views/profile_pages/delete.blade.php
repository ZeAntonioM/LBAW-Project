@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/delete_user.css') }}">
@endpush

@section('content')
<div class = "container-fluid d-flex justify-content-center ">
        <div class = "row">
            <div class = "col-12 d-flex justify-content-center p-4 card">
            <div class ="col-12" style = "margin-top: 5%;">
                <h1>Delete account</h1>
            </div>
            <div class = "col-12" style = "margin-top: 3%;">
                <h2>Please enter your password to delete account</h2>
            </div>
            <div class ="col-12" style = "margin-top: 5%;">
            <form method="POST" class ="row" action="{{ route('delete_user',['user'=> Auth::user()]) }}">
                @csrf()
                @method('delete')
                <div class = "form-group col-12 w-100">
                    <label for="password">Enter password</label>
                    <input type="password" id = "password" class="p-3 inputemail"name="password" placeholder=" Your password">
                    @if (session('password'))
                    <span class="error">
                        {{session('password')}}
                    </span>
                    @endif
                </div>
                <div class = "col-12"  style = "margin-top: 3%;" >
                    <button class = "w-100"><p>Delete account</p></button>
                </div>
                </form>
            </div>
            
            <div class="col-12 text-center goback" >
                <a href="{{ route('profile',['user'=>Auth::user()]) }}">Go back to profile</a>
            </div>
            </div>
    </div>
    </div>

@endsection