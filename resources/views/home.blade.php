{{-- resources/views/home.blade.php --}}
@extends('layouts.home-layout')

@section('content')
@php
    // Define videos array directly here
    $videos = [
        [
            'id' => 1,
            'title' => 'Mobile Legends: Epic 5-Man Wipeout',
            'creator' => 'Gaming Pro',
            'time' => '2 hours ago',
            'views' => '245K',
            'comments' => '1.2K',
            'likes' => '45K',
            'duration' => '12:45',
            'thumbnail_bg' => 'linear-gradient(45deg, #ff6b6b, #ff8e53)',
            'avatar_bg' => 'linear-gradient(45deg, #4ecdc4, #44a08d)',
            'live' => false
        ],
        [
            'id' => 2,
            'title' => 'Chill Lofi Mix - Study & Relax',
            'creator' => 'Music Vibes',
            'time' => '1 day ago',
            'views' => '189K',
            'comments' => '845',
            'likes' => '32K',
            'duration' => '8:22',
            'thumbnail_bg' => 'linear-gradient(45deg, #4776E6, #8E54E9)',
            'avatar_bg' => 'linear-gradient(45deg, #FF416C, #FF4B2B)',
            'live' => false
        ],
        [
            'id' => 3,
            'title' => 'Python Tutorial for Beginners',
            'creator' => 'Code Master',
            'time' => '3 days ago',
            'views' => '156K',
            'comments' => '2.3K',
            'likes' => '67K',
            'duration' => '45:18',
            'thumbnail_bg' => 'linear-gradient(45deg, #00b09b, #96c93d)',
            'avatar_bg' => 'linear-gradient(45deg, #8A2387, #E94057)',
            'live' => false
        ],
        [
            'id' => 4,
            'title' => 'Morning Yoga Flow',
            'creator' => 'Wellness Daily',
            'time' => '5 days ago',
            'views' => '98K',
            'comments' => '456',
            'likes' => '21K',
            'duration' => '25:30',
            'thumbnail_bg' => 'linear-gradient(45deg, #667eea, #764ba2)',
            'avatar_bg' => 'linear-gradient(45deg, #f7971e, #ffd200)',
            'live' => false
        ],
        [
            'id' => 5,
            'title' => 'iPhone 16 Pro Max Review',
            'creator' => 'Tech Guru',
            'time' => '1 week ago',
            'views' => '1.2M',
            'comments' => '8.9K',
            'likes' => '245K',
            'duration' => '32:15',
            'thumbnail_bg' => 'linear-gradient(45deg, #0f2027, #203a43, #2c5364)',
            'avatar_bg' => 'linear-gradient(45deg, #654ea3, #eaafc8)',
            'live' => false
        ],
        [
            'id' => 6,
            'title' => 'Live Cooking Show: Thai Food',
            'creator' => 'Chef Special',
            'time' => 'Streaming now',
            'views' => '12.5K',
            'comments' => '3.4K',
            'likes' => '89K',
            'duration' => '',
            'thumbnail_bg' => 'linear-gradient(45deg, #d53369, #cbad6d)',
            'avatar_bg' => 'linear-gradient(45deg, #1a2980, #26d0ce)',
            'live' => true
        ]
    ];
@endphp

<div class="home-video-grid">
    <div class="video-grid-container">
        @foreach($videos as $index => $video)
            @include('includes.video-card', [
                'video' => $video,
                'index' => $index
            ])
        @endforeach
    </div>
</div>
@endsection