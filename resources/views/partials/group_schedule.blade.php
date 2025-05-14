<div class="overflow-x-auto">
  <div class="inline-block min-w-full align-middle">
    <div class="grid grid-cols-6 gap-0 border border-gray-200 rounded-lg">
      <!-- Time Column -->
      <div class="flex flex-col border-r border-gray-200 bg-gray-50">
        <div class="h-16 border-b border-gray-200 flex items-center justify-center font-medium bg-gray-100">
          Время
        </div>
        @foreach(range(7, 18) as $hour)
        <div class="time-slot h-16 flex items-center justify-center text-gray-500 border-b border-gray-200">
          {{ sprintf('%02d:00', $hour) }}
        </div>
        @endforeach
      </div>
      
      <!-- Days Columns -->
      <div class="col-span-5 grid grid-cols-5 gap-0">
        @foreach([1 => 'Понедельник', 2 => 'Вторник', 3 => 'Среда', 4 => 'Четверг', 5 => 'Пятница'] as $dayNum => $dayName)
        <div class="flex flex-col border-r border-gray-200 last:border-r-0">
          <!-- Header with day name -->
          <div class="h-16 border-b border-gray-200 flex items-center justify-center font-medium bg-gray-100">
            {{ $dayName }}
          </div>
          
          <!-- Time slots -->
          @foreach(range(7, 18) as $hour)
          @php
            $currentItems = [];
            
            if(isset($schedule_items[$dayNum])) {
              foreach($schedule_items[$dayNum] as $item) {
                $start = \Carbon\Carbon::parse($item->start_time);
                $end = \Carbon\Carbon::parse($item->end_time);
                
                // Проверяем, проходит ли мероприятие в текущем часу
                if($start->hour <= $hour && $end->hour >= $hour) {
                  $currentItems[] = $item;
                }
              }
            }
          @endphp
          
          <div class="time-slot h-16 border-b border-gray-200 relative">
            @foreach($currentItems as $item)
              @php
                $start = \Carbon\Carbon::parse($item->start_time);
                $end = \Carbon\Carbon::parse($item->end_time);
                $isFirstHour = ($start->hour == $hour);
                $isLastHour = ($end->hour == $hour);
                
                $bgColor = 'bg-purple-100';
                if(str_contains($item->activityCategory->name, 'Игровая')) {
                  $bgColor = 'bg-orange-100';
                } elseif(str_contains($item->activityCategory->name, 'Утренник')) {
                  $bgColor = 'bg-orange-200';
                } elseif(str_contains($item->activityCategory->name, 'Прогулка')) {
                  $bgColor = 'bg-blue-100';
                } elseif(str_contains($item->activityCategory->name, 'Сон')) {
                  $bgColor = 'bg-green-100';
                }
              @endphp
              
              @if($isFirstHour)
                @php
                  $duration = $start->diffInMinutes($end);
                  $rowSpan = ceil($duration / 60);
                @endphp
                
                <div class="schedule-item {{ $bgColor }} h-full rounded-lg p-2 flex flex-col justify-center absolute top-0 left-1 right-1 bottom-0"
                     style="height: {{ $rowSpan * 64 }}px; z-index: {{ $loop->index + 1 }};"
                     title="{{ $item->activityCategory->name }} ({{ $start->format('H:i') }} - {{ $end->format('H:i') }})">
                  <div class="font-medium text-sm truncate">{{ $item->activityCategory->name }}</div>
                  <div class="text-xs text-gray-600">{{ $start->format('H:i') }}-{{ $end->format('H:i') }}</div>
                </div>
              @else
                <!-- Для последующих часов просто резервируем место -->
                <div class="absolute top-0 left-0 right-0 bottom-0" style="z-index: {{ $loop->index + 1 }};"></div>
              @endif
            @endforeach
          </div>
          @endforeach
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
  <!-- Легенда расписания -->
  <div class="mt-6 flex flex-wrap gap-4 justify-center">
    <div class="flex items-center">
      <div class="w-4 h-4 bg-purple-100 rounded mr-2"></div>
      <span class="text-sm">Занятие</span>
    </div>
    <div class="flex items-center">
      <div class="w-4 h-4 bg-orange-100 rounded mr-2"></div>
      <span class="text-sm">Игровая</span>
    </div>
    <div class="flex items-center">
      <div class="w-4 h-4 bg-orange-200 rounded mr-2"></div>
      <span class="text-sm">Утренник</span>
    </div>
    <div class="flex items-center">
      <div class="w-4 h-4 bg-blue-100 rounded mr-2"></div>
      <span class="text-sm">Прогулка</span>
    </div>
    <div class="flex items-center">
      <div class="w-4 h-4 bg-green-100 rounded mr-2"></div>
      <span class="text-sm">Сон</span>
    </div>
  </div>