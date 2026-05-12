<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EduAI – Academic Progress Tracker</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Tailwind CDN with custom config -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3525cd',
                        'primary-container': '#4f46e5',
                        'on-primary': '#ffffff',
                        secondary: '#00687a',
                        'secondary-container': '#57dffe',
                        surface: '#fcf8ff',
                        'surface-container-low': '#f5f2ff',
                        'surface-container': '#f0ecf9',
                        'surface-container-high': '#eae6f4',
                        'surface-container-highest': '#e4e1ee',
                        'on-surface': '#1b1b24',
                        'on-surface-variant': '#464555',
                        'outline-variant': '#c7c4d8',
                        error: '#ba1a1a',
                        background: '#fcf8ff',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        poppins: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        .glass {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
        .bg-mesh {
            background-color: #f0ecf9;
            background-image:
                radial-gradient(at 20% 20%, hsla(247, 82%, 57%, 0.25) 0px, transparent 50%),
                radial-gradient(at 80% 10%, hsla(192, 100%, 55%, 0.2) 0px, transparent 50%),
                radial-gradient(at 60% 80%, hsla(247, 82%, 70%, 0.15) 0px, transparent 50%);
        }
        .input-field {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(255,255,255,0.8);
            border: 1.5px solid #c7c4d8;
            border-radius: 0.75rem;
            font-size: 0.9rem;
            color: #1b1b24;
            transition: all 0.2s;
            outline: none;
            font-family: 'Inter', sans-serif;
        }
        .input-field:focus {
            border-color: #3525cd;
            box-shadow: 0 0 0 3px rgba(53, 37, 205, 0.12);
            background: white;
        }
        .btn-primary {
            width: 100%;
            padding: 0.85rem 1.5rem;
            background: linear-gradient(135deg, #3525cd, #4f46e5);
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
            letter-spacing: 0.01em;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(53, 37, 205, 0.35);
        }
        .btn-primary:active { transform: translateY(0); }
        .role-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.4rem 1rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            border: 1.5px solid transparent;
            transition: all 0.2s;
            background: rgba(53,37,205,0.06);
            color: #464555;
        }
        .role-chip.active, .role-chip:hover {
            background: #3525cd;
            color: white;
            border-color: #3525cd;
        }
        {{ $styles ?? '' }}
        /* Ensure Tailwind forms plugin classes are available */
        [type='checkbox']:checked { background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e"); background-color: #3525cd; }
    </style>
</head>
<body class="bg-mesh min-h-screen font-sans">
    {{ $slot }}
</body>
</html>
