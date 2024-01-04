@php use App\Http\Controllers\User; @endphp
@extends('layouts.admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/users.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partials/cards.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partials/pagination.css') }}">

@endpush

@push('scripts')
    <script type="module" src="{{ asset('js/pages/user.js') }}" defer></script>
@endpush

@section('content')
    <section class="adminPage">

        <form method="POST" class="hidden" id="delete">
            @csrf
            @method("DELETE")
        </form>

        @foreach($users as $user)
            <form method="POST" class="hidden" id="block-{{$user->id}}"
                  action="{{route("block_user",["user"=>$user])}}">
                @csrf
                @method("PUT")
            </form>
            <form method="POST" class="hidden" id="unblock-{{$user->id}}"
                  action="{{route("unblock_user",["user"=>$user])}}">
                @csrf
                @method("PUT")
            </form>
        @endforeach


        <section class="users-list">
            <header>
                <section class="search">
                    <form method="GET" id="search" action="{{route('admin_users')}}">
                        <input type="search" name="query" placeholder="&#128269 Search" aria-label="Search"
                               id="search-bar" value="{{$query}}"/>
                        <button class="" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                        <div class="filters">
                            <select  name="status">
                                @if($status==='')<option selected value="">Filters</option>
                                @else <option value="">Filters</option>
                                @endif
                                @if($status==='admin')<option selected value="admin">admin</option>
                                @else <option value="admin">admin</option>
                                @endif
                                @if($status==='user')<option selected value="user">user</option>
                                @else <option value="user">user</option>
                                @endif
                            </select>
                        </div>
                    </form>
                </section>
                <section>
                    <span> <i class="fa-solid fa-users"></i>  {{$registrations}} Users </span>
                    <a class="button" href="{{route('admin_appeals')}}"><i class="fa-solid fa-sync"></i> Appeals for Unblock</a>
                    <a class="button" href="{{route('admin_user_create')}}"><i class="fa-solid fa-user-plus"></i> Add
                        user</a>
                </section>
            </header>
            <section class="users">
                @foreach($users as $user)
                    <section class="user-item">
                        <section class="userSection">

                            <a href="{{route('profile',['user'=>$user])}}">
                                @include('partials.userCard',['user'=>$user, 'size'=>'.median'])
                            </a>


                        </section>
                        @if($user->is_admin)
                            <span class="status admin"> <i class="fa-solid fa-user-gear"></i> Admin</span>
                        @else
                            <span class="status user"> <i class="fa-solid fa-user"></i> User</span>
                        @endif
                        @can('update', [User::class,$user])
                            <section class="actions">
                                <a href="{{route("edit_profile",['user'=>$user])}}" class="edit" id="{{$user->id}}">
                                    <i class="fa-solid fa-user-pen"></i>
                                </a>
                                @if(!$user->is_admin)
                                    @if (!$user->is_blocked)
                                        <button class="block" id="{{$user->id}}" form="block-{{($user->id)}}">
                                            <i class="fa-solid fa-lock"></i>
                                        </button>
                                    @else
                                        <button class="unblock" id="{{$user->id}}" form="unblock-{{($user->id)}}">
                                            <i class="fa-solid fa-lock-open"></i>
                                        </button>
                                    @endif
                                @endif
                                <button class="delete" id="{{$user->id}}" form="delete"
                                        formaction="{{route("delete_user",["user"=>$user])}}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>

                            </section>
                        @endcan
                    </section>
                @endforeach
            </section>
        </section>
        @include("partials.paginator",['paginator'=>$users])

    </section>
@endsection