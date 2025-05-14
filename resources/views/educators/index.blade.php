@extends('layouts.chat')

@section('page-title', 'СПИСОК ВОСПИТАТЕЛЕЙ')
@section('page-subtitle', 'Выберите воспитателя для начала чата')

@section('content')
<div class="max-w-4xl mx-auto">
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($educators as $educator)
      <div class="bg-white rounded-xl shadow-lg hover-scale animate-fade-in-up" style="animation-delay: 0.{{ $loop->index }}s;">
        <div class="p-6">
          <div class="flex items-center space-x-4 mb-4">
            <div class="flex-shrink-0 bg-purple-100 p-2 rounded-full">
              <svg class="h-10 w-10 text-[#4A3F9B]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </div>
            <div>
              <h3 class="text-lg font-semibold">{{ $educator->full_name }}</h3>
              <p class="text-gray-600 text-sm">
                {{ $educator->groups->first()->name ?? 'Группа не указана' }}
              </p>
            </div>
          </div>
          
          <form action="{{ route('chats.start.educator', $educator) }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-gradient-to-r from-[#4A3F9B] to-[#D32F2F] text-white px-4 py-2 rounded-lg hover:opacity-90 transition">
              Начать чат
            </button>
          </form>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endsection