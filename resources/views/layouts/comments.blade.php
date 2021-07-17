@foreach($comments as $comment )
    <div class="card {{ ! $loop->first ? 'mt-2' : '' }}">
        <div class="card-header d-flex justify-content-between">
            <div class="commenter d-flex">
                <span>{{ $comment->user->name }}</span>
                <span class="text-muted">&nbsp - {{ jdate($comment->created_at)->ago() }} </span>
            </div>
            @auth
                {{-- فقط دیدگاه 2 سطحی فعال باشد --}}
                @if ($comment->parent_id == 0 )
                    <span class="btn btn-sm btn-primary" data-toggle="modal" data-target="#sendComment" data-id="{{ $comment->id }}">پاسخ به نظر</span>
                @endif
            @endauth
        </div>

        <div class="card-body">
            {{ $comment->comment }}

            {{-- Didgah haye N sathi faal bashad. --}}
            @include('layouts.comments', ['comments' => $comment->child])

            <!-- دیدگاه های یک سطحی -->
            {{-- @foreach($comment->child as $childComment)
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between">
                        <div class="commenter">
                            <span>{{ $childComment->user->name }}</span>
                            <span class="text-muted">- دو دقیقه قبل</span>
                        </div>
                    </div>

                    <div class="card-body">
                        {{ $childComment->comment }}
                    </div>
                </div>
            @endforeach --}}

        </div>
    </div>
@endforeach
