@extends('layouts.app')

@section('page-title', 'ЛИЦЕНЗИИ И СЕРТИФИКАТЫ')
@section('page-subtitle', 'Официальные документы, подтверждающие качество нашего образования')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg hover-scale animate-fade-in-up">
  <p class="mb-6">Мы понимаем, что родителям важно доверить образование детей проверенной организации, зарегистрированной во всех инстанциях.</p>
  <p>Все авторские методики, разработанные в центре, проходят государственную аттестацию и соответствуют всем образовательным стандартам.</p>
</div>

<div class="max-w-4xl mx-auto mt-12">
  <h2 class="text-2xl font-bold mb-8 gradient-text text-center">ЛИЦЕНЗИИ</h2>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @foreach($licenses as $license)
    <div class="bg-white p-4 rounded-lg shadow-md hover-scale">
      <img alt="{{ $license->title }}" class="w-full h-auto rounded" src="{{ asset('storage/' . $license->image) }}">
      <p class="mt-2 text-center font-medium">{{ $license->title }}</p>
    </div>
    @endforeach
  </div>
</div>

<div class="max-w-4xl mx-auto mt-12">
  <h2 class="text-2xl font-bold mb-8 gradient-text text-center">НАШИ ДОСТИЖЕНИЯ</h2>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($certificates as $certificate)
    <div class="bg-white p-4 rounded-lg shadow-md hover-scale">
      <img alt="{{ $certificate->title }}" class="w-full h-auto rounded" src="{{ asset('storage/' . $certificate->image) }}">
      <p class="mt-2 text-center font-medium">{{ $certificate->title }}</p>
    </div>
    @endforeach
  </div>
</div>
@endsection