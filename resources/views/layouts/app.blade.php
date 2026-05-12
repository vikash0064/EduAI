<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EduAI – {{ config('app.name', 'Academic Tracker') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "surface-container-lowest": "#ffffff",
                        "secondary-container": "#57dffe",
                        "tertiary": "#7e3000",
                        "outline-variant": "#c7c4d8",
                        "surface-container-low": "#f5f2ff",
                        "surface-bright": "#fcf8ff",
                        "on-primary": "#ffffff",
                        "surface-tint": "#4d44e3",
                        "primary-fixed": "#e2dfff",
                        "on-surface": "#1b1b24",
                        "error": "#ba1a1a",
                        "inverse-surface": "#302f39",
                        "surface-container": "#f0ecf9",
                        "inverse-primary": "#c3c0ff",
                        "surface-container-highest": "#e4e1ee",
                        "primary-container": "#4f46e5",
                        "on-tertiary": "#ffffff",
                        "on-secondary-container": "#006172",
                        "primary-fixed-dim": "#c3c0ff",
                        "tertiary-fixed": "#ffdbcc",
                        "error-container": "#ffdad6",
                        "on-secondary": "#ffffff",
                        "on-primary-fixed": "#0f0069",
                        "background": "#fcf8ff",
                        "surface": "#fcf8ff",
                        "tertiary-container": "#a44100",
                        "surface-variant": "#e4e1ee",
                        "on-tertiary-container": "#ffd2be",
                        "secondary": "#00687a",
                        "secondary-fixed": "#acedff",
                        "primary": "#3525cd",
                        "on-error": "#ffffff",
                        "on-primary-fixed-variant": "#3323cc",
                        "surface-container-high": "#eae6f4",
                        "on-primary-container": "#dad7ff",
                        "on-background": "#1b1b24",
                        "on-surface-variant": "#464555",
                        success: "#1a8754",
                    },
                    borderRadius: {
                        DEFAULT: "0.25rem", lg: "0.5rem", xl: "0.75rem", "2xl": "1rem", "3xl": "1.5rem", full: "9999px"
                    },
                    fontFamily: {
                        sans: ["Inter", "sans-serif"],
                        poppins: ["Poppins", "sans-serif"],
                    },
                    spacing: {
                        sm: "12px", md: "24px", lg: "48px", xl: "80px", gutter: "24px", xs: "4px",
                        "margin-desktop": "40px", "margin-mobile": "16px",
                    }
                }
            }
        }
    </script>

    <style>
        .glass { background: rgba(255,255,255,0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.5); }
        .shadow-soft { box-shadow: 0 4px 20px -2px rgba(79,70,229,0.07); }
        .progress-ring__circle { transition: stroke-dashoffset 0.6s cubic-bezier(0.4,0,0.2,1); transform: rotate(-90deg); transform-origin: 50% 50%; }
        .sidebar-link { display: flex; align-items: center; gap: 12px; padding: 10px 16px; border-radius: 0 16px 16px 0; transition: all 0.2s; font-family: 'Inter', sans-serif; font-size: 0.9rem; color: #464555; cursor: pointer; text-decoration: none; }
        .sidebar-link:hover { background: rgba(234,230,244,0.6); color: #1b1b24; }
        .sidebar-link.active { background: rgba(53,37,205,0.1); color: #3525cd; border-left: 3px solid #3525cd; font-weight: 600; }
        .sidebar-link .material-symbols-outlined { font-size: 22px; }
        ::-webkit-scrollbar { width: 5px; } 
        ::-webkit-scrollbar-track { background: transparent; } 
        ::-webkit-scrollbar-thumb { background: #c7c4d8; border-radius: 9999px; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeInUp 0.4s ease-out both; }
    </style>
    {{ $styles ?? '' }}
</head>
<body class="bg-background text-on-background font-sans min-h-screen">

    <!-- Sidebar -->
    <aside class="h-screen w-64 fixed left-0 top-0 glass shadow-xl flex flex-col py-6 z-50" style="border-right: 1px solid rgba(255,255,255,0.5);">
        <div class="px-6 mb-8">
            <span class="font-poppins text-2xl font-bold text-primary">EduAI</span>
            <p class="text-xs text-on-surface-variant uppercase tracking-widest mt-1 font-semibold">Academic Excellence</p>
        </div>
        <nav class="flex-1 overflow-y-auto space-y-0.5 pr-2">
            @php $role = auth()->user()->role ?? 'student'; @endphp

            <a href="{{ $role === 'admin' ? route('admin.dashboard') : ($role === 'teacher' ? route('teacher.dashboard') : ($role === 'parent' ? route('parent.dashboard') : route('student.dashboard'))) }}"
               class="sidebar-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                <span class="material-symbols-outlined">dashboard</span>Dashboard
            </a>

            @if($role === 'admin')
                <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">group</span>Users
                </a>
                <a href="{{ route('admin.fees.index') }}" class="sidebar-link {{ request()->routeIs('admin.fees*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">account_balance</span>Finances
                </a>
                <a href="{{ route('messages.index') }}" class="sidebar-link {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">mail</span>Messages
                </a>
                <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">assessment</span>Reports
                </a>
                <a href="#" class="sidebar-link">
                    <span class="material-symbols-outlined">settings_suggest</span>Settings
                </a>
            @elseif($role === 'teacher')
                <a href="{{ route('teacher.attendance.index') }}" class="sidebar-link {{ request()->routeIs('teacher.attendance*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">calendar_today</span>Attendance
                </a>
                <a href="{{ route('teacher.assignments.index') }}" class="sidebar-link {{ request()->routeIs('teacher.assignments*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">assignment</span>Assignments
                </a>
                <a href="{{ route('teacher.grades.index') }}" class="sidebar-link {{ request()->routeIs('teacher.grades*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">quiz</span>Exams
                </a>
                <a href="{{ route('messages.index') }}" class="sidebar-link {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">mail</span>Messages
                </a>
                <a href="{{ route('admin.reports.index') }}" class="sidebar-link">
                    <span class="material-symbols-outlined">assessment</span>Reports
                </a>
            @elseif($role === 'parent')
                <a href="{{ route('parent.dashboard') }}" class="sidebar-link {{ request()->routeIs('parent.dashboard') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">family_restroom</span>My Children
                </a>
                <a href="{{ route('messages.index') }}" class="sidebar-link {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">mail</span>Messages
                </a>
                <a href="#" class="sidebar-link">
                    <span class="material-symbols-outlined">notifications_active</span>Alerts
                </a>
            @else
                <a href="{{ route('student.attendance') }}" class="sidebar-link {{ request()->routeIs('student.attendance') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">calendar_today</span>Attendance
                </a>
                <a href="{{ route('student.assignments') }}" class="sidebar-link {{ request()->routeIs('student.assignments') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">assignment</span>Assignments
                </a>
                <a href="{{ route('student.grades') }}" class="sidebar-link {{ request()->routeIs('student.grades') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">quiz</span>Exams
                </a>
                <a href="{{ route('messages.index') }}" class="sidebar-link {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">mail</span>Messages
                </a>
            @endif
        </nav>
        <div class="px-2 mt-4">
            <a href="{{ route('profile.edit') }}" class="sidebar-link">
                <span class="material-symbols-outlined">settings</span>Settings
            </a>
        </div>
    </aside>

    <!-- Main -->
    <div class="ml-64 min-h-screen flex flex-col">

        <!-- Top Header -->
        <header class="sticky top-0 z-40 glass shadow-sm flex items-center justify-between h-16 px-10" style="border-bottom: 1px solid rgba(255,255,255,0.5);">
            <div class="flex items-center gap-8">
                <span class="font-poppins font-bold text-primary text-lg">{{ $pageTitle ?? 'EduAI Tracker' }}</span>
                <form action="{{ url()->current() }}" method="GET" class="relative">
                    @foreach(request()->except('q') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ $searchPlaceholder ?? 'Search courses, tasks, or AI help...' }}"
                           class="pl-10 pr-4 py-2 bg-surface-container-low border-none rounded-full w-80 text-sm focus:ring-2 focus:ring-primary/20 font-sans transition-all" />
                </form>
            </div>
            <div class="flex items-center gap-6">
            <div class="flex items-center gap-6">
                <!-- Notifications Dropdown -->
                <div x-data="{ open: false, count: {{ auth()->user()->unreadNotifications->count() }} }" class="relative">
                    <button @click="open = !open" class="relative p-2 text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined">notifications</span>
                        <template x-if="count > 0">
                            <span class="absolute top-2 right-2 w-4 h-4 bg-error text-white text-[9px] font-bold flex items-center justify-center rounded-full border-2 border-white" x-text="count"></span>
                        </template>
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="open" @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-[-10px]"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         class="absolute right-0 mt-4 w-96 bg-white rounded-[32px] shadow-2xl border border-outline-variant/10 z-[60] overflow-hidden">
                        <div class="p-6 border-b border-outline-variant/10 flex items-center justify-between bg-surface-container-low/30">
                            <h3 class="text-sm font-poppins font-bold text-on-surface">Notifications</h3>
                            <form action="{{ route('notifications.mark-read') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-[10px] font-bold text-primary uppercase hover:underline">Mark all as read</button>
                            </form>
                        </div>
                        <div class="max-h-[400px] overflow-y-auto custom-scrollbar">
                            @forelse(auth()->user()->notifications->take(10) as $notification)
                            <div class="p-5 border-b border-outline-variant/5 flex gap-4 hover:bg-surface-container-low transition-all {{ $notification->read_at ? 'opacity-60' : '' }}">
                                <div class="w-10 h-10 rounded-xl {{ $notification->data['type'] == 'alert' ? 'bg-error/10 text-error' : 'bg-primary/10 text-primary' }} flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[20px]">{{ $notification->data['icon'] ?? 'info' }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-on-surface">{{ $notification->data['title'] }}</p>
                                    <p class="text-[11px] font-medium text-on-surface-variant mt-0.5">{{ $notification->data['message'] }}</p>
                                    <p class="text-[9px] font-bold text-on-surface-variant/40 mt-2 uppercase">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                                @if(!$notification->read_at)
                                <div class="w-2 h-2 rounded-full bg-primary mt-2"></div>
                                @endif
                            </div>
                            @empty
                            <div class="p-12 text-center opacity-40">
                                <span class="material-symbols-outlined text-4xl mb-2">notifications_off</span>
                                <p class="text-xs font-bold">No notifications yet</p>
                            </div>
                            @endforelse
                        </div>
                        <div class="p-4 bg-surface-container-low/30 border-t border-outline-variant/10 text-center">
                            <a href="#" class="text-[10px] font-bold text-primary uppercase hover:underline">View all notifications</a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('messages.index') }}" class="p-2 text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">chat_bubble</span>
                </a>
                <div class="flex items-center gap-3 pl-6 border-l border-outline-variant/30">
                    <div class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-on-primary font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-on-surface font-poppins leading-tight">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-[10px] text-on-surface-variant capitalize">{{ ucfirst(auth()->user()->role ?? 'user') }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="ml-2 text-on-surface-variant hover:text-error transition-colors" title="Logout">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-10 fade-in">
            {{ $slot }}
        </main>
    </div>

</body>
</html>
