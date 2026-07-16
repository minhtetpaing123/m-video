{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        {{-- Empty --}}
    </x-slot>

    <x-user-header />
    <x-user.nav active="home" />
    
    <div class="bg-gray-100 min-h-screen pb-16 pt-3">
        <div class="max-w-2xl mx-auto px-4">
            
            {{-- Create Post Box --}}
            <x-post.create-box onclick="openCreatePostModal()" />

            {{-- Create Post Modal --}}
            <x-post.create-modal :show="false" id="createPostModal" />

            {{-- Video Grid --}}
            <x-video-layout.grid 
                :posts="$posts" 
                empty-message="No posts yet. Create your first post!"
                card-type="auth"
            />

            {{-- Loading Indicator --}}
            <div id="loading-indicator" class="text-center py-5 hidden">
                <div class="inline-block w-10 h-10 border-4 border-gray-200 border-t-blue-500 rounded-full animate-spin"></div>
                <p class="text-gray-500 text-sm mt-2">Loading more posts...</p>
            </div>

            {{-- End of Posts Message --}}
            <div id="end-message" class="text-center py-5 text-gray-500 hidden">
                No more posts to load
            </div>

            {{-- Hidden data for infinite scroll --}}
            <div id="pagination-data" 
                 data-next-page="{{ $posts->nextPageUrl() }}"
                 data-last-page="{{ $posts->lastPage() }}"
                 data-current-page="{{ $posts->currentPage() }}"
                 class="hidden">
            </div>
        </div>
    </div>

    <script>
    let loading = false;
    let endOfPosts = false;
    let nextPageUrl = document.getElementById('pagination-data')?.dataset.nextPage;
    let lastPage = document.getElementById('pagination-data')?.dataset.lastPage;
    let currentPage = document.getElementById('pagination-data')?.dataset.currentPage;

    function loadMorePosts() {
        if (loading || endOfPosts || !nextPageUrl || nextPageUrl === 'null') return;
        
        loading = true;
        document.getElementById('loading-indicator').classList.remove('hidden');
        
        fetch(nextPageUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            const temp = document.createElement('div');
            temp.innerHTML = html;
            
            const newPosts = temp.querySelectorAll('.grid > div');
            const container = document.querySelector('.grid');
            
            newPosts.forEach(post => {
                container.appendChild(post.cloneNode(true));
            });
            
            const newPaginationData = temp.querySelector('#pagination-data');
            if (newPaginationData) {
                nextPageUrl = newPaginationData.dataset.nextPage;
                lastPage = newPaginationData.dataset.lastPage;
                currentPage = newPaginationData.dataset.currentPage;
            }
            
            if (!nextPageUrl || nextPageUrl === 'null' || currentPage === lastPage) {
                endOfPosts = true;
                document.getElementById('end-message').classList.remove('hidden');
            }
            
            loading = false;
            document.getElementById('loading-indicator').classList.add('hidden');
        })
        .catch(() => {
            loading = false;
            document.getElementById('loading-indicator').classList.add('hidden');
        });
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !endOfPosts && !loading) {
                loadMorePosts();
            }
        });
    }, { threshold: 0.1, rootMargin: '100px' });

    const loadingIndicator = document.getElementById('loading-indicator');
    if (loadingIndicator) observer.observe(loadingIndicator);

    window.addEventListener('scroll', () => {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 500) {
            if (!endOfPosts && !loading && nextPageUrl && nextPageUrl !== 'null') {
                loadMorePosts();
            }
        }
    });

    function openCreatePostModal() {
        document.getElementById('createPostModal').style.display = 'flex';
    }
    </script>
</x-app-layout>