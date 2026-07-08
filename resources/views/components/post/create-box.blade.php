<!-- resources/views/components/post/create-box.blade.php -->
@props(['onclick' => ''])

<div class="bg-white rounded-lg shadow mb-3 p-3 cursor-pointer" 
     onclick="{{ $onclick }}">
    <div class="flex items-center space-x-2">
        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex-shrink-0"></div>
        <div class="flex-1 bg-gray-100 rounded-full px-4 py-2 text-gray-500 text-sm">
            What's on your mind?
        </div>
    </div>
</div>