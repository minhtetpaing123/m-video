@props(['post'])

{{-- Backward Compatibility - redirects to new modular structure --}}
<x-post.menu.owner :post="$post" />