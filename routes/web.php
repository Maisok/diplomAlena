<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CertificateDisplayController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\EducatorController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AdminQuestionController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ActivityCategoryController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\AdminGalleryController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/company', 'company')->name('company');
Route::view('/test', 'text')->name('test');
Route::view('/about', 'about')->name('about');
Route::get('/success', [CertificateDisplayController::class, 'index'])->name('certificates.display');

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/export', [ExportController::class, 'showExportForm'])->name('export.form');
    Route::post('/export', [ExportController::class, 'exportChildren'])->name('export.children');
    Route::get('/shownews', [NewsController::class, 'show'])->name('shownews.index');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::resource('groups', GroupController::class);
    Route::resource('users', UserController::class);
    Route::resource('news', NewsController::class);
    Route::resource('certificates', CertificateController::class);
    Route::resource('children', ChildController::class);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});



Route::middleware(['auth'])->group(function () {
    // Основные маршруты чатов
    Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
    Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('chats.show');
    
    // Маршруты для сообщений
    Route::prefix('/chats/{chat}')->group(function () {
        Route::get('/messages', [ChatController::class, 'getMessages'])->name('chats.messages.get');
        Route::post('/messages', [ChatController::class, 'storeMessage'])->name('chats.messages.store');
    });
    
    // Отдельный маршрут для обновлений
    Route::get('/chats-updates', [ChatController::class, 'getUpdates'])->name('chats.updates');


    Route::post('/chats/{chat}/update-status', [ChatController::class, 'updateMessageStatus'])
    ->name('chats.messages.update-status');


    Route::post('/chats/{chat}/mark-read', [ChatController::class, 'markMessagesAsRead'])
    ->name('chats.messages.mark-read');


    Route::post('/chats/{chat}/check-message-status', [ChatController::class, 'checkMessageStatus'])
    ->name('chats.check-message-status')
    ->middleware('auth');
    
    // Маршруты для создания чатов
    Route::post('/chats/start/educator/{educator}', [ChatController::class, 'startWithEducator'])
        ->name('chats.start.educator');
    Route::post('/chats/start/admin', [ChatController::class, 'startWithAdmin'])
        ->name('chats.start.admin');
});



Route::middleware(['auth'])->group(function () {
    Route::get('/educators', [EducatorController::class, 'index'])->name('educators.list');
});


Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');
Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
Route::get('/questions/all', [QuestionController::class, 'all'])->name('questions.all');

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/questions', [AdminQuestionController::class, 'index'])->name('admin.questions.index');
    Route::get('/questions/{question}', [AdminQuestionController::class, 'show'])->name('admin.questions.show');
    Route::post('/questions/{question}/answer', [AdminQuestionController::class, 'answer'])->name('admin.questions.answer');
    Route::delete('/questions/{question}', [AdminQuestionController::class, 'destroy'])->name('admin.questions.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::post('schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::delete('schedules/{scheduleItem}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
});

Route::middleware(['auth'])->group(function () {
    // Список категорий
    Route::get('activity-categories', [ActivityCategoryController::class, 'index'])
        ->name('activity-categories.index');
    
    // Форма создания категории
    Route::get('activity-categories/create', [ActivityCategoryController::class, 'create'])
        ->name('activity-categories.create');
    
    // Сохранение новой категории
    Route::post('activity-categories', [ActivityCategoryController::class, 'store'])
        ->name('activity-categories.store');
    
    // Форма редактирования категории
    Route::get('activity-categories/{activityCategory}/edit', [ActivityCategoryController::class, 'edit'])
        ->name('activity-categories.edit');
    
    // Обновление категории
    Route::match(['put', 'patch'], 'activity-categories/{activityCategory}', [ActivityCategoryController::class, 'update'])
        ->name('activity-categories.update');
    
    // Удаление категории
    Route::delete('activity-categories/{activityCategory}', [ActivityCategoryController::class, 'destroy'])
        ->name('activity-categories.destroy');
});


// Публичные роуты галереи
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{photo}', [GalleryController::class, 'show'])->name('gallery.show');

// Админские роуты галереи
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Страница управления галереей
    Route::get('/gallery', [AdminGalleryController::class, 'index'])->name('admin.gallery.index');
    
    // Создание фото
    Route::get('/gallery/create', [AdminGalleryController::class, 'create'])->name('admin.gallery.create');
    Route::post('/gallery', [AdminGalleryController::class, 'store'])->name('admin.gallery.store');
    
    // Редактирование фото
    Route::get('/gallery/{photo}/edit', [AdminGalleryController::class, 'edit'])->name('admin.gallery.edit');
    Route::put('/gallery/{photo}', [AdminGalleryController::class, 'update'])->name('admin.gallery.update');
    
    // Удаление фото
    Route::delete('/gallery/{photo}', [AdminGalleryController::class, 'destroy'])->name('admin.gallery.destroy');


    Route::get('/export/schedule/form', [ExportController::class, 'showScheduleExportForm'])->name('export.schedule.form');
    Route::post('/export/schedule', [ExportController::class, 'exportSchedule'])->name('export.schedule');
});