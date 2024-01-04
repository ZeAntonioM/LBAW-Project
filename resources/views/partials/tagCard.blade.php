<section class="tagCard">
    <section class="tagContainer"><span class="tag tag{{$tag->id%10}}">{{$tag->title}}</span></section>
    <span><i class="fa-solid fa-list-check"></i> {{count($tag->tasks)}} tasks</span>
    <section class="actions">
        <span class="edit" ><i class="fa-solid fa-pen-to-square"></i></span>
        <span class="delete"><i class="fa-solid fa-trash"></i></span>
    </section>
</section>