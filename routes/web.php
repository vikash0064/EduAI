<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ParentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = \Illuminate\Support\Facades\Auth::user();
    if ($user->isAdmin()) return redirect()->route('admin.dashboard');
    if ($user->isTeacher()) return redirect()->route('teacher.dashboard');
    if ($user->isStudent()) return redirect()->route('student.dashboard');
    if ($user->isParent()) return redirect()->route('parent.dashboard');
    
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/notifications/mark-read', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.mark-read');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/messages', [ChatController::class, 'index'])->name('messages.index');
    Route::post('/messages/send', [ChatController::class, 'sendMessage'])->name('messages.send');
    Route::post('/messages/start', [ChatController::class, 'startConversation'])->name('messages.start');
    Route::post('/messages/group', [ChatController::class, 'createGroup'])->name('messages.group');
    Route::post('/messages/add-member', [ChatController::class, 'addMember'])->name('messages.add-member');
    Route::get('/timetable', function() { return view('timetable.index'); })->name('timetable.index');
    Route::resource('events', EventController::class);

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', UserController::class);
        Route::resource('fees', \App\Http\Controllers\Admin\FeeController::class);
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
        Route::resource('announcements', \App\Http\Controllers\AnnouncementController::class);
    });

    // Teacher Routes
    Route::middleware('role:teacher')->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [TeacherController::class, 'index'])->name('dashboard');
        Route::resource('students', StudentProfileController::class);
        Route::resource('attendance', AttendanceController::class);
        Route::post('/attendance/mark-ajax', [AttendanceController::class, 'markAjax'])->name('attendance.mark-ajax');
        Route::resource('grades', GradeController::class);
        Route::post('/exams/store', [GradeController::class, 'storeExam'])->name('exams.store');
        Route::resource('assignments', \App\Http\Controllers\AssignmentController::class);
    });

    // Student Routes
    Route::middleware('role:student')->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentProfileController::class, 'showMyProfile'])->name('dashboard');
        Route::get('/attendance', [AttendanceController::class, 'myAttendance'])->name('attendance');
        Route::get('/grades', [GradeController::class, 'myGrades'])->name('grades');
        Route::get('/assignments', [\App\Http\Controllers\AssignmentController::class, 'index'])->name('assignments');
    });

    // Parent Routes
    Route::middleware('parent')->prefix('parent')->name('parent.')->group(function () {
        Route::get('/dashboard', [ParentController::class, 'index'])->name('dashboard');
        Route::get('/child/{student}', [ParentController::class, 'showChild'])->name('child.show');
        Route::get('/child/{student}/attendance', [ParentController::class, 'attendance'])->name('child.attendance');
        Route::get('/child/{student}/results', [ParentController::class, 'results'])->name('child.results');
        Route::get('/child/{student}/fees', [ParentController::class, 'fees'])->name('child.fees');
    });
});

require __DIR__.'/auth.php';
