{{-- resources/views/home.blade.php --}}
@extends('layouts.home-layout')

@section('content')
<div class="w-full px-2 sm:px-4 md:px-6 lg:px-8 py-3 sm:py-4 md:py-6">
    
    {{-- Guest Message --}}
    @guest
        <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-3 sm:p-4 mb-4 sm:mb-6">
            <p class="text-blue-400 text-xs sm:text-sm">
                👋 Welcome! 
                <a href="{{ route('login') }}" class="text-white hover:underline font-medium">Login</a> or 
                <a href="{{ route('register') }}" class="text-white hover:underline font-medium">Register</a> 
                to like, comment, and share videos.
            </p>
        </div>
    @endguest

    {{-- Video Grid --}}
    <x-video-layout.grid 
        :posts="$posts" 
        empty-message="No posts found"
        card-type="guest"
    />

    {{-- Pagination --}}
    @if(isset($posts) && $posts->hasPages())
        <div class="mt-6 sm:mt-8 flex justify-center">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection