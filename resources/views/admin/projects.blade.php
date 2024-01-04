@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home/projects.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partials/cards.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partials/pagination.css') }}">
@endpush

@push('scripts')
    <script type="module" src="{{ asset('js/pages/projects.js') }}" defer></script>
@endpush

@section('content')

    <section class="projectPage admin">


        <section class="project-list">
            <header>
                <section class="search">

                    <form method="GET" id="search" action="{{route('admin_show_projects')}}">
                        <input type="search" name="query" placeholder="&#128269 Search" aria-label="Search"
                               id="search-bar" value="{{$query}}"/>
                        <button class="" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                        <div class="filters">
                            <select  name="status">
                                @if($status==='')<option selected value="">Filters</option>
                                @else <option value="">Filters</option>
                                @endif
                                @if($status==='archive')<option selected value="archive">archive</option>
                                @else <option value="archive">archive</option>
                                @endif
                                @if($status==='open')<option selected value="open">open</option>
                                @else <option value="open">open</option>
                                @endif
                            </select>
                        </div>
                    </form>
                </section>
                <section>
                    <h5>
                        <i class="fa-solid fa-folder-closed"></i> All Projects:  {{$projects->total()}}
                    </h5>

                </section>
            </header>
            <section class="projects">
                @foreach($projects as $project)
                    @include("partials.projectCard",['$project'=>$project,'is_admin'=>true,'favorites'=>[]])

                @endforeach

            </section>

        </section>
        @include("partials.paginator",['paginator'=>$projects])

    </section>

@endsection