@extends('layouts.app')

@section('page-title', 'О КОМПАНИИ')
@section('page-subtitle', '13 лет успешной работы в сфере дошкольного образования')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg hover-scale animate-fade-in-up">
  <div class="prose max-w-none">
    <p>Частное дошкольное образовательное учреждение «Центр развития – детский сад "Семь звезд" имеет лицензию на осуществление образовательной деятельности (№ 9378 от 29.07.2016 г.) и более 13 лет предоставляет услуги в сфере дошкольного и дополнительного образования.</p>
    
    <p>В настоящее время мы нацелены на решение широкого круга задач в сфере образования и социальной сфере и на обеспечение возможностью получения дошкольного образования детям в возрасте от 1 года 6 месяцев до 7 лет, а также получение дополнительного образование детям в возрасте от 9 месяцев до 12 лет.</p>
    
    <p>Наши детские сады и центр развития оснащены современным оборудованием, разнообразными развивающими игрушками и пособиями. Комфорт детям обеспечивается за счет использования экологически чистых материалов в отделке помещений.</p>
    
    <h3 class="text-xl font-semibold mt-8 text-[#4A3F9B]">ИННОВАЦИОННЫЕ ПЛОЩАДКИ</h3>
    <ul class="list-disc list-inside space-y-2">
      <li>ГАУ ДПО ИРО по теме: "Использование реджио-педагогики в организации развивающей среды"</li>
      <li>ФГБНУ "ИИДСВ РАО" по теме: "Модернизация образования в дошкольной образовательной организации"</li>
    </ul>
  </div>
</div>

<div class="max-w-4xl mx-auto mt-8 grid md:grid-cols-2 gap-8">
  <div class="bg-white p-6 rounded-xl shadow-lg hover-scale animate-fade-in-up" style="animation-delay: 0.2s;">
    <h3 class="text-xl font-semibold mb-4 text-[#D32F2F]">РЕЖИМ РАБОТЫ</h3>
    <p class="mb-2">Детский сад:</p>
    <p class="font-bold">8:00 - 19:00 (пн-пт)</p>
    <p class="mt-4 mb-2">Центр развития:</p>
    <p class="font-bold">9:00 - 20:00 (ежедневно)</p>
  </div>
  
  <div class="bg-white p-6 rounded-xl shadow-lg hover-scale animate-fade-in-up" style="animation-delay: 0.3s;">
    <h3 class="text-xl font-semibold mb-4 text-[#D32F2F]">КОНТАКТЫ</h3>
    <p class="mb-2">Телефон:</p>
    <p class="font-bold text-lg">8 (3952) 249-836</p>
    <p class="mt-4">Анна Иванова</p>
  </div>
</div>
@endsection