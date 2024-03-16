<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttMachineController;
use App\Http\Controllers\HolidaysController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AttendanceController;
use App\Models\Attendance;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\LeavesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::group(['middleware' => ['auth', 'role:Super_admin']], function () {
        Route::get('/extract-attendance', [AttMachineController::class, 'extractAtendance'])->name('machine.extractAttendance');
        Route::get('/clear-data', [AttMachineController::class, 'clearData'])->name('machine.clearData');
        Route::get('/summary-report', [ReportController::class, 'summaryReport'])->name('report.summaryReport');
        Route::post('/search-summary-report', [ReportController::class, 'searchSummaryReport'])->name('report.searchSummaryReport');   
        Route::get('/single-summary-report', [ReportController::class, 'singleSummaryReport'])->name('report.singleSummaryReport');
        Route::post('/single-search-summary-report', [ReportController::class, 'searchSingleSummaryReport'])->name('report.searchSingleSummaryReport');
        Route::get('/single-att-report', [ReportController::class, 'singleAttendanceReport'])->name('report.singleAttendanceReport');
        Route::post('/search-single-employee-report', [ReportController::class, 'searchSingleAttendanceReport'])->name('report.searchSingleAttendanceReport');
        Route::get('/Daily-report', [ReportController::class, 'dailyAttendanceReport'])->name('report.dailyAttendanceReport');
        Route::post('/search-daily-attendance-report', [ReportController::class, 'searchDailyReport'])->name('report.searchDailyReport');  
        Route::resources([
            'employee' => EmployeeController::class,
            'machine' => AttMachineController::class,
            'holiday' => HolidaysController::class,
            'attendance' => AttendanceController::class,
            'leave' => LeavesController::class,
        ]);
    
        Route::get('generate-pdf', [PDFController::class, 'generatePDF']);
        // Routes that require the 'admin' role go here
    });
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/', function () {

        return view('dashboard');
    })->name('dashboard');

    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    
    // Route::get('/extract-attendance', function () {
    //     // return view('machine.attendanceData');
    // })->name('machine.extractAttendance');

    

    Route::resources([
        'employee' => EmployeeController::class,
        'leave' => LeavesController::class,
        
    ]);

});
