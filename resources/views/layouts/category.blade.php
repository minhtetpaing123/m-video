<!-- resources/views/layouts/category.blade.php -->
<div class="category-navigation">
    <div class="category-container">
        <div class="category-scroll-wrapper">
            <div class="category-items-container">
                @php
                    $categories = [
                        ['name' => 'All', 'active' => true, 'icon' => 'home', 'slug' => 'all'],
                        ['name' => 'Gaming', 'active' => false, 'icon' => 'sports_esports', 'slug' => 'gaming'],
                        ['name' => 'Podcasts', 'active' => false, 'icon' => 'mic', 'slug' => 'podcasts'],
                        ['name' => 'Mobile Legends', 'active' => false, 'icon' => 'videogame_asset', 'slug' => 'mobile-legends'],
                        ['name' => 'Shorts', 'active' => false, 'icon' => 'bolt', 'slug' => 'shorts'],
                        ['name' => 'Music', 'active' => false, 'icon' => 'music_note', 'slug' => 'music'],
                        ['name' => 'Live', 'active' => false, 'icon' => 'live_tv', 'slug' => 'live'],
                        ['name' => 'Trending', 'active' => false, 'icon' => 'trending_up', 'slug' => 'trending'],
                         ['name' => 'Pron', 'active' => false, 'icon' => 'stars', 'slug' => 'pron'],
                    ];
                @endphp
                
                @foreach($categories as $index => $category)
                    <a href="{{ url('/category/' . $category['slug']) }}"
                       class="category-item {{ $category['active'] ? 'active' : '' }}"
                       data-category="{{ $category['slug'] }}"
                       style="--delay: {{ $index * 0.05 }}s">
                        <span class="category-icon">
                            {{ $category['icon'] }}
                        </span>
                        <span class="category-name">{{ $category['name'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>