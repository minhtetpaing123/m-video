<div>
    {{-- WebRTC Remote Voice Audio --}}
    <audio id="remoteAudio" autoplay playsinline style="display: none;" wire:ignore></audio>
    
    {{-- Outgoing Call MP3 Audio --}}
    <audio id="outgoingRingtone" src="{{ asset('sounds/outgoing.mp3') }}" loop preload="auto" style="display: none;" wire:ignore></audio>

    {{-- Incoming Call Ringtone MP3 Audio --}}
    <audio id="incomingRingtone" src="{{ asset('sounds/ringtone.mp3') }}" loop preload="auto" style="display: none;" wire:ignore></audio>

    {{-- Hangup MP3 Audio (ဖုန်းချလိုက်စဉ် မြည်မည့်အသံ) --}}
    <audio id="hangupAudio" src="{{ asset('sounds/hangup.mp3') }}" preload="auto" style="display: none;" wire:ignore></audio>

    @if($isCalling && $user)
        <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100vw; height: 100vh; background-color: #0d1117; z-index: 999999; display: flex; flex-direction: column; align-items: center; justify-content: space-between; padding: 20px 20px 40px 20px; box-sizing: border-box; font-family: system-ui, -apple-system, sans-serif; overflow: hidden;">
            
            {{-- Video Streams Screen (Video Call ဖြစ်ပါက ပေါ်မည်) --}}
            <div id="videoContainer" style="display: {{ ($callType ?? 'voice') === 'video' ? 'block' : 'none' }}; position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;" wire:ignore>
                <video id="remoteVideo" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover; background: #000;"></video>
                <video id="localVideo" autoplay playsinline muted style="position: absolute; bottom: 120px; right: 20px; width: 110px; height: 160px; object-fit: cover; border-radius: 12px; border: 2px solid rgba(255,255,255,0.8); z-index: 2; box-shadow: 0 10px 20px rgba(0,0,0,0.5);"></video>
            </div>

            {{-- Header Status, Call Timer & Network Quality --}}
            <div style="position: relative; z-index: 10; display: flex; flex-direction: column; align-items: center; text-align: center; width: 100%; text-shadow: 0 2px 4px rgba(0,0,0,0.6);">
                <div id="networkBadge" wire:ignore style="display: inline-flex; align-items: center; gap: 6px; background: rgba(0, 0, 0, 0.4); color: #9ca3af; padding: 4px 12px; border-radius: 12px; font-size: 11px; font-weight: 600; margin-bottom: 12px; border: 1px solid rgba(255, 255, 255, 0.1); backdrop-filter: blur(4px);">
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071a10 10 0 0114.142 0M2.828 9.172a15 15 0 0121.214 0"></path>
                    </svg>
                    <span id="networkText">Connection: Checking...</span>
                </div>

                <div style="display: inline-flex; align-items: center; gap: 8px; background-color: rgba(16, 185, 129, 0.25); color: #34d399; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; border: 1px solid rgba(16, 185, 129, 0.4); backdrop-filter: blur(4px);">
                    <span style="width: 8px; height: 8px; background-color: #34d399; border-radius: 50%; display: inline-block;"></span>
                    <span id="callStatusText">{{ $isIncoming ? 'INCOMING CALL...' : 'CALLING...' }}</span>
                </div>
                
                <div id="callTimer" wire:ignore style="color: #34d399; font-size: 20px; font-weight: 700; margin-top: 10px; display: none;">00:00</div>

                <h2 style="color: #ffffff; font-size: 26px; font-weight: 700; margin: 16px 0 4px 0;">{{ $user->name }}</h2>
                <p style="color: #d1d5db; font-size: 13px; margin: 0;">
                    Messenger {{ ($callType ?? 'voice') === 'video' ? 'Video' : 'Voice' }} Call • End-to-End Encrypted
                </p>
            </div>

            {{-- Center User Avatar (Voice Call အတွက် သို့မဟုတ် Video မပွင့်သေးမီ) --}}
            <div id="avatarContainer" style="display: {{ ($callType ?? 'voice') === 'voice' ? 'flex' : 'none' }}; position: relative; z-index: 10; align-items: center; justify-content: center; width: 160px; height: 160px; margin: auto 0;">
                <div style="position: absolute; width: 180px; height: 180px; border-radius: 50%; background-color: rgba(59, 130, 246, 0.15);"></div>
                <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                     alt="{{ $user->name }}" 
                     style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid #0d1117; z-index: 10; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);">
            </div>

            {{-- Action Controls Bar --}}
            <div style="position: relative; z-index: 10; display: flex; align-items: center; justify-content: center; gap: 24px; width: 100%; max-width: 320px; margin-bottom: 10px;">
                @if($isIncoming)
                    <!-- Decline Button -->
                    <button wire:click="rejectCall" type="button" 
                            style="width: 64px; height: 64px; background-color: #ef4444; border-radius: 50%; border: none; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer; box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.4);">
                        <svg style="width: 28px; height: 28px; transform: rotate(135deg);" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6.62 10.79c.54 1.36 1.34 2.58 2.28 3.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C9.48 21 3 14.52 3 6c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                        </svg>
                    </button>

                    <!-- Accept Button -->
                    <button wire:click="acceptCall" type="button" 
                            style="width: 64px; height: 64px; background-color: #10b981; border-radius: 50%; border: none; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer; box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.4);">
                        <svg style="width: 28px; height: 28px;" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6.62 10.79c.54 1.36 1.34 2.58 2.28 3.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C9.48 21 3 14.52 3 6c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                        </svg>
                    </button>
                @else
                    <!-- Mic Mute Button -->
                    <button wire:click="toggleMute" type="button" 
                            style="width: 56px; height: 56px; background-color: {{ $isMuted ? '#b91c1c' : 'rgba(30, 41, 59, 0.8)' }}; border-radius: 50%; border: 1px solid rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: center; color: white; cursor: pointer; backdrop-filter: blur(4px);">
                        <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                        </svg>
                    </button>

                    <!-- End Call Button -->
                    <button wire:click="endCall" type="button" 
                            style="width: 64px; height: 64px; background-color: #ef4444; border-radius: 50%; border: none; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer; box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.4);">
                        <svg style="width: 30px; height: 30px; transform: rotate(135deg);" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6.62 10.79c.54 1.36 1.34 2.58 2.28 3.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C9.48 21 3 14.52 3 6c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                        </svg>
                    </button>

                    <!-- Speaker Toggle Button -->
                    <button wire:click="toggleSpeaker" type="button" 
                            style="width: 56px; height: 56px; background-color: {{ $isSpeakerOn ? '#2563eb' : 'rgba(30, 41, 59, 0.8)' }}; border-radius: 50%; border: 1px solid rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: center; color: white; cursor: pointer; backdrop-filter: blur(4px);">
                        <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    @endif

    <script>
        (function() {
            'use strict';

            window.callTimerInterval = null;
            window.networkStatsInterval = null;
            window.callSeconds = 0;
            window.pendingCandidates = [];
            window.rtcPeerConnection = null;
            window.rtcLocalStream = null;
            window.remoteStream = null;
            window.pendingOffer = null;
            window.isTimerRunning = false;

            const rtcConfig = {
                iceServers: [
                    { urls: 'stun:stun.l.google.com:19302' },
                    { urls: 'stun:stun1.l.google.com:19302' },
                    { urls: 'stun:stun2.l.google.com:19302' }
                ]
            };

            // ✅ Outgoing Ringtone Controls
            window.playOutgoingRingtone = function() {
                const audioEl = document.getElementById('outgoingRingtone');
                if (audioEl) {
                    audioEl.currentTime = 0;
                    const playPromise = audioEl.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(err => {
                            console.warn('Autoplay blocked by browser:', err);
                        });
                    }
                }
            };

            window.stopOutgoingRingtone = function() {
                const audioEl = document.getElementById('outgoingRingtone');
                if (audioEl) {
                    audioEl.pause();
                    audioEl.currentTime = 0;
                }
            };

            // ✅ Incoming Ringtone Controls
            window.playIncomingRingtone = function() {
                const audioEl = document.getElementById('incomingRingtone');
                if (audioEl) {
                    audioEl.currentTime = 0;
                    const playPromise = audioEl.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(err => {
                            console.warn('Incoming autoplay blocked, waiting for interaction:', err);
                            const unlockAudio = () => {
                                audioEl.play().catch(() => {});
                                document.removeEventListener('click', unlockAudio);
                                document.removeEventListener('touchstart', unlockAudio);
                            };
                            document.addEventListener('click', unlockAudio);
                            document.addEventListener('touchstart', unlockAudio);
                        });
                    }
                }
            };

            window.stopIncomingRingtone = function() {
                const audioEl = document.getElementById('incomingRingtone');
                if (audioEl) {
                    audioEl.pause();
                    audioEl.currentTime = 0;
                }
            };

            // ✅ Hangup Audio Controls (ဖုန်းချချိန် အသံမြည်ရန်)
            window.playHangupAudio = function() {
                const audioEl = document.getElementById('hangupAudio');
                if (audioEl) {
                    audioEl.currentTime = 0;
                    const playPromise = audioEl.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(err => {
                            console.warn('Hangup audio play blocked:', err);
                        });
                    }
                }
            };

            // ✅ Network Quality Indicator
            window.startNetworkMonitoring = function() {
                if (window.networkStatsInterval) clearInterval(window.networkStatsInterval);

                window.networkStatsInterval = setInterval(async () => {
                    if (!window.rtcPeerConnection) return;

                    try {
                        const stats = await window.rtcPeerConnection.getStats();
                        let rtt = null;

                        stats.forEach(report => {
                            if (report.type === 'remote-candidate-pair' || report.type === 'candidate-pair') {
                                if (report.currentRoundTripTime) {
                                    rtt = report.currentRoundTripTime * 1000;
                                }
                            }
                        });

                        const badge = document.getElementById('networkBadge');
                        const text = document.getElementById('networkText');

                        if (badge && text && rtt !== null) {
                            if (rtt < 120) {
                                text.textContent = `HD Quality (${Math.round(rtt)}ms)`;
                                badge.style.color = '#34d399';
                                badge.style.borderColor = 'rgba(52, 211, 153, 0.3)';
                            } else if (rtt < 280) {
                                text.textContent = `Good Connection (${Math.round(rtt)}ms)`;
                                badge.style.color = '#fba518';
                                badge.style.borderColor = 'rgba(251, 165, 24, 0.3)';
                            } else {
                                text.textContent = `Weak Network (${Math.round(rtt)}ms)`;
                                badge.style.color = '#ef4444';
                                badge.style.borderColor = 'rgba(239, 68, 68, 0.3)';
                            }
                        }
                    } catch (e) {
                        console.warn('Network stats error:', e);
                    }
                }, 2000);
            };

            window.stopNetworkMonitoring = function() {
                if (window.networkStatsInterval) {
                    clearInterval(window.networkStatsInterval);
                    window.networkStatsInterval = null;
                }
            };

            // ✅ Timer Functions
            window.startTimer = function() {
                window.stopOutgoingRingtone(); 
                window.stopIncomingRingtone();

                if (window.isTimerRunning) return;
                
                window.isTimerRunning = true;
                window.callSeconds = 0;
                
                const timerEl = document.getElementById('callTimer');
                const statusTextEl = document.getElementById('callStatusText');

                if (timerEl) {
                    timerEl.style.display = 'block';
                    timerEl.textContent = '00:00';
                }
                if (statusTextEl) {
                    statusTextEl.textContent = 'CONNECTED';
                }

                if (window.callTimerInterval) {
                    clearInterval(window.callTimerInterval);
                }

                window.callTimerInterval = setInterval(() => {
                    window.callSeconds++;
                    const mins = String(Math.floor(window.callSeconds / 60)).padStart(2, '0');
                    const secs = String(window.callSeconds % 60).padStart(2, '0');
                    const timerEl = document.getElementById('callTimer');
                    if (timerEl) {
                        timerEl.textContent = `${mins}:${secs}`;
                    }
                }, 1000);

                window.startNetworkMonitoring();
            };

            window.stopTimer = function() {
                window.isTimerRunning = false;
                
                if (window.callTimerInterval) {
                    clearInterval(window.callTimerInterval);
                    window.callTimerInterval = null;
                }
                window.callSeconds = 0;
                
                const timerEl = document.getElementById('callTimer');
                if (timerEl) {
                    timerEl.style.display = 'none';
                    timerEl.textContent = '00:00';
                }

                window.stopNetworkMonitoring();
            };

            // ✅ Media Streams Handling (Audio / Video)
            window.playRemoteAudio = function() {
                const audioEl = document.getElementById('remoteAudio');
                const remoteVideoEl = document.getElementById('remoteVideo');

                if (window.remoteStream) {
                    if (audioEl) {
                        audioEl.srcObject = window.remoteStream;
                        audioEl.volume = 1.0;
                        audioEl.play().catch(() => {});
                    }
                    if (remoteVideoEl) {
                        remoteVideoEl.srcObject = window.remoteStream;
                        remoteVideoEl.play().catch(() => {});
                    }
                }
            };

            // ✅ Media Stream Setup (Voice / Video)
            window.setupMediaStream = async function(isVideo = false) {
                try {
                    if (window.rtcLocalStream) {
                        window.rtcLocalStream.getTracks().forEach(t => t.stop());
                        window.rtcLocalStream = null;
                    }
                    
                    window.rtcLocalStream = await navigator.mediaDevices.getUserMedia({ 
                        audio: {
                            echoCancellation: true,
                            noiseSuppression: true,
                            autoGainControl: true
                        }, 
                        video: isVideo ? { width: { ideal: 1280 }, height: { ideal: 720 }, facingMode: "user" } : false 
                    });

                    const localVideoEl = document.getElementById('localVideo');
                    if (isVideo && localVideoEl) {
                        localVideoEl.srcObject = window.rtcLocalStream;
                    }
                    
                    return true;
                } catch (err) {
                    console.error('Media stream error:', err);
                    alert('Permission is required for Call!');
                    return false;
                }
            };

            // ✅ Peer Connection
            window.createPeerConnection = function(targetUserId, callId) {
                if (window.rtcPeerConnection) {
                    window.rtcPeerConnection.close();
                    window.rtcPeerConnection = null;
                }

                window.rtcPeerConnection = new RTCPeerConnection(rtcConfig);

                window.rtcPeerConnection.ontrack = (event) => {
                    window.remoteStream = event.streams[0];
                    setTimeout(() => window.playRemoteAudio(), 100);
                };

                if (window.rtcLocalStream) {
                    window.rtcLocalStream.getTracks().forEach(track => {
                        window.rtcPeerConnection.addTrack(track, window.rtcLocalStream);
                    });
                }

                window.rtcPeerConnection.onicecandidate = (event) => {
                    if (event.candidate) {
                        Livewire.dispatch('send-webrtc-signal', {
                            callId: callId,
                            toUserId: targetUserId,
                            type: 'candidate',
                            sdpData: event.candidate
                        });
                    }
                };

                window.rtcPeerConnection.oniceconnectionstatechange = () => {
                    const state = window.rtcPeerConnection?.iceConnectionState;
                    if (state === 'connected' || state === 'completed') {
                        if (!window.isTimerRunning) {
                            window.startTimer();
                        }
                    }
                };

                window.rtcPeerConnection.onconnectionstatechange = () => {
                    const state = window.rtcPeerConnection?.connectionState;
                    if (state === 'failed' || state === 'disconnected' || state === 'closed') {
                        window.playHangupAudio();
                        window.closePeerConnection();
                        Livewire.dispatch('force-dismiss-call');
                    }
                };

                return window.rtcPeerConnection;
            };

            window.processPendingCandidates = async function() {
                while (window.pendingCandidates.length > 0) {
                    const cand = window.pendingCandidates.shift();
                    if (window.rtcPeerConnection) {
                        try {
                            await window.rtcPeerConnection.addIceCandidate(new RTCIceCandidate(cand));
                        } catch (e) {
                            console.warn('Candidate error:', e);
                        }
                    }
                }
            };

            // ✅ Close Connection & Dismiss UI
            window.closePeerConnection = function() {
                window.stopOutgoingRingtone();
                window.stopIncomingRingtone();

                if (window.rtcPeerConnection) {
                    window.rtcPeerConnection.close();
                    window.rtcPeerConnection = null;
                }
                if (window.rtcLocalStream) {
                    window.rtcLocalStream.getTracks().forEach(t => t.stop());
                    window.rtcLocalStream = null;
                }
                if (window.remoteStream) {
                    window.remoteStream.getTracks().forEach(t => t.stop());
                    window.remoteStream = null;
                }
                
                window.pendingCandidates = [];
                window.pendingOffer = null;
                
                const audioEl = document.getElementById('remoteAudio');
                if (audioEl) {
                    audioEl.srcObject = null;
                    audioEl.pause();
                }

                const remoteVideoEl = document.getElementById('remoteVideo');
                if (remoteVideoEl) {
                    remoteVideoEl.srcObject = null;
                }

                const localVideoEl = document.getElementById('localVideo');
                if (localVideoEl) {
                    localVideoEl.srcObject = null;
                }
                
                window.stopTimer();
            };

            window.toggleSpeaker = async function() {
                const audioEl = document.getElementById('remoteAudio');
                if (audioEl && typeof audioEl.setSinkId === 'function') {
                    try {
                        const devices = await navigator.mediaDevices.enumerateDevices();
                        const audioOutputs = devices.filter(d => d.kind === 'audiooutput');
                        if (audioOutputs.length > 0) {
                            const currentSinkId = audioEl.sinkId;
                            const targetDevice = audioOutputs.find(d => d.deviceId !== currentSinkId) || audioOutputs[0];
                            await audioEl.setSinkId(targetDevice.deviceId);
                        }
                    } catch (err) {
                        console.warn('Speaker error:', err);
                    }
                }
            };

            // ✅ Echo Listeners
            function initEchoListeners() {
                const authUserId = @json(auth()->id());
                
                if (typeof window.Echo !== 'undefined' && authUserId && !window.echoVoiceSubscribed) {
                    window.echoVoiceSubscribed = true;
                    
                    window.Echo.private(`user.${authUserId}`)
                        .listen('.voice.call', async (e) => {

                            if (e.type === 'offer') {
                                window.pendingOffer = e.sdpData;
                                window.playIncomingRingtone();
                                
                                Livewire.dispatch('incoming-voice-call', {
                                    callId: e.callId,
                                    fromUser: e.fromUser,
                                    callType: e.callType || e.type || 'voice'
                                });
                            } else if (e.type === 'answer') {
                                window.stopOutgoingRingtone();
                                window.stopIncomingRingtone();
                                if (window.rtcPeerConnection) {
                                    try {
                                        await window.rtcPeerConnection.setRemoteDescription(
                                            new RTCSessionDescription(e.sdpData)
                                        );
                                        await window.processPendingCandidates();
                                        if (!window.isTimerRunning) {
                                            window.startTimer();
                                        }
                                    } catch (err) {
                                        console.error('Answer error:', err);
                                    }
                                }
                            } else if (e.type === 'candidate') {
                                if (window.rtcPeerConnection && window.rtcPeerConnection.remoteDescription) {
                                    try {
                                        await window.rtcPeerConnection.addIceCandidate(
                                            new RTCIceCandidate(e.sdpData)
                                        );
                                    } catch (err) {
                                        console.warn('ICE error:', err);
                                    }
                                } else {
                                    window.pendingCandidates.push(e.sdpData);
                                }
                            } else if (e.type === 'end' || e.type === 'reject') {
                                window.playHangupAudio();
                                window.closePeerConnection();
                                Livewire.dispatch('force-dismiss-call');
                            }
                        });
                }
            }

            // ✅ Livewire Event Handlers
            document.addEventListener('livewire:initialized', () => {
                initEchoListeners();

                Livewire.on('initiate-webrtc-caller', async (event) => {
                    const callInfo = Array.isArray(event) ? event[0] : event;
                    const isVideo = (callInfo.type === 'video');
                    
                    window.playOutgoingRingtone();

                    const success = await window.setupMediaStream(isVideo);
                    if (!success) {
                        window.stopOutgoingRingtone();
                        return;
                    }
                    
                    window.createPeerConnection(callInfo.receiverId, callInfo.callId);

                    const offer = await window.rtcPeerConnection.createOffer({
                        offerToReceiveAudio: true,
                        offerToReceiveVideo: isVideo
                    });
                    await window.rtcPeerConnection.setLocalDescription(offer);

                    Livewire.dispatch('send-webrtc-signal', {
                        callId: callInfo.callId,
                        toUserId: callInfo.receiverId,
                        type: 'offer',
                        sdpData: offer
                    });
                });

                Livewire.on('webrtc-accept-call', async (event) => {
                    window.stopOutgoingRingtone();
                    window.stopIncomingRingtone();

                    const callInfo = Array.isArray(event) ? event[0] : event;
                    const isVideo = (callInfo.type === 'video');

                    // UI တန်ဖိုးများကို dynamic ချိန်ညှိခြင်း
                    const videoContainer = document.getElementById('videoContainer');
                    const avatarContainer = document.getElementById('avatarContainer');

                    if (isVideo) {
                        if (videoContainer) videoContainer.style.display = 'block';
                        if (avatarContainer) avatarContainer.style.display = 'none';
                    } else {
                        if (videoContainer) videoContainer.style.display = 'none';
                        if (avatarContainer) avatarContainer.style.display = 'flex';
                    }
                    
                    const success = await window.setupMediaStream(isVideo);
                    if (!success) return;
                    
                    window.createPeerConnection(callInfo.callerId, callInfo.callId);

                    if (window.pendingOffer) {
                        try {
                            await window.rtcPeerConnection.setRemoteDescription(
                                new RTCSessionDescription(window.pendingOffer)
                            );
                            await window.processPendingCandidates();

                            const answer = await window.rtcPeerConnection.createAnswer({
                                offerToReceiveAudio: true,
                                offerToReceiveVideo: isVideo
                            });
                            await window.rtcPeerConnection.setLocalDescription(answer);

                            Livewire.dispatch('send-webrtc-signal', {
                                callId: callInfo.callId,
                                toUserId: callInfo.callerId,
                                type: 'answer',
                                sdpData: answer
                            });

                            if (!window.isTimerRunning) {
                                window.startTimer();
                            }
                            
                            setTimeout(() => {
                                window.playRemoteAudio();
                            }, 500);
                        } catch (err) {
                            console.error('Accept error:', err);
                        }
                    }
                });

                Livewire.on('toggle-audio-mute', (event) => {
                    const muteInfo = Array.isArray(event) ? event[0] : event;
                    if (window.rtcLocalStream) {
                        window.rtcLocalStream.getAudioTracks().forEach(track => {
                            track.enabled = !muteInfo.isMuted;
                        });
                    }
                });

                Livewire.on('toggle-speaker', async () => {
                    await window.toggleSpeaker();
                });

                Livewire.on('webrtc-terminate', () => {
                    window.playHangupAudio();
                    window.closePeerConnection();
                    Livewire.dispatch('force-dismiss-call');
                });
            });

            document.addEventListener('livewire:navigating', () => {
                window.closePeerConnection();
            });

        })();
    </script>
</div>
