<section class="multiselector {{$data}}">

    @if($data=="users")
        <span><i class="fa-solid fa-users"></i>  Assign users <i class="fas fa-chevron-up "></i></span>
        <section class="dropdown hidden">
            @foreach($users as $user )
                <section class="item" id="assign">
                    @if(old($user->id)||($selected && $selected->pluck('id')->contains($user->id) ))
                        <input type="checkbox" name="{{$user->id}}" id="{{$user->id}}" value="{{$user->id}}" checked>
                    @else
                        <input type="checkbox" name="{{$user->id}}" id="{{$user->id}}" value="{{$user->id}}">
                    @endif

                    <label for="{{$user->id}}"> @include('partials.userCard',['size'=>'small'])</label>
                </section>
            @endforeach
        </section>

    @elseif(date("tags"))
        <span> <i class="fa-solid fa-tag"></i>  Assign tags <i class="fas fa-chevron-up "></i></span>
        <section class="dropdown hidden">
            @foreach($tags as $tag )
                <section class="item">
                    @if(old($tag->id)||($selected && $selected->pluck('id')->contains($tag->id) ))
                        <input type="checkbox" name="{{$tag->id}}" id="{{$tag->id}}" value="{{$tag->id}}" checked>
                    @else
                        <input type="checkbox" name="{{$tag->id}}" id="{{$tag->id}}" value="{{$tag->id}}">
                    @endif
                    <label for="{{$tag->id}}"> {{$tag->title}}</label>
                </section>
            @endforeach
        </section>
    @endif

</section>

@if ($errors->has($data))
    <span class="error">
        {{ $errors->first($data) }}
    </span>
@endif