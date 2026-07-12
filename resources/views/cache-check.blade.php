<!DOCTYPE html>
<html>
<head>
    <title>Cache Check</title>
    <style>
        body { font-family: monospace; background: #0a0a0a; color: #00ff00; padding: 20px; }
        .card { background: #1a1a1a; padding: 20px; margin: 10px 0; border-radius: 8px; border: 1px solid #333; }
        .success { color: #00ff00; }
        .error { color: #ff4444; }
        .info { color: #4ecdc4; }
        .warning { color: #ffd93d; }
        h2 { color: #fff; border-bottom: 1px solid #333; padding-bottom: 10px; }
        .grid { display: grid; grid-template-columns: 200px 1fr; gap: 5px 20px; }
    </style>
</head>
<body>
    <h1>🔍 Cache Database Check</h1>
    
    @if(isset($data['error']))
        <div class="card" style="border-color: #ff4444;">
            <h2>❌ Error</h2>
            <p class="error">{{ $data['error'] }}</p>
        </div>
    @endif
    
    <div class="card">
        <h2>📦 Cache Configuration</h2>
        <div class="grid">
            <span>Driver:</span>
            <span class="info">{{ $data['cache_driver'] }}</span>
            <span>Table:</span>
            <span class="info">{{ $data['cache_table'] }}</span>
            <span>Table Exists:</span>
            <span class="{{ $data['table_exists'] ? 'success' : 'error' }}">
                {{ $data['table_exists'] ? '✅ Yes' : '❌ No' }}
            </span>
        </div>
    </div>
    
    <div class="card">
        <h2>📊 Cache Statistics</h2>
        <div class="grid">
            <span>Total Entries:</span>
            <span class="info">{{ $data['total_rows'] }}</span>
            <span>Video Cache Entries:</span>
            <span class="{{ $data['video_rows'] > 0 ? 'success' : 'warning' }}">
                {{ $data['video_rows'] }}
            </span>
        </div>
    </div>
    
    @if($data['video_rows'] > 0)
        <div class="card">
            <h2>🎬 Video Cache Keys</h2>
            <ul>
                @foreach($data['video_keys'] as $key)
                    <li style="color: #4ecdc4; word-break: break-all; font-size: 12px;">{{ $key }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="card">
        <h2>💾 Video Files</h2>
        <div class="grid">
            <span>Path:</span>
            <span style="font-size: 11px; word-break: break-all;">{{ $data['video_path'] }}</span>
            <span>Exists:</span>
            <span class="{{ $data['video_path_exists'] ? 'success' : 'error' }}">
                {{ $data['video_path_exists'] ? '✅ Yes' : '❌ No' }}
            </span>
            <span>Total Videos:</span>
            <span class="info">{{ $data['video_file_count'] }}</span>
        </div>
        
        @if($data['video_file_count'] > 0)
            <div style="margin-top: 10px;">
                <span style="color: #ffd93d;">Files:</span>
                <div style="color: #4ecdc4; font-size: 11px; word-break: break-all; margin-top: 5px;">
                    {{ implode(', ', $data['video_files']) }}
                </div>
            </div>
        @endif
    </div>
    
    <div class="card">
        <h2>🗝️ All Cache Keys (First 20)</h2>
        <ul>
            @foreach($data['all_keys'] as $key)
                <li style="color: #888; font-size: 11px; word-break: break-all;">{{ $key }}</li>
            @endforeach
            @if(count($data['all_keys']) > 20)
                <li style="color: #888; font-size: 11px;">... and {{ count($data['all_keys']) - 20 }} more</li>
            @endif
        </ul>
    </div>
    
    <div class="card" style="border-color: #ffd93d;">
        <h2>💡 Status</h2>
        @if($data['video_rows'] > 0)
            <p class="success">✅ Video caching is working! {{ $data['video_rows'] }} videos cached.</p>
        @elseif($data['video_file_count'] > 0 && $data['video_rows'] == 0)
            <p class="warning">⚠️ {{ $data['video_file_count'] }} videos exist but none are cached. Play a video to cache it.</p>
        @else
            <p class="info">⏳ No videos found. Upload a video to test caching.</p>
        @endif
    </div>
</body>
</html>