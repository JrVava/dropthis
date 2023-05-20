<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
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

// Route::get('/', function () {
//     return view('/pages/index');
// });
Auth::routes();

Route::group(['middleware' => 'auth'],function(){

	// SMTP route

	


	Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
	Route::post('/', [App\Http\Controllers\HomeController::class, 'dateFilter'])->name('dashboard.dateFilter');

	Route::get('/group/create',[App\Http\Controllers\GroupController::class, 'show'])->name('group.create');
	Route::post('/group/add',[App\Http\Controllers\GroupController::class, 'save'])->name('group.add');
	Route::get('/groups',[App\Http\Controllers\GroupController::class, 'index'])->name('groups');
	Route::delete('/group/delete/{id}',[App\Http\Controllers\GroupController::class, 'delete'])->name('group.delete');
	Route::get('/group/edit/{id}',[App\Http\Controllers\GroupController::class, 'edit'])->name('group.edit');
	Route::post('/group/update',[App\Http\Controllers\GroupController::class, 'update'])->name('group.update');
	Route::get('/group/statistics/{id}',[App\Http\Controllers\GroupController::class, 'statistics'])->name('group.statistics');
	Route::post('/group/statistics/{id}',[App\Http\Controllers\GroupController::class, 'dateFilter'])->name('group.dateFilter');

	Route::get('/links',[App\Http\Controllers\LinkController::class, 'index'])->name('links');
	Route::get('/link/create',[App\Http\Controllers\LinkController::class, 'show'])->name('link.create');
	Route::post('/link/add',[App\Http\Controllers\LinkController::class, 'save'])->name('link.add');
	Route::delete('/link/delete/{id}',[App\Http\Controllers\LinkController::class, 'delete'])->name('link.delete');
	Route::get('/link/edit/{id}',[App\Http\Controllers\LinkController::class, 'edit'])->name('link.edit');
	Route::post('/link/update',[App\Http\Controllers\LinkController::class, 'update'])->name('link.update');
	Route::get('/link/statistics/{id}',[App\Http\Controllers\LinkController::class, 'statistics'])->name('link.statistics');
	Route::post('/link/statistics/{id}',[App\Http\Controllers\LinkController::class, 'dateFilter'])->name('link.dateFilter');

	Route::get('/domains',[App\Http\Controllers\DomainController::class, 'index'])->name('domains');
	Route::get('/domains/create',[App\Http\Controllers\DomainController::class, 'show'])->name('domains.create');
	Route::post('/domains/add',[App\Http\Controllers\DomainController::class, 'save'])->name('domains.add');
	Route::get('/domains/edit/{id}',[App\Http\Controllers\DomainController::class, 'edit'])->name('domains.edit');
	Route::post('/domains/update',[App\Http\Controllers\DomainController::class, 'update'])->name('domains.update');
	Route::delete('/domains/delete/{id}',[App\Http\Controllers\DomainController::class, 'delete'])->name('domains.delete');

	Route::get('/settings',[App\Http\Controllers\SettingController::class, 'index'])->name('settings');
	Route::post('/setting/update',[App\Http\Controllers\SettingController::class, 'update'])->name('setting.update');

	Route::get('/campaigns-email-preview/{id}',[App\Http\Controllers\CampaignController::class, 'mailPreview'])->name('campaigns-email-preview');
	Route::post('/campaigns-email-preview/theme/add',[App\Http\Controllers\CampaignController::class, 'addPreviewTheme'])->name('campaigns-email-preview.theme.add');

	Route::get('/campaigns',[App\Http\Controllers\CampaignController::class, 'index'])->name('campaigns');
	Route::get('/campaigns/create',[App\Http\Controllers\CampaignController::class, 'show'])->name('campaigns.create');
	Route::post('/campaigns/add',[App\Http\Controllers\CampaignController::class, 'save'])->name('campaigns.add');
	Route::delete('/campaigns/delete/{id}',[App\Http\Controllers\CampaignController::class, 'delete'])->name('campaigns.delete');
	Route::get('/campaigns/edit/{id}',[App\Http\Controllers\CampaignController::class, 'edit'])->name('campaigns.edit');
	Route::post('/campaigns/update',[App\Http\Controllers\CampaignController::class, 'update'])->name('campaigns.update');
	Route::post('/campaigns/delete/track',[App\Http\Controllers\CampaignController::class, 'deleteTrack'])->name('campaigns.delete.track');
	
	// Route::get('/campaigns/review/{id}',[App\Http\Controllers\ReviewController::class, 'index'])->name('campaigns.review');
	// Route::get('/campaigns/review/{id}',[App\Http\Controllers\CampaignController::class, 'review'])->name('campaigns.review');
	// Route::post('/campaigns/track/feedback',[App\Http\Controllers\CampaignController::class, 'feedback'])->name('campaigns.track.feedback');
	// Route::get('/campaigns/review/zip/{id}',[App\Http\Controllers\CampaignController::class, 'zipConvert'])->name('campaigns.review.zip');
	// Route::get('/campaigns/review/download-audio/{id}/{audioExtension}',[App\Http\Controllers\CampaignController::class, 'downloadAudioFile'])->name('campaigns.review.download-audio');


	Route::get('/campaigns/statistics/{id}',[App\Http\Controllers\CampaignController::class, 'statistics'])->name('campaigns.statistics');
	Route::post('/campaigns/statistics/{id}',[App\Http\Controllers\CampaignController::class, 'dateFilter'])->name('campaigns.dateFilter');

	Route::get('/campaigns/send-test-email/{id}',[App\Http\Controllers\CampaignController::class, 'sendTestEmail'])->name('campaigns.send-test-email');

	Route::post('/campaigns/status',[App\Http\Controllers\CampaignStatusController::class, 'status'])->name('campaigns.status');

	Route::get('/emails',[App\Http\Controllers\EmailGroupController::class, 'index'])->name('emails');
	Route::get('/email/create',[App\Http\Controllers\EmailGroupController::class, 'show'])->name('email.create');
	Route::post('/email/add',[App\Http\Controllers\EmailGroupController::class, 'save'])->name('email.add');
	Route::delete('/email/delete/{id}',[App\Http\Controllers\EmailGroupController::class, 'delete'])->name('email.delete');
	Route::get('/email/edit/{id}',[App\Http\Controllers\EmailGroupController::class, 'edit'])->name('email.edit');
	Route::get('/email/status/{id}/{status}',[App\Http\Controllers\EmailGroupController::class, 'status'])->name('email.status');
	
	Route::post('/email/import',[App\Http\Controllers\EmailGroupController::class, 'csvUpload'])->name('email.import');

	Route::get('/profile',[App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
	Route::post('/profile/save',[App\Http\Controllers\ProfileController::class, 'save'])->name('profile.save');

	// Plan
	Route::get('/plans',[App\Http\Controllers\PlanController::class, 'index'])->name('plans');
	Route::delete('/plan/delete/{id}',[App\Http\Controllers\PlanController::class, 'delete'])->name('plan.delete');
	Route::get('/plan/edit/{id}',[App\Http\Controllers\PlanController::class, 'edit'])->name('plan.edit');
	Route::get('/plan/create',[App\Http\Controllers\PlanController::class, 'show'])->name('plan.create');
	Route::post('/plan/save',[App\Http\Controllers\PlanController::class, 'store'])->name('plan.save');

	// Coupons
	Route::get('/coupons',[App\Http\Controllers\CouponController::class, 'index'])->name('coupons');
	Route::get('/coupon/create',[App\Http\Controllers\CouponController::class, 'show'])->name('coupon.create');
	Route::post('/coupon/save',[App\Http\Controllers\CouponController::class, 'store'])->name('coupon.save');
	Route::delete('/coupon/delete/{id}',[App\Http\Controllers\CouponController::class, 'delete'])->name('coupon.delete');
	Route::get('/coupon/edit/{id}',[App\Http\Controllers\CouponController::class, 'edit'])->name('coupon.edit');

	// User Management
	Route::get('/users',[App\Http\Controllers\UserManagementController::class, 'index'])->name('users');
	Route::get('/users/create',[App\Http\Controllers\UserManagementController::class, 'show'])->name('users.create');
	Route::post('/user/save',[App\Http\Controllers\UserManagementController::class, 'store'])->name('user.save');
	Route::get('/user/edit/{id}',[App\Http\Controllers\UserManagementController::class, 'edit'])->name('user.edit');
	Route::post('/user/update',[App\Http\Controllers\UserManagementController::class, 'update'])->name('user.update');
	Route::post('/get/user-details',[App\Http\Controllers\UserManagementController::class, 'getUserDetails'])->name('get.user-details');
	Route::delete('/user/delete/{id}',[App\Http\Controllers\UserManagementController::class, 'delete'])->name('user.delete');

	Route::get('/billing',[App\Http\Controllers\BillingController::class, 'index'])->name('billing');
	Route::post('/verify-coupon',[App\Http\Controllers\BillingController::class, 'verifyCoupon'])->name('verify-coupon');
	Route::post('/paypal-payment',[App\Http\Controllers\BillingController::class, 'getPaymentMethod'])->name('paypal-payment');
	Route::get('paypal',[App\Http\Controllers\BillingController::class, 'getPaymentStatus'])->name('status');

	Route::post('stripe-payment', [App\Http\Controllers\BillingController::class, 'stripePayment'])->name('stripe-payment');

	Route::get('order-history',[App\Http\Controllers\orderHistoryController::class, 'index'])->name('order-history');

	Route::get('google-payment', [App\Http\Controllers\BillingController::class, 'googlePayment'])->name('google-payment');
	// Release 
	Route::get('releases',[App\Http\Controllers\ReleaseController::class, 'index'])->name('releases');
	Route::get('/release/create',[App\Http\Controllers\ReleaseController::class, 'show'])->name('release.create');
	Route::post('/release/save',[App\Http\Controllers\ReleaseController::class, 'save'])->name('release.save');
	Route::delete('/release/delete/{id}',[App\Http\Controllers\ReleaseController::class, 'delete'])->name('release.delete');
	Route::get('/release/edit/{id}',[App\Http\Controllers\ReleaseController::class, 'edit'])->name('release.edit');

	Route::post('/release/platform/ordering',[App\Http\Controllers\ReleaseController::class, 'levelOrder'])->name('release.platform.ordering');

	Route::post('/release/update',[App\Http\Controllers\ReleaseController::class, 'update'])->name('release.update');
	Route::get('/release/statistics/{id}/{label}',[App\Http\Controllers\ReleaseController::class, 'statistics'])->name('release.statistics');
	
	Route::get('/release/statistics/{id}',[App\Http\Controllers\ReleaseController::class, 'statistics'])->name('release.statistics');
	Route::post('/release/statistics/{id}',[App\Http\Controllers\ReleaseController::class, 'dateFilter'])->name('release.dateFilter');
	Route::post('/release/delete/platform',[App\Http\Controllers\ReleaseController::class, 'deletePlatform'])->name('release.delete.platform');

	Route::get('stores',[App\Http\Controllers\StoreController::class, 'index'])->name('stores');
	Route::get('/store/create',[App\Http\Controllers\StoreController::class, 'show'])->name('store.create');
	Route::post('/store/save',[App\Http\Controllers\StoreController::class, 'save'])->name('store.save');
	Route::delete('/store/delete/{id}',[App\Http\Controllers\StoreController::class, 'delete'])->name('store.delete');
	Route::get('/store/edit/{id}',[App\Http\Controllers\StoreController::class, 'edit'])->name('store.edit');
	Route::post('/store/update',[App\Http\Controllers\StoreController::class, 'update'])->name('store.update');

	/**
	 * Single Page Route
	*/
	Route::post('label-setting-store',[App\Http\Controllers\LabelSettingController::class, 'store'])->name('label-setting-store');
	Route::post('label-setting-delete',[App\Http\Controllers\LabelSettingController::class, 'delete'])->name('label-setting-delete');
	Route::post('smtp-setting-save',[App\Http\Controllers\SMTPController::class,'store'])->name('smtp-setting-save');
	Route::get('general-settings',[App\Http\Controllers\SinglePageController::class, 'index'])->name('general-settings');

});
// Review Route Define Below
Route::get('/campaigns/review/{id}/{pass_key?}',[App\Http\Controllers\ReviewController::class, 'index'])->name('campaigns.review');
Route::get('/unsubscription/{pass_key}',[App\Http\Controllers\ReviewController::class, 'unsubscription'])->name('unsubscription');

Route::post('/campaigns/track/feedback',[App\Http\Controllers\ReviewController::class, 'feedback'])->name('campaigns.track.feedback');
Route::get('/campaigns/zip/{id}/{pass_key?}',[App\Http\Controllers\ReviewController::class, 'zip'])->name('campaigns.zip');
Route::get('/campaigns/review/download-audio/{id}/{audioExtension}/{pass_key?}',[App\Http\Controllers\ReviewController::class, 'downloadAudioFile'])->name('campaigns.review.download-audio');

Route::post('/campaigns/feedback/report/pdf',[App\Http\Controllers\CampaignFeedbackReportController::class, 'feedbackReport'])->name('campaigns.feedback.report.pdf');


Route::get('/{slug}',[App\Http\Controllers\ClickController::class, 'redirectSlug'])->name('link');
Route::post('/{slug}',[App\Http\Controllers\ClickController::class, 'redirectSlug'])->name('link-post');
Route::get('/release/landing-page/{key}',[App\Http\Controllers\LandingPageClick::class, 'LandingPage'])->name('release.landing-page');
Route::get('landing-page/{id}/{platform_id}/{musicPlatForm}/{slug}',[App\Http\Controllers\LandingPageClick::class, 'click'])->name('landing-page');


