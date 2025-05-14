<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Расписание - 7 Звезд</title>
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
        .schedule-card {
            transition: all 0.2s ease;
        }
        .schedule-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .activity-item {
            transition: all 0.2s ease;
        }
        .activity-item:hover {
            transform: scale(1.02);
        }
    </style>
</head>
<body class="bg-gray-100">
    <x-header/>

    <main class="flex-grow">
        <!-- Герой секция -->
        <div class="bg-gradient-to-r from-purple-100 to-white py-12">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-3xl font-bold gradient-text mb-2">Расписание занятий</h1>
                <p class="text-lg text-gray-700">Управление расписанием для всех групп</p>
            </div>
        </div>

        <div class="container mx-auto px-4 py-8">
            <div class="flex justify-between items-center mb-8 bg-white rounded-lg shadow-md p-4">
                @if($weekStart->gt($today->copy()->startOfWeek()))
                    <a href="{{ route('schedules.index', ['week_start' => $weekStart->copy()->subWeek()->toDateString()]) }}" 
                    class="gradient-bg text-white px-6 py-2 rounded-lg hover:opacity-90 transition">
                        Предыдущая неделя
                    </a>
                @else
                    <span class="bg-gray-300 text-gray-600 px-6 py-2 rounded-lg">Предыдущая неделя</span>
                @endif
                
                <span class="text-lg font-semibold text-gray-700">
                    Неделя {{ $weekStart->format('d.m.Y') }} - {{ $weekStart->copy()->addDays(4)->format('d.m.Y') }}
                </span>
                
                @if($weekStart->lt($maxFutureWeek))
                    <a href="{{ route('schedules.index', ['week_start' => $weekStart->copy()->addWeek()->toDateString()]) }}" 
                    class="gradient-bg text-white px-6 py-2 rounded-lg hover:opacity-90 transition">
                        Следующая неделя
                    </a>
                @else
                    <span class="bg-gray-300 text-gray-600 px-6 py-2 rounded-lg">Следующая неделя</span>
                @endif
            </div>

            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                    <h4 class="font-bold">Ошибки:</h4>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @foreach($groups as $group)
            <div class="schedule-card bg-white rounded-xl shadow-lg mb-8 overflow-hidden">
                <div class="gradient-bg text-white px-6 py-4">
                    <h3 class="text-xl font-semibold">{{ $group->name }}</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Время</th>
                                @for($day = 0; $day < 5; $day++)
                                    @php
                                        $currentDate = $weekStart->copy()->addDays($day);
                                        $isPast = $currentDate->lt($today);
                                    @endphp
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $currentDate->isoFormat('dddd (DD.MM)') }}
                                    </th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @for($hour = 7; $hour < 19; $hour++)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ sprintf('%02d:00', $hour) }}
                                    </td>
                                    @for($day = 0; $day < 5; $day++)
                                        @php
                                            $currentDate = $weekStart->copy()->addDays($day);
                                            $isPast = $currentDate->lt($today);
                                            $activities = $group->scheduleItems
                                                ->where('date', $currentDate->format('Y-m-d'))
                                                ->filter(fn($item) => \Carbon\Carbon::parse($item->start_time)->hour == $hour);
                                        @endphp
                                        <td class="px-6 py-4 border @if($isPast) bg-gray-50 @endif">
                                            @if($activities->isNotEmpty())
                                                @foreach($activities as $activity)
                                                    <div class="activity-item mb-3 p-3 rounded-lg border 
                                                        {{ $activity->activityCategory->unique_across_groups ? 'bg-red-50 border-red-200' : 'bg-blue-50 border-blue-200' }}">
                                                        <div class="flex justify-between items-start">
                                                            <div>
                                                                <span class="font-medium text-gray-800">{{ $activity->activityCategory->name }}</span>
                                                                <div class="text-sm text-gray-600">
                                                                    {{ \Carbon\Carbon::parse($activity->start_time)->format('H:i') }} - 
                                                                    {{ \Carbon\Carbon::parse($activity->end_time)->format('H:i') }}
                                                                </div>
                                                            </div>
                                                            @if(!$isPast)
                                                            <form action="{{ route('schedules.destroy', $activity) }}" method="POST">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:text-red-800 transition">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @elseif(!$isPast)
                                                <form action="{{ route('schedules.store') }}" method="POST" class="space-y-2">
                                                    @csrf
                                                    <input type="hidden" name="group_id" value="{{ $group->id }}">
                                                    <input type="hidden" name="date" value="{{ $currentDate->format('Y-m-d') }}">
                                                    <input type="hidden" name="start_time" value="{{ sprintf('%02d:00', $hour) }}">
                                                    
                                                    <select name="activity_category_id" 
                                                            class="form-input w-full px-3 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3F9B] focus:border-[#4A3F9B] transition text-sm
                                                            @error('activity_category_id', "group{$group->id}-{$day}-{$hour}") border-red-500 @enderror">
                                                        <option value="">Выберите занятие...</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}">
                                                                {{ $category->name }} ({{ $category->duration_minutes }} мин)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    
                                                    @error('activity_category_id', "group{$group->id}-{$day}-{$hour}")
                                                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                                    @enderror
                                                    
                                                    @error('start_time', "group{$group->id}-{$day}-{$hour}")
                                                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                                    @enderror
                                                    
                                                    <button type="submit" 
                                                            class="gradient-bg text-white px-3 py-1 rounded-lg hover:opacity-90 transition text-sm w-full">
                                                        Добавить
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>
    </main>
    <x-footer/>
</body>
</html>