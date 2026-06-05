<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Хайрли кун</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;900&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "void": "#000000",
                        "obsidian": "#0a0a0f",
                        "midnight": "#13131f",
                        "gold": "#d4af37",
                        "gold-light": "#f5d76e",
                        "gold-dark": "#8b7500",
                        "gold-bright": "#ffd700",
                        "ivory": "#fffff0",
                    },
                    fontFamily: {
                        "display": ["Playfair Display", "serif"],
                        "serif": ["PT Serif", "serif"],
                        "sans": ["Inter", "sans-serif"],
                        "mono": ["JetBrains Mono", "monospace"],
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
            background: radial-gradient(ellipse at top, #13131f 0%, #0a0a0f 50%, #000000 100%);
        }

        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 300; }

        /* === GOLD GRADIENT TEXT === */
        .gold-text {
            background: linear-gradient(135deg, #f5d76e 0%, #d4af37 30%, #ffd700 50%, #d4af37 70%, #f5d76e 100%);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer-gold 6s ease-in-out infinite;
            display: inline-block;
        }

        @keyframes shimmer-gold {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* === SUBTLE GLOW ORBS === */
        .glow-orb {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(120px);
            z-index: -1;
        }

        /* === SUBTLE CORNER RIBBONS === */
        .ribbon-svg {
            position: fixed;
            pointer-events: none;
            z-index: 2;
            filter: drop-shadow(0 6px 20px rgba(0, 0, 0, 0.7));
            width: clamp(140px, 22vw, 280px);
            height: clamp(140px, 22vw, 280px);
            opacity: 0.55;
        }

        /* === STARS / SPARKLES === */
        .star {
            position: absolute;
            background: white;
            border-radius: 50%;
            pointer-events: none;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.5); }
        }

        /* === LOGO RING === */
        .logo-ring {
            position: relative;
            background: conic-gradient(from 0deg, #8b7500, #f5d76e, #ffd700, #d4af37, #f5d76e, #8b7500);
            padding: 4px;
            border-radius: 50%;
            animation: spin-slow 14s linear infinite;
        }

        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .logo-ring::before {
            content: '';
            position: absolute;
            inset: -8px;
            border-radius: 50%;
            border: 1px solid rgba(212, 175, 55, 0.3);
            animation: spin-slow 22s linear infinite reverse;
        }

        @keyframes glow-pulse {
            0%, 100% { filter: drop-shadow(0 0 30px rgba(212, 175, 55, 0.3)); }
            50% { filter: drop-shadow(0 0 60px rgba(212, 175, 55, 0.5)); }
        }

        .animate-glow { animation: glow-pulse 4s ease-in-out infinite; }

        /* === LUXURY GLASS CARD === */
        .luxe-glass {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.04) 0%, rgba(255, 255, 255, 0.01) 100%);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(212, 175, 55, 0.15);
            box-shadow:
                0 8px 32px 0 rgba(0, 0, 0, 0.5),
                inset 0 1px 0 0 rgba(212, 175, 55, 0.1);
        }

        /* === CLOCK CARD === */
        .clock-card {
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.08) 0%, rgba(0, 0, 0, 0.6) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(212, 175, 55, 0.25);
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.5),
                inset 0 1px 0 rgba(212, 175, 55, 0.2);
        }

        /* === LIVE CLOCK TYPOGRAPHY === */
        .clock-digits {
            font-feature-settings: "tnum" 1; /* tabular numbers */
            letter-spacing: -0.02em;
        }

        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.3; }
        }
        .clock-colon { animation: blink 1.2s ease-in-out infinite; }

        /* === CAROUSEL === */
        .carousel-container {
            position: relative;
            width: 100%;
            aspect-ratio: 21 / 9;
            border-radius: 24px;
            overflow: hidden;
            box-shadow:
                0 30px 80px rgba(0, 0, 0, 0.6),
                0 0 0 1px rgba(212, 175, 55, 0.3),
                0 0 60px rgba(212, 175, 55, 0.15);
        }

        @media (max-width: 768px) {
            .carousel-container { aspect-ratio: 16 / 10; border-radius: 18px; }
        }
        @media (max-width: 480px) {
            .carousel-container { aspect-ratio: 4 / 3; border-radius: 14px; }
        }

        .carousel-item {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 1.5s ease;
            z-index: 0;
        }
        .carousel-item.active {
            opacity: 1;
            z-index: 10;
        }
        .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            animation: ken-burns 18s ease-in-out infinite alternate;
        }
        .carousel-item.active img {
            animation: ken-burns 12s ease-in-out forwards;
        }

        @keyframes ken-burns {
            0% { transform: scale(1) translate(0, 0); }
            100% { transform: scale(1.12) translate(-1.5%, -1.5%); }
        }

        /* === CAROUSEL OVERLAY === */
        .carousel-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(to top, rgba(0,0,0,0.75) 0%, transparent 50%),
                linear-gradient(135deg, transparent 60%, rgba(212,175,55,0.08) 100%);
            display: flex;
            align-items: flex-end;
            padding: clamp(1rem, 4vw, 3rem);
            pointer-events: none;
        }

        /* === CAROUSEL DOTS === */
        .carousel-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            cursor: pointer;
            transition: all 0.4s ease;
            flex-shrink: 0;
        }
        .carousel-dot.active {
            width: 32px;
            background: linear-gradient(135deg, #f5d76e, #d4af37);
            box-shadow: 0 0 12px rgba(212, 175, 55, 0.6);
        }
        .carousel-dot:hover {
            background: rgba(245, 215, 110, 0.7);
        }

        /* === FLOATING ICONS === */
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-12px) rotate(3deg); }
        }
        .animate-float-slow { animation: float 8s ease-in-out infinite; }
        .animate-float-med { animation: float 6s ease-in-out infinite 1s; }

        /* === FLUID TYPOGRAPHY === */
        .text-fluid-greeting { font-size: clamp(2rem, 4vw + 0.5rem, 4.5rem); line-height: 1.05; }
        .text-fluid-subgreeting { font-size: clamp(1rem, 1.2vw + 0.5rem, 1.5rem); line-height: 1.4; }
        .text-fluid-clock { font-size: clamp(2.5rem, 6vw + 0.5rem, 5.5rem); line-height: 1; }
        .text-fluid-date { font-size: clamp(0.85rem, 0.8vw + 0.5rem, 1.25rem); line-height: 1.4; }

        @media (min-width: 1280px) {
            .text-fluid-greeting { font-size: clamp(3rem, 3vw + 1rem, 5rem); }
        }

        /* === LAYOUT === */
        .day-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
            padding: clamp(1rem, 4vw, 3rem);
        }

        @media (min-width: 1024px) {
            .day-layout {
                grid-template-columns: 1.1fr 1fr;
                gap: 3rem;
                align-items: center;
                min-height: 100vh;
            }
        }

        /* === HEADER LOGO === */
        .header-logo-area {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* === REDUCED MOTION === */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>

<body class="font-sans text-white selection:bg-gold/40">

@php
    // Time-aware Cyrillic greeting
    $hour = now()->hour;
    if ($hour >= 5 && $hour < 12)      { $greetingText = 'Хайрли тонг'; $greetingIcon = 'wb_twilight'; }
    elseif ($hour >= 12 && $hour < 18) { $greetingText = 'Хайрли кун';  $greetingIcon = 'wb_sunny'; }
    elseif ($hour >= 18 && $hour < 22) { $greetingText = 'Хайрли кеч';  $greetingIcon = 'nights_stay'; }
    else                                { $greetingText = 'Хайрли тун';  $greetingIcon = 'bedtime'; }

    $monthsCy = ['Январ','Феврал','Март','Апрел','Май','Июн','Июл','Август','Сентябр','Октябр','Ноябр','Декабр'];
    $weekdaysCy = ['Якшанба','Душанба','Сешанба','Чоршанба','Пайшанба','Жума','Шанба'];
    $today = now();
@endphp

{{-- ===== BACKGROUND GLOW ORBS ===== --}}
<div class="glow-orb" style="width: 600px; height: 600px; top: -200px; left: -150px; background: rgba(212, 175, 55, 0.10);"></div>
<div class="glow-orb" style="width: 500px; height: 500px; bottom: -150px; right: -100px; background: rgba(232, 180, 164, 0.08);"></div>
<div class="glow-orb" style="width: 400px; height: 400px; top: 30%; right: 20%; background: rgba(245, 215, 110, 0.06);"></div>

{{-- ===== STAR FIELD ===== --}}
<div class="fixed inset-0 -z-5 overflow-hidden pointer-events-none" id="star-field"></div>

{{-- ===== SVG DEFS ===== --}}
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
    </defs>
</svg>

{{-- ===== CORNER RIBBONS (SUBTLE) ===== --}}
<svg class="ribbon-svg" style="top: 0; left: 0;" viewBox="0 0 320 320">
    <path d="M -20 -10 Q 80 60, 60 160 T 100 290" stroke="url(#gold1)" stroke-width="10" fill="none" stroke-linecap="round" opacity="0.7"/>
    <path d="M -10 -20 Q 120 40, 140 130 T 180 250" stroke="url(#gold2)" stroke-width="7" fill="none" stroke-linecap="round" opacity="0.55"/>
</svg>

<svg class="ribbon-svg" style="top: 0; right: 0; transform: scaleX(-1);" viewBox="0 0 320 320">
    <path d="M -20 -10 Q 80 60, 60 160 T 100 290" stroke="url(#gold1)" stroke-width="10" fill="none" stroke-linecap="round" opacity="0.7"/>
    <path d="M -10 -20 Q 120 40, 140 130 T 180 250" stroke="url(#gold2)" stroke-width="7" fill="none" stroke-linecap="round" opacity="0.55"/>
</svg>

<svg class="ribbon-svg" style="bottom: 0; left: 0; transform: scaleY(-1);" viewBox="0 0 320 320">
    <path d="M -20 -10 Q 80 60, 60 160 T 100 290" stroke="url(#gold1)" stroke-width="10" fill="none" stroke-linecap="round" opacity="0.6"/>
    <path d="M -10 -20 Q 120 40, 140 130 T 180 250" stroke="url(#gold2)" stroke-width="7" fill="none" stroke-linecap="round" opacity="0.45"/>
</svg>

<svg class="ribbon-svg" style="bottom: 0; right: 0; transform: scale(-1, -1);" viewBox="0 0 320 320">
    <path d="M -20 -10 Q 80 60, 60 160 T 100 290" stroke="url(#gold1)" stroke-width="10" fill="none" stroke-linecap="round" opacity="0.6"/>
    <path d="M -10 -20 Q 120 40, 140 130 T 180 250" stroke="url(#gold2)" stroke-width="7" fill="none" stroke-linecap="round" opacity="0.45"/>
</svg>

{{-- ===== MAIN ===== --}}
<main class="day-layout">

    {{-- ============ LEFT: GREETING + CLOCK ============ --}}
    <section class="space-y-6 md:space-y-8 text-center lg:text-left">

        {{-- Logo header --}}
        <div class="header-logo-area justify-center lg:justify-start">
            <div class="logo-ring animate-glow shrink-0">
                <div class="p-[2px] rounded-full bg-black">
                    <img src="{{ asset('assets/images/1111222.png') }}"
                         alt="Logo"
                         class="w-16 h-16 md:w-20 md:h-20 rounded-full object-cover"/>
                </div>
            </div>
            <div class="text-white">
                <p class="font-serif text-sm md:text-base lg:text-lg leading-tight tracking-wide">Ўзбекистон Республикаси</p>
                <p class="font-serif text-sm md:text-base lg:text-lg leading-tight tracking-wide gold-text">Антигравити гуруҳи</p>
            </div>
        </div>

        {{-- Greeting card --}}
        <div class="luxe-glass rounded-3xl p-6 md:p-10 space-y-4">
            <div class="flex items-center justify-center lg:justify-start gap-3">
                <span class="material-symbols-outlined text-gold text-2xl md:text-3xl animate-float-slow" style="font-variation-settings: 'FILL' 1;">{{ $greetingIcon }}</span>
                <span class="font-serif italic text-gold/70 text-xs md:text-sm uppercase tracking-[0.3em]">Чин юракдан табрик</span>
            </div>

            <h1 class="font-display font-bold text-fluid-greeting text-white name-shadow">
                <span class="gold-text">{{ $greetingText }},</span><br/>
                <span class="text-ivory">азиз ҳамкасблар!</span>
            </h1>

            <p class="font-serif italic text-fluid-subgreeting text-white/70 max-w-lg mx-auto lg:mx-0">
                Бугунги кунингиз муваффақият, илҳом ва ижобий энергияга тўла бўлсин. Биргаликда буюк ишларни амалга оширамиз.
            </p>
        </div>

        {{-- Live clock card --}}
        <div class="clock-card rounded-3xl p-6 md:p-8 space-y-3">
            {{-- Date row --}}
            <div class="flex items-center justify-center lg:justify-start gap-3 text-gold/90">
                <span class="material-symbols-outlined text-base md:text-lg" style="font-variation-settings: 'FILL' 1;">calendar_month</span>
                <span class="font-serif text-fluid-date tracking-wider">
                    <span id="current-weekday">{{ $weekdaysCy[$today->dayOfWeek] }}</span>,
                    <span id="current-date">{{ $today->day }}-{{ $monthsCy[$today->month - 1] }} {{ $today->year }}-йил</span>
                </span>
            </div>

            {{-- Clock display --}}
            <div class="flex items-baseline justify-center lg:justify-start gap-1 font-mono font-bold text-fluid-clock clock-digits gold-text">
                <span id="clock-hour">--</span>
                <span class="clock-colon text-gold/70 mx-1">:</span>
                <span id="clock-min">--</span>
                <span class="clock-colon text-gold/70 mx-1">:</span>
                <span id="clock-sec" class="opacity-70">--</span>
            </div>

            {{-- Time period indicator --}}
            <div class="flex items-center justify-center lg:justify-start gap-2">
                <span class="h-px w-10 bg-gradient-to-r from-transparent to-gold/40"></span>
                <span id="time-period" class="font-serif italic text-white/60 text-xs md:text-sm uppercase tracking-[0.25em]">Тонг</span>
                <span class="h-px w-10 bg-gradient-to-l from-transparent to-gold/40"></span>
            </div>
        </div>
    </section>

    {{-- ============ RIGHT: PHOTO CAROUSEL ============ --}}
    <section class="space-y-4">
        <div class="text-center lg:text-left">
            <p class="font-serif italic text-gold/70 text-xs md:text-sm uppercase tracking-[0.3em] mb-1">Бизнинг жамоа</p>
            <h2 class="font-display font-bold text-2xl md:text-3xl lg:text-4xl text-ivory">
                <span class="gold-text">Криминология</span> жамоаси
            </h2>
        </div>

        <div class="carousel-container group" id="carousel">
            @if($groupPhotos->isNotEmpty())
                @foreach($groupPhotos as $index => $photo)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $photo->image_path) }}"
                             alt="Жамоа сурати {{ $index + 1 }}"/>
                        <div class="carousel-overlay">
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-gold text-sm md:text-base" style="font-variation-settings: 'FILL' 1;">groups</span>
                                    <span class="font-serif italic text-gold/90 text-xs md:text-sm uppercase tracking-wider">Биз биргамиз</span>
                                </div>
                                <p class="font-display font-bold text-lg md:text-2xl lg:text-3xl text-ivory">Антигравити жамоаси</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="carousel-item active">
                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&q=80&w=1920"
                         alt="Жамоа"/>
                    <div class="carousel-overlay">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-gold text-sm md:text-base" style="font-variation-settings: 'FILL' 1;">groups</span>
                                <span class="font-serif italic text-gold/90 text-xs md:text-sm uppercase tracking-wider">Биз биргамиз</span>
                            </div>
                            <p class="font-display font-bold text-lg md:text-2xl lg:text-3xl text-ivory">Антигравити жамоаси</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Decorative corners --}}
            <div class="absolute top-3 left-3 w-8 h-8 border-l-2 border-t-2 border-gold/60 rounded-tl-xl pointer-events-none z-20"></div>
            <div class="absolute top-3 right-3 w-8 h-8 border-r-2 border-t-2 border-gold/60 rounded-tr-xl pointer-events-none z-20"></div>
            <div class="absolute bottom-3 left-3 w-8 h-8 border-l-2 border-b-2 border-gold/60 rounded-bl-xl pointer-events-none z-20"></div>
            <div class="absolute bottom-3 right-3 w-8 h-8 border-r-2 border-b-2 border-gold/60 rounded-br-xl pointer-events-none z-20"></div>
        </div>

        {{-- Carousel dots --}}
        <div class="flex items-center justify-center gap-2 pt-2" id="dots-container">
            @if($groupPhotos->isNotEmpty())
                @foreach($groupPhotos as $index => $photo)
                    <button onclick="goToSlide({{ $index }})"
                            class="carousel-dot {{ $index === 0 ? 'active' : '' }}"
                            aria-label="Сурат {{ $index + 1 }}"></button>
                @endforeach
            @else
                <button class="carousel-dot active" aria-label="Сурат"></button>
            @endif
        </div>

        {{-- Photo counter --}}
        @if($groupPhotos->count() > 1)
            <div class="text-center pt-1">
                <span class="font-mono text-gold/60 text-xs tracking-wider">
                    <span id="slide-current">1</span><span class="text-gold/30 mx-1">/</span>{{ $groupPhotos->count() }}
                </span>
            </div>
        @endif
    </section>
</main>

<script>
    // ===== LIVE CLOCK =====
    const hourEl = document.getElementById('clock-hour');
    const minEl = document.getElementById('clock-min');
    const secEl = document.getElementById('clock-sec');
    const periodEl = document.getElementById('time-period');
    const dateEl = document.getElementById('current-date');
    const weekdayEl = document.getElementById('current-weekday');

    const monthsCy = ['Январ','Феврал','Март','Апрел','Май','Июн','Июл','Август','Сентябр','Октябр','Ноябр','Декабр'];
    const weekdaysCy = ['Якшанба','Душанба','Сешанба','Чоршанба','Пайшанба','Жума','Шанба'];

    function updateClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        if (hourEl) hourEl.textContent = h;
        if (minEl) minEl.textContent = m;
        if (secEl) secEl.textContent = s;

        // Time period
        const hr = now.getHours();
        let period;
        if (hr >= 5 && hr < 12) period = 'Тонг';
        else if (hr >= 12 && hr < 18) period = 'Кун';
        else if (hr >= 18 && hr < 22) period = 'Кеч';
        else period = 'Тун';
        if (periodEl) periodEl.textContent = period;

        // Date
        if (weekdayEl) weekdayEl.textContent = weekdaysCy[now.getDay()];
        if (dateEl) dateEl.textContent = `${now.getDate()}-${monthsCy[now.getMonth()]} ${now.getFullYear()}-йил`;
    }

    setInterval(updateClock, 1000);
    updateClock();

    // ===== CAROUSEL =====
    let currentSlide = 0;
    let carouselInterval = null;
    const slides = document.querySelectorAll('.carousel-item');
    const dots = document.querySelectorAll('.carousel-dot');
    const slideCurrentEl = document.getElementById('slide-current');

    function showSlide(index) {
        if (slides.length === 0) return;
        currentSlide = ((index % slides.length) + slides.length) % slides.length;

        slides.forEach((slide, i) => slide.classList.toggle('active', i === currentSlide));
        dots.forEach((dot, i) => dot.classList.toggle('active', i === currentSlide));
        if (slideCurrentEl) slideCurrentEl.textContent = (currentSlide + 1);
    }

    function goToSlide(index) {
        showSlide(index);
        if (carouselInterval) {
            clearInterval(carouselInterval);
            startCarousel();
        }
    }

    function nextSlide() { showSlide(currentSlide + 1); }

    function startCarousel() {
        if (slides.length > 1) {
            carouselInterval = setInterval(nextSlide, 6000);
        }
    }

    // ===== STAR FIELD =====
    function createStarField() {
        const field = document.getElementById('star-field');
        if (!field) return;
        for (let i = 0; i < 60; i++) {
            const star = document.createElement('div');
            star.className = 'star';
            const size = Math.random() * 2 + 0.5;
            star.style.width = `${size}px`;
            star.style.height = `${size}px`;
            star.style.left = `${Math.random() * 100}%`;
            star.style.top = `${Math.random() * 100}%`;
            star.style.opacity = (Math.random() * 0.5 + 0.2).toString();
            star.style.animation = `twinkle ${Math.random() * 3 + 2}s ease-in-out infinite ${Math.random() * 3}s`;
            field.appendChild(star);
        }
    }

    // ===== INIT =====
    document.addEventListener('DOMContentLoaded', () => {
        createStarField();
        startCarousel();

        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight') goToSlide(currentSlide + 1);
            if (e.key === 'ArrowLeft') goToSlide(currentSlide - 1);
        });

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                if (carouselInterval) clearInterval(carouselInterval);
            } else {
                startCarousel();
            }
        });
    });
</script>
</body>
</html>
