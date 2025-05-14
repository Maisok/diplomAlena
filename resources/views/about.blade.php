@extends('layouts.app')

@section('page-title', 'УВАЖАЕМЫЕ РОДИТЕЛИ!')
@section('page-subtitle', 'Вся информация для поступления в наш детский сад')

@section('content')
<div class="max-w-4xl mx-auto bg-pattern p-8 rounded-xl shadow-lg hover-scale animate-fade-in-up">
  <div class=" text-xl text-[#00197E] space-y-6">
    <p>Мы рады приветствовать вас в нашем частном детском саду, где каждый день наполнен радостью и новыми открытиями! В нашем садике мы не просто заботимся о детях, но и полностью подготавливаем вашего малыша к школьной жизни, обеспечив всестороннее развитие и необходимые навыки.</p>
    
    <p>Мы понимаем, как важно для вас выбрать правильное место для вашего ребенка, где он сможет учиться, играть и расти. В нашем саду каждый ребенок получает индивидуальное внимание и поддержку.</p>
    
    <p>Для подачи заявления в детский сад вам понадобится:</p>
    
    <ul class="list-decimal list-inside pl-4">
      <li>Заявление от родителей</li>
      <li>Копия свидетельства о рождении ребенка</li>
      <li>Медицинская справка о состоянии здоровья</li>
      <li>Заполненная анкета с контактной информацией</li>
    </ul>
    
    <div class="text-center mt-8">
      <a href="{{ asset('Анкета родителям.docx') }}" download class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        СКАЧАТЬ АНКЕТУ
      </a>
    </div>
  </div>
</div>

<div class="max-w-4xl mx-auto mt-12 bg-white p-8 rounded-xl shadow-lg hover-scale animate-fade-in-up" style="animation-delay: 0.2s;">
  <h2 class="text-2xl font-bold mb-6 gradient-text">КОНТАКТНАЯ ИНФОРМАЦИЯ</h2>
  
  <div class="grid md:grid-cols-2 gap-8">
    <div>
      <h3 class="text-xl font-semibold mb-4 text-[#4A3F9B]">АДРЕС</h3>
      <p class="text-lg mb-2">ул. Солнечная, д. 10, г. Градовет</p>
      
      <h3 class="text-xl font-semibold mt-6 mb-4 text-[#4A3F9B]">ТРАНСПОРТ</h3>
      <ul class="list-disc list-inside space-y-2">
        <li>Автобус №12 (остановка <span class="text-[#D32F2F]">Улица Солнечная</span>)</li>
        <li>Троллейбус №6 (остановка <span class="text-[#D32F2F]">Парк цветов</span>)</li>
      </ul>
    </div>
    
    <div>
      <h3 class="text-xl font-semibold mb-4 text-[#4A3F9B]">КОНТАКТЫ</h3>
      <p class="text-lg mb-2">
        Телефон: <span class="text-[#D32F2F] font-bold">7 (999) 123-45-67</span>
      </p>
      <p class="text-lg">Ответственное лицо: Анна Иванова</p>
      
      <h3 class="text-xl font-semibold mt-6 mb-4 text-[#4A3F9B]">ПАРКОВКА</h3>
      <p class="text-lg">Рядом с детским садом по ул. Лунная</p>
    </div>
  </div>
</div>
@endsection