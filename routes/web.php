<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Expense\{ExpenseCreate, ExpenseEdit, ExpenseList};
use App\Http\Livewire\Plan\{PlanList, PlanCreate};
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware([
    'auth:sanctum',
    'verified'
])->group(function () {
    Route::prefix('expenses')->name('expenses.')->group(function(){

        Route::get('/', ExpenseList::class)->name('index');
        Route::get('/create', ExpenseCreate::class)->name('create');
        Route::get('/edit/{expense}', ExpenseEdit::class)->name('edit');

        Route::get('/{expense}/photo', function($expense){

            //pegamos o usuario logado e buscamos os expenses relacionados a ele passando por parametro pro findOrFail o que veio da rota /{expense}/photo
            $expense = auth()->user()->expenses()->findOrFail($expense);

            //se a imagem nao existir iremos retornar uma mensagem de not found
            if( !Storage::disk('public')->exists($expense->photo) )
                return abort(404, 'Image not found!');
            //se a imagem existir iremos retorna-la
            $image = Storage::disk('public')->get($expense->photo);

            //pegar o formato correto da imagem
            $mimeType = File::mimeType(storage_path('app/public/' . $expense->photo));

            //retornar ela como imagem
            return response($image)->header('Content-Type', $mimeType); //header para força a renderização como imagem e nao como html


        })->name('photo');
    });

    Route::prefix('plans')->name('plans.')->group(function(){

        Route::get('/', PlanList::class)->name('index'); //apelido fica plans.index
        Route::get('/create', PlanCreate::class)->name('create');

    });

});
