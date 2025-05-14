<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>7 звёзд - Чаты</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
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
      background: linear-gradient(45deg, #4A3F9B, #D32F2F);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }
    .bg-pattern {
      background-image: url('{{ asset('images/Rectangle 9.png') }}');
      background-size: cover;
      background-position: center;
      background-blend-mode: overlay;
      background-color: rgba(255, 255, 255, 0.9);
    }
  </style>
</head>
<body class="bg-gray-100 font-rubik flex flex-col min-h-screen">

<x-header/>

<!-- Герой секция -->
<section class="relative py-16 bg-gradient-to-r from-purple-100 to-white">
  <div class="container mx-auto px-4 text-center">
    <h1 class="text-3xl md:text-4xl font-bold gradient-text mb-4 animate-fade-in-up">
      @yield('page-title')
    </h1>
    <p class="text-lg text-gray-700 max-w-2xl mx-auto animate-fade-in-up" style="animation-delay: 0.2s;">
      @yield('page-subtitle')
    </p>
  </div>
</section>

<!-- Основной контент -->
<main class="flex-grow py-12">
  <div class="container mx-auto px-4">
    @yield('content')
  </div>
</main>

<x-footer/>

<script>
  // Анимация при скролле
  document.addEventListener('DOMContentLoaded', () => {
    const animateElements = document.querySelectorAll('.animate-fade-in-up');
    
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = 1;
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, { threshold: 0.1 });
    
    animateElements.forEach(el => {
      el.style.opacity = 0;
      el.style.transform = 'translateY(20px)';
      el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
      observer.observe(el);
    });
  });
</script>

@yield('scripts')
</body>
</html>