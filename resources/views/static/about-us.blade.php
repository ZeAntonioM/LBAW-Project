@extends('layouts.app')

@push('styles')
    <link href="{{ url('css/static/about.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script type="text/javascript" src={{ url('js/app.js') }} defer></script>
@endpush

@section('content')
    <section class="about_us_section">
        <h1> About us </h1>
        <p> 
            We are a group of people dedicated to help diverse teams, from students embarking on educational journeys to
            professonals driving innovation and change. We're commited to providing a tool that is easy to use, so that
            planning a project stops being a burden and becomes a wonderful experience towards achievement.
        </p>
    </section>

    <section class="about_us_section">
        <h1> Meet the team </h1>

        <!-- Hard coded members for now. Maybe use admins of the system?-->
        <article id="team_members">
            @foreach (["Daniel Ferreira", "Francisco Cardoso", "José Martins", "Tomás Pereira"] as $member_name)
                <div class="team_member">
                    <img class="avatar about_team_avatar" src="{{ asset('img/team-avatars/' . $loop->iteration . '.jpeg') }}" alt="member_avatar">
                    <p> {{ $member_name }} </p>
                </div>
            @endforeach
        </article>
    </section>

@endsection
