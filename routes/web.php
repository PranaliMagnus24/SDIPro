<?php

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QurbaniController;
use App\Http\Controllers\RamzanCollectionController;
use App\Http\Controllers\DonationCategoryController;
use App\Http\Controllers\IjtemaFormController;
use App\Http\Controllers\CacheController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CausesController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\QurbaniDaysController;
Auth::routes();


Route::get('/', function () {
    if(Auth::check())
    {
        return redirect()->route('home');
    } else {
        return view('auth/login');
    }
});


// Route::get('/', function () {
//     if(Auth::check())
//     {
//         return redirect()->route('home');
//     } else {
//         return view('qurbanis.register');
//     }
// });

// Route::get('/dashboard', function () {
//     return view('home');
// });

Route::get('/offline', function () {
    return view('offline');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/ramadan-home', [App\Http\Controllers\HomeController::class, 'home'])->name('homeramadan');
//Route::get('/clear-all-caches', [CacheController::class, 'clearAllCaches']);
Route::get('/master-settings', function () {
    return view('settings.master');
})->name('master.settings');


Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    Route::resource('qurbanis', QurbaniController::class);
    Route::get('collection', [RamzanCollectionController::class, 'index'])->name('collectionlist');
    Route::get('/final-list/{id}', [PDFController::class, 'generatefinallist'])->name('pdf.finallist');
    ///////Final list day1 and day2
    Route::get('/finalList/{day}', [PDFController::class, 'finalListView']);


    Route::get('form', [IjtemaFormController::class, 'index'])->name('formlist');
    Route::get('/razorpay/{id}', [QurbaniController::class, 'initiateRazorpayPayment'])->name('razorpay');
    Route::get('/export-collections', [RamzanCollectionController::class, 'export'])->name('export.collections');
    // Route to handle the Razorpay payment success response (callback)
    Route::post('/razorpay/payment-success', [QurbaniController::class, 'handleRazorpayPayment'])->name('razorpay.payment.success');
    Route::get('/formcreate', [IjtemaFormController::class, 'create'])->name('create.form');
    Route::post('form', [IjtemaFormController::class, 'store'])->name('form.store');
    Route::get('form/{id}/edit', [IjtemaFormController::class, 'edit'])->name('form.edit');
    Route::put('form/{id}/update', [IjtemaFormController::class, 'update'])->name('form.update');
    Route::delete('form/{id}', [IjtemaFormController::class, 'destroy'])->name('form.destroy');
    Route::get('/form/create', [IjtemaFormController::class, 'fetch'])->name('fetch');
    Route::get('/check-contact', [IjtemaFormController::class, 'checkContact']);
    Route::get('/thankyou', [IjtemaFormController::class, 'thankyou'])->name('thankyou');
    Route::get('collection/create', [RamzanCollectionController::class, 'create'])->name('collection.create');
Route::post('collection/store', [RamzanCollectionController::class, 'store'])->name('collection.store');
Route::get('collection/{id}/edit', [RamzanCollectionController::class, 'edit'])->name('collection.edit');
Route::put('collection/upd/{id}', [RamzanCollectionController::class, 'update'])->name('collection.update');
Route::delete('collection/del/{id}', [RamzanCollectionController::class, 'destroy'])->name('collection.destroy');
Route::get('/collection/view/{id}', [RamzanCollectionController::class, 'view'])->name('collection.view');
Route::get('/collection/show/{id}', [RamzanCollectionController::class, 'show'])->name('collection.show');

});


///////////Qurbani Controller
Route::post('/whatsapp', [QurbaniController::class, 'WhatsAppMessage'])->name('whatsapp');
Route::get('/generate-pdf/{qurbani_id}', [QurbaniController::class, 'generatePDF'])->name('pdf.generate');
Route::get('qurbanis/archive/2024', [QurbaniController::class, 'archive2024'])->name('qurbanis.archive2024');

Route::post('/qurbani/approve/{id}', [QurbaniController::class, 'approveGuest'])->name('qurbani.approve');
Route::get('/qurbani/guest-submissions', [QurbaniController::class, 'guestSubmissions'])
    ->name('qurbani.guest.submissions')
    ->middleware('permission:qurbani-list');
Route::get('/qurbani/{id}/edit', [QurbaniController::class, 'edit'])->name('qurbani.edit');
Route::put('/qurbani/{id}/update', [QurbaniController::class, 'update'])->name('qurbani.update');

Route::get('/autosuggest', [QurbaniController::class, 'suggest'])->name('qurbani.autosuggest');
//////Qurbani Days Controller
Route::get('days', [QurbaniDaysController::class, 'qurbaniDay'])->name('qurbani.days');
Route::post('days', [QurbaniDaysController::class, 'qurbaniDayStore'])->name('qurbani.days.store');




//category
Route::get('category', [DonationCategoryController::class, 'index'])->name('categorylist');
Route::get('category/create', [DonationCategoryController::class, 'create'])->name('category.create');
Route::post('category', [DonationCategoryController::class, 'store'])->name('category.store');
Route::get('category/{id}/edit', [DonationCategoryController::class, 'edit'])->name('category.edit');
Route::put('category/{id}', [DonationCategoryController::class, 'update'])->name('category.update');
Route::delete('category/{id}', [DonationCategoryController::class, 'destroy'])->name('category.destroy');

Route::get('/collection/pdf/{id}', [RamzanCollectionController::class, 'generatePDF'])->name('collection.pdf');
Route::post('/send-whatsapp', [RamzanCollectionController::class, 'sendWhatsAppMessage'])->name('send.whatsapp');

Route::get('/clear-caches', [CacheController::class, 'clearAllCaches'])->name('clear.caches');

Route::post('/settings/general/update', [SettingController::class, 'updateGeneralSettings'])->name('update.general.settings');
Route::get('/get-cities/{state_id}', [SettingController::class, 'getCities']);
Route::post('/settings/whatsapp', [SettingController::class, 'updateWhatsappSettings'])->name('update.whatsapp.settings');
Route::post('/settings/sms', [SettingController::class, 'updatesmsSettings'])->name('update.sms.settings');
Route::post('/settings/payment', [SettingController::class, 'updatepaymentSettings'])->name('update.payment.settings');

//register
Route::get('/createqurbani', [RegisterController::class, 'createqurbani'])->name('create.qurbani');
Route::post('formqurbani', [RegisterController::class, 'storequrbani'])->name('formqurbani.store');
Route::get('/thankyou-qurbani', [RegisterController::class, 'thankyouqurbani'])->name('thankyouqurbani');
Route::get('/qurbani-list', [RegisterController::class, 'qurbaniList'])->name('guest.qurbani.list');
Route::post('/qurbani/{id}/approve', [RegisterController::class, 'approveQurbani'])->name('qurbani.approve');
Route::get('/admin/qurbani/guest-approve/{id}', [QurbaniController::class, 'approveGuest'])->name('admin.qurbani.guest.approve');

//causes

Route::get('/causeslist', [CausesController::class, 'index'])->name('causes.causeslist');
Route::get('/causes/create', [CausesController::class, 'create'])->name('causes.create');
Route::post('/causes/store', [CausesController::class, 'store'])->name('causes.store');
Route::get('/causes/edit/{id}', [CausesController::class, 'edit'])->name('causes.edit');
Route::put('/causes/update/{id}', [CausesController::class, 'update'])->name('causes.update');
Route::delete('/causes/delete/{id}', [CausesController::class, 'destroy'])->name('causes.delete');
Route::get('/causes/show/{id}', [CausesController::class, 'show'])->name('causes.show');

//faq
Route::get('/faqlist', [FaqController::class, 'index'])->name('faqlist');
Route::get('/faq/create', [FaqController::class, 'create'])->name('faq.create');
Route::post('/faq/store', [FaqController::class, 'store'])->name('faq.store');
Route::get('/faq/edit/{id}', [FaqController::class, 'edit'])->name('faq.edit');
Route::put('/faq/update/{id}', [FaqController::class, 'update'])->name('faq.update');
Route::delete('/faq/delete/{id}', [FaqController::class, 'destroy'])->name('faq.delete');
Route::get('/faq/show/{id}', [FaqController::class, 'show'])->name('faq.show');
