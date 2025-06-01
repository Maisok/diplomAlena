<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Доверенные лица - 7 Звезд</title>
    <script src="https://cdn.tailwindcss.com"></script> 
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .gradient-text {
            background: linear-gradient(45deg, #4A3F9B, #D32F2F);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .gradient-bg {
            background: linear-gradient(45deg, #4A3F9B, #D32F2F);
        }
    </style>
</head>
<body class="bg-gray-100">
<x-header/>

<main class="flex-grow">
    <!-- Герой секция -->
    <div class="bg-gradient-to-r from-purple-100 to-white py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-3xl font-bold gradient-text mb-2">Доверенные лица</h1>
            <p class="text-lg text-gray-700">Список всех доверенных лиц</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <div class="max-w-6xl mx-auto">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                <form action="{{ route('trusted-people.index') }}" method="GET" class="flex-1">
                    <div class="flex">
                        <input type="text" name="search"
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-[#4A3F9B] focus:border-[#4A3F9B]"
                               placeholder="Поиск по ФИО родителя или доверенному лицу" value="{{ request('search') }}">
                        <button type="submit"
                                class="bg-[#4A3F9B] text-white px-4 py-2 rounded-r-lg hover:bg-[#3a2f8b] transition">
                            Поиск
                        </button>
                    </div>
                </form>

                <a href="{{ route('trusted-people.create') }}"
                   class="gradient-bg text-white px-6 py-2 rounded-lg hover:opacity-90 transition whitespace-nowrap">
                    Добавить доверенное лицо
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ФИО</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Родитель</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Телефон</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($trustedPeople as $person)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $person->last_name }} {{ $person->first_name }} {{ $person->patronymic ?? '' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $person->parent?->full_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $person->phone_number ?: '—' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('trusted-people.edit', $person) }}" class="text-[#4A3F9B] hover:text-[#D32F2F] mr-4 transition">Редактировать</a>
                                    <form action="{{ route('trusted-people.destroy', $person) }}" method="POST" class="inline" onsubmit="return confirm('Вы уверены?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 px-6 pb-6">
                    {{ $trustedPeople->links() }}
                </div>
            </div>
        </div>
    </div>
</main>

<x-footer/>
</body>
</html>