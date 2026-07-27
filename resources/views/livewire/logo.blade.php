```html
<!-- 
    M-Video All Solutions - Livewire Logo View
    File Path: resources/views/livewire/logo.blade.php
-->
<div class="flex items-center justify-center p-4">
    <div class="relative group cursor-pointer select-none">
        
        <!-- ပတ်လည်တွင် ဖြည်းဖြည်းချင်း လည်ပတ်နေမယ့် Rainbow Glow Backdrop -->
        <div class="absolute -inset-3 bg-gradient-to-r from-cyan-500 via-pink-500 to-yellow-400 rounded-full blur-2xl opacity-40 group-hover:opacity-90 transition duration-500 animate-spin-slow"></div>

        <!-- Metallic Blue Outer Circle (စက်ဝိုင်းအပြင်ဘက် Metallic ဘောင်) -->
        <div class="relative w-64 h-64 sm:w-72 sm:h-72 md:w-80 md:h-80 rounded-full p-2.5 bg-gradient-to-b from-cyan-400 via-blue-700 to-slate-950 shadow-[0_15px_35px_rgba(2,132,199,0.4),inset_0_2px_4px_rgba(255,255,255,0.6)] group-hover:scale-105 transition-all duration-500 ease-out">
            
            <!-- Inner Bezel Ring -->
            <div class="w-full h-full rounded-full p-1.5 bg-gradient-to-b from-slate-900 via-blue-950 to-slate-900 shadow-[inset_0_5px_12px_rgba(0,0,0,0.9),0_2px_4px_rgba(56,189,248,0.3)]">
                
                <!-- Brushed Radial Metallic Navy Background -->
                <div class="relative w-full h-full rounded-full flex items-center justify-center overflow-hidden bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-blue-900 via-slate-900 to-black border border-cyan-500/40">
                    
                    <!-- Vector Graphic & SVG Keyframe Animations -->
                    <svg class="w-full h-full p-2" viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg">
                        <style>
                            /* Rainbow Gradient Flow Animation (စာသား အရောင်စီးဆင်းမှု Animation) */
                            @keyframes rainbowFlow {
                                0% { stop-color: #ff2a6d; }
                                20% { stop-color: #ff6000; }
                                40% { stop-color: #ffe600; }
                                60% { stop-color: #05ffa1; }
                                80% { stop-color: #00bbf9; }
                                100% { stop-color: #9b5de5; }
                            }

                            /* Shimmering Pulsate for Arrow Neon Glow (အလယ်မြှားခေါင်း လင်းလက်မှု Animation) */
                            @keyframes arrowPulse {
                                0%, 100% { opacity: 0.6; filter: drop-shadow(0px 0px 2px #22d3ee); }
                                50% { opacity: 1; filter: drop-shadow(0px 0px 8px #22d3ee); }
                            }

                            /* Floating Effect for Center Emblem (အလယ် လိုဂို ကြွတက်မှု Animation) */
                            @keyframes emblemFloat {
                                0%, 100% { transform: translateY(0px); }
                                50% { transform: translateY(-3px); }
                            }

                            /* Custom Rotation Animation */
                            @keyframes spinSlow {
                                from { transform: rotate(0deg); }
                                to { transform: rotate(360deg); }
                            }

                            /* Classes Application */
                            .animated-rainbow-1 { animation: rainbowFlow 6s infinite alternate linear; }
                            .animated-rainbow-2 { animation: rainbowFlow 6s infinite alternate linear 1.5s; }
                            .animated-rainbow-3 { animation: rainbowFlow 6s infinite alternate linear 3s; }
                            
                            .animated-arrows { animation: arrowPulse 2s infinite ease-in-out; }
                            .animated-emblem { animation: emblemFloat 4s infinite ease-in-out; transform-origin: center; }
                            .animate-spin-slow { animation: spinSlow 12s linear infinite; }
                        </style>

                        <defs>
                            <!-- Animated Rainbow Gradient for Curved Text -->
                            <linearGradient id="animRainbowGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" class="animated-rainbow-1" />
                                <stop offset="33%" class="animated-rainbow-2" />
                                <stop offset="66%" class="animated-rainbow-3" />
                                <stop offset="100%" class="animated-rainbow-1" />
                            </linearGradient>

                            <!-- Metallic Blue Gradient for Outer Rings & M Frame -->
                            <linearGradient id="blueMetalFrame" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#e0f2fe" />
                                <stop offset="35%" stop-color="#38bdf8" />
                                <stop offset="70%" stop-color="#0284c7" />
                                <stop offset="100%" stop-color="#0369a1" />
                            </linearGradient>

                            <!-- Glowing Cyan Gradient for Arrows -->
                            <linearGradient id="cyanNeonGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#22d3ee" />
                                <stop offset="50%" stop-color="#3b82f6" />
                                <stop offset="100%" stop-color="#6366f1" />
                            </linearGradient>

                            <!-- Curved Text Paths -->
                            <!-- Top Arc for "m-video" (Clockwise) -->
                            <path id="pathTopText" d="M 40,150 A 110,110 0 0,1 260,150" fill="none" />
                            
                            <!-- Bottom Arc for "All Solutions" (Counter-Clockwise - Right Side Up) -->
                            <path id="pathBottomText" d="M 40,150 A 110,110 0 0,0 260,150" fill="none" />

                            <!-- 3D Emboss & Shadow Filters -->
                            <filter id="3dEmbossText" x="-20%" y="-20%" width="140%" height="140%">
                                <feDropShadow dx="2" dy="4" stdDeviation="2.5" flood-color="#000000" flood-opacity="0.9" />
                                <feDropShadow dx="-1" dy="-1" stdDeviation="1.5" flood-color="#ffffff" flood-opacity="0.5" />
                            </filter>

                            <filter id="3dEmbossBlue" x="-20%" y="-20%" width="140%" height="140%">
                                <feDropShadow dx="2" dy="4" stdDeviation="2.5" flood-color="#000000" flood-opacity="0.9" />
                                <feDropShadow dx="-1" dy="-1" stdDeviation="1.5" flood-color="#7dd3fc" flood-opacity="0.5" />
                            </filter>
                        </defs>

                        <!-- Outer Metallic Blue Rings -->
                        <circle cx="150" cy="150" r="142" fill="none" stroke="url(#blueMetalFrame)" stroke-width="2.5" opacity="0.8" />
                        <circle cx="150" cy="150" r="105" fill="none" stroke="url(#blueMetalFrame)" stroke-width="3.5" filter="url(#3dEmbossBlue)" />
                        <circle cx="150" cy="150" r="62" fill="none" stroke="#0369a1" stroke-width="2" opacity="0.6" />

                        <!-- ANIMATED RAINBOW CURVED TEXT: Top "m-video" -->
                        <text font-family="ui-sans-serif, system-ui, -apple-system, sans-serif" font-size="28" font-weight="900" fill="url(#animRainbowGradient)" filter="url(#3dEmbossText)" letter-spacing="4">
                            <textPath href="#pathTopText" startOffset="50%" text-anchor="middle">
                                m-video
                            </textPath>
                        </text>

                        <!-- ANIMATED RAINBOW CURVED TEXT: Bottom "All Solutions" -->
                        <text font-family="ui-sans-serif, system-ui, -apple-system, sans-serif" font-size="22" font-weight="800" fill="url(#animRainbowGradient)" filter="url(#3dEmbossText)" letter-spacing="3">
                            <textPath href="#pathBottomText" startOffset="50%" text-anchor="middle">
                                All Solutions
                            </textPath>
                        </text>

                        <!-- CENTER EMBLEM: Floating 'M' Frame with Pulsing Arrows -->
                        <g class="animated-emblem" filter="url(#3dEmbossBlue)">
                            <!-- Outer 'M' Metallic Frame -->
                            <path d="M 102 182 
                                     L 102 122 
                                     C 102 112, 114 106, 122 115 
                                     L 150 146 
                                     L 178 115 
                                     C 186 106, 198 112, 198 122 
                                     L 198 182 
                                     C 198 190, 188 194, 180 186 
                                     L 150 154 
                                     L 120 186 
                                     C 112 194, 102 190, 102 182 Z" 
                                  fill="none" 
                                  stroke="url(#blueMetalFrame)" 
                                  stroke-width="12" 
                                  stroke-linejoin="round" 
                                  stroke-linecap="round" />

                            <!-- Pulsating Glowing Interlocking Arrows -->
                            <g class="animated-arrows">
                                <!-- Left Arrow -->
                                <path d="M 116 162 L 145 133 M 128 133 L 145 133 L 145 150" 
                                      fill="none" 
                                      stroke="url(#cyanNeonGradient)" 
                                      stroke-width="6.5" 
                                      stroke-linecap="round" 
                                      stroke-linejoin="round" />

                                <!-- Right Arrow -->
                                <path d="M 155 162 L 184 133 M 167 133 L 184 133 L 184 150" 
                                      fill="none" 
                                      stroke="url(#cyanNeonGradient)" 
                                      stroke-width="6.5" 
                                      stroke-linecap="round" 
                                      stroke-linejoin="round" />
                            </g>
                        </g>

                        <!-- Glass Reflection Overlay -->
                        <path d="M 60 110 A 105 105 0 0 1 240 110 A 100 100 0 0 0 60 110 Z" fill="#ffffff" opacity="0.08" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

```
