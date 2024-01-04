@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript" src={{ url('js/app.js') }} defer></script>
@endpush

@section('content')
    <div class = "container-fluid d-flex justify-content-center">
        <div class = "row">
            <div class = "col-12 d-flex justify-content-center p-4 card self-center">
            <div class ="col-12" style = "margin-top: 5%;">
                <h1>You have been blocked</h1>
            </div>

            <div class = "col-12" style = "margin-top: 3%;">
                <h2>If you believe you should be unblocked, appeal for it</h2>
            </div>
            <div class ="col-12" style = "margin-top: 5%;">
                <form method="POST" class ="row" action="{{ route('create_appeal')}}">
                    @csrf()
                    <div class = "form-group col-12 w-100">
                        <label for="name">Why should you be unblocked?</label>
                        <textarea type="text" id="appeal" class="p-3" name="appeal" placeholder="I should be unblocked because..."></textarea>
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" style="margin-top: 3%;" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                    
                        <div class = "col-12"  style = "margin-top: 3%;" >
                            <button type="submit" class = "w-100"><p>Appeal for Unblock</p></button>
                        </div>
                    </div>    
                </form>
            </div>
            
            <div class="col-12 text-center goback" >
                <a href="{{ route('logout') }}">Logout</a>
            </div>
            @if(session('message'))
                <div class="alert alert-success alert-dismissible fade show" style="margin-top: 3%;" role="alert">
                    {{ session('message') }}
                </div>
            @endif
            </div>
    </div>
    </div>

@endsection