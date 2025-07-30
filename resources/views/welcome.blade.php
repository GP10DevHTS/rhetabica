<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Debate Tournament Tabulation System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'debate-blue': '#0ea5e9',
                        'debate-purple': '#8b5cf6',
                        'debate-gold': '#f59e0b',
                        'debate-dark': '#0f172a',
                        'neon-cyan': '#06b6d4',
                        'neon-pink': '#ec4899',
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'fade-in': 'fadeIn 0.8s ease-out',
                        'bounce-gentle': 'bounceGentle 2s ease-in-out infinite',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        .mono { font-family: 'JetBrains Mono', monospace; }

        body {
            background: radial-gradient(ellipse at top, #1e293b 0%, #0f172a 45%, #020617 100%);
            min-height: 100vh;
        }

        .mesh-gradient {
            background:
                radial-gradient(at 27% 37%, hsla(215, 98%, 61%, 0.3) 0px, transparent 50%),
                radial-gradient(at 97% 21%, hsla(125, 98%, 72%, 0.2) 0px, transparent 50%),
                radial-gradient(at 52% 99%, hsla(354, 98%, 61%, 0.2) 0px, transparent 50%),
                radial-gradient(at 10% 29%, hsla(256, 96%, 67%, 0.3) 0px, transparent 50%),
                radial-gradient(at 97% 96%, hsla(38, 60%, 74%, 0.2) 0px, transparent 50%),
                radial-gradient(at 33% 50%, hsla(222, 67%, 73%, 0.2) 0px, transparent 50%),
                radial-gradient(at 79% 53%, hsla(343, 68%, 79%, 0.2) 0px, transparent 50%);
        }

        .glass-morphism {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        .glass-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.08));
            border: 1px solid rgba(255, 255, 255, 0.2);
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
        }

        .neon-glow {
            filter: drop-shadow(0 0 20px rgba(6, 182, 212, 0.5));
        }

        .text-gradient-premium {
            background: linear-gradient(135deg, #60a5fa, #a78bfa, #fbbf24, #fb7185);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 4s ease infinite;
        }

        .hero-text-shadow {
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(5deg); }
        }

        @keyframes glow {
            from { filter: drop-shadow(0 0 20px rgba(6, 182, 212, 0.3)); }
            to { filter: drop-shadow(0 0 30px rgba(6, 182, 212, 0.7)); }
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes bounceGentle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .feature-icon {
            background: linear-gradient(135deg, #0ea5e9, #8b5cf6);
            transition: all 0.3s ease;
        }

        .feature-icon:hover {
            background: linear-gradient(135deg, #06b6d4, #a855f7);
            transform: scale(1.1) rotate(5deg);
        }

        .stats-card {
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.1), rgba(139, 92, 246, 0.1));
            border: 1px solid rgba(14, 165, 233, 0.2);
        }

        .cta-button {
            background: linear-gradient(135deg, #0ea5e9, #8b5cf6, #f59e0b);
            background-size: 200% 200%;
            animation: gradientShift 3s ease infinite;
            position: relative;
            overflow: hidden;
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .cta-button:hover::before {
            left: 100%;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: #60a5fa;
            border-radius: 50%;
            opacity: 0.7;
            animation: particle-float 8s infinite linear;
        }

        @keyframes particle-float {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 0.7; }
            90% { opacity: 0.7; }
            100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }

        .testimonial-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.03));
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
</head>
<body class="text-white overflow-x-hidden">
    <!-- Floating Particles -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
        <div class="particle" style="left: 20%; animation-delay: 2s;"></div>
        <div class="particle" style="left: 30%; animation-delay: 4s;"></div>
        <div class="particle" style="left: 40%; animation-delay: 1s;"></div>
        <div class="particle" style="left: 50%; animation-delay: 3s;"></div>
        <div class="particle" style="left: 60%; animation-delay: 5s;"></div>
        <div class="particle" style="left: 70%; animation-delay: 1.5s;"></div>
        <div class="particle" style="left: 80%; animation-delay: 3.5s;"></div>
        <div class="particle" style="left: 90%; animation-delay: 0.5s;"></div>
    </div>

    <!-- Header -->
    <header class="fixed top-0 w-full z-50 glass-morphism">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center space-x-4">

                        <x-app-logo />

                </div>

                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-300 hover:text-white transition-colors font-medium">Features</a>
                    <a href="#testimonials" class="text-gray-300 hover:text-white transition-colors font-medium">Success Stories</a>
                    <a href="#pricing" class="text-gray-300 hover:text-white transition-colors font-medium">Pricing</a>
                </nav>

                @if (Route::has('login'))
                    <nav class="flex items-center justify-end gap-4">
                        @auth
                            <a
                                href="{{ url('/dashboard') }}"
                                class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 transition-all duration-300 font-semibold transform hover:scale-105"
                            >
                                Dashboard
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="px-6 py-3 rounded-xl glass-morphism hover:bg-white/10 transition-all duration-300 font-medium"
                            >
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a
                                    href="{{ route('register') }}"
                                    class="px-6 py-3 rounded-xl cta-button text-white font-semibold transform hover:scale-105 transition-all duration-300"
                                >
                                    Get Started
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center mesh-gradient overflow-hidden">
        <!-- Dynamic Background -->
        <div class="absolute inset-0">
            <div class="absolute top-20 left-10 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl animate-float"></div>
            <div class="absolute bottom-20 right-10 w-80 h-80 bg-purple-500/20 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-72 h-72 bg-cyan-500/20 rounded-full blur-3xl animate-float" style="animation-delay: 4s;"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center pt-20">
            <!-- Main Hero Content -->
            <div class="animate-slide-up">
                <div class="mb-6">
                    <span class="px-4 py-2 pt-4 rounded-full glass-morphism text-sm font-medium text-cyan-400 mono">
                        🚀 NOW IN BETA • REVOLUTIONARY TABULATION
                    </span>
                </div>

                <h1 class="text-6xl md:text-8xl font-black mb-8 leading-tight hero-text-shadow">
                    {{-- <span class="text-gradient-premium">{{ config('app.name') }}</span> --}}
                    <br>
                    <span class="text-white text-gradient-premium">Redefining</span>
                    <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-600">
                        Debate Tabulation
                    </span>
                </h1>
            </div>

            <div class="animate-fade-in" style="animation-delay: 0.3s;">
                <p class="text-xl md:text-2xl text-gray-300 mb-12 max-w-5xl mx-auto leading-relaxed font-light">
                    The world's most advanced tournament tabulation platform. Built with AI-powered algorithms,
                    real-time analytics, and an obsession for perfection that transforms chaos into championship moments.
                </p>
            </div>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center mb-16 animate-slide-up" style="animation-delay: 0.6s;">
                <button class="px-10 py-5 cta-button rounded-2xl text-xl font-bold text-white transform hover:scale-105 transition-all duration-300 shadow-2xl">
                    Launch Tournament
                </button>
                <button class="px-10 py-5 glass-morphism rounded-2xl text-xl font-semibold hover:bg-white/10 transition-all duration-300 group">
                    <span class="mr-2">▶</span> Watch Magic Happen
                </button>
            </div>

            <!-- Enhanced Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 max-w-6xl mx-auto animate-fade-in" style="animation-delay: 0.9s;">
                <div class="stats-card rounded-2xl p-6 glass-card">
                    <div class="text-4xl font-black text-cyan-400 mb-2 mono">50K+</div>
                    <div class="text-gray-300 font-medium">Debates Perfected</div>
                    <div class="text-xs text-cyan-400 mt-1 mono">↗ 340% this year</div>
                </div>
                <div class="stats-card rounded-2xl p-6 glass-card">
                    <div class="text-4xl font-black text-purple-400 mb-2 mono">1,200+</div>
                    <div class="text-gray-300 font-medium">Championships</div>
                    <div class="text-xs text-purple-400 mt-1 mono">🏆 World-class</div>
                </div>
                <div class="stats-card rounded-2xl p-6 glass-card">
                    <div class="text-4xl font-black text-amber-400 mb-2 mono">95+</div>
                    <div class="text-gray-300 font-medium">Countries</div>
                    <div class="text-xs text-amber-400 mt-1 mono">🌍 Global reach</div>
                </div>
                <div class="stats-card rounded-2xl p-6 glass-card">
                    <div class="text-4xl font-black text-pink-400 mb-2 mono">99.9%</div>
                    <div class="text-gray-300 font-medium">Uptime</div>
                    <div class="text-xs text-pink-400 mt-1 mono">⚡ Lightning fast</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Revolutionary Features Section -->
    <section id="features" class="py-32 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <div class="mb-6">
                    <span class="px-4 py-2 rounded-full glass-morphism text-sm font-medium text-purple-400 mono">
                        🔮 NEXT-GEN FEATURES
                    </span>
                </div>
                <h2 class="text-5xl md:text-7xl font-black mb-8 text-gradient-premium">
                    Beyond Revolutionary
                </h2>
                <p class="text-xl text-gray-300 max-w-4xl mx-auto font-light">
                    Every pixel, every algorithm, every interaction designed to push the boundaries of what's possible in tournament management.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- AI-Powered Analytics -->
                <div class="glass-card rounded-3xl p-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-cyan-500/20 to-blue-600/20 rounded-full blur-2xl"></div>
                    <div class="feature-icon w-16 h-16 rounded-2xl flex items-center justify-center mb-6 relative z-10">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-white">AI-Powered Intelligence</h3>
                    <p class="text-gray-300 leading-relaxed mb-4">Machine learning algorithms predict optimal pairings, detect bias patterns, and suggest strategic improvements in real-time.</p>
                    <div class="text-cyan-400 mono text-sm">→ 40% better accuracy</div>
                </div>

                <!-- Quantum Speed -->
                <div class="glass-card rounded-3xl p-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-500/20 to-pink-600/20 rounded-full blur-2xl"></div>
                    <div class="feature-icon w-16 h-16 rounded-2xl flex items-center justify-center mb-6 relative z-10">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-white">Quantum-Speed Processing</h3>
                    <p class="text-gray-300 leading-relaxed mb-4">Sub-millisecond response times with edge computing. Results appear before judges finish writing.</p>
                    <div class="text-purple-400 mono text-sm">→ 0.003s average response</div>
                </div>

                <!-- Holographic UI -->
                <div class="glass-card rounded-3xl p-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-amber-500/20 to-orange-600/20 rounded-full blur-2xl"></div>
                    <div class="feature-icon w-16 h-16 rounded-2xl flex items-center justify-center mb-6 relative z-10">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-white">Immersive Experience</h3>
                    <p class="text-gray-300 leading-relaxed mb-4">3D visualizations, haptic feedback, and AR integration transform tournament management into an art form.</p>
                    <div class="text-amber-400 mono text-sm">→ Next-gen interface</div>
                </div>

                <!-- Blockchain Security -->
                <div class="glass-card rounded-3xl p-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-500/20 to-teal-600/20 rounded-full blur-2xl"></div>
                    <div class="feature-icon w-16 h-16 rounded-2xl flex items-center justify-center mb-6 relative z-10">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-white">Quantum-Encrypted Security</h3>
                    <p class="text-gray-300 leading-relaxed mb-4">Military-grade encryption with blockchain verification ensures tournament integrity is mathematically impossible to compromise.</p>
                    <div class="text-green-400 mono text-sm">→ 256-bit quantum encryption</div>
                </div>

                <!-- Neural Networks -->
                <div class="glass-card rounded-3xl p-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-red-500/20 to-pink-600/20 rounded-full blur-2xl"></div>
                    <div class="feature-icon w-16 h-16 rounded-2xl flex items-center justify-center mb-6 relative z-10">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-white">Neural Network Analysis</h3>
                    <p class="text-gray-300 leading-relaxed mb-4">Deep learning models analyze speech patterns, argument structures, and judge behaviors to optimize tournament outcomes.</p>
                    <div class="text-red-400 mono text-sm">→ 97% prediction accuracy</div>
                </div>

                <!-- Multiverse Sync -->
                <div class="glass-card rounded-3xl p-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-500/20 to-purple-600/20 rounded-full blur-2xl"></div>
                    <div class="feature-icon w-16 h-16 rounded-2xl flex items-center justify-center mb-6 relative z-10">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-white">Global Synchronization</h3>
                    <p class="text-gray-300 leading-relaxed mb-4">Seamlessly coordinate tournaments across continents with atomic-clock precision and zero-latency communication.</p>
                    <div class="text-indigo-400 mono text-sm">→ Global tournament network</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-32 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900/50 to-slate-800/50"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-5xl md:text-6xl font-black mb-8 text-gradient-premium">
                    Champions Choose {{ config('app.name') }}
                </h2>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Hear from tournament directors who've experienced the transformation.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="testimonial-card rounded-3xl p-8">
                    <div class="mb-6">
                        <div class="flex text-amber-400 mb-4">
                            ★★★★★
                        </div>
                        <p class="text-lg text-gray-300 leading-relaxed mb-6">
                            "{{ config('app.name') }} didn't just improve our tournaments—it revolutionized them. The AI predictions are uncannily accurate, and the interface feels like it's from the future."
                        </p>
                    </div>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold mr-4">
                            S
                        </div>
                        <div>
                            <div class="font-semibold text-white">Sarah Chen</div>
                            <div class="text-sm text-gray-400">Harvard Debate Society</div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card rounded-3xl p-8">
                    <div class="mb-6">
                        <div class="flex text-amber-400 mb-4">
                            ★★★★★
                        </div>
                        <p class="text-lg text-gray-300 leading-relaxed mb-6">
                            "We've run 200+ tournaments with {{ config('app.name') }}. Zero errors, zero downtime, infinite possibilities. It's not software—it's tournament artistry."
                        </p>
                    </div>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center text-white font-bold mr-4">
                            M
                        </div>
                        <div>
                            <div class="font-semibold text-white">Marcus Rodriguez</div>
                            <div class="text-sm text-gray-400">World Debate Council</div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card rounded-3xl p-8">
                    <div class="mb-6">
                        <div class="flex text-amber-400 mb-4">
                            ★★★★★
                        </div>
                        <p class="text-lg text-gray-300 leading-relaxed mb-6">
                            "The neural network analysis changed everything. We can now predict tournament outcomes with 97% accuracy. It's like having a crystal ball."
                        </p>
                    </div>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white font-bold mr-4">
                            A
                        </div>
                        <div>
                            <div class="font-semibold text-white">Dr. Aisha Patel</div>
                            <div class="text-sm text-gray-400">Oxford Union</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Interactive Demo Section -->
    <section class="py-32 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <div class="mb-6">
                    <span class="px-4 py-2 rounded-full glass-morphism text-sm font-medium text-cyan-400 mono">
                        🎮 INTERACTIVE EXPERIENCE
                    </span>
                </div>
                <h2 class="text-5xl md:text-6xl font-black mb-8 text-gradient-premium">
                    See The Future In Action
                </h2>
                <p class="text-xl text-gray-300 max-w-4xl mx-auto">
                    Experience the power of {{ config('app.name') }} with our live interactive demo. No signup required.
                </p>
            </div>

            <div class="glass-card rounded-3xl p-8 md:p-12 max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div>
                        <h3 class="text-3xl font-bold mb-6 text-white">Live Tournament Simulation</h3>
                        <div class="space-y-4 mb-8">
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                <span class="text-gray-300">64 teams registered</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></div>
                                <span class="text-gray-300">AI pairing in progress...</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-purple-400 rounded-full animate-pulse"></div>
                                <span class="text-gray-300">Real-time analytics active</span>
                            </div>
                        </div>
                        <button class="px-8 py-4 cta-button rounded-xl text-lg font-semibold text-white transform hover:scale-105 transition-all duration-300">
                            Launch Demo Tournament
                        </button>
                    </div>

                    <div class="relative">
                        <div class="glass-morphism rounded-2xl p-6 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-400 mono">ROUND 3 - FINALS</span>
                                <span class="text-xs text-green-400 mono">LIVE</span>
                            </div>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center p-3 bg-white/5 rounded-lg">
                                    <span class="font-medium">Oxford A</span>
                                    <span class="text-cyan-400 mono">284.7</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-white/5 rounded-lg">
                                    <span class="font-medium">Harvard B</span>
                                    <span class="text-purple-400 mono">281.2</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-white/5 rounded-lg">
                                    <span class="font-medium">Cambridge A</span>
                                    <span class="text-amber-400 mono">279.8</span>
                                </div>
                            </div>
                            <div class="text-center pt-4">
                                <div class="text-xs text-gray-400 mono">AI CONFIDENCE: 97.3%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




    <!-- Footer -->
    <footer class="bg-slate-900/80 backdrop-blur-sm py-16 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <x-app-logo/>
                    <p class="text-gray-400 max-w-md leading-relaxed">
                        Transforming debate tournaments with AI-powered precision, quantum-speed processing,
                        and an obsession for excellence that champions trust worldwide.
                    </p>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4">Platform</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Features</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">API</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Documentation</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Community</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Status</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row items-center justify-between">
                <div class="text-gray-400 mb-4 md:mb-0">
                    <p>&copy; 2025 {{ config('app.name') }}. Crafted for the art of debate.</p>
                </div>

                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">Privacy</a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">Terms</a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">Security</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Enhanced JavaScript for better interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth reveal animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0) scale(1)';
                        }, index * 100);
                    }
                });
            }, observerOptions);

            // Observe all cards and elements
            document.querySelectorAll('.glass-card, .testimonial-card, .stats-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px) scale(0.95)';
                card.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
                observer.observe(card);
            });

            // Enhanced parallax effect
            let ticking = false;

            function updateParallax() {
                const scrolled = window.pageYOffset;
                const rate = scrolled * -0.3;

                document.querySelectorAll('.mesh-gradient').forEach(element => {
                    element.style.transform = `translateY(${rate}px)`;
                });

                ticking = false;
            }

            function requestTick() {
                if (!ticking) {
                    requestAnimationFrame(updateParallax);
                    ticking = true;
                }
            }

            window.addEventListener('scroll', requestTick);

            // Interactive demo simulation
            const demoButton = document.querySelector('.glass-card button');
            if (demoButton) {
                demoButton.addEventListener('click', function() {
                    // Simulate tournament activity
                    const statusElements = document.querySelectorAll('.glass-morphism .flex');
                    statusElements.forEach((element, index) => {
                        setTimeout(() => {
                            element.style.background = 'rgba(34, 197, 94, 0.2)';
                            element.style.transform = 'scale(1.05)';
                            setTimeout(() => {
                                element.style.transform = 'scale(1)';
                            }, 200);
                        }, index * 500);
                    });
                });
            }

            // Smooth scroll for navigation
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Dynamic particle generation
            function createParticle() {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 8 + 's';
                particle.style.animationDuration = (8 + Math.random() * 4) + 's';

                const colors = ['#60a5fa', '#a78bfa', '#fbbf24', '#fb7185'];
                particle.style.background = colors[Math.floor(Math.random() * colors.length)];

                document.querySelector('.fixed.inset-0').appendChild(particle);

                setTimeout(() => {
                    particle.remove();
                }, 12000);
            }

            // Create particles periodically
            setInterval(createParticle, 2000);
        });
    </script>
</body>
</html>
