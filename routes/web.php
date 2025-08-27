<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;


Route::get('/', function (Request $request) {
    $user = $request->user();
    if (auth()->check() && $user->hasRole('admin')) {
        return redirect('/admin');
    }
    return redirect('/admin/orders');
});


Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'it'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('language.switch');


