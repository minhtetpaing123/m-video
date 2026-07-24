@php
    $viewMode = $this->viewMode ?? 'grid';
@endphp

@if($viewMode === 'list')
    @include('livewire.layout.video-card.list')
@elseif($viewMode === 'netflix')
    @include('livewire.layout.video-card.netflix')
@elseif($viewMode === 'youtube')
    @include('livewire.layout.video-card.youtube')
@else
    @include('livewire.layout.video-card.grid')
@endif