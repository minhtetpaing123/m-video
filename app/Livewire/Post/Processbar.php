<?php

namespace App\Livewire\Post;

use Livewire\Component;
use Livewire\Attributes\On;

class Processbar extends Component
{
    public $id;
    public $title = 'Uploading...';
    public $status = '';
    public $percent = 0;
    public $isVisible = false;

    public function mount($id, $title = 'Uploading...')
    {
        $this->id = $id;
        $this->title = $title;
        $this->status = $title;
    }

    #[On('show-progress')]
    public function showProgress($data = null)
    {
        $message = '';
        
        if (is_string($data)) {
            $message = $data;
        } elseif (is_array($data)) {
            if (isset($data['message'])) {
                $message = $data['message'];
            } elseif (isset($data[0]) && is_string($data[0])) {
                $message = $data[0];
            } elseif (isset($data[0]['message'])) {
                $message = $data[0]['message'];
            }
        }
        
        $this->status = $message ?: $this->title;
        $this->percent = 0;
        $this->isVisible = true;
    }

    #[On('update-progress')]
    public function updateProgress($data = null)
    {
        // 🔥 JavaScript ကနေ dispatch လုပ်တဲ့ payload ပုံစံကို ကိုင်တွယ်ခြင်း
        $percent = null;
        $text = null;

        // JavaScript dispatch: Livewire.dispatch('update-progress', { percent: 50, text: '...' })
        if (is_array($data) && isset($data['percent'])) {
            $percent = $data['percent'];
            $text = $data['text'] ?? null;
        }
        // PHP dispatch: $this->dispatch('update-progress', percent: 50, text: '...')
        elseif (is_array($data) && isset($data[0]) && is_array($data[0])) {
            $percent = $data[0]['percent'] ?? null;
            $text = $data[0]['text'] ?? null;
        }
        // PHP dispatch: $this->dispatch('update-progress', ['percent' => 50, 'text' => '...'])
        elseif (is_array($data) && isset($data[0]) && is_numeric($data[0])) {
            $percent = $data[0];
            $text = $data[1] ?? null;
        }

        // 📢 Debug: Log ထုတ်ကြည့်ပါ
        \Illuminate\Support\Facades\Log::info('updateProgress received', [
            'data' => $data,
            'percent' => $percent,
            'text' => $text
        ]);

        if ($percent !== null) {
            $this->percent = (int) $percent;
        }
        if ($text !== null) {
            $this->status = $text;
        }
    }

    #[On('hide-progress')]
    public function hideProgress($data = null)
    {
        $this->isVisible = false;
        $this->percent = 0;
        $this->status = '';
    }

    public function render()
    {
        return view('livewire.post.processbar');
    }
}