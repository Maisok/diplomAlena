<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Расписание всех групп | 7 звёзд</title>
    <script src="https://cdn.tailwindcss.com"></script> 
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        .hover-scale {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-scale:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .gradient-text {
            background: linear-gradient(45deg, #7c3aed, #f59e0b);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .schedule-item {
            transition: all 0.3s ease;
        }
        .schedule-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">

<x-header />

<section class="py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold gradient-text mb-8 text-center">Расписание всех групп</h1>

        <!-- Карточки групп -->
        @foreach($groups as $group)
    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover-scale animate-fade-in-up mb-8 max-w-6xl mx-auto">
        <div class="bg-gradient-to-r from-purple-500 to-purple-700 p-6 text-white flex justify-between items-center">
            <h2 class="text-xl font-bold">Группа {{ $group->name }}</h2>
        </div>

        <div class="p-6">
            <!-- Расписание -->
            @include('partials.group_schedule', ['schedule_items' => $group->formatted_schedule])
        </div>
    </div>
@endforeach
    </div>
</section>

<x-footer />

</body>
</html>