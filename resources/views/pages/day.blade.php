<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Институт муҳити — Криминология тадқиқот институти</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,900;1,400;1,700&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&family=Lora:ital,wght@0,400;0,600;1,400;1,600&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "void": "#000000",
                        "obsidian": "#0a0a0f",
                        "midnight": "#0e1118",
                        "gold": "#d4af37",
                        "gold-light": "#f5d76e",
                        "gold-dark": "#8b7500",
                        "gold-bright": "#ffd700",
                        "cyan-glow": "#38bdf8",
                        "emerald-glow": "#34d399",
                    },
                    fontFamily: {
                        "display": ["Playfair Display", "serif"],
                        "serif": ["PT Serif", "serif"],
                        "story": ["Cormorant Garamond", "PT Serif", "serif"],
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
            background: radial-gradient(ellipse at center, #0c101c 0%, #060810 50%, #000000 100%);
        }

        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 300; }

        /* === GRADIENT TEXTS === */
        .gold-text {
            background: linear-gradient(135deg, #f5d76e 0%, #ffd700 25%, #d4af37 50%, #ffd700 75%, #f5d76e 100%);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer-gold 6s ease-in-out infinite;
            display: inline-block;
        }

        .cyan-text {
            background: linear-gradient(135deg, #bae6fd 0%, #38bdf8 35%, #0284c7 70%, #bae6fd 100%);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer-gold 6s ease-in-out infinite;
            display: inline-block;
        }

        .emerald-text {
            background: linear-gradient(135deg, #a7f3d0 0%, #34d399 35%, #059669 70%, #a7f3d0 100%);
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

        /* === GLOW ORBS === */
        .glow-orb {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(140px);
            z-index: 0;
            opacity: 0.18;
            transition: all 1s ease;
        }

        /* === CORNER RIBBONS === */
        .ribbon-svg {
            position: fixed;
            pointer-events: none;
            z-index: 2;
            filter: drop-shadow(0 6px 20px rgba(0, 0, 0, 0.7));
            width: clamp(140px, 20vw, 260px);
            height: clamp(140px, 20vw, 260px);
            opacity: 0.45;
        }

        /* === LOGO RING (STATIC, NO SPIN) === */
        .logo-ring {
            position: relative;
            background: linear-gradient(135deg, #8b7500, #ffd700, #d4af37, #8b7500);
            padding: 4px;
            border-radius: 50%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.6), 0 0 30px rgba(212, 175, 55, 0.3);
        }

        /* === LUXURY GLASS CARDS === */
        .luxe-glass {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.01) 100%);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(212, 175, 55, 0.2);
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.7),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
        }

        .academic-card {
            background: linear-gradient(145deg, rgba(14, 27, 49, 0.8) 0%, rgba(6, 12, 23, 0.9) 100%);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(56, 189, 248, 0.3);
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.8), 0 0 40px rgba(56, 189, 248, 0.15);
        }

        .book-card {
            background: linear-gradient(145deg, rgba(28, 22, 18, 0.88) 0%, rgba(12, 9, 7, 0.96) 100%);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(212, 175, 55, 0.35);
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.8), 0 0 40px rgba(212, 175, 55, 0.12);
        }

        /* === STORY / BADIA CREATIVE AUTO-SCROLL STYLES === */
        .story-scroll-box {
            position: relative;
            background: linear-gradient(180deg, rgba(16, 11, 7, 0.88) 0%, rgba(7, 4, 2, 0.96) 100%);
            border: 1px solid rgba(212, 175, 55, 0.35);
            border-radius: 1.5rem;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.85), inset 0 0 40px rgba(212, 175, 55, 0.05);
            overflow: hidden;
        }

        .story-scroll-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 38px;
            background: linear-gradient(to bottom, rgba(16, 11, 7, 0.98), transparent);
            z-index: 5;
            pointer-events: none;
        }

        .story-scroll-box::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 44px;
            background: linear-gradient(to top, rgba(7, 4, 2, 0.98), transparent);
            z-index: 5;
            pointer-events: none;
        }

        .story-scroll-inner {
            height: clamp(420px, 58vh, 680px);
            overflow-y: scroll;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding: 2rem 2.5rem;
            scroll-behavior: auto;
        }

        .story-scroll-inner::-webkit-scrollbar {
            display: none;
        }

        .story-content-text {
            font-family: 'Cormorant Garamond', 'PT Serif', serif;
            font-size: clamp(1.45rem, 2.05vw, 2.25rem);
            line-height: 1.88;
            letter-spacing: 0.015em;
            color: #fef3c7;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.9);
            white-space: pre-line;
            text-align: justify;
        }

        .story-content-text::first-letter {
            font-family: 'Playfair Display', 'Cormorant Garamond', serif;
            float: left;
            font-size: 3.4em;
            line-height: 0.82;
            padding-right: 0.18em;
            padding-top: 0.08em;
            color: #ffd700;
            font-weight: 700;
            text-shadow: 0 0 25px rgba(255, 215, 0, 0.6);
        }

        .scroll-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            background: rgba(212, 175, 55, 0.12);
            border: 1px solid rgba(212, 175, 55, 0.35);
            font-size: 0.72rem;
            color: #f5d76e;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
        }

        @keyframes pulse-scroll {
            0%, 100% { transform: translateY(0); opacity: 0.6; }
            50% { transform: translateY(3px); opacity: 1; }
        }

        .scroll-arrow-anim {
            animation: pulse-scroll 1.5s ease-in-out infinite;
        }

        .lyric-card {
            background: linear-gradient(145deg, rgba(22, 16, 36, 0.8) 0%, rgba(9, 6, 18, 0.95) 100%);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(168, 85, 247, 0.3);
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.8), 0 0 45px rgba(168, 85, 247, 0.15);
        }

        /* === LATEST NEWS === */
        .news-card {
            background: linear-gradient(145deg, rgba(15, 23, 42, 0.88) 0%, rgba(7, 11, 20, 0.96) 100%);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(56, 189, 248, 0.28);
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.8), 0 0 45px rgba(56, 189, 248, 0.15);
        }
        .news-hero-container {
            position: relative;
            border-radius: 1.25rem;
            overflow: hidden;
            background: #000;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.7);
        }
        .news-thumb {
            transition: all 0.25s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .news-thumb.active {
            border-color: #38bdf8;
            box-shadow: 0 0 12px rgba(56, 189, 248, 0.6);
            transform: scale(1.04);
        }
        .news-content-text {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: clamp(1.2rem, 1.55vw, 1.6rem);
            line-height: 1.9;
            letter-spacing: 0.01em;
            color: #f1f5f9;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.7);
            white-space: pre-line;
            text-align: justify;
        }

        /* === LIVE CLOCK === */
        .clock-card {
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.08) 0%, rgba(0, 0, 0, 0.75) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(212, 175, 55, 0.25);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
        }

        .clock-digits {
            font-feature-settings: "tnum" 1;
            letter-spacing: -0.02em;
        }

        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.3; }
        }
        .clock-colon { animation: blink 1.2s ease-in-out infinite; }

        /* === PHOTO CAROUSEL === */
        .carousel-box {
            position: relative;
            width: 100%;
            height: clamp(440px, 68vh, 740px);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0,0,0,0.8), 0 0 0 1px rgba(212,175,55,0.3);
        }

        @media (max-width: 768px) {
            .carousel-box { height: clamp(280px, 45vh, 400px); border-radius: 18px; }
        }

        .carousel-slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 1.4s ease;
            z-index: 0;
        }
        .carousel-slide.active {
            opacity: 1;
            z-index: 10;
        }
        .carousel-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            animation: ken-burns 14s ease-in-out forwards;
        }

        @keyframes ken-burns {
            0% { transform: scale(1) translate(0, 0); }
            100% { transform: scale(1.12) translate(-1%, -1%); }
        }

        /* === MASTER SCENE MANAGEMENT === */
        .master-scene {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
            transform: scale(1);
            transition: opacity 0.9s ease, transform 0.9s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
            padding: 1.25rem 2rem 5.5rem 2rem;
            box-sizing: border-box;
        }

        .master-scene-hidden {
            opacity: 0;
            pointer-events: none;
            transform: scale(0.97);
        }

        /* === BOTTOM NAV === */
        .master-nav {
            position: fixed;
            bottom: 1.25rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 50;
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            backdrop-filter: blur(16px);
            background: rgba(0, 0, 0, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.8);
        }

        .tab-btn {
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            padding: 0.35rem 0.85rem;
            border-radius: 9999px;
            color: rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .tab-btn.active {
            background: linear-gradient(135deg, #f5d76e, #d4af37);
            color: #000;
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.6);
        }

        /* Star twinkle */
        .star-point {
            position: absolute;
            background: white;
            border-radius: 50%;
            pointer-events: none;
        }
        @keyframes twinkle {
            0%, 100% { opacity: 0.2; transform: scale(1); }
            50% { opacity: 0.9; transform: scale(1.4); }
        }
    </style>
</head>

@php
    $today = now('Asia/Tashkent');
    $hour = $today->hour;

    // Vaqtga qarab 3 ta davr: Tong (05:00 - 11:59), Kun (12:00 - 17:59), Tun (18:00 - 04:59)
    if ($hour >= 5 && $hour < 12) {
        $greetingText = 'Хайрли тонг';
        $greetingIcon = 'wb_twilight';
        $periodText = 'Тонг';
        $periodIcon = 'wb_twilight';
    } elseif ($hour >= 12 && $hour < 18) {
        $greetingText = 'Хайрли кун';
        $greetingIcon = 'wb_sunny';
        $periodText = 'Кун';
        $periodIcon = 'wb_sunny';
    } else {
        $greetingText = 'Хайрли тун';
        $greetingIcon = 'nights_stay';
        $periodText = 'Тун';
        $periodIcon = 'nights_stay';
    }

    $monthsCy = ['Январ','Феврал','Март','Апрел','Май','Июн','Июл','Август','Сентябр','Октябр','Ноябр','Декабр'];
    $weekdaysCy = ['Якшанба','Душанба','Сешанба','Чоршанба','Пайшанба','Жума','Шанба'];
@endphp

<body class="font-sans text-white">

{{-- Background Glow Orbs --}}
<div class="glow-orb" id="orb1" style="width: 600px; height: 600px; top: -150px; left: -100px; background: #38bdf8;"></div>
<div class="glow-orb" id="orb2" style="width: 550px; height: 550px; bottom: -150px; right: -100px; background: #d4af37;"></div>
<div class="glow-orb" id="orb3" style="width: 450px; height: 450px; top: 40%; right: 25%; background: #a855f7;"></div>

{{-- Stars Field --}}
<div class="fixed inset-0 -z-5 overflow-hidden pointer-events-none" id="star-field"></div>

{{-- Corner Ribbons --}}
<svg class="ribbon-svg" style="top: 0; left: 0;" viewBox="0 0 320 320">
    <path d="M -20 -10 Q 80 60, 60 160 T 100 290" stroke="#d4af37" stroke-width="10" fill="none" stroke-linecap="round"/>
</svg>
<svg class="ribbon-svg" style="top: 0; right: 0; transform: scaleX(-1);" viewBox="0 0 320 320">
    <path d="M -20 -10 Q 80 60, 60 160 T 100 290" stroke="#d4af37" stroke-width="10" fill="none" stroke-linecap="round"/>
</svg>
<svg class="ribbon-svg" style="bottom: 0; left: 0; transform: scaleY(-1);" viewBox="0 0 320 320">
    <path d="M -20 -10 Q 80 60, 60 160 T 100 290" stroke="#d4af37" stroke-width="10" fill="none" stroke-linecap="round"/>
</svg>
<svg class="ribbon-svg" style="bottom: 0; right: 0; transform: scale(-1, -1);" viewBox="0 0 320 320">
    <path d="M -20 -10 Q 80 60, 60 160 T 100 290" stroke="#d4af37" stroke-width="10" fill="none" stroke-linecap="round"/>
</svg>

{{-- ======================================================== --}}
{{-- ================= MASTER SCENE 1: CLOCK & DAY ================= --}}
{{-- ======================================================== --}}
<section id="scene-day" class="master-scene">
    <div class="w-full max-w-[96vw] xl:max-w-[1800px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 xl:gap-14 items-center">

        {{-- Left: Greeting & Clock --}}
        <div class="lg:col-span-6 space-y-6 md:space-y-8 text-center lg:text-left">
            <div class="flex items-center justify-center lg:justify-start gap-5">
                <div class="logo-ring shrink-0">
                    <div class="p-[2px] rounded-full bg-black">
                        <img src="{{ asset('assets/images/1111222.png') }}"
                             alt="Logo"
                             class="w-20 h-20 md:w-24 md:h-24 rounded-full object-cover"/>
                    </div>
                </div>
                <div>
                    <p class="font-serif text-sm md:text-lg leading-tight tracking-wide text-white/90">Ўзбекистон Республикаси</p>
                    <p class="font-serif text-base md:text-2xl leading-tight tracking-wide gold-text font-bold">Криминология тадқиқот институти</p>
                </div>
            </div>

            <div class="luxe-glass rounded-3xl p-6 sm:p-8 md:p-10 space-y-4">
                <div class="flex items-center justify-center lg:justify-start gap-2">
                    <span class="material-symbols-outlined text-gold text-2xl md:text-3xl" id="greeting-icon" style="font-variation-settings: 'FILL' 1;">{{ $greetingIcon }}</span>
                    <span class="text-xs sm:text-sm font-serif uppercase tracking-widest text-gold/80">Кундалик фаолият</span>
                </div>
                <h1 class="font-display font-bold text-4xl sm:text-5xl md:text-6xl xl:text-7xl text-white leading-tight">
                    <span class="gold-text" id="greeting-text">{{ $greetingText }},</span><br/>
                    <span>азиз ҳамкасблар!</span>
                </h1>
                <p class="font-serif italic text-white/80 text-base sm:text-lg md:text-xl lg:text-2xl max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    Бугунги кунингиз юксак касбий муваффақиятлар, илмий кашфиётлар ва ижобий энергияга бой бўлсин.
                </p>
            </div>

            {{-- Live Clock & Day --}}
            <div class="clock-card rounded-3xl p-6 sm:p-8 md:p-10 space-y-5">
                {{-- Kun qismi (Hafta kuni va Sana) --}}
                <div class="flex items-center justify-center lg:justify-start gap-2 text-gold/90 text-base md:text-xl lg:text-2xl">
                    <span class="material-symbols-outlined text-gold text-xl md:text-2xl" style="font-variation-settings: 'FILL' 1;">calendar_month</span>
                    <span class="font-serif tracking-wider">
                        <span id="current-weekday">{{ $weekdaysCy[$today->dayOfWeek] }}</span>,
                        <span id="current-date">{{ $today->day }}-{{ $monthsCy[$today->month - 1] }} {{ $today->year }}-йил</span>
                    </span>
                </div>

                {{-- Soat qismi (Raqamli jonli soat) --}}
                <div class="flex items-baseline justify-center lg:justify-start gap-1 font-mono font-bold text-5xl sm:text-7xl md:text-8xl xl:text-9xl clock-digits gold-text">
                    <span id="clock-hour">--</span>
                    <span class="clock-colon text-gold/70 mx-1">:</span>
                    <span id="clock-min">--</span>
                    <span class="clock-colon text-gold/70 mx-1">:</span>
                    <span id="clock-sec" class="opacity-70">--</span>
                </div>

                {{-- Vaqt davri: Тонг / Кун / Тун --}}
                <div class="flex items-center justify-center lg:justify-start gap-2 pt-1">
                    <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-gold/10 border border-gold/30 backdrop-blur-md">
                        <span class="material-symbols-outlined text-gold text-xl md:text-2xl" id="period-icon" style="font-variation-settings: 'FILL' 1;">{{ $periodIcon }}</span>
                        <span id="time-period" class="font-serif font-bold text-gold text-sm md:text-base uppercase tracking-[0.25em]">{{ $periodText }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Interactive teaser / Quick look --}}
        <div class="lg:col-span-6 space-y-4">
            <div class="text-center lg:text-left">
                <p class="font-serif italic text-gold/80 text-sm md:text-base uppercase tracking-[0.25em]">Институт тарихи ва ҳаёти</p>
                <h2 class="font-display font-bold text-3xl md:text-4xl lg:text-5xl text-white">
                    Бизнинг <span class="gold-text">жамоа ва ижод</span>
                </h2>
            </div>

            @if($groupPhotos->isNotEmpty())
                <div class="carousel-box">
                    <img src="{{ asset('storage/' . $groupPhotos->first()->image_path) }}" alt="Jamoa" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-transparent to-transparent flex items-end p-8">
                        <div>
                            <span class="text-xs sm:text-sm uppercase tracking-widest text-gold font-bold">Биргаликда кучлимиз</span>
                            <p class="font-display text-2xl md:text-3xl lg:text-4xl font-bold text-white">Криминология тадқиқот институти</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- ======================================================== --}}
{{-- ================= MASTER SCENE 2: CREATIVE WORKS ======== --}}
{{-- ======================================================== --}}
<section id="scene-works" class="master-scene master-scene-hidden">
    <div class="w-full max-w-[96vw] xl:max-w-[1800px] mx-auto px-2 sm:px-4" id="works-stage">
        @if($employeeWorks->isNotEmpty())
            @foreach($employeeWorks as $idx => $work)
                <div class="work-slide {{ $idx === 0 ? '' : 'hidden' }}" id="work-slide-{{ $idx }}">

                    @if($work->layout_theme === 'lyric')
                        {{-- ============ VARIANT 1: LYRIC POETRY ============ --}}
                        <div class="lyric-card rounded-3xl p-6 sm:p-10 md:p-14 relative overflow-hidden border border-purple-500/30">
                            {{-- Watermark feather --}}
                            <div class="absolute -right-8 -bottom-8 opacity-10 pointer-events-none">
                                <span class="material-symbols-outlined text-[280px] text-purple-400">history_edu</span>
                            </div>

                            <div class="text-center max-w-5xl mx-auto space-y-6">
                                {{-- Author Badge --}}
                                <div class="inline-flex items-center gap-3 px-5 py-2.5 rounded-full bg-purple-900/50 border border-purple-500/40">
                                    @if($work->employee && $work->employee->photo)
                                        <img src="{{ asset('storage/' . $work->employee->photo) }}"
                                             alt="{{ $work->employee->full_name }}"
                                             class="w-12 h-12 rounded-full object-cover border border-purple-300"/>
                                    @endif
                                    <div class="text-left">
                                        <p class="text-base font-bold text-white leading-tight">{{ $work->employee->full_name ?? 'Институт ходими' }}</p>
                                        <p class="text-xs text-purple-300 italic">{{ $work->employee->position ?? '' }}</p>
                                    </div>
                                </div>

                                {{-- Category & Title --}}
                                <div class="space-y-3">
                                    <p class="text-sm uppercase tracking-[0.3em] text-purple-300 font-serif italic">📜 Назмий сатрлар</p>
                                    <h2 class="font-display font-bold text-3xl sm:text-4xl md:text-5xl lg:text-6xl text-white">
                                        «<span class="gold-text">{{ $work->title }}</span>»
                                    </h2>
                                    @if($work->excerpt)
                                        <p class="text-base text-purple-200/80 italic font-serif">{{ $work->excerpt }}</p>
                                    @endif
                                </div>

                                {{-- Poem lines --}}
                                <div class="py-6 my-2 px-8 rounded-2xl bg-black/40 border border-purple-500/30 max-h-[62vh] overflow-y-auto">
                                    <p class="font-serif italic text-lg sm:text-xl md:text-2xl lg:text-3xl text-purple-100 leading-relaxed sm:leading-loose whitespace-pre-line">
                                        {{ $work->content }}
                                    </p>
                                </div>
                            </div>
                        </div>

                    @elseif($work->layout_theme === 'book' || $work->type === 'story')
                        {{-- ============ VARIANT 2: BOOK / PROSE STORY (HIKOYA / BADIA) ============ --}}
                        <div class="book-card rounded-3xl p-6 sm:p-10 md:p-12 relative overflow-hidden border border-amber-600/30">
                            {{-- Watermark book icon in background --}}
                            <div class="absolute -right-8 -bottom-8 opacity-5 pointer-events-none">
                                <span class="material-symbols-outlined text-[320px] text-gold">auto_stories</span>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] xl:grid-cols-[340px_1fr] gap-8 md:gap-12 items-start">
                                {{-- Left: Author Profile --}}
                                <div class="text-center lg:text-left space-y-4">
                                    <div class="relative inline-block mx-auto lg:mx-0">
                                        <div class="w-40 h-40 md:w-52 md:h-52 xl:w-64 xl:h-64 rounded-3xl overflow-hidden border-2 border-gold shadow-2xl bg-black">
                                            @if($work->employee && $work->employee->photo)
                                                <img src="{{ asset('storage/' . $work->employee->photo) }}"
                                                     alt="{{ $work->employee->full_name }}"
                                                     class="w-full h-full object-cover"/>
                                            @else
                                                <img src="{{ asset('assets/images/1111222.png') }}"
                                                     alt="Logo"
                                                     class="w-full h-full object-cover"/>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase tracking-widest text-gold font-serif">Муаллиф:</p>
                                        <h3 class="font-display font-bold text-xl md:text-3xl text-white">{{ $work->employee->full_name ?? '—' }}</h3>
                                        <p class="text-sm text-amber-200/80 font-serif italic">{{ $work->employee->position ?? '' }}</p>
                                    </div>

                                    <div class="pt-2">
                                        <span class="scroll-badge">
                                            <span class="material-symbols-outlined text-sm scroll-arrow-anim">arrow_downward</span>
                                            <span>Автоматик ҳаракат</span>
                                        </span>
                                    </div>
                                </div>

                                {{-- Right: Story content --}}
                                <div class="space-y-4 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-gold text-xl">auto_stories</span>
                                        <span class="text-xs sm:text-sm uppercase tracking-[0.25em] text-gold/90 font-serif font-bold">Ҳикоя ва бадиалар</span>
                                    </div>
                                    <h2 class="font-display font-bold text-3xl sm:text-4xl md:text-5xl lg:text-6xl text-white leading-tight">
                                        {{ $work->title }}
                                    </h2>
                                    @if($work->excerpt)
                                        <p class="text-amber-200/90 font-serif italic text-base md:text-lg border-l-2 border-gold pl-4 py-1">
                                            {{ $work->excerpt }}
                                        </p>
                                    @endif

                                    {{-- Creative Large Text Auto-Scroll Box --}}
                                    <div class="story-scroll-box">
                                        <div class="story-scroll-inner autoscroll-target" id="story-scroll-{{ $idx }}">
                                            <div class="story-content-text">
                                                {{ $work->content }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @elseif($work->layout_theme === 'academic')
                        {{-- ============ VARIANT 3: ACADEMIC / SCIENTIFIC ============ --}}
                        <div class="academic-card rounded-3xl p-6 sm:p-10 md:p-14 relative overflow-hidden border border-cyan-500/30">
                            <div class="max-w-6xl mx-auto space-y-8">
                                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-cyan-500/20 pb-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-xl bg-cyan-500/20 border border-cyan-400/40 flex items-center justify-center text-cyan-300">
                                            <span class="material-symbols-outlined text-3xl">science</span>
                                        </div>
                                        <div>
                                            <span class="text-xs uppercase tracking-widest text-cyan-300 font-mono font-bold">Илмий тадқиқот ва назария</span>
                                            <h2 class="font-display font-bold text-2xl sm:text-3xl md:text-4xl text-white">{{ $work->title }}</h2>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3 px-4 py-2 rounded-xl bg-black/40 border border-cyan-500/30">
                                        @if($work->employee && $work->employee->photo)
                                            <img src="{{ asset('storage/' . $work->employee->photo) }}"
                                                 alt="{{ $work->employee->full_name }}"
                                                 class="w-10 h-10 rounded-full object-cover"/>
                                        @endif
                                        <div class="text-left">
                                            <p class="text-sm font-bold text-white">{{ $work->employee->full_name ?? '' }}</p>
                                            <p class="text-xs text-cyan-200/70">{{ $work->employee->position ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>

                                @if($work->excerpt)
                                    <div class="p-4 rounded-xl bg-cyan-950/40 border-l-4 border-cyan-400 text-cyan-100 text-base font-sans">
                                        <strong>Долзарблиги:</strong> {{ $work->excerpt }}
                                    </div>
                                @endif

                                <div class="p-8 rounded-2xl bg-black/50 border border-cyan-500/20 max-h-[58vh] overflow-y-auto">
                                    <p class="font-sans text-white/95 text-lg sm:text-xl md:text-2xl leading-relaxed whitespace-pre-line">
                                        {{ $work->content }}
                                    </p>
                                </div>
                            </div>
                        </div>

                    @else
                        {{-- ============ VARIANT 4: MODERN CARD / QUOTE ============ --}}
                        <div class="luxe-glass rounded-3xl p-8 sm:p-12 md:p-16 relative overflow-hidden border border-gold/40 text-center max-w-5xl mx-auto space-y-8">
                            <span class="material-symbols-outlined text-gold text-5xl">format_quote</span>
                            <h2 class="font-display font-bold text-3xl sm:text-4xl md:text-5xl lg:text-6xl gold-text">
                                {{ $work->title }}
                            </h2>
                            <div class="p-8 rounded-2xl bg-black/40 border border-gold/20 max-h-[58vh] overflow-y-auto">
                                <p class="font-serif italic text-xl sm:text-2xl md:text-3xl text-white/90 leading-relaxed whitespace-pre-line">
                                    «{{ $work->content }}»
                                </p>
                            </div>
                            <div class="flex items-center justify-center gap-4 pt-2">
                                @if($work->employee && $work->employee->photo)
                                    <img src="{{ asset('storage/' . $work->employee->photo) }}"
                                         alt="{{ $work->employee->full_name }}"
                                         class="w-14 h-14 rounded-full object-cover border border-gold"/>
                                @endif
                                <div class="text-left">
                                    <p class="font-bold text-lg text-white">{{ $work->employee->full_name ?? '—' }}</p>
                                    <p class="text-sm text-gold/80 italic">{{ $work->employee->position ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            @endforeach

            {{-- Works Sub-Nav (if multiple works) --}}
            @if($employeeWorks->count() > 1)
                <div class="flex items-center justify-center gap-2 pt-6">
                    @foreach($employeeWorks as $idx => $w)
                        <button onclick="showWorkIndex({{ $idx }})"
                                class="work-dot w-2.5 h-2.5 rounded-full {{ $idx === 0 ? 'bg-gold w-8 shadow-lg shadow-gold/50' : 'bg-white/30' }} transition-all"
                                id="work-dot-{{ $idx }}"></button>
                    @endforeach
                </div>
            @endif
        @else
            <div class="luxe-glass rounded-3xl p-12 text-center max-w-xl mx-auto space-y-4">
                <span class="material-symbols-outlined text-gold text-6xl">edit_note</span>
                <h3 class="font-display font-bold text-2xl text-white">Ижодий ишлар кутилмоқда</h3>
                <p class="text-sm text-white/70">Админ панел орқали ходимларнинг шеър, ҳикоя ва илмий фикрларини киритинг.</p>
            </div>
        @endif
    </div>
</section>

{{-- ======================================================== --}}
{{-- ================= MASTER SCENE 3: GALLERY =============== --}}
{{-- ======================================================== --}}
<section id="scene-gallery" class="master-scene master-scene-hidden">
    <div class="w-full max-w-[96vw] xl:max-w-[1800px] mx-auto space-y-4 px-2 sm:px-4">
        <div class="text-center space-y-2">
            <p class="font-serif italic text-gold/80 text-sm md:text-base uppercase tracking-[0.3em]">Бизнинг фаолият</p>
            <h2 class="font-display font-bold text-3xl md:text-5xl lg:text-6xl text-white">
                <span class="gold-text">Криминология</span> жамоаси
            </h2>
        </div>

        @if($groupPhotos->isNotEmpty())
            <div class="carousel-box group" id="gallery-carousel">
                @foreach($groupPhotos as $index => $photo)
                    <div class="carousel-slide {{ $index === 0 ? 'active' : '' }}" id="gal-slide-{{ $index }}">
                        <img src="{{ asset('storage/' . $photo->image_path) }}"
                             alt="Жамоа сурати {{ $index + 1 }}"/>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-transparent to-transparent flex items-end p-8 md:p-12">
                            <div class="space-y-2">
                                <span class="text-xs sm:text-sm md:text-base uppercase tracking-widest text-gold font-semibold">Жамоавий тадбирлар ва кундалик фаолият</span>
                                <p class="font-display font-bold text-3xl md:text-4xl lg:text-5xl text-white">Институт аҳли биргаликда</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($groupPhotos->count() > 1)
                <div class="flex items-center justify-center gap-2 pt-3">
                    @foreach($groupPhotos as $index => $photo)
                        <button onclick="goToGalSlide({{ $index }})"
                                class="gal-dot w-2.5 h-2.5 rounded-full {{ $index === 0 ? 'bg-gold w-10 shadow-lg shadow-gold/50' : 'bg-white/30' }} transition-all"
                                id="gal-dot-{{ $index }}"></button>
                    @endforeach
                </div>
            @endif
        @else
            <div class="luxe-glass rounded-3xl p-12 text-center max-w-xl mx-auto space-y-4">
                <span class="material-symbols-outlined text-gold text-6xl">photo_library</span>
                <h3 class="font-display font-bold text-2xl text-white">Жамоа суратлари мавжуд эмас</h3>
                <p class="text-sm text-white/70">Админ панелнинг «Гуруҳ расмлари» бўлимидан суратлар юкланг.</p>
            </div>
        @endif
    </div>
</section>

{{-- ======================================================== --}}
{{-- ================= MASTER SCENE: LATEST NEWS ============ --}}
{{-- ======================================================== --}}
<section id="scene-news" class="master-scene master-scene-hidden">
    <div class="w-full max-w-[96vw] xl:max-w-[1800px] mx-auto z-10 px-2 sm:px-4">
        @if(isset($latestNews) && $latestNews->count() > 0)
            @foreach($latestNews as $nIdx => $item)
                @php
                    $imgs = is_array($item->images) ? $item->images : [];
                @endphp
                <div class="news-slide {{ $nIdx > 0 ? 'hidden' : '' }}" id="news-slide-{{ $nIdx }}">
                    <div class="news-card rounded-3xl p-6 sm:p-10 md:p-12 relative overflow-hidden">
                        {{-- Top Section: Category, Date, Pagination --}}
                        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-white/10 pb-5 mb-6">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-sky-400 text-2xl">newspaper</span>
                                <span class="text-xs sm:text-sm uppercase tracking-[0.25em] text-sky-300 font-bold">
                                    {{ $item->category ?: 'Сўнгги янгиликлар' }}
                                </span>
                                @if($item->published_date)
                                    <span class="text-white/40 text-xs sm:text-sm">•</span>
                                    <span class="text-xs sm:text-sm text-white/70 font-mono">
                                        {{ \Carbon\Carbon::parse($item->published_date)->format('d.m.Y') }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="scroll-badge" style="background: rgba(56, 189, 248, 0.12); border-color: rgba(56, 189, 248, 0.35); color: #7dd3fc;">
                                    <span class="material-symbols-outlined text-sm scroll-arrow-anim">arrow_downward</span>
                                    <span>Автоматик ҳаракат</span>
                                </span>
                                @if($latestNews->count() > 1)
                                    <span class="px-3.5 py-1.5 rounded-full bg-white/10 text-xs sm:text-sm font-mono text-white/90">
                                        {{ $nIdx + 1 }} / {{ $latestNews->count() }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Body: Left (Multi-Image Display) & Right (Title + Auto-scroll Content) --}}
                        <div class="grid grid-cols-1 {{ count($imgs) > 0 ? 'lg:grid-cols-12' : '' }} gap-8 lg:gap-12 items-start">
                            @if(count($imgs) > 0)
                                {{-- Left: Multi-Image Showcase --}}
                                <div class="lg:col-span-5 space-y-4">
                                    {{-- Main Hero Image --}}
                                    <div class="news-hero-container aspect-[16/11] min-h-[360px] lg:min-h-[460px] w-full">
                                        @foreach($imgs as $pIdx => $img)
                                            <img src="{{ asset('storage/' . $img) }}"
                                                 id="news-img-{{ $nIdx }}-{{ $pIdx }}"
                                                 class="news-hero-img-{{ $nIdx }} w-full h-full object-cover transition-opacity duration-700 absolute inset-0 {{ $pIdx === 0 ? 'opacity-100' : 'opacity-0' }}"
                                                 alt="{{ $item->title }} - {{ $pIdx + 1 }}" />
                                        @endforeach
                                        @if(count($imgs) > 1)
                                            <div class="absolute bottom-3 right-3 px-3 py-1.5 rounded-full bg-black/75 backdrop-blur-md text-xs font-mono text-white/90 flex items-center gap-1.5 border border-white/20">
                                                <span class="material-symbols-outlined text-sm text-sky-400">photo_library</span>
                                                <span id="news-counter-{{ $nIdx }}">1 / {{ count($imgs) }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Thumbnails strip if more than 1 image --}}
                                    @if(count($imgs) > 1)
                                        <div class="flex items-center gap-2.5 overflow-x-auto pb-1 max-w-full">
                                            @foreach($imgs as $pIdx => $img)
                                                <div onclick="selectNewsPhoto({{ $nIdx }}, {{ $pIdx }})"
                                                     id="news-thumb-{{ $nIdx }}-{{ $pIdx }}"
                                                     class="news-thumb news-thumb-{{ $nIdx }} w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 {{ $pIdx === 0 ? 'active' : 'opacity-60 hover:opacity-100' }} bg-black">
                                                    <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover" alt="Thumb {{ $pIdx + 1 }}" />
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- Right: Content & Title --}}
                            <div class="{{ count($imgs) > 0 ? 'lg:col-span-7' : 'lg:col-span-12' }} space-y-4 min-w-0">
                                <h2 class="font-display font-bold text-3xl sm:text-4xl md:text-5xl lg:text-6xl text-white leading-tight">
                                    {{ $item->title }}
                                </h2>

                                {{-- Auto-Scrolling News Content Box --}}
                                <div class="story-scroll-box" style="border-color: rgba(56, 189, 248, 0.25);">
                                    <div class="story-scroll-inner autoscroll-target" id="news-scroll-{{ $nIdx }}">
                                        <div class="news-content-text">
                                            {!! nl2br(e($item->content)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Dots pagination if multiple news --}}
            @if($latestNews->count() > 1)
                <div class="flex items-center justify-center gap-2 pt-4">
                    @foreach($latestNews as $nIdx => $item)
                        <button onclick="showNewsIndex({{ $nIdx }})"
                                class="news-dot w-2.5 h-2.5 rounded-full {{ $nIdx === 0 ? 'bg-sky-400 w-10 shadow-lg shadow-sky-400/50' : 'bg-white/30' }} transition-all"
                                id="news-dot-{{ $nIdx }}"></button>
                    @endforeach
                </div>
            @endif
        @else
            <div class="luxe-glass rounded-3xl p-12 text-center max-w-xl mx-auto space-y-4">
                <span class="material-symbols-outlined text-sky-400 text-6xl">feed</span>
                <h3 class="font-display font-bold text-2xl text-white">Янгиликлар мавжуд эмас</h3>
                <p class="text-sm text-white/70">Админ панелнинг «Сўнгги янгиликлар» бўлимидан янгиликлар юкланг.</p>
            </div>
        @endif
    </div>
</section>

{{-- ======================================================== --}}
{{-- ================= MASTER BOTTOM NAVIGATION ============== --}}
{{-- ======================================================== --}}
<nav class="master-nav">
    <button class="tab-btn active" id="tab-btn-day" onclick="switchMasterScene('day')">
        <span class="material-symbols-outlined text-sm">schedule</span>
        <span>Соат ва кун</span>
    </button>
    <button class="tab-btn" id="tab-btn-news" onclick="switchMasterScene('news')">
        <span class="material-symbols-outlined text-sm">newspaper</span>
        <span>Сўнгги янгиликлар</span>
    </button>
    <button class="tab-btn" id="tab-btn-works" onclick="switchMasterScene('works')">
        <span class="material-symbols-outlined text-sm">auto_stories</span>
        <span>Ходимлар ижоди</span>
    </button>
    <button class="tab-btn" id="tab-btn-gallery" onclick="switchMasterScene('gallery')">
        <span class="material-symbols-outlined text-sm">photo_camera</span>
        <span>Жамоа галереяси</span>
    </button>
</nav>

<script>
    // ===== LIVE CLOCK & DAY =====
    const hourEl = document.getElementById('clock-hour');
    const minEl = document.getElementById('clock-min');
    const secEl = document.getElementById('clock-sec');
    const periodEl = document.getElementById('time-period');
    const periodIconEl = document.getElementById('period-icon');
    const greetingEl = document.getElementById('greeting-text');
    const greetingIconEl = document.getElementById('greeting-icon');
    const weekdayEl = document.getElementById('current-weekday');
    const dateEl = document.getElementById('current-date');

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

        const hr = now.getHours();
        let period, greeting, icon;

        // Vaqtga qarab 3 ta davr: Tong (05:00 - 11:59), Kun (12:00 - 17:59), Tun (18:00 - 04:59)
        if (hr >= 5 && hr < 12) {
            period = 'Тонг';
            greeting = 'Хайрли тонг';
            icon = 'wb_twilight';
        } else if (hr >= 12 && hr < 18) {
            period = 'Кун';
            greeting = 'Хайрли кун';
            icon = 'wb_sunny';
        } else {
            period = 'Тун';
            greeting = 'Хайрли тун';
            icon = 'nights_stay';
        }

        if (periodEl) periodEl.textContent = period;
        if (periodIconEl) periodIconEl.textContent = icon;
        if (greetingEl) greetingEl.textContent = greeting + ',';
        if (greetingIconEl) greetingIconEl.textContent = icon;

        // Sana va hafta kunini yangilash
        if (weekdayEl) weekdayEl.textContent = weekdaysCy[now.getDay()];
        if (dateEl) dateEl.textContent = `${now.getDate()}-${monthsCy[now.getMonth()]} ${now.getFullYear()}-йил`;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // ===== MASTER SCENE MANAGER =====
    const scenes = ['day', 'news', 'works', 'gallery'];
    let currentSceneIndex = 0;
    let masterTimer = null;

    const sceneDurations = {
        'day': 14000,       // 14 seconds for clock & day
        'news': 26000,      // 26 seconds for latest news
        'works': 30000,     // 30 seconds for creative works (gives ample time to auto-scroll stories)
        'gallery': 14000    // 14 seconds for photos
    };

    function switchMasterScene(sceneName) {
        scenes.forEach(name => {
            const sec = document.getElementById('scene-' + name);
            const btn = document.getElementById('tab-btn-' + name);
            if (sec) {
                if (name === sceneName) {
                    sec.classList.remove('master-scene-hidden');
                } else {
                    sec.classList.add('master-scene-hidden');
                }
            }
            if (btn) btn.classList.toggle('active', name === sceneName);
        });

        currentSceneIndex = scenes.indexOf(sceneName);

        if (sceneName === 'works') {
            showWorkIndex(currentWorkIdx);
        } else {
            stopStoryAutoScroll();
        }

        if (sceneName === 'news') {
            showNewsIndex(currentNewsIdx);
        } else {
            stopNewsPhotoCycle();
        }

        restartMasterTimer();
    }

    function nextMasterScene() {
        currentSceneIndex = (currentSceneIndex + 1) % scenes.length;
        switchMasterScene(scenes[currentSceneIndex]);
    }

    function prevMasterScene() {
        currentSceneIndex = (currentSceneIndex - 1 + scenes.length) % scenes.length;
        switchMasterScene(scenes[currentSceneIndex]);
    }

    function restartMasterTimer() {
        if (masterTimer) clearTimeout(masterTimer);
        const currentName = scenes[currentSceneIndex];
        const dur = sceneDurations[currentName] || 14000;
        masterTimer = setTimeout(() => {
            nextMasterScene();
        }, dur);
    }

    // ===== STORY / BADIA AUTO-SCROLL (MOUSE WHEEL SIMULATION) =====
    let storyScrollInterval = null;
    let storyScrollTimer = null;
    let workSwitchTimer = null;

    function stopStoryAutoScroll() {
        if (storyScrollInterval) {
            clearInterval(storyScrollInterval);
            storyScrollInterval = null;
        }
        if (storyScrollTimer) {
            clearTimeout(storyScrollTimer);
            storyScrollTimer = null;
        }
    }

    function startStoryAutoScroll(slideElement) {
        stopStoryAutoScroll();
        if (!slideElement) return;

        const container = slideElement.querySelector('.autoscroll-target');
        if (!container) return;

        // Reset to top
        container.scrollTop = 0;

        let isUserInteracting = false;
        let resumeTimeout = null;

        // Foydalanuvchi kursor yoki sichqoncha g'ildiragini sursa, 3 soniya kutib keyin davom etadi
        if (!container._hasInteractionListeners) {
            container._hasInteractionListeners = true;
            const onUserTouch = () => {
                isUserInteracting = true;
                if (resumeTimeout) clearTimeout(resumeTimeout);
                resumeTimeout = setTimeout(() => {
                    isUserInteracting = false;
                }, 3000);
            };
            container.addEventListener('wheel', onUserTouch, { passive: true });
            container.addEventListener('touchstart', onUserTouch, { passive: true });
            container.addEventListener('mousedown', onUserTouch, { passive: true });
        }

        // 1 soniya kutib, xuddi kursorni pastga sekin aylantirgandek harakatlanish boshlanadi
        storyScrollTimer = setTimeout(() => {
            let reachedBottom = false;

            storyScrollInterval = setInterval(() => {
                if (isUserInteracting) return;

                const maxScroll = container.scrollHeight - container.clientHeight;
                if (maxScroll <= 2) {
                    return; // Agar matn to'liq sig'ib tursa, harakat shart emas
                }

                if (!reachedBottom) {
                    // Har 35ms da aniq 1 piksel pastga siljiydi (soniyasiga ~28px - kursor g'ildiragi kabi)
                    container.scrollTop += 1;

                    // Eng pastiga yetganda
                    if (container.scrollTop >= maxScroll - 2) {
                        reachedBottom = true;
                        // Pastki qismda 4 soniya to'xtab turadi
                        storyScrollTimer = setTimeout(() => {
                            // Silliq qilib boshiga qaytaradi
                            container.scrollTo({ top: 0, behavior: 'smooth' });
                            storyScrollTimer = setTimeout(() => {
                                reachedBottom = false;
                            }, 2000);
                        }, 4000);
                    }
                }
            }, 35);
        }, 1000);
    }

    // ===== NEWS SUB-CAROUSEL & MULTI-PHOTO CYCLE =====
    const totalNews = {{ isset($latestNews) ? $latestNews->count() : 0 }};
    let currentNewsIdx = 0;
    let newsSwitchTimer = null;
    let newsPhotoCycleTimer = null;
    const currentPhotoIdxPerNews = {};

    function selectNewsPhoto(newsIdx, photoIdx) {
        const heroImgs = document.querySelectorAll('.news-hero-img-' + newsIdx);
        const thumbs = document.querySelectorAll('.news-thumb-' + newsIdx);
        const counter = document.getElementById('news-counter-' + newsIdx);
        if (!heroImgs.length) return;

        currentPhotoIdxPerNews[newsIdx] = photoIdx % heroImgs.length;

        heroImgs.forEach((img, i) => {
            img.classList.toggle('opacity-100', i === currentPhotoIdxPerNews[newsIdx]);
            img.classList.toggle('opacity-0', i !== currentPhotoIdxPerNews[newsIdx]);
        });

        thumbs.forEach((thumb, i) => {
            thumb.classList.toggle('active', i === currentPhotoIdxPerNews[newsIdx]);
            thumb.classList.toggle('opacity-60', i !== currentPhotoIdxPerNews[newsIdx]);
        });

        if (counter) {
            counter.textContent = `${currentPhotoIdxPerNews[newsIdx] + 1} / ${heroImgs.length}`;
        }
    }

    function stopNewsPhotoCycle() {
        if (newsPhotoCycleTimer) {
            clearInterval(newsPhotoCycleTimer);
            newsPhotoCycleTimer = null;
        }
    }

    function startNewsPhotoCycle(newsIdx) {
        stopNewsPhotoCycle();
        const heroImgs = document.querySelectorAll('.news-hero-img-' + newsIdx);
        if (heroImgs.length <= 1) return;

        newsPhotoCycleTimer = setInterval(() => {
            if (scenes[currentSceneIndex] === 'news') {
                const cur = currentPhotoIdxPerNews[newsIdx] || 0;
                const nextP = (cur + 1) % heroImgs.length;
                selectNewsPhoto(newsIdx, nextP);
            }
        }, 4500);
    }

    function showNewsIndex(idx) {
        if (totalNews <= 0) return;
        currentNewsIdx = ((idx % totalNews) + totalNews) % totalNews;

        stopStoryAutoScroll();
        stopNewsPhotoCycle();

        let activeSlide = null;
        for (let i = 0; i < totalNews; i++) {
            const el = document.getElementById('news-slide-' + i);
            const dot = document.getElementById('news-dot-' + i);
            const isActive = (i === currentNewsIdx);
            if (el) {
                el.classList.toggle('hidden', !isActive);
                if (isActive) activeSlide = el;
            }
            if (dot) {
                dot.classList.toggle('bg-sky-400', isActive);
                dot.classList.toggle('w-8', isActive);
                dot.classList.toggle('bg-white/30', !isActive);
            }
        }

        if (activeSlide) {
            startStoryAutoScroll(activeSlide);
            startNewsPhotoCycle(currentNewsIdx);
        }

        scheduleNextNews();
    }

    function scheduleNextNews() {
        if (newsSwitchTimer) clearTimeout(newsSwitchTimer);
        if (totalNews <= 1) return;

        newsSwitchTimer = setTimeout(() => {
            if (scenes[currentSceneIndex] === 'news') {
                showNewsIndex(currentNewsIdx + 1);
            }
        }, 26000);
    }

    // ===== WORKS SUB-CAROUSEL =====
    const totalWorks = {{ $employeeWorks->count() }};
    let currentWorkIdx = 0;

    function showWorkIndex(idx) {
        if (totalWorks <= 0) return;
        currentWorkIdx = ((idx % totalWorks) + totalWorks) % totalWorks;

        stopStoryAutoScroll();

        let activeSlide = null;
        for (let i = 0; i < totalWorks; i++) {
            const el = document.getElementById('work-slide-' + i);
            const dot = document.getElementById('work-dot-' + i);
            const isActive = (i === currentWorkIdx);
            if (el) {
                el.classList.toggle('hidden', !isActive);
                if (isActive) activeSlide = el;
            }
            if (dot) {
                dot.classList.toggle('bg-gold', isActive);
                dot.classList.toggle('w-8', isActive);
                dot.classList.toggle('bg-white/30', !isActive);
            }
        }

        // Faol slayd hikoya/badia bo'lsa avtomatik harakatlantiramiz
        if (activeSlide) {
            startStoryAutoScroll(activeSlide);
        }

        scheduleNextWork();
    }

    function scheduleNextWork() {
        if (workSwitchTimer) clearTimeout(workSwitchTimer);
        if (totalWorks <= 1) return;

        const currentSlide = document.getElementById('work-slide-' + currentWorkIdx);
        const hasStoryScroll = currentSlide && currentSlide.querySelector('.autoscroll-target');

        // Hikoya/badia matnlari uzun bo'lgani uchun 32 soniya, boshqa qisqa ishlar uchun 10 soniya
        const delay = hasStoryScroll ? 32000 : 10000;

        workSwitchTimer = setTimeout(() => {
            if (scenes[currentSceneIndex] === 'works') {
                showWorkIndex(currentWorkIdx + 1);
            }
        }, delay);
    }

    // ===== GALLERY SUB-CAROUSEL =====
    const totalPhotos = {{ $groupPhotos->count() }};
    let currentGalIdx = 0;

    function goToGalSlide(idx) {
        if (totalPhotos <= 0) return;
        currentGalIdx = ((idx % totalPhotos) + totalPhotos) % totalPhotos;

        for (let i = 0; i < totalPhotos; i++) {
            const slide = document.getElementById('gal-slide-' + i);
            const dot = document.getElementById('gal-dot-' + i);
            if (slide) slide.classList.toggle('active', i === currentGalIdx);
            if (dot) {
                dot.classList.toggle('bg-gold', i === currentGalIdx);
                dot.classList.toggle('w-8', i === currentGalIdx);
                dot.classList.toggle('bg-white/30', i !== currentGalIdx);
            }
        }
    }

    if (totalPhotos > 1) {
        setInterval(() => {
            if (scenes[currentSceneIndex] === 'gallery') {
                goToGalSlide(currentGalIdx + 1);
            }
        }, 6000);
    }

    // ===== STAR BACKGROUND =====
    function createStarField() {
        const field = document.getElementById('star-field');
        if (!field) return;
        for (let i = 0; i < 50; i++) {
            const star = document.createElement('div');
            star.className = 'star-point';
            const size = Math.random() * 2 + 1;
            star.style.width = `${size}px`;
            star.style.height = `${size}px`;
            star.style.left = `${Math.random() * 100}%`;
            star.style.top = `${Math.random() * 100}%`;
            star.style.opacity = (Math.random() * 0.6 + 0.2).toString();
            star.style.animation = `twinkle ${Math.random() * 3 + 2}s ease-in-out infinite ${Math.random() * 3}s`;
            field.appendChild(star);
        }
    }

    // ===== INIT =====
    document.addEventListener('DOMContentLoaded', () => {
        createStarField();
        restartMasterTimer();

        // Keyboard controls
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight') nextMasterScene();
            if (e.key === 'ArrowLeft') prevMasterScene();
        });
    });
</script>
</body>
</html>
