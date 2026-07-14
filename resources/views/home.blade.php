{{-- resources/views/home.blade.php --}}
@extends('layouts.home-layout')

@section('content')
<div class="bg-black min-h-screen">
    <div class="container mx-auto px-4 py-6">
        
        {{-- GUEST MESSAGE --}}
        @guest
            <div class="bg-blue-500/20 border border-blue-500/30 rounded-xl p-4 mb-6 backdrop-blur-sm">
                <p class="text-blue-400 text-sm">
                    👋 Welcome! 
                    <a href="{{ route('login') }}" class="text-white hover:underline font-medium">Login</a> or 
                    <a href="{{ route('register') }}" class="text-white hover:underline font-medium">Register</a> 
                    to like, comment, and share videos.
                </p>
            </div>
        @endguest

        {{-- ============================================ --}}
        {{-- VIDEO GRID --}}
        {{-- ============================================ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
            @forelse($posts as $index => $post)
                {{-- ============================================ --}}
                {{-- Video, Link, Image အကုန်ပြမယ် --}}
                {{-- ============================================ --}}
                @if($post->video || $post->link || $post->image)
                    @guest
                        <x-guest.video-card :post="$post" :index="$index" />
                    @else
                        <x-auth.video-card :post="$post" :index="$index" />
                    @endguest
                @endif
            @empty
                <div class="col-span-full text-center py-16">
                    <div class="text-6xl mb-4">🎬</div>
                    <p class="text-gray-400 text-lg">No posts found</p>
                    @auth
                        <a href="#" onclick="openModal('createPostModal')" 
                           class="text-blue-400 hover:underline mt-2 inline-block">
                            Create your first post
                        </a>
                    @endauth
                </div>
            @endforelse
        </div>

        {{-- ============================================ --}}
        {{-- PAGINATION --}}
        {{-- ============================================ --}}
        @if(isset($posts) && $posts->hasPages())
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection