@if (Auth::user()->id == $post->user_id)
                    <div class="own-post" id="{{$post->id}}">
                @else
                    <div class="participants-post">
                @endif
                        <div class="post-header">
                            <div class="post-user">
                                @include('partials.userCard', ['size' => '', 'user' => $post->user])
                            </div>
                            @if (Auth::user()->id === $post->user_id)
                                <div class="post-edit">
                                    <button class="edit-post"><i class="fas fa-edit"></i></button>
                                    <form action="{{ route('delete_post', ['project' => $project, 'post' => $post]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="delete-post" type="submit"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            @endif
                        </div>
                        <div class="post-content"> 
                            <div class="post-body">
                                <p class="content">{{ $post->content }}</p>
                            </div>
                            <div class="post-footer">
                                <p class="date-post"> 
                                @if ($post->last_edited !== null) 
                                    Last edited: {{ date('d-m-Y', strtotime($post->last_edited)) }}
                                @else
                                    Posted: {{ date('d-m-Y', strtotime($post->submit_date)) }}
                                @endif
                                </p>
                            </div>
                        </div>
                </div>