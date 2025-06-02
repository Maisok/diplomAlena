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
        <h1 class="text-3xl font-bold gradient-text mb-8 text-center">Расписание на текущую неделю</h1>

        <!-- Форма фильтрации по группе -->
        <form method="GET" action="{{ route('group.schedules') }}" class="mb-6 max-w-xl mx-auto">
            <label for="group_id" class="block text-sm font-medium text-gray-700 mb-2">Выберите группу:</label>
            <select id="group_id" name="group_id" onchange="this.form.submit()" class="w-full p-2 border rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                <option value="">Все группы</option>
                @foreach($allGroups as $groupOption)
                    <option value="{{ $groupOption->id }}" {{ $selectedGroupId == $groupOption->id ? 'selected' : '' }}>
                        {{ $groupOption->name }}
                    </option>
                @endforeach
            </select>
        </form>

        <!-- Карточки групп -->
        @if($groups->isEmpty())
            <p class="text-center text-gray-500 text-lg">Нет данных о расписании на текущую неделю.</p>
        @else
            @foreach($groups as $group)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover-scale animate-fade-in-up mb-8 max-w-6xl mx-auto">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-700 p-6 text-white flex justify-between items-center">
                        <h2 class="text-xl font-bold">Группа {{ $group->name }}</h2>
                    </div>

                    <div class="p-6">
                        @include('partials.group_schedule', ['schedule_items' => $group->formatted_schedule])
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</section>

<x-footer />

</body>
</html>