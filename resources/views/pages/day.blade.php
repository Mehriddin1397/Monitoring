<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Xayrli kun</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "error": "#ba1a1a",
                        "secondary-fixed-dim": "#e9c349",
                        "on-error-container": "#93000a",
                        "surface-container-low": "#f3f4f5",
                        "on-secondary-fixed": "#241a00",
                        "on-error": "#ffffff",
                        "on-primary-fixed-variant": "#1f477b",
                        "tertiary": "#745b00",
                        "surface-container-lowest": "#ffffff",
                        "secondary": "#735c00",
                        "on-background": "#191c1d",
                        "on-primary": "#ffffff",
                        "tertiary-fixed-dim": "#eac249",
                        "tertiary-container": "#cca72f",
                        "inverse-surface": "#2e3132",
                        "on-tertiary-fixed-variant": "#584400",
                        "primary": "#001e40",
                        "on-tertiary-container": "#4f3d00",
                        "surface": "#f8f9fa",
                        "inverse-on-surface": "#f0f1f2",
                        "surface-variant": "#e1e3e4",
                        "surface-dim": "#d9dadb",
                        "secondary-container": "#fed65b",
                        "inverse-primary": "#a7c8ff",
                        "secondary-fixed": "#ffe088",
                        "tertiary-fixed": "#ffe08b",
                        "surface-container-high": "#e7e8e9",
                        "primary-container": "#003366",
                        "on-tertiary-fixed": "#241a00",
                        "on-surface-variant": "#43474f",
                        "surface-tint": "#3a5f94",
                        "on-surface": "#191c1d",
                        "outline-variant": "#c3c6d1",
                        "primary-fixed-dim": "#a7c8ff",
                        "on-primary-container": "#799dd6",
                        "background": "#f8f9fa",
                        "primary-fixed": "#d5e3ff",
                        "on-primary-fixed": "#001b3c",
                        "on-secondary-fixed-variant": "#574500",
                        "on-secondary-container": "#745c00",
                        "on-secondary": "#ffffff",
                        "surface-bright": "#f8f9fa",
                        "outline": "#737780",
                        "on-tertiary": "#ffffff",
                        "surface-container": "#edeeef",
                        "error-container": "#ffdad6",
                        "surface-container-highest": "#e1e3e4"
                    },
                    fontFamily: {
                        "headline": ["Manrope"],
                        "body": ["Plus Jakarta Sans"],
                        "label": ["Plus Jakarta Sans"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .glass-card {
            background: rgba(225, 227, 228, 0.4);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .gold-gradient {
            background: linear-gradient(135deg, #001e40 0%, #003366 100%);
        }

        .carousel-item {
            opacity: 0;
            transition: opacity 1s ease-in-out;
            position: absolute;
            inset: 0;
            z-index: 0;
        }

        .carousel-item.active {
            opacity: 1;
            z-index: 10;
        }
    </style>
</head>
<body class="bg-surface font-body text-on-surface antialiased overflow-x-hidden">
<div class="fixed inset-0 z-[-1]">
    <img class="w-full h-full object-cover brightness-[0.8]" data-alt="luxury modern office background"
         src="{{ asset('assets/images/1212.jpg') }}"/>
    <div class="absolute inset-0 bg-primary/20 backdrop-blur-[4px]"></div>
</div>

<main class="min-h-screen flex flex-col items-center px-6 py-12 md:px-20 md:py-20 max-w-7xl mx-auto">
    <header class="text-center space-y-8 mb-16 w-full">
        <div class="flex justify-center mb-8">
            <div class="p-1 rounded-full shadow-lg border border-white/50">
                <img src="{{ asset('assets/images/1111222.png') }}"
                     alt="Logo"
                     class="w-40 h-40 rounded-full object-cover">
            </div>
        </div>
        <div class="space-y-4">
            <h1 class="font-headline font-extrabold text-5xl md:text-8xl text-primary leading-tight tracking-tight drop-shadow-sm"
                id="greeting">
                Xayrli kun, <br class="md:hidden"/>aziz hamkasblar!
            </h1>
            <div class="inline-flex flex-col items-center" id="date-time-container">
                <p class="font-headline font-semibold text-2xl md:text-3xl text-secondary-container bg-primary px-8 py-2 rounded-full shadow-lg"
                   id="current-date">
                    --:--
                </p>
                <p class="font-body font-medium text-lg md:text-xl text-primary mt-4 tracking-widest uppercase"
                   id="current-time">
                    --
                </p>
            </div>
        </div>
    </header>

    <section class="relative w-full aspect-video md:aspect-[21/9] rounded-[3rem] overflow-hidden shadow-2xl border-4 border-white/20 group">
        <div class="relative w-full h-full" id="carousel">

            @if($groupPhotos->isNotEmpty())
                @foreach($groupPhotos as $index => $photo)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img class="w-full h-full object-cover"
                             src="{{ asset('storage/' . $photo->image_path) }}"
                             alt="Antigravity Guruh Rasmi"/>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex items-end p-12">
                            <p class="text-white font-headline text-2xl font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                Kriminologiya jamoasi
                            </p>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="carousel-item active">
                    <img class="w-full h-full object-cover"
                         src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&q=80"
                         alt="Team collaborating"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex items-end p-12">
                        <p class="text-white font-headline text-2xl font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                            Kriminologiya jamoasi
                        </p>
                    </div>
                </div>
            @endif

        </div>

        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex gap-3">
            @if($groupPhotos->isNotEmpty())
                @foreach($groupPhotos as $index => $photo)
                    <div class="carousel-dot w-3 h-3 rounded-full {{ $index === 0 ? 'bg-white' : 'bg-white/40' }} transition-colors duration-300"></div>
                @endforeach
            @else
                <div class="carousel-dot w-3 h-3 rounded-full bg-white transition-colors duration-300"></div>
            @endif
        </div>
    </section>
</main>

<script>
    // Dynamic Date and Time Update
    function updateDateTime() {
        const dateEl = document.getElementById('current-date');
        const timeEl = document.getElementById('current-time');
        const now = new Date();

        // Format time
        const timeOptions = {hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false};
        timeEl.textContent = now.toLocaleTimeString('uz-UZ', timeOptions);

        // Format date
        const dateOptions = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
        dateEl.textContent = now.toLocaleDateString('uz-UZ', dateOptions);
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();

    // Automatic Carousel Logic
    let currentSlide = 0;
    const slides = document.querySelectorAll('.carousel-item');
    const dots = document.querySelectorAll('.carousel-dot');

    function updateCarousel() {
        if(slides.length === 0) return;

        slides.forEach((slide, index) => {
            slide.classList.toggle('active', index === currentSlide);
        });
        dots.forEach((dot, index) => {
            dot.style.backgroundColor = index === currentSlide ? '#ffffff' : 'rgba(255, 255, 255, 0.4)';
        });
        currentSlide = (currentSlide + 1) % slides.length;
    }

    // Faqatgina 1 tadan ko'p rasm bo'lsa carousel aylansin
    if(slides.length > 1) {
        setInterval(updateCarousel, 5000); // Change image every 5 seconds
    }
</script>
</body>
</html>
