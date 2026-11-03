@php
    $todayDate = now('Asia/Tashkent');
    // Tug'ilgan kuni bugun bo'lgan xodimlarni olish (oy va kun mosligi)
    $birthdayEmployees = \App\Models\Employee::whereMonth('birth_date', $todayDate->month)
        ->whereDay('birth_date', $todayDate->day)
        ->get();

    // Dasturchi/admin uchun test ko'rish rejimi (?test_bday=1 bo'lsa va ro'yxat bo'sh bo'lsa)
    if ($birthdayEmployees->isEmpty() && request()->has('test_bday')) {
        $firstEmp = \App\Models\Employee::whereNotNull('photo')->first() ?? \App\Models\Employee::first();
        if ($firstEmp) {
            $birthdayEmployees = collect([$firstEmp]);
        }
    }
@endphp

@if($birthdayEmployees->isNotEmpty())
<div id="bdayCelebrationModalOverlay" class="bday-celebration-overlay" style="display: none;">
    <!-- Confetti Canvas -->
    <canvas id="bdayConfettiCanvas" class="bday-confetti-canvas"></canvas>

    <!-- Modal Box -->
    <div class="bday-modal-dialog">
        <!-- Close button -->
        <button type="button" class="bday-close-btn" onclick="closeBirthdayCelebration(true)" aria-label="Ёпиш">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <!-- Top celebration badge -->
        <div class="bday-header-badge">
            <span class="bday-sparkle-icon">✨</span>
            <span class="bday-badge-text">ТАВАЛЛУД АЙЁМИНГИЗ МУБОРАК БЎЛСИН!</span>
            <span class="bday-sparkle-icon">✨</span>
        </div>

        <div class="bday-inst-title">
            Ўзбекистон Республикаси Криминология тадқиқот институти
        </div>

        <!-- Slider container (bir nechta xodim bo'lsa o'tish imkoni) -->
        <div class="bday-slider-container">
            @foreach($birthdayEmployees as $idx => $emp)
                <div class="bday-slide {{ $idx === 0 ? 'active' : '' }}" data-slide-index="{{ $idx }}">
                    <!-- Photo with luxury festive ring -->
                    <div class="bday-avatar-wrapper">
                        <div class="bday-avatar-glow"></div>
                        <div class="bday-avatar-ring">
                            @if($emp->photo && file_exists(public_path('storage/' . $emp->photo)))
                                <img src="{{ asset('storage/' . $emp->photo) }}" alt="{{ $emp->full_name }}" class="bday-avatar-img">
                            @elseif($emp->photo && file_exists(public_path($emp->photo)))
                                <img src="{{ asset($emp->photo) }}" alt="{{ $emp->full_name }}" class="bday-avatar-img">
                            @else
                                <div class="bday-avatar-placeholder">
                                    <svg width="70" height="70" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <span class="bday-cake-badge">🎂</span>
                    </div>

                    <!-- Celebrant info -->
                    <h2 class="bday-emp-name">{{ $emp->full_name }}</h2>
                    <div class="bday-emp-position">
                        <span class="bday-pos-icon">💼</span>
                        <span>{{ $emp->position ?? 'Институт ходими' }}</span>
                    </div>

                    <!-- Wish message box -->
                    <div class="bday-wish-card">
                        <div class="bday-quote-icon">“</div>
                        @if(!empty($emp->custom_wish))
                            <p class="bday-wish-text">{{ $emp->custom_wish }}</p>
                        @else
                            <p class="bday-wish-text">
                                Муҳтарам ҳамкасб! Сизни бугунги қутлуғ ва қувончли айём — таваллуд кунингиз билан институт раҳбарияти ва жамоаси номидан чин қалбдан муборакбод этамиз!
                            </p>
                            <p class="bday-wish-subtext">
                                Сизга мустаҳкам соғлик-омонлик, узоқ ва мазмунли умр, оилавий хотиржамлик ҳамда масъулиятли илмий-амалий фаолиятингизда улкан муваффақиятлар тилаймиз!
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($birthdayEmployees->count() > 1)
            <!-- Multi-celebrant pagination -->
            <div class="bday-pagination">
                <button type="button" class="bday-nav-btn" onclick="prevBirthdaySlide()">‹ Олдинги</button>
                <span id="bdaySlideCounter" class="bday-counter">1 / {{ $birthdayEmployees->count() }}</span>
                <button type="button" class="bday-nav-btn" onclick="nextBirthdaySlide()">Кейинги ›</button>
            </div>
        @endif

        <!-- Footer Actions -->
        <div class="bday-actions">
            <button type="button" class="bday-congrat-btn" onclick="celebrateWithMoreConfetti()">
                <span class="bday-btn-icon">🎉</span>
                <span>Чин дилдан табриклаймиз!</span>
            </button>
            <button type="button" class="bday-later-btn" onclick="closeBirthdayCelebration(true)">
                Раҳмат, ёпиш
            </button>
        </div>
    </div>
</div>

<!-- Floating re-open trigger button (sahifada qoladi, xohlagan payt qayta ochish uchun) -->
<button type="button" id="bdayFloatingBtn" class="bday-floating-trigger" onclick="openBirthdayCelebration()" title="Бугун таваллуд айём! Кўриш учун босинг">
    <span class="bday-float-icon">🎂</span>
    <span class="bday-float-text">Бугун таваллуд айём</span>
    <span class="bday-float-badge">{{ $birthdayEmployees->count() }}</span>
</button>

<style>
/* ================= CELEBRATION MODAL STYLES ================= */
.bday-celebration-overlay {
    position: fixed;
    inset: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(3, 8, 18, 0.82);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: 999999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    transition: opacity 0.35s ease-out;
}

.bday-celebration-overlay.bday-visible {
    opacity: 1;
}

.bday-confetti-canvas {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
}

.bday-modal-dialog {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 580px;
    background: linear-gradient(145deg, #0e1e2e 0%, #08111a 60%, #04090e 100%);
    border: 2px solid rgba(212, 175, 55, 0.65);
    border-radius: 28px;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.85),
                0 0 45px rgba(212, 175, 55, 0.28),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
    padding: 36px 32px 30px;
    text-align: center;
    color: #ffffff;
    transform: scale(0.85) translateY(20px);
    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.bday-celebration-overlay.bday-visible .bday-modal-dialog {
    transform: scale(1) translateY(0);
}

/* Close Button */
.bday-close-btn {
    position: absolute;
    top: 18px;
    right: 18px;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #cbd5e1;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.bday-close-btn:hover {
    background: rgba(239, 68, 68, 0.25);
    border-color: #ef4444;
    color: #ffffff;
    transform: rotate(90deg);
}

/* Header Badge */
.bday-header-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(90deg, rgba(212, 175, 55, 0.15), rgba(212, 175, 55, 0.35), rgba(212, 175, 55, 0.15));
    border: 1px solid rgba(212, 175, 55, 0.5);
    padding: 6px 18px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 1px;
    color: #f5d76e;
    text-transform: uppercase;
    box-shadow: 0 0 15px rgba(212, 175, 55, 0.2);
    margin-bottom: 8px;
}

.bday-inst-title {
    font-size: 13px;
    color: #94a3b8;
    letter-spacing: 0.5px;
    margin-bottom: 22px;
}

/* Avatar Frame */
.bday-avatar-wrapper {
    position: relative;
    width: 140px;
    height: 140px;
    margin: 0 auto 18px;
}

.bday-avatar-glow {
    position: absolute;
    inset: -8px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(212, 175, 55, 0.6) 0%, rgba(212, 175, 55, 0) 70%);
    animation: bdayPulseGlow 3s infinite ease-in-out;
}

@keyframes bdayPulseGlow {
    0%, 100% { opacity: 0.5; transform: scale(0.96); }
    50% { opacity: 0.9; transform: scale(1.06); }
}

.bday-avatar-ring {
    position: relative;
    width: 140px;
    height: 140px;
    border-radius: 50%;
    padding: 4px;
    background: linear-gradient(135deg, #d4af37 0%, #f5d76e 50%, #8b7500 100%);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
}

.bday-avatar-img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    background: #020617;
    display: block;
}

.bday-avatar-placeholder {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: #1e293b;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
}

.bday-cake-badge {
    position: absolute;
    bottom: -2px;
    right: 2px;
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border: 2px solid #ffffff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    animation: bdayBounce 2.5s infinite ease-in-out;
}

@keyframes bdayBounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

/* Employee Details */
.bday-emp-name {
    font-size: 23px;
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 6px;
    line-height: 1.3;
    letter-spacing: -0.2px;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.6);
}

.bday-emp-position {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    color: #f5d76e;
    font-weight: 600;
    background: rgba(212, 175, 55, 0.12);
    border: 1px solid rgba(212, 175, 55, 0.3);
    padding: 4px 14px;
    border-radius: 20px;
    margin-bottom: 20px;
}

/* Wish Card */
.bday-wish-card {
    position: relative;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(212, 175, 55, 0.22);
    border-radius: 18px;
    padding: 18px 22px;
    margin-bottom: 24px;
    text-align: center;
}

.bday-quote-icon {
    font-family: Georgia, serif;
    font-size: 40px;
    line-height: 20px;
    color: rgba(212, 175, 55, 0.35);
    height: 18px;
}

.bday-wish-text {
    font-size: 15px;
    color: #f1f5f9;
    line-height: 1.6;
    margin: 6px 0 6px;
    font-weight: 500;
}

.bday-wish-subtext {
    font-size: 13.5px;
    color: #94a3b8;
    line-height: 1.55;
    margin: 6px 0 0;
}

/* Multi-slide */
.bday-slide {
    display: none;
}
.bday-slide.active {
    display: block;
    animation: bdaySlideFade 0.4s ease-out;
}

@keyframes bdaySlideFade {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}

.bday-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    margin-bottom: 20px;
}

.bday-nav-btn {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #ffffff;
    padding: 4px 12px;
    border-radius: 8px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.bday-nav-btn:hover {
    background: rgba(212, 175, 55, 0.25);
    border-color: #d4af37;
}

.bday-counter {
    font-size: 13px;
    color: #cbd5e1;
    font-weight: 600;
}

/* Actions */
.bday-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    align-items: center;
}

.bday-congrat-btn {
    flex: 1;
    max-width: 290px;
    background: linear-gradient(135deg, #d4af37 0%, #b8860b 50%, #996515 100%);
    border: none;
    color: #0b141e;
    font-weight: 800;
    font-size: 15px;
    padding: 12px 20px;
    border-radius: 14px;
    cursor: pointer;
    box-shadow: 0 8px 20px rgba(212, 175, 55, 0.35);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.25s ease;
}

.bday-congrat-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 26px rgba(212, 175, 55, 0.5);
    background: linear-gradient(135deg, #f5d76e 0%, #d4af37 50%, #b8860b 100%);
}

.bday-congrat-btn:active {
    transform: translateY(0);
}

.bday-later-btn {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.18);
    color: #cbd5e1;
    font-weight: 600;
    font-size: 14px;
    padding: 12px 18px;
    border-radius: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.bday-later-btn:hover {
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff;
}

/* Floating Trigger Button */
.bday-floating-trigger {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 99990;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, #0f273d 0%, #0a1826 100%);
    border: 1.5px solid #d4af37;
    border-radius: 40px;
    padding: 10px 18px 10px 14px;
    color: #ffffff;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5), 0 0 16px rgba(212, 175, 55, 0.35);
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    animation: bdayFloatGlow 3s infinite ease-in-out;
}

@keyframes bdayFloatGlow {
    0%, 100% {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5), 0 0 14px rgba(212, 175, 55, 0.3);
        transform: translateY(0);
    }
    50% {
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.6), 0 0 24px rgba(212, 175, 55, 0.6);
        transform: translateY(-4px);
    }
}

.bday-floating-trigger:hover {
    transform: translateY(-6px) scale(1.05);
    border-color: #f5d76e;
}

.bday-float-icon {
    font-size: 22px;
}

.bday-float-text {
    font-size: 13.5px;
    font-weight: 700;
    color: #f5d76e;
    letter-spacing: 0.2px;
}

.bday-float-badge {
    background: #ef4444;
    color: #ffffff;
    font-size: 11px;
    font-weight: 800;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1.5px solid #ffffff;
}

@media (max-width: 576px) {
    .bday-modal-dialog {
        padding: 26px 18px 22px;
        border-radius: 20px;
    }
    .bday-emp-name {
        font-size: 19px;
    }
    .bday-avatar-wrapper, .bday-avatar-ring {
        width: 110px;
        height: 110px;
    }
    .bday-actions {
        flex-direction: column;
    }
    .bday-congrat-btn, .bday-later-btn {
        width: 100%;
        max-width: none;
    }
    .bday-floating-trigger .bday-float-text {
        display: none;
    }
    .bday-floating-trigger {
        padding: 10px;
        border-radius: 50%;
    }
}
</style>

<script>
(function() {
    const todayStr = '{{ $todayDate->format("Y-m-d") }}';
    const storageKey = 'smart_ijro_bday_seen_' + todayStr;
    let currentSlide = 0;
    const totalSlides = {{ $birthdayEmployees->count() }};
    let confettiTimer = null;
    let confettiActive = false;

    // 09:00 AM target time builder
    function getTodayNineAM() {
        const t = new Date();
        t.setHours(9, 0, 0, 0);
        return t;
    }

    // Modalni ochish
    window.openBirthdayCelebration = function() {
        const overlay = document.getElementById('bdayCelebrationModalOverlay');
        if (!overlay) return;

        overlay.style.display = 'flex';
        // Force reflow for animation
        void overlay.offsetWidth;
        overlay.classList.add('bday-visible');

        // Confetti boshlash
        startConfettiAnimation();
    };

    // Modalni yopish
    window.closeBirthdayCelebration = function(markAsSeen = true) {
        if (markAsSeen) {
            try {
                localStorage.setItem(storageKey, '1');
            } catch (e) {}
        }
        const overlay = document.getElementById('bdayCelebrationModalOverlay');
        if (!overlay) return;

        overlay.classList.remove('bday-visible');
        stopConfettiAnimation();
        setTimeout(function() {
            overlay.style.display = 'none';
        }, 360);
    };

    // "Табриклаймиз" tugmasi bosilganda
    window.celebrateWithMoreConfetti = function() {
        burstConfetti(180);
        const btn = document.querySelector('.bday-congrat-btn');
        if (btn) {
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<span>✨ Табригингиз қабул қилинди!</span>';
            btn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
            btn.style.color = '#ffffff';
            setTimeout(function() {
                closeBirthdayCelebration(true);
                setTimeout(function() {
                    btn.innerHTML = originalHTML;
                    btn.style.background = '';
                    btn.style.color = '';
                }, 400);
            }, 1400);
        }
    };

    // Slider navigatsiyasi
    function showSlide(index) {
        const slides = document.querySelectorAll('.bday-slide');
        if (!slides.length) return;
        currentSlide = (index + slides.length) % slides.length;
        slides.forEach((s, i) => {
            s.classList.toggle('active', i === currentSlide);
        });
        const counter = document.getElementById('bdaySlideCounter');
        if (counter) {
            counter.innerText = (currentSlide + 1) + ' / ' + slides.length;
        }
    }

    window.nextBirthdaySlide = function() {
        showSlide(currentSlide + 1);
    };

    window.prevBirthdaySlide = function() {
        showSlide(currentSlide - 1);
    };

    // Esc bosilganda yoki backdrop bosilganda yopish
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const overlay = document.getElementById('bdayCelebrationModalOverlay');
            if (overlay && overlay.classList.contains('bday-visible')) {
                closeBirthdayCelebration(true);
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const overlay = document.getElementById('bdayCelebrationModalOverlay');
        if (overlay) {
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    closeBirthdayCelebration(true);
                }
            });
        }

        // ================= VAZIFA TALABIGA BINOAN VAQT MANTIQI =================
        // 1. Agar foydalanuvchi tizim ichida bo'lsa va soat 9:00 ga yetmagan bo'lsa -> 9:00:00 da chiqadi.
        // 2. Agar tizimda bo'lmagan bo'lsa va 9:00 dan keyin tizimga kirgan bo'lsa -> kirgan vaqti darhol chiqadi.
        const urlParams = new URLSearchParams(window.location.search);
        const isForceTest = urlParams.has('test_bday');

        if (isForceTest) {
            setTimeout(openBirthdayCelebration, 600);
            return;
        }

        const now = new Date();
        const targetNineAM = getTodayNineAM();
        const alreadySeen = localStorage.getItem(storageKey);

        if (now >= targetNineAM) {
            // 9:00 dan keyin kirgan xolat:
            // Agar bugun hali ko'rmagan bo'lsa, kirgan vaqtida chiqadi
            if (!alreadySeen) {
                setTimeout(openBirthdayCelebration, 1200);
            }
        } else {
            // 9:00 dan oldin tizim ichida bo'lgan xolat:
            // 9:00:00 ga qadar qolgan millisekundlar hisoblanadi va taymer o'rnatiladi
            const msUntilNine = targetNineAM.getTime() - now.getTime();
            console.log('[Smart-Ijro] Bugungi tug\'ilgan kun tabrigi soat 09:00:00 da ochiladi (qolgan vaqt: ' + Math.round(msUntilNine / 1000) + ' soniya).');
            setTimeout(function() {
                openBirthdayCelebration();
            }, msUntilNine);
        }
    });

    // ================= ENGINES: STANDALONE CONFETTI =================
    let particles = [];
    const colors = ['#d4af37', '#f5d76e', '#ef4444', '#3b82f6', '#10b981', '#ec4899', '#8b5cf6', '#ffffff'];

    function initCanvas() {
        const canvas = document.getElementById('bdayConfettiCanvas');
        if (!canvas) return null;
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        return canvas;
    }

    function createParticle() {
        const w = window.innerWidth;
        return {
            x: Math.random() * w,
            y: -20 - Math.random() * 50,
            size: Math.random() * 9 + 5,
            color: colors[Math.floor(Math.random() * colors.length)],
            speedY: Math.random() * 3 + 2.5,
            speedX: Math.random() * 2 - 1,
            rotation: Math.random() * 360,
            rotationSpeed: Math.random() * 6 - 3,
            wobble: Math.random() * 10,
            wobbleSpeed: Math.random() * 0.08 + 0.04
        };
    }

    function burstConfetti(count = 120) {
        for (let i = 0; i < count; i++) {
            particles.push(createParticle());
        }
    }

    function renderConfetti() {
        if (!confettiActive && particles.length === 0) return;
        const canvas = document.getElementById('bdayConfettiCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Keep generating if active
        if (confettiActive && particles.length < 150) {
            particles.push(createParticle());
        }

        for (let i = particles.length - 1; i >= 0; i--) {
            const p = particles[i];
            p.y += p.speedY;
            p.x += Math.sin(p.wobble) * 1.5 + p.speedX;
            p.wobble += p.wobbleSpeed;
            p.rotation += p.rotationSpeed;

            ctx.save();
            ctx.translate(p.x, p.y);
            ctx.rotate((p.rotation * Math.PI) / 180);
            ctx.fillStyle = p.color;
            ctx.fillRect(-p.size / 2, -p.size / 2, p.size, p.size * 0.6);
            ctx.restore();

            if (p.y > canvas.height + 30) {
                particles.splice(i, 1);
            }
        }

        confettiTimer = requestAnimationFrame(renderConfetti);
    }

    function startConfettiAnimation() {
        initCanvas();
        confettiActive = true;
        particles = [];
        burstConfetti(100);
        if (confettiTimer) cancelAnimationFrame(confettiTimer);
        renderConfetti();

        // 6 soniyadan keyin yangi zarralar ishlab chiqarishni to'xtatish (CPU tejash uchun)
        setTimeout(function() {
            confettiActive = false;
        }, 6000);
    }

    function stopConfettiAnimation() {
        confettiActive = false;
        particles = [];
        if (confettiTimer) {
            cancelAnimationFrame(confettiTimer);
            confettiTimer = null;
        }
        const canvas = document.getElementById('bdayConfettiCanvas');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
    }

    window.addEventListener('resize', function() {
        const canvas = document.getElementById('bdayConfettiCanvas');
        if (canvas) {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
    });
})();
</script>
@endif
