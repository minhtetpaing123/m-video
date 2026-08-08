<div>
    @foreach($posts as $post)
        <div wire:key="island-post-card-{{ $post['id'] ?? $post->id }}">
            @include('livewire.dashboard.post.post-card', [
                'post' => is_array($post) ? (object) $post : $post
            ])
        </div>
    @endforeach
</div>