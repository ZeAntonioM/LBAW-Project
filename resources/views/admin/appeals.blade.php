@php use App\Http\Controllers\User; @endphp
@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/partials/cards.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partials/pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('css/appeals.css') }}">
@endpush

@section('content')
    <section class="adminPage">

        @foreach($appeals as $appeal)
            <form method="POST" class="hidden" id="accept-{{$appeal->id}}"
                  action="{{route("accept_appeal",["appeal"=>$appeal])}}">
                @csrf
                @method("PUT")
            </form>
            <form method="POST" class="hidden" id="delete" action="{{route('deny_appeal', ['appeal'=>$appeal])}}">
                @csrf
                @method("DELETE")
            </form>
        @endforeach


        <section class="appeals-list">
            <header>
                <section class="search">
                    <form method="GET" id="search" action="{{route('admin_appeals')}}">
                        <input type="search" name="query" placeholder="&#128269 Search" aria-label="Search"
                               id="search-bar" value="{{$query}}"/>
                        <button class="" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>

                    </form>
                </section>
                <section>
                    <span> <i class="fa-solid fa-users"></i>  {{$amount}} Appeals </span>
                    <a class="button" href="{{route('admin_users')}}"><i class="fa-solid fa-sync"></i>See Users</a>
                </section>
            </header>
            <section class="appeals">

                @if($amount==0)
                    <section class="no-appeals">
                        <h1>No appeals found</h1>
                    </section>
                @else

                @foreach($appeals as $appeal)
                    <section class="appeal-item">
                        <section class="appeal-header">
                            <section class="userSection">

                                <a href="{{route('profile',['user'=>$appeal->user])}}">
                                    @include('partials.userCard',['user'=>$appeal->user, 'size'=>''])
                                </a>


                            </section>
                            @can('update', [User::class,$appeal->user])
                                <section class="actions">
                                    
                                    <button class="accept-appeal" id="{{$appeal->id}}" form="accept-{{($appeal->id)}}">
                                        Accept Appeal
                                    </button>
                                    <button class="delete" id="{{$appeal->id}}" form="delete"
                                            formaction="{{route('deny_appeal', ['appeal'=>$appeal])}}">
                                        Deny Appeal
                                    </button>

                                </section>
                            @endcan
                        </section>

                        <section class="appealBody">
                            <p>{{$appeal->content}}</p>
                        </section>
                        
                    </section>
                @endforeach
                @endif
            </section>
        </section>
        @include("partials.paginator",['paginator'=>$appeals])

    </section>
@endsection
