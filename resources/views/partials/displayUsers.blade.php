
@foreach($users as $user)
    <div class="user">
        <section class="name"> {!! $user->name !!} </section>
        <section class="email"> {!! $user->email !!} </section>
        @if($user->is_admin)
            <section class="role"> Admin </section>
        @else
            <section class="role"> User </section>
        @endif
        <section class="change"> <a href="{{route('edit_profile',['user'=>$user->id])}}"><button> Edit </button></a> </section>
        <section class="change"> <a href=""><button> Delete </button></a>  </section>
    </div>
@endforeach