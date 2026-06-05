<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Туғилган Кунлар</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;900&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "void": "#000000",
                        "obsidian": "#0a0a0f",
                        "gold": "#d4af37",
                        "gold-light": "#f5d76e",
                        "gold-dark": "#8b7500",
                        "gold-bright": "#ffd700",
                    },
                    fontFamily: {
                        "display": ["Playfair Display", "serif"],
                        "serif": ["PT Serif", "serif"],
                        "sans": ["Inter", "sans-serif"],
                    },
                }
            },
        }
    </script>

    <style>
        * { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }

        html, body {
            background: #000000;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            min-height: 100dvh;
            overflow-x: hidden;
        }

        body {
            background: radial-gradient(ellipse at center, #0a0a0f 0%, #000000 100%);
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300;
        }

        /* === GOLD GRADIENT TEXT === */
        .gold-text {
            background: linear-gradient(135deg, #f5d76e 0%, #d4af37 30%, #ffd700 50%, #d4af37 70%, #f5d76e 100%);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer-gold 5s ease-in-out infinite;
            display: inline-block;
        }

        .silver-text {
            background: linear-gradient(135deg, #ffffff 0%, #e8e8e8 30%, #ffffff 50%, #d4d4d4 70%, #ffffff 100%);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer-gold 6s ease-in-out infinite;
        }

        @keyframes shimmer-gold {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* === ANIMATED GOLD RING === */
        .gold-ring {
            position: relative;
            background: conic-gradient(from 0deg, #8b7500, #f5d76e, #ffd700, #d4af37, #f5d76e, #8b7500, #d4af37, #f5d76e);
            padding: 5px;
            border-radius: 50%;
            animation: spin-slow 8s linear infinite;
            aspect-ratio: 1 / 1;
            width: 100%;
        }

        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .gold-ring-inner {
            border-radius: 50%;
            overflow: hidden;
            background: #000;
            padding: 2px;
            width: 100%;
            height: 100%;
        }

        /* === FALLING CONFETTI === */
        .confetti-piece {
            position: fixed;
            top: -50px;
            pointer-events: none;
            will-change: transform;
        }

        @keyframes confetti-fall {
            0% { transform: translateY(-100vh) rotate(0deg) rotateX(0deg); opacity: 0; }
            8% { opacity: 1; }
            92% { opacity: 1; }
            100% { transform: translateY(110vh) rotate(720deg) rotateX(360deg); opacity: 0; }
        }

        @keyframes confetti-sway {
            0%, 100% { margin-left: 0; }
            25% { margin-left: 40px; }
            50% { margin-left: -30px; }
            75% { margin-left: 25px; }
        }

        /* === RIBBONS === */
        .ribbon-svg {
            position: fixed;
            pointer-events: none;
            z-index: 5;
            filter: drop-shadow(0 6px 20px rgba(0, 0, 0, 0.7));
            /* Responsive size */
            width: clamp(160px, 28vw, 380px);
            height: clamp(160px, 28vw, 380px);
        }

        /* === SCENES === */
        .scene-wrapper {
            position: relative;
            width: 100%;
            min-height: 100vh;
            min-height: 100dvh;
        }

        .scene {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
            transform: scale(1);
            transition: opacity 0.8s ease, transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        .scene-hidden {
            opacity: 0;
            pointer-events: none;
            transform: scale(0.98);
        }

        @keyframes glow-pulse {
            0%, 100% { filter: drop-shadow(0 0 40px rgba(212, 175, 55, 0.4)); }
            50% { filter: drop-shadow(0 0 80px rgba(212, 175, 55, 0.6)); }
        }

        @keyframes balloon-float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
        }

        @keyframes balloon-float-delayed {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(-3deg); }
        }

        @keyframes star-twinkle {
            0%, 100% { opacity: 0.6; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.3); }
        }

        @keyframes star-burst {
            0% { opacity: 0; transform: scale(0) rotate(0deg); }
            50% { opacity: 1; transform: scale(1.4) rotate(180deg); }
            100% { opacity: 0.8; transform: scale(1) rotate(360deg); }
        }

        .animate-glow { animation: glow-pulse 4s ease-in-out infinite; }
        .animate-balloon-1 { animation: balloon-float 5s ease-in-out infinite; }
        .animate-balloon-2 { animation: balloon-float-delayed 6s ease-in-out infinite 0.5s; }
        .animate-twinkle { animation: star-twinkle 2.5s ease-in-out infinite; }
        .animate-burst { animation: star-burst 3s ease-in-out infinite; }

        .name-shadow {
            text-shadow:
                0 0 30px rgba(212, 175, 55, 0.4),
                0 4px 20px rgba(0, 0, 0, 0.8);
        }

        .wish-shadow {
            text-shadow:
                0 0 40px rgba(255, 255, 255, 0.15),
                0 4px 20px rgba(0, 0, 0, 0.9);
        }

        /* === NAV DOTS === */
        .nav-dot {
            transition: all 0.4s ease;
            cursor: pointer;
            flex-shrink: 0;
        }
        .nav-dot.active {
            background: linear-gradient(135deg, #f5d76e, #d4af37);
            width: 28px;
            box-shadow: 0 0 12px rgba(212, 175, 55, 0.6);
        }

        /* === NAV DOTS WRAPPER (scrollable when many) === */
        .nav-dots-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            max-width: min(60vw, 400px);
            overflow-x: auto;
            scrollbar-width: none;
            padding: 4px 2px;
            scroll-behavior: smooth;
        }
        .nav-dots-wrapper::-webkit-scrollbar { display: none; }

        /* === FLUID PORTRAIT SIZE === */
        .portrait-frame {
            width: clamp(180px, 26vw, 360px);
            max-width: 100%;
        }

        /* === FLUID TYPE SCALE (kichraytirilgan, balanced) === */
        .text-fluid-greeting { font-size: clamp(1.25rem, 1.5vw + 0.75rem, 2.25rem); line-height: 1.1; }
        .text-fluid-name { font-size: clamp(1.5rem, 2vw + 0.75rem, 2.75rem); line-height: 1.15; }
        .text-fluid-role { font-size: clamp(0.85rem, 0.6vw + 0.6rem, 1.15rem); line-height: 1.3; letter-spacing: 0.02em; }
        .text-fluid-title { font-size: clamp(1.5rem, 2.5vw + 0.5rem, 3.25rem); line-height: 1.1; }
        .text-fluid-wish { font-size: clamp(1.25rem, 1.8vw + 0.5rem, 2.5rem); line-height: 1.2; }

        /* Wider portrait area on very large screens — keep balance */
        @media (min-width: 1280px) {
            .text-fluid-greeting { font-size: clamp(1.75rem, 1.2vw + 1rem, 2.5rem); }
            .text-fluid-name { font-size: clamp(2rem, 1.5vw + 1rem, 3rem); }
            .text-fluid-title { font-size: clamp(2rem, 2vw + 1rem, 3.75rem); }
            .text-fluid-wish { font-size: clamp(1.5rem, 1.5vw + 0.75rem, 2.75rem); }
        }

        /* === BALLOON SIZES === */
        .balloon-big {
            width: clamp(130px, 16vw, 240px);
            height: clamp(130px, 16vw, 240px);
        }
        .balloon-small {
            width: clamp(95px, 11vw, 170px);
            height: clamp(95px, 11vw, 170px);
        }

        /* === LAYOUT BREAKPOINTS === */
        @media (max-width: 1023px) {
            .scene-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            .portrait-area, .text-area {
                grid-column: 1;
            }
        }

        /* Long name handling */
        .truncate-name {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            word-break: break-word;
            hyphens: auto;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* === HEADER LAYOUT === */
        .site-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 30;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: flex-end;
        }

        @media (max-width: 640px) {
            .site-header {
                padding: 0.75rem 1rem;
                justify-content: center;
            }
            .header-text { display: none; }
            .header-logo { width: 48px; height: 48px; }
        }

        /* === BOTTOM NAV === */
        .bottom-nav {
            position: fixed;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 30;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 9999px;
            backdrop-filter: blur(12px);
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(212, 175, 55, 0.3);
            max-width: calc(100vw - 2rem);
        }

        @media (max-width: 640px) {
            .bottom-nav { bottom: 0.5rem; padding: 0.4rem 0.6rem; }
        }

        /* Hide scene B balloons positioning on tiny screens */
        @media (max-width: 640px) {
            .balloons-area {
                position: relative;
                height: clamp(200px, 35vh, 320px);
            }
        }
    </style>
</head>

<body class="font-sans text-white selection:bg-gold/40">

@php
    $firstEmployee = $birthdayEmployees->first();
    $monthsCy = ['Январ','Феврал','Март','Апрел','Май','Июн','Июл','Август','Сентябр','Октябр','Ноябр','Декабр'];
    $todayDateCy = now()->day.'-'.$monthsCy[now()->month - 1].' '.now()->year.'-йил';
    $employeeCount = $birthdayEmployees->count();
    $showDotsAsList = $employeeCount <= 12; // After 12, switch to compact arrow nav
@endphp

{{-- ===== SVG DEFINITIONS ===== --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <defs>
        <linearGradient id="gold1" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#f5d76e"/>
            <stop offset="30%" stop-color="#ffd700"/>
            <stop offset="60%" stop-color="#d4af37"/>
            <stop offset="100%" stop-color="#8b7500"/>
        </linearGradient>
        <linearGradient id="gold2" x1="100%" y1="0%" x2="0%" y2="100%">
            <stop offset="0%" stop-color="#ffd700"/>
            <stop offset="50%" stop-color="#d4af37"/>
            <stop offset="100%" stop-color="#8b7500"/>
        </linearGradient>
        <radialGradient id="goldStar" cx="35%" cy="30%">
            <stop offset="0%" stop-color="#fff8dc"/>
            <stop offset="30%" stop-color="#f5d76e"/>
            <stop offset="70%" stop-color="#d4af37"/>
            <stop offset="100%" stop-color="#8b7500"/>
        </radialGradient>
        <radialGradient id="goldStarShine" cx="40%" cy="35%">
            <stop offset="0%" stop-color="#ffffff" stop-opacity="0.9"/>
            <stop offset="30%" stop-color="#ffd700" stop-opacity="0.4"/>
            <stop offset="100%" stop-color="transparent"/>
        </radialGradient>
    </defs>
</svg>

{{-- ===== GOLD RIBBONS IN 4 CORNERS ===== --}}
<svg class="ribbon-svg" style="top: 0; left: 0;" viewBox="0 0 320 320">
    <path d="M -20 -10 Q 80 60, 60 160 T 100 290" stroke="url(#gold1)" stroke-width="16" fill="none" stroke-linecap="round" opacity="0.9"/>
    <path d="M -10 -20 Q 120 40, 140 130 T 180 250" stroke="url(#gold2)" stroke-width="12" fill="none" stroke-linecap="round" opacity="0.75"/>
    <path d="M 0 -20 Q 50 100, 180 80 T 290 130" stroke="url(#gold1)" stroke-width="13" fill="none" stroke-linecap="round" opacity="0.8"/>
    <path d="M 20 -20 Q 90 20, 160 50 T 280 60" stroke="url(#gold2)" stroke-width="9" fill="none" stroke-linecap="round" opacity="0.65"/>
</svg>

<svg class="ribbon-svg" style="top: 0; right: 0; transform: scaleX(-1);" viewBox="0 0 320 320">
    <path d="M -20 -10 Q 80 60, 60 160 T 100 290" stroke="url(#gold1)" stroke-width="14" fill="none" stroke-linecap="round" opacity="0.8"/>
    <path d="M -10 -20 Q 120 40, 140 130 T 180 250" stroke="url(#gold2)" stroke-width="10" fill="none" stroke-linecap="round" opacity="0.65"/>
    <path d="M 0 -20 Q 50 100, 180 80 T 290 130" stroke="url(#gold1)" stroke-width="11" fill="none" stroke-linecap="round" opacity="0.7"/>
</svg>

<svg class="ribbon-svg" style="bottom: 0; left: 0; transform: scaleY(-1);" viewBox="0 0 320 320">
    <path d="M -20 -10 Q 80 60, 60 160 T 100 290" stroke="url(#gold1)" stroke-width="17" fill="none" stroke-linecap="round" opacity="0.9"/>
    <path d="M -10 -20 Q 120 40, 140 130 T 180 250" stroke="url(#gold2)" stroke-width="12" fill="none" stroke-linecap="round" opacity="0.75"/>
    <path d="M 0 -20 Q 50 100, 180 80 T 290 130" stroke="url(#gold1)" stroke-width="14" fill="none" stroke-linecap="round" opacity="0.8"/>
</svg>

<svg class="ribbon-svg" style="bottom: 0; right: 0; transform: scale(-1, -1);" viewBox="0 0 320 320">
    <path d="M -20 -10 Q 80 60, 60 160 T 100 290" stroke="url(#gold1)" stroke-width="15" fill="none" stroke-linecap="round" opacity="0.85"/>
    <path d="M -10 -20 Q 120 40, 140 130 T 180 250" stroke="url(#gold2)" stroke-width="11" fill="none" stroke-linecap="round" opacity="0.7"/>
    <path d="M 0 -20 Q 50 100, 180 80 T 290 130" stroke="url(#gold1)" stroke-width="12" fill="none" stroke-linecap="round" opacity="0.75"/>
</svg>

{{-- ===== FALLING CONFETTI ===== --}}
<div class="fixed inset-0 pointer-events-none z-[3] overflow-hidden" id="confetti-container"></div>

{{-- ===== HEADER ===== --}}
<header class="site-header">
    <div class="flex items-center gap-3 md:gap-4">
        <div class="p-[2px] rounded-full bg-gradient-to-br from-gold-light via-gold to-gold-dark shadow-2xl">
            <div class="p-[2px] rounded-full bg-black">
                <img src="{{ asset('assets/images/1111222.png') }}"
                     alt="Logo"
                     class="header-logo w-12 h-12 md:w-16 md:h-16 lg:w-20 lg:h-20 rounded-full object-cover"/>
            </div>
        </div>
        <div class="header-text text-white">
            <p class="font-serif text-xs md:text-base lg:text-lg leading-tight tracking-wide">Ўзбекистон Республикаси</p>
            <p class="font-serif text-xs md:text-base lg:text-lg leading-tight tracking-wide gold-text">Криминология тадқиқот институти</p>
        </div>
    </div>
</header>

{{-- ===== MAIN STAGE ===== --}}
<main class="scene-wrapper">

    @if($firstEmployee)
        {{-- ============ SCENE A: PORTRAIT + NAME ============ --}}
        <section id="scene-a" class="scene">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 md:px-12 lg:px-16 pt-24 pb-24 lg:py-8">
                <div class="scene-grid grid grid-cols-1 lg:grid-cols-[auto_1fr] gap-6 sm:gap-10 lg:gap-16 items-center">

                    {{-- LEFT: PORTRAIT --}}
                    <div class="portrait-area flex justify-center lg:justify-start">
                        <div class="relative portrait-frame">
                            {{-- Halo glow --}}
                            <div class="absolute inset-0 bg-gold/30 rounded-full blur-[60px] scale-110"></div>

                            {{-- Gold ring --}}
                            <div class="relative gold-ring animate-glow">
                                <div class="gold-ring-inner">
                                    <div class="w-full h-full rounded-full overflow-hidden bg-obsidian">
                                        <img id="featured-image"
                                             src="{{ $firstEmployee->photo ? asset('storage/' . $firstEmployee->photo) : 'https://ui-avatars.com/api/?name='.urlencode($firstEmployee->full_name).'&size=512&background=0a0a0f&color=d4af37&bold=true&format=png' }}"
                                             alt="{{ $firstEmployee->full_name }}"
                                             class="w-full h-full object-cover"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT: TEXT --}}
                    <div class="text-area text-center lg:text-left space-y-3 md:space-y-4 lg:space-y-5 min-w-0">
                        <p class="font-display font-normal text-fluid-greeting text-white/95 tracking-wide name-shadow">
                            Ҳурматли
                        </p>
                        <h2 id="featured-name" class="font-display font-bold text-fluid-name leading-tight name-shadow truncate-name">
                            <span class="gold-text">{{ $firstEmployee->full_name }}</span>
                        </h2>

                        <div class="flex items-center justify-center lg:justify-start gap-3 py-2 md:py-3">
                            <span class="h-px w-12 md:w-20 bg-gradient-to-r from-transparent to-gold/60"></span>
                            <span class="material-symbols-outlined text-gold text-xl md:text-2xl" style="font-variation-settings: 'FILL' 1;">stars</span>
                            <span class="h-px w-12 md:w-20 bg-gradient-to-l from-transparent to-gold/60"></span>
                        </div>
                        <h1 class="font-display font-bold text-fluid-title leading-[1.1] text-white name-shadow">
                            Туғилган кунингиз<br/>
                            <span class="gold-text">муборак бўлсин!</span>
                        </h1>
                        <div class="pt-2 md:pt-4 flex items-center justify-center lg:justify-start gap-2 text-gold/80">
                            <span class="material-symbols-outlined text-sm md:text-base" style="font-variation-settings: 'FILL' 1;">calendar_month</span>
                            <span class="font-serif italic text-xs md:text-sm lg:text-base tracking-wider">{{ $todayDateCy }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============ SCENE B: WISH + BALLOONS ============ --}}
        <section id="scene-b" class="scene scene-hidden">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 md:px-12 lg:px-16 pt-24 pb-24 lg:py-8">
                <div class="scene-grid grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-10 lg:gap-16 items-center">

                    {{-- LEFT: WISH TEXT --}}
                    <div class="text-center lg:text-left order-2 lg:order-1 space-y-4 md:space-y-6 min-w-0">
                        <div class="flex items-center justify-center lg:justify-start gap-3 mb-2">
                            <svg class="w-8 h-8 md:w-10 md:h-10 animate-twinkle shrink-0" viewBox="0 0 100 100">
                                <polygon points="50,5 61,38 95,38 67,57 78,90 50,70 22,90 33,57 5,38 39,38" fill="url(#goldStar)"/>
                            </svg>
                        </div>

                        <h2 class="font-display font-bold text-fluid-wish leading-[1.15] silver-text wish-shadow">
                            Сизга узоқ умр,<br/>
                            соғлиқ ва иш<br/>
                            фаолиятингизда<br/>
                            <span class="gold-text">улкан муваффақиятлар</span><br/>
                            тилаймиз!
                        </h2>

                        <div class="flex items-center justify-center lg:justify-start gap-3 pt-3 md:pt-6">
                            <span class="h-px w-10 md:w-20 bg-gradient-to-r from-transparent to-gold/60"></span>
                            <span class="font-serif italic text-gold/80 text-[10px] md:text-sm tracking-[0.2em] uppercase">Криминология жамоаси</span>
                            <span class="h-px w-10 md:w-20 bg-gradient-to-l from-transparent to-gold/60"></span>
                        </div>
                    </div>

                    {{-- RIGHT: GOLD STAR BALLOONS --}}
                    <div class="balloons-area flex justify-center lg:justify-end order-1 lg:order-2 relative h-[260px] sm:h-[320px] md:h-[400px] lg:h-[500px] xl:h-[600px]">

                        {{-- Top sparkles --}}
                        <div class="absolute top-0 left-1/4 animate-burst" style="filter: drop-shadow(0 0 12px rgba(255,215,0,0.8));">
                            <svg class="w-10 h-10 md:w-14 md:h-14" viewBox="0 0 100 100">
                                <polygon points="50,5 55,40 95,50 55,60 50,95 45,60 5,50 45,40" fill="url(#goldStarShine)"/>
                            </svg>
                        </div>
                        <div class="absolute top-6 right-4 md:top-12 md:right-12 animate-burst" style="animation-delay: 1s; filter: drop-shadow(0 0 8px rgba(255,215,0,0.6));">
                            <svg class="w-7 h-7 md:w-10 md:h-10" viewBox="0 0 100 100">
                                <polygon points="50,5 55,40 95,50 55,60 50,95 45,60 5,50 45,40" fill="url(#goldStarShine)"/>
                            </svg>
                        </div>

                        {{-- Main star balloon 1 --}}
                        <div class="absolute top-4 right-4 md:top-2 md:right-2 animate-balloon-1" style="filter: drop-shadow(0 20px 40px rgba(212, 175, 55, 0.5));">
                            <svg class="balloon-big" viewBox="0 0 200 200">
                                <polygon points="100,10 130,75 200,75 145,115 165,180 100,140 35,180 55,115 0,75 70,75" fill="url(#goldStar)"/>
                                <polygon points="100,10 130,75 200,75 145,115 165,180 100,140 35,180 55,115 0,75 70,75" fill="url(#goldStarShine)" opacity="0.6"/>
                            </svg>
                            <div class="absolute left-1/2 bottom-0 w-px h-16 md:h-24 lg:h-32 bg-gradient-to-b from-gold-dark via-gold/60 to-transparent -translate-x-1/2"></div>
                        </div>

                        {{-- Star balloon 2 --}}
                        <div class="absolute top-16 right-20 sm:top-24 sm:right-28 md:top-32 md:right-36 lg:top-40 lg:right-44 animate-balloon-2" style="filter: drop-shadow(0 20px 40px rgba(212, 175, 55, 0.4));">
                            <svg class="balloon-small" viewBox="0 0 200 200">
                                <polygon points="100,10 130,75 200,75 145,115 165,180 100,140 35,180 55,115 0,75 70,75" fill="url(#goldStar)"/>
                                <polygon points="100,10 130,75 200,75 145,115 165,180 100,140 35,180 55,115 0,75 70,75" fill="url(#goldStarShine)" opacity="0.5"/>
                            </svg>
                            <div class="absolute left-1/2 bottom-0 w-px h-14 md:h-20 lg:h-28 bg-gradient-to-b from-gold-dark via-gold/60 to-transparent -translate-x-1/2"></div>
                        </div>

                        {{-- Bottom sparkle --}}
                        <div class="absolute bottom-8 left-8 md:bottom-12 md:left-12 animate-burst" style="animation-delay: 2s; filter: drop-shadow(0 0 8px rgba(255,215,0,0.7));">
                            <svg class="w-6 h-6 md:w-9 md:h-9" viewBox="0 0 100 100">
                                <polygon points="50,5 55,40 95,50 55,60 50,95 45,60 5,50 45,40" fill="url(#goldStarShine)"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        {{-- Empty state --}}
        <section class="scene">
            <div class="text-center max-w-2xl px-6">
                <span class="material-symbols-outlined text-gold text-7xl md:text-9xl mb-6 animate-glow inline-block" style="font-variation-settings: 'FILL' 1;">celebration</span>
                <h2 class="font-display text-3xl md:text-5xl lg:text-6xl font-bold gold-text mb-4">Бугун байрам йўқ</h2>
                <p class="font-serif italic text-white/60 text-base md:text-lg lg:text-xl">Бугун туғилган куни билан табрикланадиган ҳамкасблар мавжуд эмас.</p>
            </div>
        </section>
    @endif
</main>

{{-- ===== BOTTOM NAVIGATION ===== --}}
@if($employeeCount > 1)
    <nav class="bottom-nav">
        {{-- Previous arrow (always shown when > 1) --}}
        <button onclick="goToPrev()"
                aria-label="Олдинги"
                class="shrink-0 w-7 h-7 md:w-8 md:h-8 flex items-center justify-center rounded-full bg-gold/10 hover:bg-gold/25 transition-colors text-gold">
            <span class="material-symbols-outlined text-base md:text-lg">chevron_left</span>
        </button>

        @if($showDotsAsList)
            {{-- Dots (only when ≤ 12 employees) --}}
            <div class="nav-dots-wrapper" id="nav-dots">
                @foreach($birthdayEmployees as $index => $employee)
                    <button onclick="updateFeatured({{ $index }})"
                            class="nav-dot w-2 h-2 rounded-full bg-white/30 hover:bg-white/60 {{ $index === 0 ? 'active' : '' }}"
                            data-index="{{ $index }}"
                            aria-label="{{ $employee->full_name }}"></button>
                @endforeach
            </div>
        @endif

        {{-- Counter --}}
        <span class="shrink-0 text-gold/80 text-[11px] md:text-xs font-sans tracking-wider font-semibold whitespace-nowrap">
            <span id="current-num">1</span><span class="text-gold/40 mx-1">/</span>{{ $employeeCount }}
        </span>

        {{-- Next arrow --}}
        <button onclick="goToNext()"
                aria-label="Кейинги"
                class="shrink-0 w-7 h-7 md:w-8 md:h-8 flex items-center justify-center rounded-full bg-gold/10 hover:bg-gold/25 transition-colors text-gold">
            <span class="material-symbols-outlined text-base md:text-lg">chevron_right</span>
        </button>
    </nav>
@endif

<script>
    // ===== EMPLOYEE DATA =====
    const employees = [
            @foreach($birthdayEmployees as $employee)
        {
            name: "{{ addslashes($employee->full_name) }}",
            role: "{{ addslashes($employee->position) }}",
            img: "{{ $employee->photo ? asset('storage/' . $employee->photo) : 'https://ui-avatars.com/api/?name='.urlencode($employee->full_name).'&size=512&background=0a0a0f&color=d4af37&bold=true&format=png' }}"
        }@if(!$loop->last),@endif
        @endforeach
    ];

    let currentIndex = 0;
    let cycleTimeouts = [];

    const sceneA = document.getElementById('scene-a');
    const sceneB = document.getElementById('scene-b');
    const imgEl = document.getElementById('featured-image');
    const nameEl = document.getElementById('featured-name');
    const roleEl = document.getElementById('featured-role');
    const currentNumEl = document.getElementById('current-num');
    const navDots = document.querySelectorAll('.nav-dot');

    // Scene durations
    const SCENE_A_DURATION = employees.length > 1 ? 5000 : 8000;
    const SCENE_B_DURATION = 4000;

    function clearCycle() {
        cycleTimeouts.forEach(t => clearTimeout(t));
        cycleTimeouts = [];
    }

    function showScene(scene) {
        if (!sceneA || !sceneB) return;
        if (scene === 'a') {
            sceneA.classList.remove('scene-hidden');
            sceneB.classList.add('scene-hidden');
        } else {
            sceneA.classList.add('scene-hidden');
            sceneB.classList.remove('scene-hidden');
        }
    }

    function updateFeatured(index) {
        if (employees.length === 0) return;
        if (index === currentIndex && !sceneA.classList.contains('scene-hidden')) {
            // Same employee, but maybe restart scene
            clearCycle();
            startCycle();
            return;
        }

        currentIndex = ((index % employees.length) + employees.length) % employees.length;
        const emp = employees[currentIndex];

        // Pre-fade scene A
        if (sceneA) {
            sceneA.classList.add('scene-hidden');
        }

        setTimeout(() => {
            if (imgEl) imgEl.src = emp.img;
            if (nameEl) nameEl.innerHTML = `<span class="gold-text">${escapeHtml(emp.name)}</span>`;
            if (roleEl) roleEl.textContent = emp.role;
            navDots.forEach((dot, i) => dot.classList.toggle('active', i === currentIndex));
            if (currentNumEl) currentNumEl.textContent = (currentIndex + 1);

            // Scroll active dot into view (if exists)
            const activeDot = document.querySelector('.nav-dot.active');
            if (activeDot) {
                activeDot.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }

            showScene('a');
            burstConfetti(20);
        }, 400);

        clearCycle();
        cycleTimeouts.push(setTimeout(startCycle, 500));
    }

    function goToNext() {
        updateFeatured((currentIndex + 1) % employees.length);
    }

    function goToPrev() {
        updateFeatured((currentIndex - 1 + employees.length) % employees.length);
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function startCycle() {
        if (employees.length === 0) return;
        clearCycle();

        // After A duration → switch to B
        cycleTimeouts.push(setTimeout(() => {
            showScene('b');

            // After B duration → next employee OR loop back to A
            cycleTimeouts.push(setTimeout(() => {
                if (employees.length > 1) {
                    goToNext();
                } else {
                    showScene('a');
                    startCycle();
                }
            }, SCENE_B_DURATION));
        }, SCENE_A_DURATION));
    }

    // ===== FALLING CONFETTI =====
    const MAX_CONFETTI = 100;
    let activeConfetti = 0;

    function spawnConfetti() {
        if (activeConfetti >= MAX_CONFETTI) return;
        const container = document.getElementById('confetti-container');
        if (!container) return;

        const piece = document.createElement('div');
        piece.className = 'confetti-piece';

        const types = ['rect', 'rect', 'rect', 'circle', 'ribbon'];
        const type = types[Math.floor(Math.random() * types.length)];
        const colors = ['#ffd700', '#d4af37', '#f5d76e', '#8b7500', '#ffeb3b'];
        const color = colors[Math.floor(Math.random() * colors.length)];

        const size = Math.random() * 8 + 6;
        const left = Math.random() * 100;
        const duration = Math.random() * 6 + 6;
        const swayDuration = Math.random() * 3 + 2;

        if (type === 'rect') {
            piece.style.width = `${size}px`;
            piece.style.height = `${size * 1.6}px`;
            piece.style.background = color;
        } else if (type === 'circle') {
            piece.style.width = `${size * 0.8}px`;
            piece.style.height = `${size * 0.8}px`;
            piece.style.background = color;
            piece.style.borderRadius = '50%';
        } else {
            piece.style.width = `${size * 0.6}px`;
            piece.style.height = `${size * 3}px`;
            piece.style.background = `linear-gradient(180deg, ${color}, ${colors[(colors.indexOf(color) + 1) % colors.length]})`;
            piece.style.borderRadius = '2px';
        }

        piece.style.left = `${left}%`;
        piece.style.animation = `confetti-fall ${duration}s linear forwards, confetti-sway ${swayDuration}s ease-in-out infinite`;
        piece.style.boxShadow = `0 0 8px ${color}80`;

        container.appendChild(piece);
        activeConfetti++;
        setTimeout(() => {
            piece.remove();
            activeConfetti--;
        }, duration * 1000 + 500);
    }

    function burstConfetti(count = 25) {
        for (let i = 0; i < count; i++) {
            setTimeout(spawnConfetti, i * 60);
        }
    }

    // ===== INIT =====
    document.addEventListener('DOMContentLoaded', () => {
        showScene('a');
        startCycle();

        // Continuous confetti (less frequent)
        setInterval(spawnConfetti, 350);
        burstConfetti(40);

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight') goToNext();
            if (e.key === 'ArrowLeft') goToPrev();
            if (e.key === ' ' || e.key === 'Spacebar') {
                e.preventDefault();
                const isOnA = !sceneA.classList.contains('scene-hidden');
                showScene(isOnA ? 'b' : 'a');
                clearCycle();
                cycleTimeouts.push(setTimeout(startCycle, 1500));
            }
        });

        // Pause cycle on tab hidden
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                clearCycle();
            } else {
                startCycle();
            }
        });
    });
</script>
</body>
</html>
