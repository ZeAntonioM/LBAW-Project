@extends('layouts.project')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forum.css') }}">
    <link rel="stylesheet" href="{{ asset('css/project.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partials/cards.css') }}">
@endpush

@push('scripts')
    <script type="module" src={{ url('js/forumPages.js') }}  defer></script>
    <script type="module" src="{{ asset('js/editPost.js') }}" defer></script>
@endpush


@section('content')

    <section class="forum">

        <div class="forum-container" data-project="{{$project->id}}">
            
            @if (count($posts) === 0)
                <div id="no-posts">
                    <p>There are no posts yet. Be the first posting here!</p>
                </div>
            @endif
            @foreach ($posts as $post)
                @include('partials.postCard',['post'=>$post])
            @endforeach

        </div>

        <div class="new-post">
            <form action="{{ route('create_post', ['project' => $project->id]) }}" method="POST">
                @csrf
                <div class="new-post-body">
                    <textarea name="content" id="content" cols="30" rows="10" placeholder="Write your post here..."></textarea>
                    @if($errors->has('content'))
                        <span class="error">
                            {{ $errors->first('content') }}
                        </span>
                    @endif
                </div>
                <div class="new-post-footer">
                    <button type="submit">Post</button>
                </div>
            </form>
        </div>
        
    </section>

    

@endsection
