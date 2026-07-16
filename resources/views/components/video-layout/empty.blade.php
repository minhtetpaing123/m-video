{{-- resources/views/components/video-layout/empty.blade.php --}}
@props(['message' => 'No posts found'])

<div class="empty-state">
    <div class="empty-state-icon">🎬</div>
    <p class="empty-state-text">{{ $message }}</p>
    @auth
        <a href="#" onclick="openCreatePostModal()" class="empty-state-link">
            Create your first post
        </a>
    @endauth
</div>

<style>
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state-icon {
    font-size: 60px;
    margin-bottom: 16px;
}

.empty-state-text {
    color: #888;
    font-size: 18px;
    margin-bottom: 8px;
}

.empty-state-link {
    color: #2d88ff;
    text-decoration: underline;
    display: inline-block;
    margin-top: 8px;
    cursor: pointer;
}
</style>