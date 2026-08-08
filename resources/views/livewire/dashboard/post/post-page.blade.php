<div>
    @foreach($posts as $post)
        @include('livewire.dashboard.post.post-card', ['post' => $post])
    @endforeach
</div>
