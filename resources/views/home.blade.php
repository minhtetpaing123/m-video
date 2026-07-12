{{-- resources/views/home.blade.php --}}
@extends('layouts.home-layout')

@section('content')
<div class="home-video-grid">
    <div class="video-grid-container">
        @forelse($posts as $index => $post)
            @if($post->video)
                @guest
                    <x-guest.video-card :post="$post" :index="$index" />
                @else
                    <x-auth.video-card :post="$post" :index="$index" />
                @endguest
            @endif
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-400 text-lg">No videos found</p>
                @auth
                    <a href="{{ route('posts.create') }}" class="text-blue-400 hover:underline">
                        Upload your first video
                    </a>
                @endauth
            </div>
        @endforelse
    </div>
    
    @if(isset($posts) && $posts->hasPages())
        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection