<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Институт муҳити — Криминология тадқиқот институти</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,900;1,400;1,700&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet"/>
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
            background: linear-gradient(145deg, rgba(28, 22, 18, 0.85) 0%, rgba(12, 9, 7, 0.95) 100%);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(212, 175, 55, 0.35);
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.8), 0 0 40px rgba(212, 175, 55, 0.12);
        }

        .lyric-card {
            background: linear-gradient(145deg, rgba(22, 16, 36, 0.8) 0%, rgba(9, 6, 18, 0.95) 100%);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(168, 85, 247, 0.3);
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.8), 0 0 45px rgba(168, 85, 247, 0.15);
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
            aspect-ratio: 16 / 9;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0,0,0,0.8), 0 0 0 1px rgba(212,175,55,0.3);
        }

        @media (max-width: 768px) {
            .carousel-box { aspect-ratio: 16 / 10; border-radius: 18px; }
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
            padding: clamp(1rem, 3vw, 2.5rem);
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
    $hour = now()->hour;
    if ($hour >= 5 && $hour < 12)      { $greetingText = 'Хайрли тонг'; $greetingIcon = 'wb_twilight'; }
    elseif ($hour >= 12 && $hour < 18) { $greetingText = 'Хайрли кун';  $greetingIcon = 'wb_sunny'; }
    elseif ($hour >= 18 && $hour < 22) { $greetingText = 'Хайрли кеч';  $greetingIcon = 'nights_stay'; }
    else                                { $greetingText = 'Хайрли тун';  $greetingIcon = 'bedtime'; }

    $monthsCy = ['Январ','Феврал','Март','Апрел','Май','Июн','Июл','Август','Сентябр','Октябр','Ноябр','Декабр'];
    $weekdaysCy = ['Якшанба','Душанба','Сешанба','Чоршанба','Пайшанба','Жума','Шанба'];
    $today = now();
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
    <div class="w-full max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-[1.1fr_1fr] gap-8 md:gap-12 items-center">

        {{-- Left: Greeting & Clock --}}
        <div class="space-y-6 md:space-y-8 text-center lg:text-left">
            <div class="flex items-center justify-center lg:justify-start gap-4">
                <div class="logo-ring shrink-0">
                    <div class="p-[2px] rounded-full bg-black">
                        <img src="{{ asset('assets/images/1111222.png') }}"
                             alt="Logo"
                             class="w-16 h-16 md:w-20 md:h-20 rounded-full object-cover"/>
                    </div>
                </div>
                <div>
                    <p class="font-serif text-sm md:text-base leading-tight tracking-wide text-white/90">Ўзбекистон Республикаси</p>
                    <p class="font-serif text-sm md:text-base leading-tight tracking-wide gold-text font-bold">Криминология тадқиқот институти</p>
                </div>
            </div>

            <div class="luxe-glass rounded-3xl p-6 md:p-8 space-y-3">
                <div class="flex items-center justify-center lg:justify-start gap-2">
                    <span class="material-symbols-outlined text-gold text-2xl md:text-3xl" style="font-variation-settings: 'FILL' 1;">{{ $greetingIcon }}</span>
                    <span class="text-xs font-serif uppercase tracking-widest text-gold/80">Кундалик фаолият</span>
                </div>
                <h1 class="font-display font-bold text-3xl sm:text-4xl md:text-5xl lg:text-6xl text-white">
                    <span class="gold-text">{{ $greetingText }},</span><br/>
                    <span>азиз ҳамкасблар!</span>
                </h1>
                <p class="font-serif italic text-white/75 text-sm md:text-base lg:text-lg max-w-lg mx-auto lg:mx-0">
                    Бугунги кунингиз юксак касбий муваффақиятлар, илмий кашфиётлар ва ижобий энергияга бой бўлсин.
                </p>
            </div>

            {{-- Live Clock --}}
            <div class="clock-card rounded-3xl p-6 md:p-8 space-y-2">
                <div class="flex items-center justify-center lg:justify-start gap-2 text-gold/90 text-sm md:text-base">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">calendar_month</span>
                    <span class="font-serif tracking-wider">
                        <span id="current-weekday">{{ $weekdaysCy[$today->dayOfWeek] }}</span>,
                        <span id="current-date">{{ $today->day }}-{{ $monthsCy[$today->month - 1] }} {{ $today->year }}-йил</span>
                    </span>
                </div>

                <div class="flex items-baseline justify-center lg:justify-start gap-1 font-mono font-bold text-4xl sm:text-5xl md:text-6xl lg:text-7xl clock-digits gold-text">
                    <span id="clock-hour">--</span>
                    <span class="clock-colon text-gold/70 mx-1">:</span>
                    <span id="clock-min">--</span>
                    <span class="clock-colon text-gold/70 mx-1">:</span>
                    <span id="clock-sec" class="opacity-70">--</span>
                </div>

                <div class="flex items-center justify-center lg:justify-start gap-2 pt-1">
                    <span class="h-px w-10 bg-gradient-to-r from-transparent to-gold/40"></span>
                    <span id="time-period" class="font-serif italic text-white/60 text-xs md:text-sm uppercase tracking-[0.25em]">Тонг</span>
                    <span class="h-px w-10 bg-gradient-to-l from-transparent to-gold/40"></span>
                </div>
            </div>
        </div>

        {{-- Right: Interactive teaser / Quick look --}}
        <div class="space-y-4">
            <div class="text-center lg:text-left">
                <p class="font-serif italic text-gold/80 text-xs md:text-sm uppercase tracking-[0.25em]">Институт тарихи ва ҳаёти</p>
                <h2 class="font-display font-bold text-2xl md:text-3xl text-white">
                    Бизнинг <span class="gold-text">жамоа ва ижод</span>
                </h2>
            </div>

            @if($groupPhotos->isNotEmpty())
                <div class="carousel-box">
                    <img src="{{ asset('storage/' . $groupPhotos->first()->image_path) }}" alt="Jamoa" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end p-6">
                        <div>
                            <span class="text-xs uppercase tracking-widest text-gold font-bold">Биргаликда кучлимиз</span>
                            <p class="font-display text-xl md:text-2xl font-bold text-white">Криминология тадқиқот институти</p>
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
    <div class="w-full max-w-6xl mx-auto" id="works-stage">
        @if($employeeWorks->isNotEmpty())
            @foreach($employeeWorks as $idx => $work)
                <div class="work-slide {{ $idx === 0 ? '' : 'hidden' }}" id="work-slide-{{ $idx }}">

                    @if($work->layout_theme === 'lyric')
                        {{-- ============ VARIANT 1: LYRIC POETRY ============ --}}
                        <div class="lyric-card rounded-3xl p-6 sm:p-10 md:p-12 relative overflow-hidden border border-purple-500/30">
                            {{-- Watermark feather --}}
                            <div class="absolute -right-8 -bottom-8 opacity-10 pointer-events-none">
                                <span class="material-symbols-outlined text-[240px] text-purple-400">history_edu</span>
                            </div>

                            <div class="text-center max-w-3xl mx-auto space-y-6">
                                {{-- Author Badge --}}
                                <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-purple-900/40 border border-purple-500/30">
                                    @if($work->employee && $work->employee->photo)
                                        <img src="{{ asset('storage/' . $work->employee->photo) }}"
                                             alt="{{ $work->employee->full_name }}"
                                             class="w-10 h-10 rounded-full object-cover border border-purple-300"/>
                                    @endif
                                    <div class="text-left">
                                        <p class="text-sm font-bold text-white leading-tight">{{ $work->employee->full_name ?? 'Институт ходими' }}</p>
                                        <p class="text-[11px] text-purple-300 italic">{{ $work->employee->position ?? '' }}</p>
                                    </div>
                                </div>

                                {{-- Category & Title --}}
                                <div class="space-y-2">
                                    <p class="text-xs uppercase tracking-[0.3em] text-purple-300 font-serif italic">📜 Назмий сатрлар</p>
                                    <h2 class="font-display font-bold text-2xl sm:text-3xl md:text-4xl lg:text-5xl text-white">
                                        «<span class="gold-text">{{ $work->title }}</span>»
                                    </h2>
                                    @if($work->excerpt)
                                        <p class="text-sm text-purple-200/80 italic font-serif">{{ $work->excerpt }}</p>
                                    @endif
                                </div>

                                {{-- Poem lines --}}
                                <div class="py-4 my-2 px-6 rounded-2xl bg-black/30 border border-purple-500/20 max-h-[50vh] overflow-y-auto">
                                    <p class="font-serif italic text-base sm:text-lg md:text-xl lg:text-2xl text-purple-100 leading-relaxed sm:leading-loose whitespace-pre-line">
                                        {{ $work->content }}
                                    </p>
                                </div>
                            </div>
                        </div>

                    @elseif($work->layout_theme === 'book')
                        {{-- ============ VARIANT 2: BOOK / PROSE STORY ============ --}}
                        <div class="book-card rounded-3xl p-6 sm:p-10 md:p-12 relative overflow-hidden border border-amber-600/30">
                            <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-center">
                                {{-- Left: Author Profile --}}
                                <div class="text-center lg:text-left space-y-4">
                                    <div class="relative inline-block mx-auto lg:mx-0">
                                        <div class="w-36 h-36 md:w-48 md:h-48 rounded-2xl overflow-hidden border-2 border-gold shadow-2xl bg-black">
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
                                        <h3 class="font-display font-bold text-xl md:text-2xl text-white">{{ $work->employee->full_name ?? '—' }}</h3>
                                        <p class="text-xs text-amber-200/80 font-serif italic">{{ $work->employee->position ?? '' }}</p>
                                    </div>
                                </div>

                                {{-- Right: Story content --}}
                                <div class="space-y-4">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-gold">auto_stories</span>
                                        <span class="text-xs uppercase tracking-[0.25em] text-gold/90 font-serif">Бадиий наср ва бадиалар</span>
                                    </div>
                                    <h2 class="font-display font-bold text-2xl sm:text-3xl md:text-4xl text-white">
                                        {{ $work->title }}
                                    </h2>
                                    @if($work->excerpt)
                                        <p class="text-amber-200 font-serif italic border-l-2 border-gold pl-3 py-1">{{ $work->excerpt }}</p>
                                    @endif
                                    <div class="p-6 rounded-2xl bg-black/40 border border-gold/20 max-h-[45vh] overflow-y-auto">
                                        <p class="font-serif text-white/90 text-sm sm:text-base md:text-lg leading-relaxed whitespace-pre-line text-justify">
                                            {{ $work->content }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @elseif($work->layout_theme === 'academic')
                        {{-- ============ VARIANT 3: ACADEMIC / SCIENTIFIC ============ --}}
                        <div class="academic-card rounded-3xl p-6 sm:p-10 md:p-12 relative overflow-hidden border border-cyan-500/30">
                            <div class="max-w-4xl mx-auto space-y-6">
                                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-cyan-500/20 pb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-cyan-500/20 border border-cyan-400/40 flex items-center justify-center text-cyan-300">
                                            <span class="material-symbols-outlined text-2xl">science</span>
                                        </div>
                                        <div>
                                            <span class="text-[11px] uppercase tracking-widest text-cyan-300 font-mono font-bold">Илмий тадқиқот ва назария</span>
                                            <h2 class="font-display font-bold text-xl sm:text-2xl md:text-3xl text-white">{{ $work->title }}</h2>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-black/40 border border-cyan-500/30">
                                        @if($work->employee && $work->employee->photo)
                                            <img src="{{ asset('storage/' . $work->employee->photo) }}"
                                                 alt="{{ $work->employee->full_name }}"
                                                 class="w-8 h-8 rounded-full object-cover"/>
                                        @endif
                                        <div class="text-left">
                                            <p class="text-xs font-bold text-white">{{ $work->employee->full_name ?? '' }}</p>
                                            <p class="text-[10px] text-cyan-200/70">{{ $work->employee->position ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>

                                @if($work->excerpt)
                                    <div class="p-3 rounded-xl bg-cyan-950/40 border-l-4 border-cyan-400 text-cyan-100 text-sm font-sans">
                                        <strong>Долзарблиги:</strong> {{ $work->excerpt }}
                                    </div>
                                @endif

                                <div class="p-6 rounded-2xl bg-black/50 border border-cyan-500/20 max-h-[45vh] overflow-y-auto">
                                    <p class="font-sans text-white/95 text-base sm:text-lg md:text-xl leading-relaxed whitespace-pre-line">
                                        {{ $work->content }}
                                    </p>
                                </div>
                            </div>
                        </div>

                    @else
                        {{-- ============ VARIANT 4: MODERN CARD / QUOTE ============ --}}
                        <div class="luxe-glass rounded-3xl p-6 sm:p-10 md:p-12 relative overflow-hidden border border-gold/40 text-center max-w-3xl mx-auto space-y-6">
                            <span class="material-symbols-outlined text-gold text-4xl">format_quote</span>
                            <h2 class="font-display font-bold text-2xl sm:text-3xl md:text-4xl gold-text">
                                {{ $work->title }}
                            </h2>
                            <div class="p-6 rounded-2xl bg-black/40 border border-gold/20 max-h-[45vh] overflow-y-auto">
                                <p class="font-serif italic text-lg sm:text-xl md:text-2xl text-white/90 leading-relaxed whitespace-pre-line">
                                    «{{ $work->content }}»
                                </p>
                            </div>
                            <div class="flex items-center justify-center gap-3 pt-2">
                                @if($work->employee && $work->employee->photo)
                                    <img src="{{ asset('storage/' . $work->employee->photo) }}"
                                         alt="{{ $work->employee->full_name }}"
                                         class="w-12 h-12 rounded-full object-cover border border-gold"/>
                                @endif
                                <div class="text-left">
                                    <p class="font-bold text-base text-white">{{ $work->employee->full_name ?? '—' }}</p>
                                    <p class="text-xs text-gold/80 italic">{{ $work->employee->position ?? '' }}</p>
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
    <div class="w-full max-w-6xl mx-auto space-y-6">
        <div class="text-center space-y-2">
            <p class="font-serif italic text-gold/80 text-xs md:text-sm uppercase tracking-[0.3em]">Бизнинг фаолият</p>
            <h2 class="font-display font-bold text-3xl md:text-4xl lg:text-5xl text-white">
                <span class="gold-text">Криминология</span> жамоаси
            </h2>
        </div>

        @if($groupPhotos->isNotEmpty())
            <div class="carousel-box group" id="gallery-carousel">
                @foreach($groupPhotos as $index => $photo)
                    <div class="carousel-slide {{ $index === 0 ? 'active' : '' }}" id="gal-slide-{{ $index }}">
                        <img src="{{ asset('storage/' . $photo->image_path) }}"
                             alt="Жамоа сурати {{ $index + 1 }}"/>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end p-8">
                            <div class="space-y-1">
                                <span class="text-xs uppercase tracking-widest text-gold font-semibold">Жамоавий тадбирлар ва кундалик фаолият</span>
                                <p class="font-display font-bold text-2xl md:text-3xl text-white">Институт аҳли биргаликда</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($groupPhotos->count() > 1)
                <div class="flex items-center justify-center gap-2 pt-2">
                    @foreach($groupPhotos as $index => $photo)
                        <button onclick="goToGalSlide({{ $index }})"
                                class="gal-dot w-2 h-2 rounded-full {{ $index === 0 ? 'bg-gold w-8' : 'bg-white/30' }} transition-all"
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
{{-- ================= MASTER BOTTOM NAVIGATION ============== --}}
{{-- ======================================================== --}}
<nav class="master-nav">
    <button class="tab-btn active" id="tab-btn-day" onclick="switchMasterScene('day')">
        <span class="material-symbols-outlined text-sm">schedule</span>
        <span>Соат ва кун</span>
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
    // ===== LIVE CLOCK =====
    const hourEl = document.getElementById('clock-hour');
    const minEl = document.getElementById('clock-min');
    const secEl = document.getElementById('clock-sec');
    const periodEl = document.getElementById('time-period');

    function updateClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        if (hourEl) hourEl.textContent = h;
        if (minEl) minEl.textContent = m;
        if (secEl) secEl.textContent = s;

        const hr = now.getHours();
        let period;
        if (hr >= 5 && hr < 12) period = 'Тонг';
        else if (hr >= 12 && hr < 18) period = 'Кун';
        else if (hr >= 18 && hr < 22) period = 'Кеч';
        else period = 'Тун';
        if (periodEl) periodEl.textContent = period;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // ===== MASTER SCENE MANAGER =====
    const scenes = ['day', 'works', 'gallery'];
    let currentSceneIndex = 0;
    let masterTimer = null;

    const sceneDurations = {
        'day': 12000,       // 12 seconds for clock
        'works': 16000,     // 16 seconds for creative works
        'gallery': 12000    // 12 seconds for photos
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
        const dur = sceneDurations[currentName] || 12000;
        masterTimer = setTimeout(() => {
            nextMasterScene();
        }, dur);
    }

    // ===== WORKS SUB-CAROUSEL =====
    const totalWorks = {{ $employeeWorks->count() }};
    let currentWorkIdx = 0;

    function showWorkIndex(idx) {
        if (totalWorks <= 0) return;
        currentWorkIdx = ((idx % totalWorks) + totalWorks) % totalWorks;

        for (let i = 0; i < totalWorks; i++) {
            const el = document.getElementById('work-slide-' + i);
            const dot = document.getElementById('work-dot-' + i);
            if (el) el.classList.toggle('hidden', i !== currentWorkIdx);
            if (dot) {
                dot.classList.toggle('bg-gold', i === currentWorkIdx);
                dot.classList.toggle('w-8', i === currentWorkIdx);
                dot.classList.toggle('bg-white/30', i !== currentWorkIdx);
            }
        }
    }

    if (totalWorks > 1) {
        setInterval(() => {
            if (scenes[currentSceneIndex] === 'works') {
                showWorkIndex(currentWorkIdx + 1);
            }
        }, 8000);
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
