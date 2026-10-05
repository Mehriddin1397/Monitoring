<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Туғилган кунлар — Ўзбекистон Республикаси Криминология тадқиқот институти</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,900;1,400;1,700&family=Cinzel:wght@600;700;800;900&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "void": "#000000",
                        "gold": "#d4af37",
                        "gold-light": "#f5d76e",
                        "gold-dark": "#8b7500",
                    },
                    fontFamily: {
                        "display": ["Playfair Display", "serif"],
                        "cinzel": ["Cinzel", "serif"],
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
            transition: background 1.2s ease;
        }

        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 300; }

        /* ======================================================== */
        /* ================= GENDER THEME BACKGROUNDS ============= */
        /* ======================================================== */

        /* MEN BACKGROUND: MILITARY / SECURITY / TACTICAL GOLD */
        body.theme-male {
            background: radial-gradient(ellipse at center, #0e1e17 0%, #06110d 45%, #020705 80%, #000000 100%);
        }

        /* WOMEN BACKGROUND: LUXURY ROSES & SPRING FLORAL */
        body.theme-female {
            background: radial-gradient(ellipse at center, #360a19 0%, #1c040d 45%, #0d0106 80%, #000000 100%);
        }

        /* ======================================================== */
        /* ================= EXECUTIVE ROYAL PORTRAIT FRAME ======= */
        /* ======================================================== */
        .portrait-frame-container {
            width: clamp(260px, 25vw, 360px);
            aspect-ratio: 3 / 4;
            position: relative;
            flex-shrink: 0;
        }

        /* 1. MALE EXECUTIVE FRAME: Handcrafted Gold & Steel Baguette */
        .portrait-frame-male {
            width: 100%;
            height: 100%;
            border-radius: 20px;
            padding: 10px;
            background: linear-gradient(145deg, #ffd700 0%, #8b7500 25%, #1a2920 50%, #d4af37 75%, #ffd700 100%);
            box-shadow:
                0 30px 80px rgba(0, 0, 0, 0.9),
                0 0 55px rgba(212, 175, 55, 0.45),
                0 0 35px rgba(16, 185, 129, 0.25);
            position: relative;
        }
        .portrait-frame-male-inner {
            width: 100%;
            height: 100%;
            border-radius: 14px;
            overflow: hidden;
            border: 2px solid #ffd700;
            background: #000;
            position: relative;
        }

        /* 2. FEMALE EXECUTIVE FRAME: Gold & Rose-Pearl Royal Baguette */
        .portrait-frame-female {
            width: 100%;
            height: 100%;
            border-radius: 20px;
            padding: 10px;
            background: linear-gradient(145deg, #ffd700 0%, #fda4af 30%, #fb7185 55%, #ffd700 80%, #f43f5e 100%);
            box-shadow:
                0 30px 80px rgba(0, 0, 0, 0.9),
                0 0 60px rgba(251, 113, 133, 0.5),
                0 0 35px rgba(212, 175, 55, 0.35);
            position: relative;
        }
        .portrait-frame-female-inner {
            width: 100%;
            height: 100%;
            border-radius: 14px;
            overflow: hidden;
            border: 2px solid #fda4af;
            background: #000;
            position: relative;
        }

        /* Decorative Gold Corner Pieces */
        .frame-corner {
            position: absolute;
            width: 22px;
            height: 22px;
            pointer-events: none;
            z-index: 10;
        }
        .frame-corner-tl { top: 5px; left: 5px; border-top: 3px solid #ffd700; border-left: 3px solid #ffd700; border-top-left-radius: 8px; }
        .frame-corner-tr { top: 5px; right: 5px; border-top: 3px solid #ffd700; border-right: 3px solid #ffd700; border-top-right-radius: 8px; }
        .frame-corner-bl { bottom: 5px; left: 5px; border-bottom: 3px solid #ffd700; border-left: 3px solid #ffd700; border-bottom-left-radius: 8px; }
        .frame-corner-br { bottom: 5px; right: 5px; border-bottom: 3px solid #ffd700; border-right: 3px solid #ffd700; border-bottom-right-radius: 8px; }

        /* ======================================================== */
        /* ================= STATIC LOGO (NO SPIN!) =============== */
        /* ======================================================== */
        .static-logo-box {
            padding: 3px;
            background: linear-gradient(135deg, #ffd700, #d4af37, #8b7500);
            border-radius: 50%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.7);
        }

        /* ======================================================== */
        /* ================= SHIMMER TEXTS ======================== */
        /* ======================================================== */
        .gold-shimmer {
            background: linear-gradient(135deg, #f5d76e 0%, #ffd700 25%, #d4af37 50%, #ffd700 75%, #f5d76e 100%);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: text-glow 6s ease-in-out infinite;
            display: inline-block;
        }

        .rose-shimmer {
            background: linear-gradient(135deg, #fff1f2 0%, #fda4af 25%, #fb7185 50%, #ffd700 75%, #fff1f2 100%);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: text-glow 6s ease-in-out infinite;
            display: inline-block;
        }

        @keyframes text-glow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* ======================================================== */
        /* ================= SCENE MANAGEMENT ===================== */
        /* ======================================================== */
        .scene-stage {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
            transform: scale(1);
            transition: opacity 0.8s ease, transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
            padding: clamp(1rem, 3vw, 2.5rem);
            z-index: 10;
        }

        .scene-stage-hidden {
            opacity: 0;
            pointer-events: none;
            transform: scale(0.97);
        }

        /* Particles */
        .falling-item {
            position: fixed;
            top: -40px;
            pointer-events: none;
            will-change: transform;
            z-index: 5;
        }
        @keyframes float-down {
            0% { transform: translateY(-10vh) rotate(0deg); opacity: 0; }
            10% { opacity: 0.9; }
            90% { opacity: 0.9; }
            100% { transform: translateY(110vh) rotate(540deg); opacity: 0; }
        }

        /* Bottom Nav */
        .bottom-nav {
            position: fixed;
            bottom: 1.25rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 50;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.85rem;
            border-radius: 9999px;
            backdrop-filter: blur(14px);
            background: rgba(0, 0, 0, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.8);
        }
        .nav-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transition: all 0.4s ease;
            cursor: pointer;
        }
        .nav-dot.active {
            width: 28px;
            background: #ffd700;
            box-shadow: 0 0 12px #ffd700;
        }
    </style>
</head>

@php
    $firstEmployee = $birthdayEmployees->first();
    $firstGender = $firstEmployee->gender ?? 'male';
    $monthsCy = ['Январ','Феврал','Март','Апрел','Май','Июн','Июл','Август','Сентябр','Октябр','Ноябр','Декабр'];
    $todayDateCy = now()->day.'-'.$monthsCy[now()->month - 1].' '.now()->year.'-йил';
    $employeeCount = $birthdayEmployees->count();
@endphp

<body class="font-sans text-white theme-{{ $firstGender }}" id="app-body">

{{-- ======================================================== --}}
{{-- ================= BACKGROUND GRAPHICS LAYER ============ --}}
{{-- ======================================================== --}}

{{-- 1. MALE BACKGROUND: MILITARY VEHICLE / TACTICAL SHIELDS / RADAR --}}
<div id="bg-graphics-male" class="{{ $firstGender === 'male' ? '' : 'hidden' }} fixed inset-0 pointer-events-none z-0 overflow-hidden">
    {{-- Military Radar Web --}}
    <svg class="absolute -top-20 -left-20 w-[550px] h-[550px] opacity-20 text-emerald-400" viewBox="0 0 200 200" fill="none" stroke="currentColor">
        <circle cx="100" cy="100" r="90" stroke-width="0.5" stroke-dasharray="3 3"/>
        <circle cx="100" cy="100" r="70" stroke-width="0.8"/>
        <circle cx="100" cy="100" r="45" stroke-width="0.5"/>
        <circle cx="100" cy="100" r="20" stroke-width="1"/>
        <line x1="100" y1="0" x2="100" y2="200" stroke-width="0.5"/>
        <line x1="0" y1="100" x2="200" y2="100" stroke-width="0.5"/>
        <polygon points="100,10 103,25 97,25" fill="currentColor"/>
    </svg>

    {{-- Military Armored Vehicle / Tank / Drone Silhouette --}}
    <div class="absolute bottom-6 right-6 opacity-25 text-emerald-300">
        <svg class="w-[320px] sm:w-[480px] lg:w-[600px] h-auto" viewBox="0 0 600 240" fill="currentColor">
            {{-- Modern Armored Vehicle Silhouette --}}
            <path d="M 50 180 L 110 180 L 130 140 L 260 140 L 300 110 L 420 110 L 470 140 L 550 150 L 560 180 L 580 190 L 580 210 L 40 210 L 40 190 Z" opacity="0.7"/>
            {{-- Gun Cannon Barrel --}}
            <rect x="250" y="85" width="280" height="12" rx="4" opacity="0.9"/>
            {{-- Turret --}}
            <path d="M 280 110 L 310 75 L 430 75 L 460 110 Z" opacity="0.95"/>
            {{-- Radar Antenna / Optics --}}
            <rect x="360" y="55" width="25" height="20" rx="3"/>
            <line x1="372" y1="55" x2="372" y2="35" stroke="currentColor" stroke-width="3"/>
            {{-- Tracks / Wheels --}}
            <circle cx="90" cy="195" r="20" fill="#000" stroke="currentColor" stroke-width="4"/>
            <circle cx="150" cy="195" r="20" fill="#000" stroke="currentColor" stroke-width="4"/>
            <circle cx="210" cy="195" r="20" fill="#000" stroke="currentColor" stroke-width="4"/>
            <circle cx="270" cy="195" r="20" fill="#000" stroke="currentColor" stroke-width="4"/>
            <circle cx="330" cy="195" r="20" fill="#000" stroke="currentColor" stroke-width="4"/>
            <circle cx="390" cy="195" r="20" fill="#000" stroke="currentColor" stroke-width="4"/>
            <circle cx="450" cy="195" r="20" fill="#000" stroke="currentColor" stroke-width="4"/>
            <circle cx="510" cy="195" r="20" fill="#000" stroke="currentColor" stroke-width="4"/>
        </svg>
    </div>

    {{-- Defense Shield & Sword Crest watermark --}}
    <div class="absolute top-1/4 right-1/4 opacity-10 text-gold pointer-events-none">
        <span class="material-symbols-outlined text-[320px]">shield</span>
    </div>
</div>

{{-- 2. FEMALE BACKGROUND: LUXURY ROSES & SPRING FLORAL --}}
<div id="bg-graphics-female" class="{{ $firstGender === 'female' ? '' : 'hidden' }} fixed inset-0 pointer-events-none z-0 overflow-hidden">
    {{-- Top-Left Blooming Roses Garland --}}
    <svg class="absolute -top-10 -left-10 w-[280px] sm:w-[400px] h-auto opacity-40 text-rose-400" viewBox="0 0 300 300" fill="currentColor">
        {{-- Rose 1 --}}
        <circle cx="90" cy="90" r="50" fill="url(#roseGrad)" opacity="0.8"/>
        <path d="M 60 70 Q 90 40 120 70 Q 140 100 110 120 Q 70 130 60 90 Z" fill="#fda4af" opacity="0.6"/>
        <path d="M 75 80 Q 90 60 105 80 Q 115 100 95 105 Z" fill="#f43f5e"/>
        {{-- Golden Leaves --}}
        <path d="M 140 90 Q 200 60 210 110 Q 170 140 140 90 Z" fill="#d4af37" opacity="0.5"/>
        <path d="M 90 140 Q 60 200 110 210 Q 140 170 90 140 Z" fill="#d4af37" opacity="0.5"/>
        {{-- Rose 2 --}}
        <circle cx="190" cy="140" r="35" fill="url(#roseGrad)" opacity="0.75"/>
    </svg>

    {{-- Bottom-Right Luxury Floral Bouquet --}}
    <svg class="absolute -bottom-10 -right-10 w-[300px] sm:w-[450px] h-auto opacity-40 text-rose-400" viewBox="0 0 300 300" fill="currentColor">
        <circle cx="210" cy="210" r="60" fill="url(#roseGrad)" opacity="0.85"/>
        <path d="M 180 190 Q 210 160 240 190 Q 260 220 230 240 Q 190 250 180 210 Z" fill="#fda4af" opacity="0.6"/>
        {{-- Golden vines --}}
        <path d="M 150 210 Q 90 180 80 230 Q 120 260 150 210 Z" fill="#d4af37" opacity="0.5"/>
        <path d="M 210 150 Q 180 90 230 80 Q 260 120 210 150 Z" fill="#d4af37" opacity="0.5"/>
    </svg>

    {{-- Floral Heart Silhouette watermark --}}
    <div class="absolute top-1/3 right-1/4 opacity-10 text-rose-300 pointer-events-none">
        <span class="material-symbols-outlined text-[300px]">local_florist</span>
    </div>

    {{-- SVG Gradients for Roses --}}
    <svg width="0" height="0" class="absolute">
        <defs>
            <radialGradient id="roseGrad" cx="40%" cy="40%">
                <stop offset="0%" stop-color="#fff1f2"/>
                <stop offset="40%" stop-color="#fb7185"/>
                <stop offset="85%" stop-color="#e11d48"/>
                <stop offset="100%" stop-color="#881337"/>
            </radialGradient>
        </defs>
    </svg>
</div>

{{-- ===== FIXED STATIC INSTITUT HEADER (NO SPIN!) ===== --}}
<header class="fixed top-0 left-0 right-0 z-30 p-4 sm:p-6 flex justify-end">
    <div class="flex items-center gap-3 sm:gap-4 bg-black/50 backdrop-blur-md px-4 py-2 rounded-full border border-white/10 shadow-2xl">
        <div class="static-logo-box shrink-0">
            <img src="{{ asset('assets/images/1111222.png') }}"
                 alt="Logo"
                 class="w-10 h-10 sm:w-14 sm:h-14 rounded-full object-cover"/>
        </div>
        <div class="text-left hidden sm:block">
            <p class="font-serif text-xs text-white/80 leading-tight">Ўзбекистон Республикаси</p>
            <p class="font-serif text-sm font-bold gold-shimmer leading-tight">Криминология тадқиқот институти</p>
        </div>
    </div>
</header>

{{-- ===== PARTICLES CONTAINER ===== --}}
<div class="fixed inset-0 pointer-events-none overflow-hidden z-[5]" id="particles-container"></div>

{{-- ===== MAIN STAGE ===== --}}
<main class="relative w-full min-h-screen">
    @if($firstEmployee)

        {{-- ======================================================== --}}
        {{-- ================= SCENE A: CELEBRATION HERO ============ --}}
        {{-- ======================================================== --}}
        <section id="scene-a" class="scene-stage">
            <div class="w-full max-w-6xl mx-auto pt-16 pb-20">
                <div class="grid grid-cols-1 lg:grid-cols-[auto_1fr] gap-8 sm:gap-12 lg:gap-16 items-center">

                    {{-- EXECUTIVE PORTRAIT BAGUETTE (3:4 PROPORTION, STATIC, NO SPIN!) --}}
                    <div class="flex justify-center lg:justify-start">
                        <div class="portrait-frame-container">
                            <div id="portrait-frame-outer" class="{{ $firstGender === 'female' ? 'portrait-frame-female' : 'portrait-frame-male' }}">
                                <div id="portrait-frame-inner" class="{{ $firstGender === 'female' ? 'portrait-frame-female-inner' : 'portrait-frame-male-inner' }}">
                                    {{-- Gold Corner Accents --}}
                                    <div class="frame-corner frame-corner-tl"></div>
                                    <div class="frame-corner-tr"></div>
                                    <div class="frame-corner-bl"></div>
                                    <div class="frame-corner-br"></div>

                                    <img id="featured-image"
                                         src="{{ $firstEmployee->photo ? asset('storage/' . $firstEmployee->photo) : 'https://ui-avatars.com/api/?name='.urlencode($firstEmployee->full_name).'&size=512&background=0a0a0f&color=d4af37&bold=true&format=png' }}"
                                         alt="{{ $firstEmployee->full_name }}"
                                         class="w-full h-full object-cover"/>
                                </div>
                            </div>

                            {{-- Emblem Badge on Frame --}}
                            <div id="frame-badge-top" class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-black border border-gold text-gold text-[11px] font-serif uppercase tracking-widest flex items-center gap-1.5 shadow-xl z-20">
                                <span class="material-symbols-outlined text-xs" id="badge-icon-el">military_tech</span>
                                <span id="badge-title-el">Шараф</span>
                            </div>
                        </div>
                    </div>

                    {{-- TEXT & COMPOSITION AREA --}}
                    <div class="text-center lg:text-left space-y-4 md:space-y-6">

                        {{-- Gender-specific category tag --}}
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-black/60 border border-white/20 shadow-lg">
                            <span class="material-symbols-outlined text-gold text-lg" id="tag-icon">military_tech</span>
                            <span class="text-xs uppercase tracking-[0.25em] text-white/90 font-serif" id="tag-text">
                                {{ $firstGender === 'female' ? 'Гўзаллик ва латофат' : 'Мардлик ва садоқат' }}
                            </span>
                        </div>

                        {{-- Salutation --}}
                        <p class="font-serif italic text-xl sm:text-2xl text-white/85" id="salutation-text">
                            {{ $firstGender === 'female' ? 'Муҳтарама ва мунис ҳамкасбимиз,' : 'Ҳурматли ва муҳтарам ҳамкасбимиз,' }}
                        </p>

                        {{-- Full Name --}}
                        <h1 class="font-display font-bold text-3xl sm:text-4xl md:text-5xl lg:text-6xl text-white leading-tight">
                            <span id="featured-name" class="{{ $firstGender === 'female' ? 'rose-shimmer' : 'gold-shimmer' }}">{{ $firstEmployee->full_name }}</span>
                        </h1>

                        {{-- Position --}}
                        <p class="font-serif text-sm sm:text-base md:text-lg text-white/75 italic" id="featured-role">
                            {{ $firstEmployee->position }}
                        </p>

                        {{-- Divider --}}
                        <div class="flex items-center justify-center lg:justify-start gap-4 py-2">
                            <span class="h-px w-16 bg-gradient-to-r from-transparent to-white/40"></span>
                            <span class="material-symbols-outlined text-gold text-2xl" id="divider-icon">stars</span>
                            <span class="h-px w-16 bg-gradient-to-l from-transparent to-white/40"></span>
                        </div>

                        {{-- Congratulations Heading --}}
                        <h2 class="font-display font-bold text-2xl sm:text-3xl md:text-4xl text-white">
                            Туғилган кунингиз <span id="congrat-highlight" class="{{ $firstGender === 'female' ? 'rose-shimmer' : 'gold-shimmer' }}">муборак бўлсин!</span>
                        </h2>

                        {{-- Date Badge --}}
                        <div class="pt-2 flex items-center justify-center lg:justify-start gap-2 text-white/60">
                            <span class="material-symbols-outlined text-sm">calendar_month</span>
                            <span class="font-serif italic text-xs sm:text-sm tracking-wider">{{ $todayDateCy }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ======================================================== --}}
        {{-- ================= SCENE B: WISH & BLESSINGS ============ --}}
        {{-- ======================================================== --}}
        <section id="scene-b" class="scene-stage scene-stage-hidden">
            <div class="w-full max-w-4xl mx-auto pt-16 pb-20 text-center space-y-8">

                {{-- Emblem / Symbol --}}
                <div class="inline-flex p-4 rounded-full bg-white/5 border border-white/10 shadow-2xl" id="wish-symbol-box">
                    <span class="material-symbols-outlined text-5xl text-gold" id="wish-symbol-icon">auto_awesome</span>
                </div>

                {{-- Sub-heading --}}
                <p class="text-xs uppercase tracking-[0.35em] text-white/75 font-serif" id="wish-subheading">
                    Институт жамоаси номидан эзгу тилаклар
                </p>

                {{-- The Wish Card --}}
                <div class="p-8 sm:p-12 rounded-3xl bg-black/70 border border-white/15 backdrop-blur-xl shadow-2xl">
                    <h2 class="font-display font-bold text-xl sm:text-2xl md:text-3xl lg:text-4xl text-white leading-relaxed" id="featured-wish">
                        @if(!empty($firstEmployee->custom_wish))
                            {!! nl2br(e($firstEmployee->custom_wish)) !!}
                        @else
                            {{ $firstGender === 'female'
                                ? 'Сизга баҳорий кайфият, мустаҳкам соғлик, оилавий хотиржамлик ва беқиёс гўзаллик ҳамиша ҳамроҳ бўлишини тилаймиз!'
                                : 'Сизга мустаҳкам соғлик, узоқ ва мазмунли умр, оилавий хотиржамлик ҳамда масъулиятли фаолиятингизда улкан зафарлар тилаймиз!' }}
                        @endif
                    </h2>
                </div>

                {{-- Sign-off --}}
                <div class="flex items-center justify-center gap-4 pt-4">
                    <span class="h-px w-16 bg-gradient-to-r from-transparent to-white/40"></span>
                    <span class="font-serif italic text-white/80 text-xs sm:text-sm tracking-[0.2em] uppercase">
                        Криминология тадқиқот институти жамоаси
                    </span>
                    <span class="h-px w-16 bg-gradient-to-l from-transparent to-white/40"></span>
                </div>
            </div>
        </section>

    @else
        <section class="scene-stage">
            <div class="text-center max-w-xl px-6 space-y-4">
                <span class="material-symbols-outlined text-white/50 text-7xl">celebration</span>
                <h2 class="font-display text-3xl font-bold gold-shimmer">Бугун байрам йўқ</h2>
                <p class="font-serif italic text-white/60">Бугун туғилган куни нишонланадиган ходимлар мавжуд эмас.</p>
            </div>
        </section>
    @endif
</main>

{{-- ===== BOTTOM NAVIGATION (WHEN MULTIPLE EMPLOYEES) ===== --}}
@if($employeeCount > 1)
    <nav class="bottom-nav">
        <button onclick="goToPrev()" aria-label="Олдинги" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors">
            <span class="material-symbols-outlined text-base">chevron_left</span>
        </button>

        <div class="flex items-center gap-2" id="nav-dots">
            @foreach($birthdayEmployees as $index => $employee)
                <button onclick="updateFeatured({{ $index }})"
                        class="nav-dot {{ $index === 0 ? 'active' : '' }}"
                        data-index="{{ $index }}"
                        aria-label="{{ $employee->full_name }}"></button>
            @endforeach
        </div>

        <span class="text-white/80 text-xs font-mono font-semibold">
            <span id="current-num">1</span> / {{ $employeeCount }}
        </span>

        <button onclick="goToNext()" aria-label="Кейинги" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors">
            <span class="material-symbols-outlined text-base">chevron_right</span>
        </button>
    </nav>
@endif

<script>
    // ===== EMPLOYEES DATA =====
    const employees = [
        @foreach($birthdayEmployees as $employee)
        {
            name: "{{ addslashes($employee->full_name) }}",
            role: "{{ addslashes($employee->position) }}",
            img: "{{ $employee->photo ? asset('storage/' . $employee->photo) : 'https://ui-avatars.com/api/?name='.urlencode($employee->full_name).'&size=512&background=0a0a0f&color=d4af37&bold=true&format=png' }}",
            gender: "{{ $employee->gender ?? 'male' }}",
            wish: "{{ addslashes($employee->custom_wish ?? '') }}"
        }@if(!$loop->last),@endif
        @endforeach
    ];

    let currentIndex = 0;
    let cycleTimeouts = [];

    const bodyEl = document.getElementById('app-body');
    const sceneA = document.getElementById('scene-a');
    const sceneB = document.getElementById('scene-b');
    const imgEl = document.getElementById('featured-image');
    const nameEl = document.getElementById('featured-name');
    const roleEl = document.getElementById('featured-role');
    const wishEl = document.getElementById('featured-wish');
    const currentNumEl = document.getElementById('current-num');
    const navDots = document.querySelectorAll('.nav-dot');

    const portraitOuter = document.getElementById('portrait-frame-outer');
    const portraitInner = document.getElementById('portrait-frame-inner');
    const bgMale = document.getElementById('bg-graphics-male');
    const bgFemale = document.getElementById('bg-graphics-female');
    const badgeIconEl = document.getElementById('badge-icon-el');
    const badgeTitleEl = document.getElementById('badge-title-el');
    const tagIcon = document.getElementById('tag-icon');
    const tagText = document.getElementById('tag-text');
    const salutationText = document.getElementById('salutation-text');
    const congratHighlight = document.getElementById('congrat-highlight');
    const dividerIcon = document.getElementById('divider-icon');
    const wishSymbolIcon = document.getElementById('wish-symbol-icon');
    const wishSubheading = document.getElementById('wish-subheading');

    const SCENE_A_DURATION = 7500;
    const SCENE_B_DURATION = 5000;

    function clearCycle() {
        cycleTimeouts.forEach(t => clearTimeout(t));
        cycleTimeouts = [];
    }

    function showScene(scene) {
        if (!sceneA || !sceneB) return;
        if (scene === 'a') {
            sceneA.classList.remove('scene-stage-hidden');
            sceneB.classList.add('scene-stage-hidden');
        } else {
            sceneA.classList.add('scene-stage-hidden');
            sceneB.classList.remove('scene-stage-hidden');
        }
    }

    function applyGenderTheme(gender) {
        const isFemale = (gender === 'female');

        // Body class
        bodyEl.classList.remove('theme-male', 'theme-female');
        bodyEl.classList.add(isFemale ? 'theme-female' : 'theme-male');

        // Background layers
        if (bgMale) bgMale.classList.toggle('hidden', isFemale);
        if (bgFemale) bgFemale.classList.toggle('hidden', !isFemale);

        // Executive Royal Baguette Ramka sinflari (STATIC!)
        if (portraitOuter && portraitInner) {
            portraitOuter.className = isFemale ? 'portrait-frame-female' : 'portrait-frame-male';
            portraitInner.className = isFemale ? 'portrait-frame-female-inner' : 'portrait-frame-male-inner';
        }

        // Icons and titles
        if (isFemale) {
            if (badgeIconEl) badgeIconEl.textContent = 'local_florist';
            if (badgeTitleEl) badgeTitleEl.textContent = 'Латофат';
            if (tagIcon) tagIcon.textContent = 'spa';
            if (tagText) tagText.textContent = 'Гўзаллик ва латофат тимсоли';
            if (salutationText) salutationText.textContent = 'Муҳтарама ва мунис ҳамкасбимиз,';
            if (dividerIcon) dividerIcon.textContent = 'local_florist';
            if (wishSymbolIcon) wishSymbolIcon.textContent = 'favorite';
            if (wishSubheading) wishSubheading.textContent = 'Ҳаётингиз баҳор гулларидек гўзал ва нурафшон бўлсин';
            if (nameEl) nameEl.className = 'rose-shimmer';
            if (congratHighlight) congratHighlight.className = 'rose-shimmer';
        } else {
            if (badgeIconEl) badgeIconEl.textContent = 'military_tech';
            if (badgeTitleEl) badgeTitleEl.textContent = 'Жасорат';
            if (tagIcon) tagIcon.textContent = 'military_tech';
            if (tagText) tagText.textContent = 'Мардлик, шараф ва садоқат';
            if (salutationText) salutationText.textContent = 'Ҳурматли ва муҳтарам ҳамкасбимиз,';
            if (dividerIcon) dividerIcon.textContent = 'stars';
            if (wishSymbolIcon) wishSymbolIcon.textContent = 'workspace_premium';
            if (wishSubheading) wishSubheading.textContent = 'Институт ривожи ва Ватан равнақи йўлида фидокорона меҳнатингиз бардавом бўлсин';
            if (nameEl) nameEl.className = 'gold-shimmer';
            if (congratHighlight) congratHighlight.className = 'gold-shimmer';
        }
    }

    function updateFeatured(index) {
        if (employees.length === 0) return;
        currentIndex = ((index % employees.length) + employees.length) % employees.length;
        const emp = employees[currentIndex];

        if (sceneA) sceneA.classList.add('scene-stage-hidden');

        setTimeout(() => {
            applyGenderTheme(emp.gender);

            if (imgEl) imgEl.src = emp.img;
            if (nameEl) nameEl.textContent = emp.name;
            if (roleEl) roleEl.textContent = emp.role;

            const isFemale = (emp.gender === 'female');
            if (wishEl) {
                if (emp.wish && emp.wish.trim().length > 0) {
                    wishEl.innerHTML = emp.wish.replace(/\n/g, '<br/>');
                } else {
                    const defWish = isFemale
                        ? 'Сизга баҳорий кайфият, мустаҳкам соғлик, оилавий хотиржамлик ва беқиёс гўзаллик ҳамиша ҳамроҳ бўлишини тилаймиз!'
                        : 'Сизга мустаҳкам соғлик, узоқ ва мазмунли умр, оилавий хотиржамлик ҳамда масъулиятли фаолиятингизда улкан зафарлар тилаймиз!';
                    wishEl.innerHTML = defWish;
                }
            }

            navDots.forEach((dot, i) => dot.classList.toggle('active', i === currentIndex));
            if (currentNumEl) currentNumEl.textContent = (currentIndex + 1);

            showScene('a');
            burstFallingItems(emp.gender, 20);
        }, 400);

        clearCycle();
        cycleTimeouts.push(setTimeout(startCycle, 500));
    }

    function goToNext() { updateFeatured(currentIndex + 1); }
    function goToPrev() { updateFeatured(currentIndex - 1); }

    function startCycle() {
        if (employees.length === 0) return;
        clearCycle();

        cycleTimeouts.push(setTimeout(() => {
            showScene('b');
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

    // ===== GENTLE FALLING PARTICLES (ROSE PETALS FOR WOMEN, GOLD STARS FOR MEN) =====
    let activeParticles = 0;
    const MAX_PARTICLES = 50;

    function spawnParticle(gender = 'male') {
        if (activeParticles >= MAX_PARTICLES) return;
        const container = document.getElementById('particles-container');
        if (!container) return;

        const p = document.createElement('div');
        p.className = 'falling-item';

        const size = Math.random() * 10 + 8;
        const left = Math.random() * 100;
        const duration = Math.random() * 6 + 6;

        if (gender === 'female') {
            // Rose Petal Shape & Colors
            const colors = ['#fb7185', '#fda4af', '#f43f5e', '#ffe4e6', '#ffd700'];
            const color = colors[Math.floor(Math.random() * colors.length)];
            p.style.width = `${size * 1.3}px`;
            p.style.height = `${size * 1.8}px`;
            p.style.background = color;
            p.style.borderRadius = '50% 0 50% 50%';
            p.style.opacity = (Math.random() * 0.4 + 0.6).toString();
            p.style.boxShadow = `0 0 10px ${color}80`;
        } else {
            // Military Gold Star / Shield particle
            const colors = ['#ffd700', '#f5d76e', '#d4af37', '#8b7500'];
            const color = colors[Math.floor(Math.random() * colors.length)];
            p.style.width = `${size}px`;
            p.style.height = `${size}px`;
            p.style.background = color;
            p.style.borderRadius = Math.random() > 0.4 ? '50%' : '2px';
            p.style.opacity = (Math.random() * 0.4 + 0.5).toString();
            p.style.boxShadow = `0 0 8px ${color}90`;
        }

        p.style.left = `${left}%`;
        p.style.animation = `float-down ${duration}s linear forwards`;

        container.appendChild(p);
        activeParticles++;

        setTimeout(() => {
            p.remove();
            activeParticles--;
        }, duration * 1000 + 500);
    }

    function burstFallingItems(gender, count = 20) {
        for (let i = 0; i < count; i++) {
            setTimeout(() => spawnParticle(gender), i * 70);
        }
    }

    // ===== INIT =====
    document.addEventListener('DOMContentLoaded', () => {
        if (employees.length > 0) {
            const first = employees[0];
            applyGenderTheme(first.gender);
            showScene('a');
            startCycle();
            burstFallingItems(first.gender, 25);

            setInterval(() => {
                const current = employees[currentIndex] || employees[0];
                spawnParticle(current.gender);
            }, 450);
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight') goToNext();
            if (e.key === 'ArrowLeft') goToPrev();
            if (e.key === ' ' || e.key === 'Spacebar') {
                e.preventDefault();
                const isOnA = !sceneA.classList.contains('scene-stage-hidden');
                showScene(isOnA ? 'b' : 'a');
                clearCycle();
                cycleTimeouts.push(setTimeout(startCycle, 2000));
            }
        });
    });
</script>
</body>
</html>
