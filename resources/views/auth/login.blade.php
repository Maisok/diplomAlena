<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>7 звезд - Вход</title>
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Mono+One&display=swap" rel="stylesheet">

    <style>
        .custom-card {
            background-color: #8EC5FC;
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            border: 5px dashed white;
            color: white;
            position: relative;
            margin: 20px;
        }
        .custom-card img {
            position: absolute;
            top: -50px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 50%;
            border: 5px dashed white;
        }

        body {
            font-family: 'Rubik Mono One', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
    <x-header/>

    <div class="flex-grow container mx-auto px-4 py-8">
        <div class="flex justify-center">
            <div class="w-full md:w-2/3 lg:w-1/2">
                <div class="card bg-white shadow-lg rounded-lg p-8">
                    <div class="card-header text-2xl font-bold text-center mb-6">Вход</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="login" class="block text-gray-700 text-sm font-bold mb-2">Логин (5 цифр)</label>
                                <input id="login" type="text" class="form-control @error('login') is-invalid @enderror bg-gray-200 rounded-lg p-2 w-full" name="login" value="{{ old('login') }}" required autocomplete="login" autofocus>
                                @error('login')
                                    <span class="invalid-feedback text-red-500 text-xs mt-1" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Пароль</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror bg-gray-200 rounded-lg p-2 w-full" name="password" required autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback text-red-500 text-xs mt-1" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="g-recaptcha" data-sitekey="6Lc9BYEqAAAAAIeUpt8pHeRh9FoKB9t5DLmsMxdu"></div>
                                @error('g-recaptcha-response')
                                    <p class="text-red-500 text-xs mt-1">Пожалуйста, подтвердите, что вы не робот.</p>
                                @enderror
                            </div>

                            <div class="flex justify-center">
                                <button type="submit" class="btn btn-primary bg-[#5543AE] text-white py-2 px-6 rounded-lg shadow-lg">
                                    Войти
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-footer/>
</body>
</html>