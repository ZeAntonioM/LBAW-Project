<section class="userCard {{$size}}">
    @isset($user->id)
        <img class="icon avatar" src="{{ $user->image() }}" alt="default user icon">
    @else
        <img class="icon avatar" src="{{ $user->image }}" alt="default user icon">
    @endisset
    <section class="info">
        <h3>{{$user->name}}</h3>
        <h5>{{$user->email}}</h5>
    </section>

</section>