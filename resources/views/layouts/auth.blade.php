<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Auth')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon.png') }}">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        /* Professional purple background - exact match */
        .purple-bg {
            background: #000000;
            position: relative;
            overflow: hidden;
        }
        
        .purple-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(247, 85, 85, 0.4) 0%, rgba(234, 51, 51, 0.5) 50%, #000000 100%);
        }
        
        /* Subtle noise texture overlay */
        .purple-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0.03;
            mix-blend-mode: soft-light;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            background-size: 200px 200px;
        }
        
        /* Background glow effects */
        .bg-glow-top {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 120vh;
            height: 60vh;
            border-radius: 0 0 50% 50%;
            background: rgba(247, 85, 85, 0.2);
            filter: blur(80px);
        }

        .bg-glow-top-animated {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100vh;
            height: 60vh;
            border-radius: 0 0 100% 100%;
            background: rgba(234, 51, 51, 0.2);
            filter: blur(60px);
            animation: pulseGlow 8s ease-in-out infinite alternate;
        }

        .bg-glow-bottom {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 90vh;
            height: 90vh;
            border-radius: 50% 50% 0 0;
            background: rgba(247, 85, 85, 0.2);
            filter: blur(60px);
            animation: pulseGlow2 6s ease-in-out infinite alternate 1s;
        }

        .bg-glow-spot-1 {
            position: absolute;
            left: 25%;
            top: 25%;
            width: 384px;
            height: 384px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            filter: blur(100px);
            animation: pulse 4s ease-in-out infinite;
            opacity: 0.4;
        }

        .bg-glow-spot-2 {
            position: absolute;
            right: 25%;
            bottom: 25%;
            width: 384px;
            height: 384px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            filter: blur(100px);
            animation: pulse 4s ease-in-out infinite 1s;
            opacity: 0.4;
        }
        
        @keyframes pulseGlow {
            0%, 100% { opacity: 0.15; transform: translateX(-50%) scale(0.98); }
            50% { opacity: 0.3; transform: translateX(-50%) scale(1.02); }
        }
        
        @keyframes pulseGlow2 {
            0%, 100% { opacity: 0.3; transform: translateX(-50%) scale(1); }
            50% { opacity: 0.5; transform: translateX(-50%) scale(1.1); }
        }
        
        /* Glass card effect - enhanced */
        .glass-card {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transform-style: preserve-3d;
            transition: transform 0.1s ease-out;
            position: relative;
        }
        
        /* 3D Tilt Effect - Enhanced */
        .tilt-container {
            perspective: 1500px;
        }
        
        /* Remove card glow effects that change color */
        .card-glow {
            display: none;
        }

        /* Card border edge animations */
        .card-edge-glow {
            position: absolute;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .card-edge-top {
            top: 0;
            left: 20%;
            right: 20%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
            filter: blur(0.5px);
        }

        .card-edge-right {
            top: 20%;
            bottom: 20%;
            right: 0;
            width: 1px;
            background: linear-gradient(180deg, transparent, rgba(255,255,255,0.6), transparent);
            filter: blur(0.5px);
        }

        .card-edge-bottom {
            bottom: 0;
            left: 20%;
            right: 20%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
            filter: blur(0.5px);
        }

        .card-edge-left {
            top: 20%;
            bottom: 20%;
            left: 0;
            width: 1px;
            background: linear-gradient(180deg, transparent, rgba(255,255,255,0.6), transparent);
            filter: blur(0.5px);
        }

        .glass-card:hover .card-edge-glow {
            opacity: 0.8;
            animation: edgePulse 2s ease-in-out infinite alternate;
        }

        @keyframes edgePulse {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 0.8; }
        }
        
        /* Enhanced traveling light beams */
        .light-beam {
            position: absolute;
            opacity: 0;
            filter: blur(2px);
            transition: opacity 0.3s ease;
        }

        .glass-card:hover .light-beam {
            opacity: 0.7;
        }

        /* Thinner traveling light beams - only on hover */
        .light-beam-top {
            top: 0;
            left: -50%;
            height: 1px; /* Made thinner from 3px to 1px */
            width: 50%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: beamTop 3s ease-in-out infinite 1s;
        }

        .light-beam-right {
            top: -50%;
            right: 0;
            height: 50%;
            width: 1px; /* Made thinner from 3px to 1px */
            background: linear-gradient(180deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: beamRight 3s ease-in-out infinite 1.6s;
        }

        .light-beam-bottom {
            bottom: 0;
            right: -50%;
            height: 1px; /* Made thinner from 3px to 1px */
            width: 50%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: beamBottom 3s ease-in-out infinite 2.2s;
        }

        .light-beam-left {
            bottom: -50%;
            left: 0;
            height: 50%;
            width: 1px; /* Made thinner from 3px to 1px */
            background: linear-gradient(180deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: beamLeft 3s ease-in-out infinite 2.8s;
        }
        
        @keyframes beamTop {
            0%, 100% { 
                left: -50%; 
                opacity: 0.3;
                filter: blur(1px);
            }
            50% { 
                left: 100%; 
                opacity: 0.7;
                filter: blur(2.5px);
            }
        }
        
        @keyframes beamRight {
            0%, 100% { 
                top: -50%; 
                opacity: 0.3;
                filter: blur(1px);
            }
            50% { 
                top: 100%; 
                opacity: 0.7;
                filter: blur(2.5px);
            }
        }
        
        @keyframes beamBottom {
            0%, 100% { 
                right: -50%; 
                opacity: 0.3;
                filter: blur(1px);
            }
            50% { 
                right: 100%; 
                opacity: 0.7;
                filter: blur(2.5px);
            }
        }
        
        @keyframes beamLeft {
            0%, 100% { 
                bottom: -50%; 
                opacity: 0.3;
                filter: blur(1px);
            }
            50% { 
                bottom: 100%; 
                opacity: 0.7;
                filter: blur(2.5px);
            }
        }

        /* Corner glow spots */
        .corner-glow-1 {
            position: absolute;
            top: 0;
            left: 0;
            height: 5px;
            width: 5px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            filter: blur(1px);
            animation: cornerPulse1 2s ease-in-out infinite alternate;
        }

        .corner-glow-2 {
            position: absolute;
            top: 0;
            right: 0;
            height: 8px;
            width: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            filter: blur(2px);
            animation: cornerPulse2 2.4s ease-in-out infinite alternate 0.5s;
        }

        .corner-glow-3 {
            position: absolute;
            bottom: 0;
            right: 0;
            height: 8px;
            width: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            filter: blur(2px);
            animation: cornerPulse3 2.2s ease-in-out infinite alternate 1s;
        }

        .corner-glow-4 {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 5px;
            width: 5px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            filter: blur(1px);
            animation: cornerPulse4 2.3s ease-in-out infinite alternate 1.5s;
        }

        @keyframes cornerPulse1 {
            0%, 100% { opacity: 0.2; }
            50% { opacity: 0.4; }
        }

        @keyframes cornerPulse2 {
            0%, 100% { opacity: 0.2; }
            50% { opacity: 0.4; }
        }

        @keyframes cornerPulse3 {
            0%, 100% { opacity: 0.2; }
            50% { opacity: 0.4; }
        }

        @keyframes cornerPulse4 {
            0%, 100% { opacity: 0.2; }
            50% { opacity: 0.4; }
        }
        
        /* Input styles - fully transparent blur effect */
        .glass-input {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: white;
            transition: all 0.3s ease;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .glass-input:focus {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.15);
            outline: none;
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
        }

        .glass-input::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        /* Remove input highlight that changes colors */
        .input-highlight {
            display: none;
        }

        /* Remove input border glow that changes colors */
        .input-border-glow {
            display: none;
        }
        
        /* Enhanced checkbox */
        .custom-checkbox {
            appearance: none;
            width: 16px;
            height: 16px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 3px;
            background: rgba(255, 255, 255, 0.05);
            position: relative;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .custom-checkbox:checked {
            background: white;
            border-color: white;
        }

        .custom-checkbox:checked::after {
            content: '';
            position: absolute;
            left: 4px;
            top: 1px;
            width: 6px;
            height: 10px;
            border: solid black;
            border-width: 0 3px 3px 0;
            transform: rotate(45deg);
        }
        
        /* Enhanced buttons */
        .glass-button {
            background: white;
            color: #1f2937;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .glass-button:hover {
            transform: scale(1.02);
            background: #f9fafb;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .glass-button-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .glass-button-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-color: rgba(255, 255, 255, 0.2);
            transform: scale(1.02);
        }

        /* Button glow effect */
        .button-glow {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            filter: blur(10px);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .glass-button:hover .button-glow {
            opacity: 0.7;
        }

        /* Button shimmer effect */
        .button-shimmer {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.3) 50%, transparent 100%);
            transform: translateX(-100%);
            transition: transform 1.5s ease;
        }

        .glass-button:hover .button-shimmer {
            transform: translateX(100%);
        }
        
        /* Loading spinner */
        .loading-spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(0, 0, 0, 0.7);
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Entrance animations */
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        .slide-up {
            animation: slideUp 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideUp {
            0% { opacity: 0; transform: translateY(10px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Icon styling */
        .input-icon {
            color: rgba(255, 255, 255, 0.4);
            transition: all 0.3s ease;
        }

        .input-container:focus-within .input-icon {
            color: white;
        }

        /* Card inner patterns */
        .card-pattern {
            position: absolute;
            inset: 0;
            opacity: 0.03;
            background-image: linear-gradient(135deg, white 0.5px, transparent 0.5px), linear-gradient(45deg, white 0.5px, transparent 0.5px);
            background-size: 30px 30px;
        }

        /* Logo glow effect */
        .logo-glow {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom right, rgba(255,255,255,0.1), transparent);
            opacity: 0.5;
        }

        /* Remove card border effects */
        .card-border-thin {
            display: none;
        }

        /* Remove card border glow */
        .glass-card .absolute.-inset-\[0\.5px\] {
            display: none;
        }
    </style>
</head>
<body class="min-h-screen">
    <main>
        @yield('content')
    </main>
    
    <script>
        function addTiltEffect() {
            const card = document.querySelector('.glass-card');
            if (!card) return;
            
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const rotateX = (y - centerY) / 30; // Increased from 50 to 30
                const rotateY = (centerX - x) / 30; // Increased from 50 to 30
                
                card.style.transform = `perspective(1500px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(10px)`;
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1500px) rotateX(0deg) rotateY(0deg) translateZ(0px)';
            });
        }

        // Input focus effects
        function addInputEffects() {
            const inputs = document.querySelectorAll('.glass-input');
            inputs.forEach(input => {
                input.addEventListener('focus', () => {
                    input.closest('.input-container').style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', () => {
                    input.closest('.input-container').style.transform = 'scale(1)';
                });
            });
        }

        // Button hover effects
        function addButtonEffects() {
            const buttons = document.querySelectorAll('.glass-button, .glass-button-secondary');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', () => {
                    button.style.transform = 'scale(1.02)';
                });
                
                button.addEventListener('mouseleave', () => {
                    button.style.transform = 'scale(1)';
                });
            });
        }
        
        // Initialize effects when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                addTiltEffect();
                addInputEffects();
                addButtonEffects();
            }, 500);
        });
    </script>
    
    @stack('scripts')
</body>
</html>
