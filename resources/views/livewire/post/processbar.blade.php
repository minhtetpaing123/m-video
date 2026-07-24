<div>
@if($isVisible)
<div id="{{ $id }}Container" 
     wire:key="processbar-container-{{ $id }}"
     style="position: fixed; top: 0; left: 0; right: 0; z-index: 9999; background: rgba(0,0,0,0.85); padding: 14px 20px; border-bottom: 2px solid #2d88ff; backdrop-filter: blur(4px); animation: slideDown 0.4s ease;">
    <div style="max-width: 800px; margin: 0 auto; display: flex; align-items: center; gap: 16px;">
        <div style="flex-shrink: 0; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
            <svg style="width: 32px; height: 32px; animation: spin 1s linear infinite;" viewBox="0 0 50 50">
                <circle cx="25" cy="25" r="20" fill="none" stroke="rgba(45,136,255,0.2)" stroke-width="4"/>
                <circle cx="25" cy="25" r="20" fill="none" stroke="#2d88ff" stroke-width="4" stroke-dasharray="80 120" stroke-linecap="round"/>
            </svg>
        </div>
        
        <div style="flex: 1; min-width: 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <span style="color: #ffffff; font-size: 14px; font-weight: 500;" id="{{ $id }}Status">{{ $status ?: $title }}</span>
                <span style="color: #2d88ff; font-size: 14px; font-weight: 700;" id="{{ $id }}Percent">{{ round($percent) }}%</span>
            </div>
            
            <div style="width: 100%; height: 6px; background: rgba(255,255,255,0.1); border-radius: 4px; overflow: hidden;">
                <div id="{{ $id }}Bar" style="width: {{ $percent }}%; height: 100%; background: #2d88ff; transition: width 0.2s ease;"></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            // 🔥 JavaScript ကနေ event ကို နားထောင်ခြင်း
            Livewire.on('update-progress', (data) => {
                console.log('🎯 update-progress received:', data);
                
                let percent = null;
                let text = null;

                // Case 1: Direct object { percent: 50, text: '...' }
                if (typeof data === 'object' && data !== null && data.percent !== undefined) {
                    percent = data.percent;
                    text = data.text || null;
                }
                // Case 2: Array wrapper [{ percent: 50, text: '...' }]
                else if (Array.isArray(data) && data.length > 0) {
                    let first = data[0];
                    if (typeof first === 'object' && first !== null && first.percent !== undefined) {
                        percent = first.percent;
                        text = first.text || null;
                    }
                    // Case 3: [50, '...']
                    else if (typeof first === 'number') {
                        percent = first;
                        text = data[1] || null;
                    }
                }

                if (percent !== null) {
                    let p = Math.round(percent);
                    let bar = document.getElementById('{{ $id }}Bar');
                    let percentText = document.getElementById('{{ $id }}Percent');
                    let statusText = document.getElementById('{{ $id }}Status');
                    
                    if (bar) bar.style.width = p + '%';
                    if (percentText) percentText.innerText = p + '%';
                    if (statusText && text) statusText.innerText = text;
                    
                    console.log('✅ Updated progress:', p + '%', text);
                } else {
                    console.warn('⚠️ No percent value found in data:', data);
                }
            });

            Livewire.on('hide-progress', () => {
                let container = document.getElementById('{{ $id }}Container');
                if (container) {
                    container.style.opacity = '0';
                    setTimeout(() => {
                        container.style.display = 'none';
                    }, 400);
                }
            });
        });

        // CSS Animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideDown {
                from { transform: translateY(-100%); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    </script>
</div>
@endif
</div>