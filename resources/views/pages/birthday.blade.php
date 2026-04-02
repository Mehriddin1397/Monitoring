<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Tug'ilgan Kunlar</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet"/>
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#001e40",
                        "secondary": "#735c00",
                        "secondary-container": "#fed65b",
                        "on-secondary-container": "#745c00",
                        "surface": "#f8f9fa",
                        "surface-variant": "#e1e3e4",
                        "primary-container": "#003366",
                    },
                    fontFamily: {
                        "headline": ["Manrope"],
                        "body": ["Plus Jakarta Sans"],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-delayed': 'float 8s ease-in-out infinite 1s',
                        'confetti': 'confetti 10s linear infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {transform: 'translateY(0) rotate(0deg)'},
                            '50%': {transform: 'translateY(-20px) rotate(5deg)'},
                        },
                        confetti: {
                            '0%': {transform: 'translateY(-10%) rotate(0deg)', opacity: '1'},
                            '100%': {transform: 'translateY(110vh) rotate(360deg)', opacity: '0'},
                        }
                    }
                },
            },
        }
    </script>
    <style>
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .celebratory-gradient {
            background: radial-gradient(circle at top, rgba(0, 51, 102, 0.4) 0%, rgba(248, 249, 250, 1) 100%);
        }

        .balloon {
            position: fixed;
            z-index: 1;
            pointer-events: none;
        }

        .fade-transition {
            transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
        }

        .fade-out {
            opacity: 0;
            transform: translateY(10px);
        }

        .sidebar-active {
            border: 2px solid #735c00 !important;
            background: rgba(255, 255, 255, 0.95) !important;
            transform: translateX(-8px);
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-surface font-body text-on-surface selection:bg-secondary-container min-h-screen overflow-x-hidden">

@php
    // Birinchi xodimni asosiy ekranga chiqarish uchun olib olamiz
    $firstEmployee = $birthdayEmployees->first();
@endphp

<div class="fixed inset-0 -z-20">
    <img alt="Festive modern office" class="w-full h-full object-cover brightness-90 contrast-110"
         src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80"/>
    <div class="absolute inset-0 celebratory-gradient"></div>
</div>

<div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden">
    <div class="balloon animate-float left-[5%] top-[20%] opacity-80">
        <span class="material-symbols-outlined text-secondary-container text-7xl" style="font-variation-settings: 'FILL' 1;">circle</span>
    </div>
    <div class="balloon animate-float-delayed right-[8%] top-[15%] opacity-70">
        <span class="material-symbols-outlined text-primary-container text-8xl" style="font-variation-settings: 'FILL' 1;">circle</span>
    </div>
    <div class="balloon animate-float left-[12%] bottom-[15%] opacity-60">
        <span class="material-symbols-outlined text-secondary text-6xl" style="font-variation-settings: 'FILL' 1;">circle</span>
    </div>

    <div class="absolute top-0 left-1/4 w-2 h-2 bg-yellow-400 rounded-full animate-confetti"></div>
    <div class="absolute top-0 left-1/2 w-3 h-3 bg-blue-400 rounded-sm animate-confetti" style="animation-delay: 2s;"></div>
    <div class="absolute top-0 left-3/4 w-2 h-4 bg-pink-400 rotate-45 animate-confetti" style="animation-delay: 4s;"></div>
    <div class="absolute top-0 left-2/3 w-2 h-2 bg-green-400 rounded-full animate-confetti" style="animation-delay: 1.5s;"></div>
</div>

<main class="relative z-10 container mx-auto pt-12 pb-24 px-6 flex flex-col items-center">
    <header class="w-full text-center mb-16 px-4">
        <div class="inline-flex items-center gap-4 mb-4">
            <span class="h-px w-12 md:w-20 bg-white/50"></span>

            <div class="p-1  rounded-full shadow-lg border border-white/50">
                <img src="{{ asset('assets/images/1111222.png') }}"
                     alt="Antigravity Logo"
                     class="w-40 h-40 rounded-full object-cover">
            </div>

            <span class="h-px w-12 md:w-20 bg-white/50"></span>
        </div>
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-extrabold font-headline text-white tracking-tight drop-shadow-[0_4px_10px_rgba(0,0,0,0.5)]">
            Tug'ilgan kuningiz muborak bo'lsin!
        </h1>
    </header>

    @if($firstEmployee)
        <div class="w-full max-w-7xl grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

            <section class="lg:col-span-8 flex justify-center w-full">
                <div class="glass-panel p-8 md:p-14 rounded-[3rem] shadow-[0_20px_50px_rgba(0,0,0,0.2)] w-full max-w-3xl relative overflow-hidden group border-white/40">
                    <div class="absolute top-0 right-0 p-8">
                        <span class="material-symbols-outlined text-secondary-container text-8xl opacity-40 animate-pulse" style="font-variation-settings: 'FILL' 1;">celebration</span>
                    </div>
                    <div class="absolute bottom-0 left-0 p-8">
                        <span class="material-symbols-outlined text-primary-container text-7xl opacity-20" style="font-variation-settings: 'FILL' 1;">redeem</span>
                    </div>

                    <div class="relative flex flex-col items-center fade-transition" id="featured-content">
                        <div class="relative mb-12">
                            <div class="absolute inset-0 bg-secondary-container/30 blur-3xl rounded-full scale-110 animate-pulse"></div>
                            <div class="w-64 h-64 md:w-80 md:h-80 rounded-[2.5rem] overflow-hidden shadow-2xl p-2 bg-white relative z-10 transform transition-transform duration-700 hover:scale-105">
                                <img id="featured-image"
                                     src="{{ $firstEmployee->photo ? asset('storage/' . $firstEmployee->photo) : 'https://ui-avatars.com/api/?name='.urlencode($firstEmployee->full_name).'&size=512&background=random' }}"
                                     alt="{{ $firstEmployee->full_name }}"
                                     class="w-full h-full object-cover rounded-[2rem]" />
                            </div>
                        </div>
                        <div class="text-center space-y-4">
                            <h2 id="featured-name" class="text-5xl md:text-7xl font-black font-headline text-primary tracking-tighter drop-shadow-sm">
                                {{ $firstEmployee->full_name }}
                            </h2>
                            <div class="flex items-center justify-center gap-4">
                                <span class="h-[2px] w-12 bg-secondary/30"></span>
                                <p id="featured-role" class="text-2xl md:text-3xl font-semibold text-primary/70 font-body uppercase tracking-widest">
                                    {{ $firstEmployee->position }}
                                </p>
                                <span class="h-[2px] w-12 bg-secondary/30"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="lg:col-span-4 space-y-8">
                <div class="flex items-center justify-between px-4">
                    <h3 class="text-white font-headline text-3xl font-black drop-shadow-md">
                        Hamkasblar
                    </h3>
                    <span class="bg-white text-primary text-lg px-4 py-1 rounded-full font-bold shadow-lg">{{ $birthdayEmployees->count() }}</span>
                </div>

                <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar" id="sidebar-list">
                    @foreach($birthdayEmployees as $index => $employee)
                        <div class="sidebar-item glass-panel p-5 rounded-3xl flex items-center gap-5 transition-all cursor-pointer border-white/50 {{ $index === 0 ? 'sidebar-active' : '' }}" onclick="updateFeatured({{ $index }})">
                            <div class="w-20 h-20 rounded-2xl overflow-hidden shadow-lg border-2 border-white shrink-0">
                                <img src="{{ $employee->photo ? asset('storage/' . $employee->photo) : 'https://ui-avatars.com/api/?name='.urlencode($employee->full_name).'&background=random' }}"
                                     alt="{{ $employee->full_name }}"
                                     class="w-full h-full object-cover"/>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xl font-bold text-primary font-headline truncate">{{ $employee->full_name }}</h4>
                                <p class="text-sm text-primary/60 font-bold uppercase tracking-tight truncate">{{ $employee->position }}</p>
                            </div>
                            <span class="material-symbols-outlined text-secondary-container text-3xl transition-opacity" style="font-variation-settings: 'FILL' 1;">cake</span>
                        </div>
                    @endforeach
                </div>


            </section>
        </div>
    @endif
</main>

<script>
    // Laraveldan kelayotgan collection'ni JavaScript array'ga o'tkazamiz
    const employees = [
            @foreach($birthdayEmployees as $employee)
        {
            name: "{{ addslashes($employee->full_name) }}",
            role: "{{ addslashes($employee->position) }}",
            img: "{{ $employee->photo ? asset('storage/' . $employee->photo) : 'https://ui-avatars.com/api/?name='.urlencode($employee->full_name).'&background=random' }}"
        },
        @endforeach
    ];

    let currentIndex = 0;
    const content = document.getElementById('featured-content');
    const imgEl = document.getElementById('featured-image');
    const nameEl = document.getElementById('featured-name');
    const roleEl = document.getElementById('featured-role');
    const sidebarItems = document.querySelectorAll('.sidebar-item');

    function updateFeatured(index) {
        if(employees.length === 0) return;

        currentIndex = index;
        content.classList.add('fade-out');

        setTimeout(() => {
            const emp = employees[index];
            imgEl.src = emp.img;
            nameEl.textContent = emp.name;
            roleEl.textContent = emp.role;

            // Sidebar highlight update
            sidebarItems.forEach((item, i) => {
                if (i === index) {
                    item.classList.add('sidebar-active');
                } else {
                    item.classList.remove('sidebar-active');
                }
            });

            content.classList.remove('fade-out');
        }, 500);
    }

    // Har 5 soniyada keyingi xodimga o'tkazish
    if(employees.length > 1) {
        setInterval(() => {
            let nextIndex = (currentIndex + 1) % employees.length;
            updateFeatured(nextIndex);
        }, 5000);
    }
</script>
</body>
</html>
