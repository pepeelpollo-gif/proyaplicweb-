<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\empleadoscontroller;
use App\Http\Controllers\examenjaimecontroller;
use App\Http\Controllers\accesocontroller;
use App\Http\Controllers\jquerycontroller;
use App\Http\Controllers\actividadescontroller;
use App\Http\Controllers\citascontroller;

// Rutas de Citas
Route::get('altacita',          [citascontroller::class, 'altacita'])->name('altacita');
Route::get('cargacarritocitas', [citascontroller::class, 'cargacarrito'])->name('cargacarritocitas');
Route::get('reportecitas',      [citascontroller::class, 'reporte'])->name('reportecitas');
Route::get('eliminadetalle',    [citascontroller::class, 'eliminadetalle'])->name('eliminadetalle');
Route::get('modificacita',      [citascontroller::class, 'modificacita'])->name('modificacita');
Route::post('guardamodifica',   [citascontroller::class, 'guardamodifica'])->name('guardamodifica');
Route::get('/getlistaclientes', [citascontroller::class, 'getListaClientes'])->name('getlistaclientes');


// --- Rutas de Empleados ---
Route::get('inicio',[empleadoscontroller::class,'inicio'])->name('inicio');
Route::get('reporteempleados',[empleadoscontroller::class,'reporteempleados'])->name('reporteempleados');
Route::get('altaempleado',[empleadoscontroller::class,'altaempleado'])->name('altaempleado');
Route::post('guardaempleado',[empleadoscontroller::class,'guardaempleado'])->name('guardaempleado');
Route::get('desactivaempleado',[empleadoscontroller::class,'desactivaempleado'])->name('desactivaempleado');
Route::get('activaempleado',[empleadoscontroller::class,'activaempleado'])->name('activaempleado');
Route::get('eliminaempleado',[empleadoscontroller::class,'eliminaempleado'])->name('eliminaempleado');
Route::get('editaempleado',[empleadoscontroller::class,'editaempleado'])->name('editaempleado');
Route::post('actualizaemp',[empleadoscontroller::class,'actualizaemp'])->name('actualizaemp');



// 1. Ruta para ver el reporte (apunta a 'index')
Route::get(uri: 'reportejaime', action: [examenjaimecontroller::class, 'index'])->name('reporte.jaime');

Route::get(uri: 'altaactividad', action: [actividadescontroller::class, 'altaactividad'])->name('altaactividad');
Route::get(uri: 'datosemp', action: [actividadescontroller::class, 'datosemp'])->name('datosemp');
Route::get(uri: 'datosacti', action: [actividadescontroller::class, 'datosacti'])->name('datosacti');
Route::get(uri: 'infotar', action: [actividadescontroller::class, 'infotar'])->name('infotar');
Route::get('cargacarrito',[actividadescontroller::class,'cargacarrito'])->name('cargacarrito');
Route::get(uri: 'datoscorrectivo', action: [actividadescontroller::class, 'datoscorrectivo'])->name('datoscorrectivo');


Route::get(uri: 'p1', action: [jquerycontroller::class, 'p1'])->name('p1');
Route::get(uri: 'calculo', action: [jquerycontroller::class, 'calculo'])->name('calculo');
Route::get(uri: 'p2', action: [jquerycontroller::class, 'p2'])->name('p2');
Route::get(uri: 'datos', action: [jquerycontroller::class, 'datos'])->name('datos');


//2. eliminar (apunta a 'destroy')
Route::delete('/animal/{id}', [examenjaimecontroller::class, 'destroy'])->name('animal.destroy');

//01/12/25 
Route::get('editajaime/{id}', [App\Http\Controllers\examenjaimecontroller::class, 'edit'])->name('edita.jaime');//4. Ruta guarda animal


//rutas login
Route::get('login', action: [accesocontroller::class, 'login'])->name('login');
Route::post('validar', action: [accesocontroller::class, 'validar'])->name('validar');
Route::get('cerrarsesion', action: [accesocontroller::class, 'cerrarsesion'])->name('cerrarsesion');


