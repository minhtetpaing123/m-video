<?php

namespace App\Livewire\Search;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Auth;

#[Layout('livewire.layout.search-layout')]
class Search extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    #[Url(history: true)]
    public $q = '';

    #[Url(history: true)]
    public $type = 'all';

    #[Url(history: true)]
    public $sort = 'relevance';

    #[Url(history: true)]
    public $category = '';

    #[Url(history: true)]
    public $duration = '';

    // ✅ 18+ Mature Filter
    #[Url(history: true)]
    public $mature = ''; // '', '0', '1'

    public $perPage = 12;

    public function search()
    {
        // ဘာမှမလုပ်ဘူး
    }

    public function updatedQ()
    {
        $this->resetPage();
    }

    public function updatedType()
    {
        $this->resetPage();
    }

    public function updatedSort()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function updatedDuration()
    {
        $this->resetPage();
    }

    // ✅ Mature ပြောင်းရင် Page Reset
    public function updatedMature()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->q = '';
        $this->type = 'all';
        $this->sort = 'relevance';
        $this->category = '';
        $this->duration = '';
        $this->mature = ''; // ✅ Mature ကိုပါရှင်းမယ်
        $this->resetPage();
    }

    public function getSearchResultsProperty()
    {
        if (empty($this->q) && empty($this->category)) {
            return collect([]);
        }

        $query = Post::query()
            ->with(['user'])
            ->whereNull('deleted_at')
            ->visibleTo();

        if (!empty($this->q)) {
            $query->where(function ($q) {
                $q->where('title', 'LIKE', '%' . $this->q . '%')
                  ->orWhere('description', 'LIKE', '%' . $this->q . '%')
                  ->orWhere('content', 'LIKE', '%' . $this->q . '%');
            });
        }

        if (!empty($this->category)) {
            $query->where('category', $this->category);
        }

        if (!empty($this->duration)) {
            switch ($this->duration) {
                case 'short':
                    $query->where('video_duration', '<', 300);
                    break;
                case 'medium':
                    $query->whereBetween('video_duration', [300, 1800]);
                    break;
                case 'long':
                    $query->where('video_duration', '>', 1800);
                    break;
            }
        }

        // ✅ 18+ Mature Filter
        if ($this->mature !== '') {
            $query->where('is_mature', $this->mature);
        }

        switch ($this->sort) {
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'most_viewed':
                $query->orderBy('views_count', 'desc');
                break;
            case 'most_liked':
                $query->orderBy('likes_count', 'desc');
                break;
            case 'relevance':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query->paginate($this->perPage);
    }

    public function getCategoriesProperty()
    {
        return Post::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category')
            ->map(function ($cat) {
                return [
                    'value' => $cat,
                    'label' => ucfirst(str_replace('_', ' ', $cat))
                ];
            });
    }

    public function getTotalResultsProperty()
    {
        if ($this->searchResults->isEmpty()) {
            return 0;
        }
        return $this->searchResults->total();
    }

    public function render()
    {
        return view('livewire.search.search', [
            'results' => $this->searchResults,
            'totalResults' => $this->totalResults,
            'categories' => $this->categories,
        ]);
    }
}