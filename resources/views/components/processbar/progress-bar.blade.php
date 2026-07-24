{{-- resources/views/components/processbar/progress-bar.blade.php --}}
@props([
    'id' => 'uploadProgress',
    'title' => 'Uploading your post...',
    'autoInit' => 'true'
])

<div id="{{ $id }}Container" style="display: none; position: fixed; top: 0; left: 0; right: 0; z-index: 9999; background: rgba(0,0,0,0.4); padding: 14px 20px; border-bottom: 2px solid #e74c3c; backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);">
    
    <div style="max-width: 800px; margin: 0 auto; display: flex; align-items: center; gap: 16px;">
        
        {{-- ============================================ --}}
        {{-- SPINNER ICON (စက်သွားလည်နေတဲ့) --}}
        {{-- ============================================ --}}
        <div style="flex-shrink: 0; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
            <svg style="width: 32px; height: 32px; animation: spin 1s linear infinite;" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
                <circle cx="25" cy="25" r="20" fill="none" stroke="rgba(231,76,60,0.15)" stroke-width="4"/>
                <circle cx="25" cy="25" r="20" fill="none" stroke="#e74c3c" stroke-width="4" stroke-dasharray="80 120" stroke-linecap="round" style="animation: spinDash 1.5s ease-in-out infinite;"/>
            </svg>
        </div>
        
        {{-- ============================================ --}}
        {{-- INFO --}}
        {{-- ============================================ --}}
        <div style="flex: 1; min-width: 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <span style="color: #ffffff; font-size: 14px; font-weight: 500; letter-spacing: 0.3px;" id="{{ $id }}Status">{{ $title }}</span>
                <span style="color: #e74c3c; font-size: 14px; font-weight: 700;" id="{{ $id }}Percent">0%</span>
            </div>
            
            {{-- ============================================ --}}
            {{-- PROGRESS BAR (အနီရောင် + Shimmer) --}}
            {{-- ============================================ --}}
            <div style="width: 100%; height: 6px; background: rgba(255,255,255,0.1); border-radius: 4px; overflow: hidden; position: relative;">
                <div id="{{ $id }}Bar" style="width: 0%; height: 100%; background: linear-gradient(90deg, #e74c3c, #ff6b6b, #e74c3c); background-size: 200% 100%; border-radius: 4px; transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1); animation: shimmer 2s infinite linear;"></div>
            </div>
            
            {{-- ============================================ --}}
            {{-- SIZE & SPEED + CANCEL --}}
            {{-- ============================================ --}}
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 4px;">
                <div style="display: flex; gap: 16px;">
                    <span style="color: rgba(255,255,255,0.5); font-size: 11px;" id="{{ $id }}Size">0 MB / 0 MB</span>
                    <span style="color: rgba(255,255,255,0.5); font-size: 11px;" id="{{ $id }}Speed">⏳ Calculating...</span>
                </div>
                <button onclick="window.cancelUpload('{{ $id }}')" 
                        style="background: none; border: none; color: rgba(255,255,255,0.5); font-size: 12px; cursor: pointer; padding: 4px 12px; border-radius: 4px; transition: all 0.3s ease; display: flex; align-items: center; gap: 6px;"
                        onmouseover="this.style.color='#e74c3c'; this.style.background='rgba(231,76,60,0.15)';"
                        onmouseout="this.style.color='rgba(255,255,255,0.5)'; this.style.background='transparent';">
                    <span style="font-size: 14px;">✕</span> Cancel
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- CSS ANIMATIONS --}}
{{-- ============================================ --}}
<style>
/* Spinner Animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes spinDash {
    0% { stroke-dashoffset: 0; }
    50% { stroke-dashoffset: -40; }
    100% { stroke-dashoffset: -80; }
}

/* Shimmer Animation (အလင်းပြန်) */
@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

/* Container Slide Down */
@keyframes slideDown {
    from { 
        opacity: 0; 
        transform: translateY(-30px);
    }
    to { 
        opacity: 1; 
        transform: translateY(0);
    }
}

/* Percent Number Animation */
@keyframes countUp {
    from { 
        opacity: 0; 
        transform: scale(0.7);
    }
    to { 
        opacity: 1; 
        transform: scale(1);
    }
}

#{{ $id }}Container {
    animation: slideDown 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

#{{ $id }}Percent {
    transition: all 0.3s ease;
    display: inline-block;
}

#{{ $id }}Percent.update {
    animation: countUp 0.3s ease;
}

/* Mobile Responsive */
@media (max-width: 640px) {
    #{{ $id }}Container {
        padding: 10px 14px !important;
    }
    
    #{{ $id }}Container > div {
        gap: 10px !important;
    }
    
    #{{ $id }}Status {
        font-size: 13px !important;
    }
    
    #{{ $id }}Percent {
        font-size: 13px !important;
    }
    
    #{{ $id }}Container button {
        font-size: 11px !important;
        padding: 2px 8px !important;
    }
    
    #{{ $id }}Container .spinner-icon {
        width: 28px !important;
        height: 28px !important;
    }
}
</style>

{{-- ============================================ --}}
{{-- JAVASCRIPT --}}
{{-- ============================================ --}}
@if($autoInit === 'true')
<script>
(function() {
    'use strict';

    class ProgressBar {
        constructor(containerId) {
            this.containerId = containerId;
            this.abortController = null;
            this.startTime = null;
            this.uploadedBytes = 0;
            this.totalSize = 0;
            this.speedInterval = null;
            this.isVisible = false;
            this.onCancelCallback = null;
            this.defaultTitle = 'Uploading your post...';
            this.lastPercent = 0;
            
            window.progressBars = window.progressBars || {};
            window.progressBars[containerId] = this;
        }

        show(title = null) {
            const container = document.getElementById(this.containerId + 'Container');
            if (!container) return;
            
            container.style.display = 'block';
            this.isVisible = true;
            this.startTime = Date.now();
            this.uploadedBytes = 0;
            this.lastPercent = 0;
            
            if (title) {
                this.defaultTitle = title;
                const statusEl = document.getElementById(this.containerId + 'Status');
                if (statusEl) statusEl.textContent = title;
            }
            
            this.update(0, null, 0, this.totalSize);
        }

        update(percent, statusText = null, uploadedSize = null, totalSize = null) {
            const bar = document.getElementById(this.containerId + 'Bar');
            const percentText = document.getElementById(this.containerId + 'Percent');
            const statusEl = document.getElementById(this.containerId + 'Status');
            const sizeText = document.getElementById(this.containerId + 'Size');
            const speedText = document.getElementById(this.containerId + 'Speed');

            if (bar) {
                bar.style.width = Math.min(percent, 100) + '%';
            }
            
            if (percentText) {
                const newPercent = Math.round(Math.min(percent, 100));
                if (newPercent !== this.lastPercent) {
                    this.lastPercent = newPercent;
                    percentText.textContent = newPercent + '%';
                    percentText.classList.remove('update');
                    void percentText.offsetWidth;
                    percentText.classList.add('update');
                }
            }
            
            if (statusEl && statusText !== null) {
                statusEl.textContent = statusText;
            }

            if (uploadedSize !== null && totalSize !== null) {
                this.uploadedBytes = uploadedSize;
                this.totalSize = totalSize;
                
                if (sizeText) {
                    sizeText.textContent = this.formatFileSize(uploadedSize) + ' / ' + this.formatFileSize(totalSize);
                }

                const elapsed = (Date.now() - this.startTime) / 1000;
                if (elapsed > 0 && uploadedSize > 0) {
                    const speed = uploadedSize / elapsed;
                    if (speedText) {
                        speedText.textContent = this.formatSpeed(speed);
                    }
                }
            }
        }

        hide() {
            const container = document.getElementById(this.containerId + 'Container');
            if (container) {
                container.style.display = 'none';
                this.isVisible = false;
            }
            
            const bar = document.getElementById(this.containerId + 'Bar');
            const percentText = document.getElementById(this.containerId + 'Percent');
            const sizeText = document.getElementById(this.containerId + 'Size');
            const speedText = document.getElementById(this.containerId + 'Speed');
            const statusEl = document.getElementById(this.containerId + 'Status');

            if (bar) bar.style.width = '0%';
            if (percentText) {
                percentText.textContent = '0%';
                percentText.classList.remove('update');
            }
            if (sizeText) sizeText.textContent = '0 MB / 0 MB';
            if (speedText) speedText.textContent = '';
            if (statusEl) statusEl.textContent = this.defaultTitle;

            if (this.speedInterval) {
                clearInterval(this.speedInterval);
                this.speedInterval = null;
            }
        }

        cancel() {
            if (this.abortController) {
                this.abortController.abort();
                this.abortController = null;
            }
            
            if (this.onCancelCallback) {
                this.onCancelCallback();
            }
            
            this.hide();
        }

        setAbortController(controller) {
            this.abortController = controller;
        }

        onCancel(callback) {
            this.onCancelCallback = callback;
        }

        formatFileSize(bytes) {
            if (bytes === 0) return '0 MB';
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }

        formatSpeed(bytesPerSecond) {
            if (bytesPerSecond < 1024) {
                return bytesPerSecond.toFixed(0) + ' B/s';
            } else if (bytesPerSecond < 1024 * 1024) {
                return (bytesPerSecond / 1024).toFixed(1) + ' KB/s';
            } else {
                return (bytesPerSecond / (1024 * 1024)).toFixed(1) + ' MB/s';
            }
        }

        simulate(totalSize = 10 * 1024 * 1024, duration = 5000) {
            this.show('Simulating upload...');
            this.totalSize = totalSize;
            
            const startTime = Date.now();
            const interval = setInterval(() => {
                const elapsed = Date.now() - startTime;
                const percent = Math.min((elapsed / duration) * 100, 100);
                const uploaded = (percent / 100) * totalSize;
                
                this.update(percent, 'Uploading...', uploaded, totalSize);
                
                if (percent >= 100) {
                    clearInterval(interval);
                    this.update(100, '✅ Complete!', totalSize, totalSize);
                    setTimeout(() => this.hide(), 1000);
                }
            }, 100);
        }
    }

    window.cancelUpload = function(containerId) {
        const progress = window.progressBars ? window.progressBars[containerId] : null;
        if (progress) {
            progress.cancel();
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        const containerId = '{{ $id }}';
        if (!window.progressBars || !window.progressBars[containerId]) {
            new ProgressBar(containerId);
        }
    });

})();
</script>
@endif