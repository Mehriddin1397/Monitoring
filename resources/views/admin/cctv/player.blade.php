<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $camera['name'] ?? 'Kamera' }} - CCTV Jonli efir</title>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body {
            background: #020617;
            overflow: hidden;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #ffffff;
        }
        #player-container {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000000;
        }
        video {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #000000;
        }
        .header-overlay {
            position: absolute;
            top: 10px;
            left: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(6px);
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            pointer-events: none;
            z-index: 20;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .live-dot {
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            box-shadow: 0 0 8px #ef4444;
            animation: pulse-dot 1.5s infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.9); }
        }
        .controls-overlay {
            position: absolute;
            bottom: 10px;
            right: 12px;
            display: flex;
            gap: 6px;
            z-index: 20;
        }
        .ctrl-btn {
            background: rgba(15, 23, 42, 0.75);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 11px;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .ctrl-btn:hover {
            background: rgba(59, 130, 246, 0.85);
            border-color: #3b82f6;
        }
        .loader-wrap {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            z-index: 10;
            text-align: center;
            color: #94a3b8;
            font-size: 13px;
        }
        .spinner {
            width: 36px;
            height: 36px;
            border: 3px solid rgba(255, 255, 255, 0.1);
            border-top: 3px solid #3b82f6;
            border-radius: 50%;
            animation: spin 0.9s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div id="player-container">
        <!-- Live Header Overlay -->
        <div class="header-overlay">
            <span class="live-dot"></span>
            <span style="color:#ef4444; font-weight:700; font-size:11px; letter-spacing:0.5px;">JONLI</span>
            <span>{{ $camera['name'] ?? 'Kamera' }}</span>
        </div>

        <!-- Video Element -->
        <video id="cctvVideo" autoplay playsinline muted></video>

        <!-- Loader -->
        <div id="loader" class="loader-wrap">
            <div class="spinner"></div>
            <div id="loaderText">Video oqimiga ulanmoqda...</div>
        </div>

        <!-- Floating Controls -->
        <div class="controls-overlay">
            <button type="button" class="ctrl-btn" onclick="toggleMute()" id="muteBtn" title="Ovoz">
                🔇 Ovoz
            </button>
            <button type="button" class="ctrl-btn" onclick="retryStream()" title="Qayta yuklash">
                🔄 Qayta
            </button>
            <button type="button" class="ctrl-btn" onclick="toggleFullscreen()" title="To'liq ekran">
                ⛶ Katta
            </button>
        </div>
    </div>

    <script>
        const video = document.getElementById('cctvVideo');
        const loader = document.getElementById('loader');
        const loaderText = document.getElementById('loaderText');
        const muteBtn = document.getElementById('muteBtn');

        const streamName = "{{ $streamName }}";
        const token = "{{ $token }}";

        // Build URLs (Use local proxy if hosted on HTTPS, fallback to direct LAN IP if on local dev)
        const isHttps = window.location.protocol === 'https:';
        const origin = window.location.origin;

        const webrtcUrl = isHttps 
            ? `${origin}/cctv-webrtc/${streamName}/whep?token=${token}`
            : `http://192.168.40.123:8889/${streamName}/whep?token=${token}`;

        const hlsUrl = isHttps
            ? `${origin}/cctv-hls/${streamName}/index.m3u8?token=${token}`
            : `http://192.168.40.123:8888/${streamName}/index.m3u8?token=${token}`;

        let pc = null;
        let hlsInstance = null;

        async function startWebRTC() {
            try {
                if (pc) {
                    pc.close();
                    pc = null;
                }

                pc = new RTCPeerConnection({
                    iceServers: [{ urls: 'stun:stun.l.google.com:19302' }]
                });

                pc.addTransceiver('video', { direction: 'recvonly' });
                pc.addTransceiver('audio', { direction: 'recvonly' });

                pc.ontrack = (event) => {
                    video.srcObject = event.streams[0];
                    video.play().then(() => {
                        if (loader) loader.style.display = 'none';
                    }).catch(() => {});
                };

                const offer = await pc.createOffer();
                await pc.setLocalDescription(offer);

                const response = await fetch(webrtcUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/sdp' },
                    body: offer.sdp
                });

                if (!response.ok) {
                    throw new Error('WebRTC WHEP ulanmadi, HLS ga o\'tiladi (HTTP ' + response.status + ')');
                }

                const answerSdp = await response.text();
                await pc.setRemoteDescription(new RTCSessionDescription({
                    type: 'answer',
                    sdp: answerSdp
                }));
            } catch (err) {
                console.warn('WebRTC fallback to HLS:', err.message);
                startHls();
            }
        }

        function startHls() {
            if (hlsInstance) {
                hlsInstance.destroy();
                hlsInstance = null;
            }

            if (Hls.isSupported()) {
                hlsInstance = new Hls({
                    lowLatencyMode: true,
                    backBufferLength: 30,
                    enableWorker: true
                });

                hlsInstance.loadSource(hlsUrl);
                hlsInstance.attachMedia(video);

                hlsInstance.on(Hls.Events.MANIFEST_PARSED, () => {
                    video.play().then(() => {
                        if (loader) loader.style.display = 'none';
                    }).catch(() => {});
                });

                hlsInstance.on(Hls.Events.ERROR, (event, data) => {
                    if (data.fatal) {
                        console.warn('Fatal HLS error, retrying in 3s:', data);
                        if (loaderText) loaderText.innerText = 'Qayta ulanmoqda...';
                        setTimeout(startHls, 3000);
                    }
                });
            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = hlsUrl;
                video.addEventListener('loadedmetadata', () => {
                    video.play().then(() => {
                        if (loader) loader.style.display = 'none';
                    }).catch(() => {});
                });
            }
        }

        function toggleMute() {
            video.muted = !video.muted;
            if (muteBtn) {
                muteBtn.innerText = video.muted ? "🔇 Ovoz" : "🔊 Ovoz";
            }
        }

        function toggleFullscreen() {
            const container = document.getElementById('player-container');
            if (!document.fullscreenElement) {
                if (container.requestFullscreen) {
                    container.requestFullscreen();
                } else if (container.webkitRequestFullscreen) {
                    container.webkitRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        function retryStream() {
            if (loader) loader.style.display = 'flex';
            if (loaderText) loaderText.innerText = 'Qayta yuklanmoqda...';
            startWebRTC();
        }

        // Auto start
        startWebRTC();
    </script>
</body>
</html>
