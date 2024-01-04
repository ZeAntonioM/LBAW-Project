@if (Auth::user()->id == $comment->user_id)
                    <div class="own-comment col-12 p-4" id="{{$comment->id}}" style="margin-bottom: 10px;">
                @else
                    <div class="participants-comment col-12 p-4" style="margin-bottom: 10px;">
                @endif
                    <div class ="row">
                        <div class="comment-header col-12">
                              <div class="row">
                                    <div class ="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 userName">
                                        @isset($comment->user->id)
                                            <img class="icon avatar" src="{{$comment->user->image()}}" alt="default user icon">                            
                                        @else
                                            <img class="icon avatar" src="{{$comment->user->image}}" alt="default user icon">
                                        @endisset
                                        <span>{{$comment->user->name}}</span>
                                    </div>
                                    @if (Auth::user()->id == $comment->user_id)
                                    <div class =" col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-8  editButtons">
                                    <button class="edit-comment" style = "margin-right: 10px;"><i class="fas fa-edit"></i></button>
                                    <form action="{{route('delete_comment',['project'=>$project,'task'=> $task,'comment'=>$comment])}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="delete-comment" type="submit" style ="background-color:var(--error-text-color);"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                    </div>
                                    @endif
                                </div>
                        </div>
                        <div class="comment-content col-12"> 
                            <div class="comment-body">
                                <p class="content">{{ $comment->content }}</p>
                            </div>
                            <div class="comment-footer d-flex justify-content-end">
                                <p class="date-post"> 
                                @if ($comment->last_edited !== null) 
                                    Last edited: {{ date('d-m-Y', strtotime($comment->last_edited)) }}
                                @else
                                    Posted: {{ date('d-m-Y', strtotime($comment->submit_date)) }}
                                @endif
                                </p>
                            </div>
                        </div>
                        </div>
                </div>

