<!-- Футер -->
<footer class="bg-gray-900 text-white py-12">
  <div class="container mx-auto px-4 w-2/5">
    <div class="flex flex-col md:flex-row justify-between gap-8">
      
      <!-- Левый блок -->
      <div class="text-center md:text-left">
        <h3 class="text-xl font-bold mb-4">7 звёзд</h3>
        <p class="text-gray-400 mb-4">
          Частный детский сад с 13-летним опытом работы. </br> Гармоничное развитие детей от 1.5 до 7 лет.
        </p>
        <p class="text-gray-400 text-sm">
          © 2025 Детский сад "7 звёзд". Все права защищены.
        </p>
      </div>

      <!-- Навигация -->
      <div class="text-center md:text-left">
        <h3 class="text-lg font-semibold mb-4">Навигация</h3>
        <ul class="space-y-2">
          <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition">Главная</a></li>
          @if(auth()->check())
            <li><a href="{{ route('shownews.index') }}" class="text-gray-400 hover:text-white transition">Новости</a></li>
          @endif
          <li><a href="{{ route('certificates.display') }}" class="text-gray-400 hover:text-white transition">Лицензии</a></li>
          <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white transition">Родители</a></li>
          <li><a href="{{ route('company') }}" class="text-gray-400 hover:text-white transition">О нас</a></li>
        </ul>
      </div>

      <!-- Контакты -->
      <div class="text-center md:text-left">
        <h3 class="text-lg font-semibold mb-4">Контакты</h3>
        <ul class="space-y-2 text-gray-400">
          <li>ул. Солнечная, д. 10</li>
          <li>г. Иркутск</li>
          <li>+7 (3952) 12-34-56</li>
          <li>info@7stars.ru</li>
        </ul>
      </div>
    </div>
  </div>
</footer>
