<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $title ?? 'AgencyOS' }}</title>

<script>
    (function () {
        try {
            var t = localStorage.getItem('theme');
            if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        } catch (e) {}
    })();
</script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('css/app.css') }}">

<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    primary: {
                        50:'#F6FFF2',100:'#D9FAC9',200:'#D1FAB8',300:'#C2F269',400:'#BDF04D',
                        500:'#A3D825',600:'#8EC91F',700:'#73A61A',800:'#5A7F16',900:'#3E5910'
                    },
                    neutral: {
                        50:'#FFFFFF',100:'#F6FFF2',200:'#E8F2E3',300:'#D5DDD1',400:'#B5CBAA',
                        500:'#9BAA94',600:'#7D8479',700:'#5F645C',800:'#4E514C',900:'#2E312D'
                    }
                },
                fontFamily: {
                    sans: ['Inter','Geist','Nunito','ui-sans-serif','system-ui','sans-serif'],
                    display: ['Inter','ui-sans-serif','system-ui','sans-serif']
                },
                borderRadius: { xl: '12px', '2xl': '16px', '3xl': '20px' },
                boxShadow: {
                    soft: '0 1px 2px rgba(46,49,45,0.04), 0 6px 20px rgba(46,49,45,0.06)',
                    card: '0 1px 3px rgba(46,49,45,0.05), 0 8px 24px rgba(46,49,45,0.08)',
                    glow: '0 0 0 1px rgba(138,201,31,0.25), 0 8px 24px rgba(138,201,31,0.18)'
                }
            }
        }
    };
</script>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide) window.lucide.createIcons();
    });
    document.addEventListener('alpine:initialized', function () {
        if (window.lucide) window.lucide.createIcons();
    });
    // Re-renderiza ícones quando o Livewire atualiza o DOM
    document.addEventListener('livewire:navigated', function () {
        if (window.lucide) window.lucide.createIcons();
    });
</script>

<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@livewireStyles
