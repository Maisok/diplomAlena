<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить доверенное лицо - 7 Звезд</title>
    <script src="https://cdn.tailwindcss.com"></script> 
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
</head>
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
<body class="bg-gray-100">
<x-header/>
<div class="container mx-auto px-4 py-12">
    @if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
        <ul class="space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg">
        <div class="p-6">
            <h2 class="text-2xl font-semibold mb-4">Редактировать доверенное лицо</h2>
            <form action="{{ route('trusted-people.update', $trusted_person) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">Родитель</label>
                    <select name="parent_id" id="parent_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ $trusted_person->parent_id == $parent->id ? 'selected' : '' }}>
                                {{ $parent->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Фамилия</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $trusted_person->last_name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                </div>

                <div class="mb-4">
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Имя</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $trusted_person->first_name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                </div>

                <div class="mb-4">
                    <label for="patronymic" class="block text-sm font-medium text-gray-700 mb-2">Отчество</label>
                    <input type="text" name="patronymic" id="patronymic" value="{{ old('patronymic', $trusted_person->patronymic) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Телефон</label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $trusted_person->phone_number) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div class="">
                    <a href="{{ route('trusted-people.index') }}" class="text-gray-600 hover:text-gray-900">Отмена</a>
                    <button type="submit" class="gradient-bg text-white px-4 py-2 rounded-lg">Обновить</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('phone_number').addEventListener('input', function (e) {
        let input = e.target.value.replace(/\D/g, '');
        let formatted = '';

        if (input.length > 0) formatted = '8';
        if (input.length > 1) formatted += ' ' + input.substring(1, 4);
        if (input.length > 4) formatted += ' ' + input.substring(4, 7);
        if (input.length > 7) formatted += ' ' + input.substring(7, 9);
        if (input.length > 9) formatted += ' ' + input.substring(9, 11);

        e.target.value = formatted;
    });
</script>
<x-footer/>

</body>
</html>