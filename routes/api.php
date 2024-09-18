<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'ApiController@login');
Route::post('register', 'ApiController@register');
Route::post('loginApp/', 'ApiController@loginApp');

Route::get('roles/', 'ApiController@listarRoles');

Route::get('users/', 'ApiController@listarUsers');
Route::get('users/{id}', 'ApiController@buscarUser');
Route::post('users/', 'ApiController@grabarUser');
Route::put('users/{id}', 'ApiController@actualizarUser');
Route::post('users/{id}', 'ApiController@eliminarUser');
//Route::get('users/jrs/{jrs_id}', 'ApiController@listarUsersXJrs');

Route::get('tactividades/', 'ApiVySController@listarTActividades');
Route::post('tactividades/', 'ApiVySController@grabarTActividades');
Route::put('tactividades/{tact_id}', 'ApiVySController@actualizarTActividades');
Route::post('tactividades/{tact_id}', 'ApiVySController@eliminarTActividades');

Route::get('tformularios/', 'ApiVySController@listarTFormularios');
Route::post('tformularios/', 'ApiVySController@grabarTFormularios');
Route::put('tformularios/{tfrm_id}', 'ApiVySController@actualizarTFormularios');
Route::post('tformularios/{tfrm_id}', 'ApiVySController@eliminarTFormularios');

Route::get('formularios/{act_id}', 'ApiVySController@listarFormularios');
Route::post('formularios/', 'ApiVySController@grabarFormularios');
Route::put('formularios/{frm_id}', 'ApiVySController@actualizarFormularios');
Route::put('formularios_campos/{frm_id}', 'ApiVySController@actualizarFormulariosCampos');
Route::post('formularios/{frm_id}', 'ApiVySController@eliminarFormularios');

Route::get('nodos/', 'ApiVySController@listarNodos');
Route::post('nodos/', 'ApiVySController@grabarNodos');
Route::put('nodos/{nodo_id}', 'ApiVySController@actualizarNodos');
Route::post('nodos/{nodo_id}', 'ApiVySController@eliminarNodos');

Route::get('nodosProcesos/', 'ApiVySController@listarNodosProcesos');
Route::post('nodosProcesos/', 'ApiVySController@grabarNodosProcesos');
Route::put('nodosProcesos/{nopr_id}', 'ApiVySController@actualizarNodosProcesos');
Route::post('nodosProcesos/{nopr_id}', 'ApiVySController@eliminarNodosProcesos');

Route::get('catalogos/', 'ApiVySController@listarCatalogos');
Route::post('catalogos/', 'ApiVySController@grabarCatalogos');
Route::put('catalogos/{cat_id}', 'ApiVySController@actualizarCatalogos');
Route::post('catalogos/{cat_id}', 'ApiVySController@eliminarCatalogos');

Route::get('procesos/todos', 'ApiVySController@listarTodosProcesos');
Route::get('procesos/{cat_id}', 'ApiVySController@listarProcesos');
Route::post('procesos/', 'ApiVySController@grabarProcesos');
Route::put('procesos/{prc_id}', 'ApiVySController@actualizarProcesos');
Route::post('procesos/{prc_id}', 'ApiVySController@eliminarProcesos');
Route::get('procesoXPrcId/{prc_id}', 'ApiVySController@listarProcesoXPrcId');
Route::get('procesosXNodoId/{nodo_id}', 'ApiVySController@listarProcesosXNodoId');

Route::get('procesosTodos', 'ApiVySController@listarProcesosTodos');
Route::post('procesosXUsrId', 'ApiVySController@listarProcesosXUsrId');

Route::get('actividades/{prc_id}', 'ApiVySController@listarActividades');
Route::post('actividades/', 'ApiVySController@grabarActividades');
Route::put('actividades/{act_id}', 'ApiVySController@actualizarActividades');
Route::post('actividades/{act_id}', 'ApiVySController@eliminarActividades');

Route::get('actividad/{prc_id}/{act_orden}', 'ApiVySController@listarActividadXPrcIdXOrden');

Route::get('casos/{usr_id}', 'ApiVySController@listarCasos');
Route::get('casosArchivados/{usr_id}', 'ApiVySController@listarCasosArchivados');
Route::get('caso/{cas_id}', 'ApiVySController@listarCasoXId');
Route::put('casos/{cas_id}', 'ApiVySController@actualizarCasos');
Route::put('casosArchivar/{cas_id}', 'ApiVySController@archivarCasos');
Route::put('casosDerivar/{cas_id}', 'ApiVySController@derivarCasos');
Route::post('casos/', 'ApiVySController@grabarCasos');
Route::get('casosHistorico/{cas_id}', 'ApiVySController@historicoCasos');

Route::post('buscarCasos', 'ApiVySController@buscarCasos');
Route::get('buscarCasosTodos', 'ApiVySController@buscarCasosTodos');
Route::put('estadoCaso/{cas_id}', 'ApiVySController@actualizarEstadoCaso');
Route::post('subirAdjunto', 'ApiVySController@subirAdjunto');

Route::get('dashboard', 'ApiVySController@dashboardCasos');

// Impresiones
Route::get('impresiones/{act_id}', 'ApiVySController@listarImpresiones');
Route::delete('impresiones/{imp_id}', 'ApiVySController@eliminarImpresion');
Route::post('impresiones/', 'ApiVySController@grabarImpresion');
Route::put('impresiones/{imp_id}', 'ApiVySController@actualizarImpresion');

// Usuarios nodos
Route::get('usrnodos', 'ApiVySController@listarUsrNodos');
Route::post('usrnodos', 'ApiVySController@grabarUsrNodos');
Route::delete('usrnodos/{usn_id}', 'ApiVySController@eliminarUsrNodos');
Route::put('usrnodos/{usn_id}', 'ApiVySController@actualizarUsrNodos');

Route::get('usrnodosXId/{id}', 'ApiVySController@listarUsrNodosXId');
// para Round-robin
Route::get('usrNodosXNodoId/{nodo_id}', 'ApiVySController@listarUsrNodosXNodoId');
Route::get('usrNodosRR/{nodo_id}', 'ApiVySController@listarUltUsrNodo');
Route::put('usrNodosRR', 'ApiVySController@modificarUltUsrNodo');

Route::get('prc_exportar/{prc_id}', 'ApiVySController@exportarProceso');
Route::post('prc_importar/{cat_id}', 'ApiVySController@importarProceso');

// Correspondencia
Route::get('tiposCorrespondencia', 'ApiCrrController@listarTiposCorrespondencia');

Route::get('correspondencia/{usr_id}', 'ApiCrrController@listarCorrespondencia');
Route::get('correspondenciaXId/{crr_id}', 'ApiCrrController@listarCorrespondenciaXId');
Route::post('correspondencia/', 'ApiCrrController@grabarCorrespondencia');
Route::put('estadoCorrespondencia/{crr_id}', 'ApiCrrController@actualizarEstadoCorrespondencia');

Route::get('copiasXCrrId/{crr_id}', 'ApiCrrController@listarCopiasXCrrId');

Route::get('actuaciones/{crr_id}', 'ApiCrrController@listarActuacionesXCRR');

Route::get('actuaciones', 'ApiCrrController@listarActuaciones');
Route::get('actuaciones', 'ApiCrrController@grabarActuaciones');
Route::put('actuaciones/{act_id}', 'ApiCrrController@actualizarActuaciones');
Route::post('actuaciones/{act_id}', 'ApiCrrController@eliminarActuaciones');

Route::post('crrSetHistorico/{crr_id}', 'ApiCrrController@setHistorico');
Route::post('crrDerivar/{crr_id}/{nodo_id}', 'ApiCrrController@derivar');
Route::post('crrSetCopia/{crr_id}/{nodo_id}', 'ApiCrrController@setCopia');
Route::post('crrDelCopia/{cp_id}', 'ApiCrrController@delCopia');

Route::get('subtiposDoc/{doc_id}', 'ApiCrrController@listarSubtiposDocXDocID');


//////Ws
Route::get('tipows/', 'ApiWsController@listarTipoWs');
Route::post('tipows/', 'ApiWsController@grabarTipoWs');
Route::put('tipows/{tws_id}', 'ApiWsController@actualizarTipoWs');
Route::post('tipows/{tws_id}', 'ApiWsController@eliminarTipoWs');

Route::get('ws/', 'ApiWsController@listarWs');
Route::post('ws/', 'ApiWsController@grabarWs');
Route::put('ws/{ws_id}', 'ApiWsController@actualizarWs');
Route::post('ws/{ws_id}', 'ApiWsController@eliminarWs');


//////Archivos
Route::get('tiposArchivo/', 'ApiArchController@listarTiposArchivo');
Route::post('tiposArchivo/', 'ApiArchController@grabarTiposArchivo');
Route::put('tiposArchivo/{tarch_id}', 'ApiArchController@actualizarTiposArchivo');
Route::post('tiposArchivo/{tarch_id}', 'ApiArchController@eliminarTiposArchivo');

Route::get('archivos/', 'ApiArchController@listarArchivos');
Route::post('archivos/', 'ApiArchController@grabarArchivos');
Route::put('archivos/{arch_id}', 'ApiArchController@actualizarArchivos');
Route::post('archivos/{arch_id}', 'ApiArchController@eliminarArchivos');

Route::get('tiposDoc/', 'ApiArchController@listarTiposDoc');
Route::post('tiposDoc/', 'ApiArchController@grabarTiposDoc');
Route::put('tiposDoc/{tdoc_id}', 'ApiArchController@actualizarTiposDoc');
Route::post('tiposDoc/{tdoc_id}', 'ApiArchController@eliminarTiposDoc');

Route::get('subtiposDoc/', 'ApiArchController@listarSubtiposDoc');
Route::post('subtiposDoc/', 'ApiArchController@grabarSubtiposDoc');
Route::put('subtiposDoc/{stdoc_id}', 'ApiArchController@actualizarSubtiposDoc');
Route::post('subtiposDoc/{stdoc_id}', 'ApiArchController@eliminarSubtiposDoc');

// casos externos
Route::post('casoexterno', 'ApiVySIntegracionController@recibirCaso');

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::post('logout', 'ApiController@logout');
    Route::post('listUsers', 'ApiController@listUsers');
});