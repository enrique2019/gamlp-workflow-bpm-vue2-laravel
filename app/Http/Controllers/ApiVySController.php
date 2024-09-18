<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegistrationFormRequest;

class ApiVySController extends Controller
{
    public function listarNodosProcesos()
    {
        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_nodos_procesos np
                                    inner join rmx_vys_nodos n on n.nodo_id = np.nopr_nodo_id
                                    inner join rmx_vys_procesos p on p.prc_id = np.nopr_prc_id
                                    where np.nopr_estado <> 'X'
                                      and n.nodo_estado <> 'X'
                                      and p.prc_estado <> 'X'
                                    order by nopr_registrado");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarNodosProcesos(Request $request)
    {
        $nopr_nodo_id = $request["nopr_nodo_id"];
        $nopr_prc_id = $request["nopr_prc_id"];
        $nopr_usr_id = $request["nopr_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("insert into rmx_vys_nodos_procesos (nopr_nodo_id, nopr_prc_id, nopr_usr_id) values
                                    ('$nopr_nodo_id', '$nopr_prc_id', '$nopr_usr_id') ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarNodosProcesos(Request $request)
    {
        $nopr_id = $request["nopr_id"];
        $nopr_nodo_id = $request["nopr_nodo_id"];
        $nopr_prc_id = $request["nopr_prc_id"];
        $nopr_usr_id = $request["nopr_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_nodos_procesos 
                            set     nopr_nodo_id = '$nopr_nodo_id', 
                                    nopr_prc_id = '$nopr_prc_id',
                                    nopr_usr_id = $nopr_usr_id
                                where nopr_id = $nopr_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarNodosProcesos(Request $request)
    {
        $nopr_id = $request["nopr_id"];
        $nopr_usr_id = $request["nopr_usr_id"];
        $nopr_estado = 'X';

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_nodos_procesos 
                                    set nopr_estado = '$nopr_estado',
                                    nopr_usr_id = '$nopr_usr_id'
                                where nopr_id = $nopr_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarNodos()
    {
        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_nodos
                                    where nodo_estado <> 'X' 
                                    order by nodo_codigo");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarNodos(Request $request)
    {
        $nodo_codigo = $request["nodo_codigo"];
        $nodo_descripcion = $request["nodo_descripcion"];
        $nodo_padre_id = $request["nodo_padre_id"];
        $nodo_usr_id = $request["nodo_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select * from sp_insertar_nodos(?, ?, ?, ?)",
                                   array ($nodo_codigo, $nodo_descripcion, $nodo_padre_id, 1) );
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarNodos(Request $request)
    {
        $nodo_id = $request["nodo_id"];
        $nodo_codigo = $request["nodo_codigo"];
        $nodo_descripcion = $request["nodo_descripcion"];
        $nodo_padre_id = $request["nodo_padre_id"];
        $nodo_usr_id = $request["nodo_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_nodos 
                            set     nodo_codigo = '$nodo_codigo', 
                                    nodo_descripcion = '$nodo_descripcion',
                                    nodo_padre_id = '$nodo_padre_id',
                                    nodo_usr_id = $nodo_usr_id
                                where nodo_id = $nodo_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarNodos(Request $request)
    {
        $nodo_id = $request["nodo_id"];
        $nodo_estado = 'X';

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_nodos 
                                    set nodo_estado = '$nodo_estado'
                                where nodo_id = $nodo_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarTFormularios()
    {
        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_tipos_formulario
                                    where tfrm_estado = 'A' ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarTFormularios(Request $request)
    {
        $tfrm_descripcion = $request["tfrm_descripcion"];
        $tfrm_usr_id = $request["tfrm_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("insert into rmx_vys_tipos_formulario (tfrm_descripcion, tfrm_usr_id) values
                                    ('$tfrm_descripcion', 1) ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarTFormularios(Request $request)
    {
        $tfrm_id = $request["tfrm_id"];
        $tfrm_descripcion = $request["tfrm_descripcion"];
        $tfrm_usr_id = $request["tfrm_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_tipos_formulario 
                            set     tfrm_descripcion = '$tfrm_descripcion',
                                    tfrm_usr_id = $tfrm_usr_id
                                where tfrm_id = $tfrm_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarTFormularios(Request $request)
    {
        $tfrm_id = $request["tfrm_id"];
        $tfrm_estado = 'X';

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_tipos_formulario 
                                    set tfrm_estado = '$tfrm_estado'
                                where tfrm_id = $tfrm_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarTActividades()
    {
        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_tipos_actividad
                                    where tact_estado = 'A' ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarTActividades(Request $request)
    {
        $tact_codigo = $request["tact_codigo"];
        $tact_descripcion = $request["tact_descripcion"];
        $tact_icono = $request["tact_icono"];
        $tact_usr_id = $request["tact_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("insert into rmx_vys_tipos_actividad (tact_codigo, tact_descripcion, tact_icono, tact_usr_id) values
                                    ('$tact_codigo', '$tact_descripcion', '$tact_icono', 1) ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarTActividades(Request $request)
    {
        $tact_id = $request["tact_id"];
        $tact_codigo = $request["tact_codigo"];
        $tact_descripcion = $request["tact_descripcion"];
        $tact_icono = $request["tact_icono"];
        $tact_usr_id = $request["tact_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_tipos_actividad 
                            set     tact_codigo = '$tact_codigo', 
                                    tact_descripcion = '$tact_descripcion',
                                    tact_icono = '$tact_icono',
                                    tact_usr_id = $tact_usr_id
                                where tact_id = $tact_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarTActividades(Request $request)
    {
        $tact_id = $request["tact_id"];
        $tact_estado = 'X';

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_tipos_actividad 
                                    set tact_estado = '$tact_estado'
                                where tact_id = $tact_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarCatalogos()
    {
        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_catalogos
                                    where cat_estado = 'A'
                                    order by cat_codigo ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarCatalogos(Request $request)
    {
        $cat_codigo = $request["cat_codigo"];
        $cat_descripcion = $request["cat_descripcion"];
        $cat_padre_id = $request["cat_padre_id"];
        $cat_usr_id = $request["cat_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("insert into rmx_vys_catalogos (cat_codigo, cat_descripcion, cat_padre_id, cat_usr_id) values
                                    ('$cat_codigo', '$cat_descripcion', '$cat_padre_id', 1) ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarCatalogos(Request $request)
    {
        $cat_id = $request["cat_id"];
        $cat_codigo = $request["cat_codigo"];
        $cat_descripcion = $request["cat_descripcion"];
        $cat_padre_id = $request["cat_padre_id"];
        $cat_usr_id = $request["cat_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_catalogos 
                            set     cat_codigo = '$cat_codigo', 
                                    cat_descripcion = '$cat_descripcion',
                                    cat_padre_id = '$cat_padre_id',
                                    cat_usr_id = $cat_usr_id
                                where cat_id = $cat_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarCatalogos(Request $request)
    {
        $cat_id = $request["cat_id"];
        $cat_estado = 'X';

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_catalogos 
                                    set cat_estado = '$cat_estado'
                                where cat_id = $cat_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarProcesos(Request $request)
    {
        $cat_id = $request["cat_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_procesos 
                                    inner join rmx_vys_catalogos on cat_id = prc_cat_id
                                    where prc_estado <> 'X' 
                                        and prc_cat_id = $cat_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarProcesosTodos(Request $request)
    {
        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_procesos 
                                    inner join rmx_vys_catalogos on cat_id = prc_cat_id
                                    where prc_estado <> 'X' 
                                    order by cat_codigo, prc_data->>'prc_codigo' ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarProcesosXUsrId(Request $request)
    {
        $usr_id = $request["usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
            from rmx_vys_procesos p
            inner join rmx_vys_catalogos on cat_id = p.prc_cat_id

            inner join rmx_vys_nodos_procesos np on np.nopr_prc_id = p.prc_id
            inner join rmx_usr_nodos usn on usn.usn_nodo_id = np.nopr_nodo_id

            where prc_estado <> 'X'
              and np.nopr_estado <> 'X'
              and usn.usn_estado <> 'X'
              and usn.usn_user_id = $usr_id 
            order by cat_codigo, prc_data->>'prc_codigo' ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarProcesoXPrcId(Request $request)
    {
        $prc_id = $request["prc_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_procesos 
                                    inner join rmx_vys_catalogos on cat_id = prc_cat_id
                                    where prc_estado <> 'X' 
                                    and prc_id = $prc_id 
                                    order by cat_codigo, prc_data->>'prc_codigo' ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarProcesosXNodoId(Request $request)
    {
        $nodo_id = $request["nodo_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select u.name, p.prc_data->>'prc_codigo' as prc_codigo, p.prc_data->>'prc_descripcion' as prc_descripcion, 
                                    a.act_data->>'act_descripcion' as act_descripcion, count(*) as conteo
                                    from rmx_vys_casos c
                                    inner join rmx_vys_nodos n on n.nodo_id = c.cas_nodo_id
                                    inner join rmx_vys_actividades a on a.act_id = c.cas_act_id
                                    inner join rmx_vys_procesos p on p.prc_id = a.act_prc_id
                                    inner join users u on u.id = c.cas_usr_id
                                    where cas_estado <> 'X'
                                    and p.prc_estado <> 'X'
                                    and a.act_estado <> 'X'
                                    and n.nodo_estado <> 'X'
                                    and n.nodo_id = $nodo_id
                                    group by u.name, p.prc_data->>'prc_codigo', p.prc_data->>'prc_descripcion', 
                                    a.act_data->>'act_descripcion' 
                                    order by u.name, p.prc_data->>'prc_codigo', p.prc_data->>'prc_descripcion', 
                                    a.act_data->>'act_descripcion' ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarProcesos(Request $request)
    {
        $prc_cat_id = $request["prc_cat_id"];
        $prc_data = $request["prc_data"];
        $prc_data_campos_clave = $request["prc_data_campos_clave"];
        $prc_modelo = $request["prc_modelo"];
        $prc_usr_id = $request["prc_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $sql = "insert into rmx_vys_procesos (prc_cat_id, prc_data, prc_data_campos_clave, prc_modelo, prc_usr_id) values
                        ($prc_cat_id, '$prc_data'::json, '$prc_data_campos_clave'::json, '$prc_modelo'::json, $prc_usr_id) ";
            //$prc_data_campos_clave NO usar json_encode($prc_data_campos_clave, 0);
            $data = \DB::select($sql);
            return response()->json(["data" => $data, "success" => $success ]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarProcesos(Request $request)
    {
        $prc_id = $request["prc_id"];
        $prc_cat_id = $request["prc_cat_id"];
        $prc_data = $request["prc_data"];
        $prc_data_campos_clave = $request["prc_data_campos_clave"];
        $prc_modelo = $request["prc_modelo"];
        $prc_usr_id = $request["prc_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            //$prc_data_campos_clave NO usar json_encode($prc_data_campos_clave, 0);
            $sql = "update rmx_vys_procesos 
                    set prc_cat_id = $prc_cat_id, 
                        prc_data = '$prc_data'::json,
                        prc_data_campos_clave = '$prc_data_campos_clave'::json,
                        prc_modelo = '$prc_modelo'::json,
                        prc_usr_id = $prc_usr_id
                    where prc_id = $prc_id ";
            $data = \DB::select($sql);
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarProcesos(Request $request)
    {
        $prc_id = $request["prc_id"];
        $prc_usr_id = $request["prc_usr_id"];
        $prc_estado = 'X';

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_procesos 
                                    set prc_estado = '$prc_estado',
                                    prc_usr_id = $prc_usr_id
                                where prc_id = $prc_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarActividades(Request $request)
    {
        $prc_id = $request["prc_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_actividades
                                    inner join rmx_vys_procesos on prc_id = act_prc_id
                                    inner join rmx_vys_tipos_actividad on tact_id = act_tact_id
                                    inner join rmx_vys_nodos on nodo_id = act_nodo_id
                                    where act_estado = 'A' 
                                      and act_prc_id = $prc_id 
                                    order by (act_data->>'act_orden')::integer");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }


    public function grabarActividades(Request $request)
    {
        $act_prc_id = $request["act_prc_id"];
        $act_tact_id = $request["act_tact_id"];
        $act_nodo_id = $request["act_nodo_id"];
        $act_data = $request["act_data"];
        $act_data_reglas = $request["act_data_reglas"];
        $act_data_form = $request["act_data_form"];
        $act_usr_id = $request["act_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("insert into rmx_vys_actividades (act_prc_id, act_tact_id, act_nodo_id, act_data, act_data_reglas, act_data_form, act_usr_id) values
                                    ($act_prc_id, $act_tact_id, $act_nodo_id, '$act_data'::json, '$act_data_reglas'::json, '$act_data_form'::json, 1) ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarActividades(Request $request)
    {
        $act_id = $request["act_id"];
        $act_prc_id = $request["act_prc_id"];
        $act_tact_id = $request["act_tact_id"];
        $act_nodo_id = $request["act_nodo_id"];
        $act_data = $request["act_data"];
        $act_data_reglas = $request["act_data_reglas"];
        $act_data_form = $request["act_data_form"];
        $act_usr_id = $request["act_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_actividades 
                            set     act_prc_id = $act_prc_id,
                                    act_tact_id = $act_tact_id,
                                    act_nodo_id = $act_nodo_id, 
                                    act_data = '$act_data'::json,
                                    act_data_reglas = '$act_data_reglas'::json,
                                    act_data_form = '$act_data_form'::json,
                                    act_usr_id = $act_usr_id
                                where act_id = $act_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarActividades(Request $request)
    {
        $act_id = $request["act_id"];
        $act_estado = 'X';

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_actividades 
                                    set act_estado = '$act_estado'
                                where act_id = $act_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarActividadXPrcIdXOrden(Request $request)
    {
        $prc_id = $request["prc_id"];
        $act_orden = $request["act_orden"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_actividades
                                    where act_estado = 'A' 
                                      and act_prc_id = $prc_id
                                      and act_data->>'act_orden' = '$act_orden' 
                                    order by act_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

	public function listarCasos(Request $request) {
		$usr_id = $request["usr_id"];

		$success = array('code' => 200, 'mensaje' => 'OK');
		$error   = array('message' => 'error de instancia', 'code' => 500);
		try {
			$data = \DB::select("SELECT *
				FROM rmx_vys_casos c JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
					JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
					JOIN rmx_usr_nodos n ON c.cas_nodo_id = n.usn_nodo_id
				WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H')
                    AND n.usn_estado <> 'X'
					AND n.usn_user_id = $usr_id 
				ORDER BY c.cas_modificado desc");
			return response()->json(['data' => $data, 'success' => $success]);
		} catch (error $e) {
			return response()->json(['error' => $error]);
		}
	}

	public function listarCasosArchivados(Request $request) {
		$usr_id = $request["usr_id"];

		$success = array('code' => 200, 'mensaje' => 'OK');
		$error   = array('message' => 'error de instancia', 'code' => 500);
		try {
			$data = \DB::select("SELECT *
				FROM rmx_vys_casos c JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
					JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
					JOIN rmx_usr_nodos n ON c.cas_nodo_id = n.usn_nodo_id
				WHERE c.cas_estado = 'H'
					AND n.usn_user_id = $usr_id
				ORDER BY c.cas_modificado desc");
			return response()->json(['data' => $data, 'success' => $success]);
		} catch (error $e) {
			return response()->json(['error' => $error]);
		}
	}

    public function historicoCasos(Request $request) {
		$cas_id = $request["cas_id"];
		$success = array('code' => 200, 'mensaje' => 'OK');
		$error   = array('message' => 'error de instancia', 'code' => 500);
		try {
			$data = \DB::select("SELECT *
                    from rmx_vys_historico_casos
                    inner join rmx_vys_actividades on act_id = htc_cas_act_id
                    inner join rmx_vys_nodos on nodo_id = htc_cas_nodo_id
                    inner join users on id = htc_cas_usr_id
                    where htc_cas_id = $cas_id order by htc_id desc");
			return response()->json(['data' => $data, 'success' => $success]);
		} catch (error $e) {
			return response()->json(['error' => $error]);
		}
	}

    public function listarCasoXId(Request $request)
    {
        $cas_id = $request["cas_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_casos c
                                    inner join rmx_vys_actividades a on a.act_id = c.cas_act_id
									inner join rmx_vys_procesos p on p.prc_id = a.act_prc_id
									LEFT join rmx_vys_formularios f on f.frm_act_id = c.cas_act_id
                                    where c.cas_estado <> 'X'
                                      and c.cas_id = $cas_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function buscarCasos(Request $request)
    {
        $cas_nro_caso = $request["cas_nro_caso"];
        $cas_gestion = $request["cas_gestion"];

        $success = array('code' => 200, 'mensaje' => 'OK');
        $error   = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT *
                FROM rmx_vys_casos c 
                    JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                    JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                    JOIN rmx_usr_nodos n ON n.usn_nodo_id = c.cas_nodo_id AND n.usn_estado <> 'X'
                    JOIN rmx_vys_nodos nnn ON nnn.nodo_id = n.usn_nodo_id
                WHERE c.cas_estado <> 'X'
                    AND c.cas_data->>'cas_nro_caso' = '$cas_nro_caso'
                    AND c.cas_data->>'cas_gestion' = '$cas_gestion'
                ORDER BY c.cas_modificado desc 
                LIMIT 20 ");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }
/*
    public function recibirCaso(Request $request)
    {
        $ext_BODY = $request["ext_body"];
        $ext_ACT_ID = $request["ext_act_id"];
        $ext_NODO_ID = $request["ext_nodo_id"];
        $ext_USR_ID = $request["ext_usr_id"];
        $ext_CONV_USR = $request["CONV_USR"];
        $ext_CONV_CODIGO_RECEPCION = $request["CONV_CODIGO_RECEPCION"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $gestion = date("Y");
            $ext_data = '{"cas_gestion": "' . $gestion . '", "cas_nro_caso": "INNOVA", "cas_nombre_caso": "INNOVA", 
                            "cas_ext_usr":"' . $ext_CONV_USR . '", "cas_ext_codigo":"' . $ext_CONV_CODIGO_RECEPCION . '"}';
            $ext_data_valores = '[]';
            $ext_body2 = json_decode($ext_BODY, 0);

            if ($ext_body2->CONV_TPO_TRAM == 1) {
                $_prefijo = "CONV_";
                $_act_id = 222;
                $_nodo_id = 34;
            } else if ($ext_body2->CONV_TPO_TRAM == 2) {
                $_prefijo = "CONVCAR_";
                $_act_id = 241;
                $_nodo_id = 34;
            } else if ($ext_body2->CONV_TPO_TRAM == 3) {
                $_prefijo = "CONVPH_";
                $_act_id = 240;
                $_nodo_id = 34;
            }

            $cas_id = 0; // caso inicializado
            $caso = [];
            $personasss = []; //varias personas
            $data = []; $data2 = [];

            foreach ($ext_body2->CONV_PERSONAS as $p) {
                $_nombre_completo = $p->nombres . " " . $p->primer_apellido . " " . $p->segundo_apellido;
                $_tipo_persona = $p->tipo_persona;

                $persona = []; //una persona
                $persona[] = array("col_campo" => $_prefijo . "CI_N",     "col_value" => $p->ci);
                $persona[] = array("col_campo" => $_prefijo . "EXP",      "col_value" => $p->expedido);
                $persona[] = array("col_campo" => $_prefijo . "NOM",      "col_value" => $p->nombres);
                $persona[] = array("col_campo" => $_prefijo . "PAT",      "col_value" => $p->primer_apellido);
                $persona[] = array("col_campo" => $_prefijo . "MAT",      "col_value" => $p->segundo_apellido);
                $persona[] = array("col_campo" => $_prefijo . "CAS",      "col_value" => $p->apellido_casada);
                $persona[] = array("col_campo" => $_prefijo . "DOM_PROP", "col_value" => $p->domicilio);
                $persona[] = array("col_campo" => $_prefijo . "TEL",      "col_value" => $p->telefono);
                $persona[] = array("col_campo" => $_prefijo . "CEL",      "col_value" => $p->telefono);
                $persona[] = array("col_campo" => $_prefijo . "MAIL",     "col_value" => $p->email);

                // CONV_TIPO_NAT 1=Propietario, 2=Representante,3=Apoderado --> Tercero Responsable
                if ($_tipo_persona == 'Propietario') {
                    $caso[] = array("frm_campo" => $_prefijo . "TIPO_PERS", "frm_value" => "1", "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "TIPO_NAT", "frm_value" => "1", "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "CI_PROPIET_SOL", "frm_value" => $p->ci, "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "NOM_PROPIET_SOL", "frm_value" =>  $_nombre_completo, "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    // solo carga PROPIETARIOS
                    $personasss[] = $persona; // cargar una persona en personas
                }
                if ($_tipo_persona == 'Representante') {
                    $caso[] = array("frm_campo" => $_prefijo . "TIPO_PERS", "frm_value" => "1", "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "TIPO_NAT",  "frm_value" => "2",                  "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "CI_J",      "frm_value" => $p->ci,               "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "EXP_J",     "frm_value" => $p->expedido,         "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "NOM_J",     "frm_value" => $p->nombres,          "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "PAT_J",     "frm_value" => $p->primer_apellido,  "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "MAT_J",     "frm_value" => $p->segundo_apellido, "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "CAS_J",     "frm_value" => $p->apellido_casada,  "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "DOM_J",     "frm_value" => $p->domicilio,        "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "TEL_J",     "frm_value" => $p->telefono,         "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "CEL_J",     "frm_value" => $p->telefono,         "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "MAIL_J",    "frm_value" => $p->email,            "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "NUM_POD_J", "frm_value" => $p->nro_poder,        "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "FEC_POD_J", "frm_value" => $p->fecha_poder,      "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "NOT_J",     "frm_value" => $p->nro_notaria,      "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                }
                if ($_tipo_persona == 'Tercero') {
                    $caso[] = array("frm_campo" => $_prefijo . "TIPO_PERS", "frm_value" => "1", "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "TIPO_NAT", "frm_value" => "3", "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "CI_TERC",      "frm_value" => $p->ci,               "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "EXP_TERC",     "frm_value" => $p->expedido,         "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "NOM_TERC",     "frm_value" => $p->nombres,          "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "PAT_TERC",     "frm_value" => $p->primer_apellido,  "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "MAT_TERC",     "frm_value" => $p->segundo_apellido, "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "CAS_TERC",     "frm_value" => $p->apellido_casada,  "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "DOM_TERC",     "frm_value" => $p->domicilio,        "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "TEL_TERC",     "frm_value" => $p->telefono,         "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "CEL_TERC",     "frm_value" => $p->telefono,         "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "MAIL_TERC",    "frm_value" => $p->email,            "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "NUM_POD_TERC", "frm_value" => $p->nro_poder,        "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "FEC_POD_TERC", "frm_value" => $p->fecha_poder,      "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "NOT_TERC",     "frm_value" => $p->nro_notaria,      "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                }
            }

            foreach ($ext_body2->CONV_PREDIO as $pred) {
                $caso[] = array("frm_campo" => $_prefijo . "ZN",            "frm_value" => $pred->zona, "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "SB_DISTR",      "frm_value" => $pred->subdistrito, "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "DISTR",         "frm_value" => $pred->distrito, "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "MZNO",          "frm_value" => $pred->manzana, "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "TIPO_VIA",      "frm_value" => "N/A", "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "NOM_VIA",       "frm_value" => $pred->calle, "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "NUM_LOTE",      "frm_value" => $pred->lote, "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "NUM_PREDIO",    "frm_value" => $pred->nro_predio, "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "SUB_REG",       "frm_value" => "- falta -", "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "NUM_PARTIDA",   "frm_value" => $pred->matricula, "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "LUGAR_PARTIDA", "frm_value" => "- falta -", "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
            }

            // Registra CASO
            $caso[] = array("frm_campo" => "GRILLA_PROP", "frm_value" => $personasss, "frm_deshabilitado" => "true", "frm_deshabilitadoo" => true);            
            $ext_data_valores = json_encode($caso);

            $data2 = \DB::select("insert into rmx_vys_casos (cas_act_id, cas_nodo_id, cas_data, cas_data_valores, cas_usr_id) values
                                    ($_act_id, $_nodo_id, '$ext_data'::json, '$ext_data_valores'::json, '$ext_USR_ID') 
                                    returning cas_id ");

            // Registra EXTERNO
            $data = \DB::select("insert into rmx_vys_externos (ext_act_id, ext_nodo_id, ext_body, ext_usr_id) values
                                    ($_act_id, $_nodo_id, '$ext_BODY'::json, '$ext_USR_ID') ");

            return response()->json(["data" => $data, "data2" => $data2, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
*/
    public function grabarCasos(Request $request)
    {
        $cas_act_id = $request["cas_act_id"];
        $cas_nodo_id = $request["cas_nodo_id"];
        $cas_data = $request["cas_data"];
        $cas_data_valores = $request["cas_data_valores"];
        $cas_usr_id = $request["cas_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {

            $cas_data = json_encode($cas_data, 0);
            $cas_data_valores = json_encode($cas_data_valores, 0);

            $data = \DB::select("insert into rmx_vys_casos (cas_act_id, cas_nodo_id, cas_data, cas_data_valores, cas_usr_id) values
                                    ($cas_act_id, $cas_nodo_id, '$cas_data'::json, '$cas_data_valores'::json, '$cas_usr_id') ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarEstadoCaso(Request $request)
    {
        $cas_id = $request["cas_id"];
        $cas_estado = $request["cas_estado"];
        $cas_usr_id = $request["cas_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_casos 
                                set cas_estado = '$cas_estado',
                                    cas_usr_id = $cas_usr_id
                                where cas_id = $cas_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function dashboardCasos(Request $request)
    {
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error   = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT c.cas_data->>'cas_gestion' as \"Gestión\", 
					EXTRACT(MONTH FROM c.cas_registrado)::integer as \"Mes\",
					c.cas_data->>'cas_nro_caso' as \"No. Caso\", 
					p.prc_data->>'prc_descripcion' as \"Proceso\",
					a.act_data->>'act_descripcion' as \"Actividad\",
					no.nodo_descripcion as \"Nodo\",
					u.name as \"Funcionario\",
                    CASE
                    WHEN c.cas_estado = 'T' THEN 'Tomado'
                    WHEN c.cas_estado = 'A' THEN 'Libre'
                    END as \"Estado\"
				FROM rmx_vys_casos c 
					JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
					JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
					JOIN rmx_usr_nodos n ON c.cas_nodo_id = n.usn_nodo_id and c.cas_usr_id = n.usn_user_id
                    JOIN rmx_vys_nodos no ON no.nodo_id = a.act_nodo_id
                    JOIN users u ON u.id = c.cas_usr_id
				WHERE c.cas_estado <> 'X'
				ORDER BY c.cas_id desc ");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function listarFormularios(Request $request)
    {
        $act_id = $request["act_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_formularios 
                                    inner join rmx_vys_actividades on act_id = frm_act_id
                                    where frm_estado = 'A' 
                                        and frm_act_id = $act_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarFormularios(Request $request)
    {
        $frm_id = $request["frm_id"];
        $frm_estado = 'X';

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_formularios 
                                    set frm_estado = '$frm_estado'
                                where frm_id = $frm_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

	public function grabarFormularios(Request $request) {
		$frm_act_id = $request['frm_act_id'];
		$frm_data = $request['frm_data'];
		$frm_data_campos = $request['frm_data_campos'];
		$frm_usr_id = $request['frm_usr_id'];
		$frm_funciones = $request['frm_funciones'];

		$success = array('code' => 200, 'mensaje' => 'OK');
		$error   = array('message' => 'error de instancia', 'code' => 500);
		try {
			\DB::table('rmx_vys_formularios')->insert([
				'frm_act_id' => $frm_act_id,
				'frm_data' => $frm_data,
				'frm_data_campos' => $frm_data_campos,
				'frm_funciones' => $frm_funciones,
				'frm_usr_id' => $frm_usr_id,
			]);
			return response()->json(['data' => true, 'success' => $success]);
		} catch (error $e) {
			return response()->json(['error' => $error]);
		}
	}

	public function actualizarFormularios(Request $request) {
		$frm_id = $request['frm_id'];
		$frm_act_id = $request['frm_act_id'];
		$frm_data = json_encode($request['frm_data']);
		$frm_data_campos = $request['frm_data_campos'];
		$frm_funciones = $request['frm_funciones'];
		$frm_usr_id = $request['frm_usr_id'];

		$success = array('code' => 200, 'mensaje' => 'OK');
		$error   = array('message' => 'error de instancia', 'code' => 500);
		try {
			$data = \DB::table('rmx_vys_formularios')
				->where('frm_id', $frm_id)
				->update([
					'frm_act_id' => $frm_act_id,
					'frm_data' => $frm_data,
					'frm_data_campos' => $frm_data_campos,
                    'frm_funciones' => $frm_funciones,
					'frm_usr_id' => $frm_usr_id,
				]);
			return response()->json(['data' => $data, 'success' => $success]);
		} catch (error $e) {
			return response()->json(['error' => $error]);
		}
	}

	public function actualizarFormulariosCampos(Request $request) {
		$frm_data_campos = $request['frm_data_campos'];
		$frm_id = $request['frm_id'];

		$success = array('code' => 200, 'mensaje' => 'OK');
		$error   = array('message' => 'error de instancia', 'code' => 500);
		try {
			$data = \DB::table('rmx_vys_formularios')
				->where('frm_id', $frm_id)
				->update([
					'frm_data_campos' => $frm_data_campos
				]);
			return response()->json(['data' => $data, 'success' => $success]);
		} catch (error $e) {
			return response()->json(['error' => $error]);
		}
	}

	public function actualizarCasos(Request $request) {
		$cas_id = $request["cas_id"];
		$cas_act_id = $request["cas_act_id"];
		$cas_nodo_id = $request["cas_nodo_id"];
		$cas_data = $request["cas_data"];
		$cas_data_valores = $request["cas_data_valores"];
		$cas_usr_id = $request["cas_usr_id"];
		$cas_estado = $request["cas_estado"];

		$success = array("code"	=> 200, "mensaje"	=> 'OK',);
		$error   = array("message" => "error de instancia", "code" => 500);
		try {
			$cas_data = json_encode($cas_data, 0);
			$cas_data_valores = json_encode($cas_data_valores, 0);
            $data = \DB::select("UPDATE rmx_vys_casos SET
					cas_nodo_id = $cas_nodo_id,
					cas_act_id = $cas_act_id,
					cas_data = '$cas_data'::json,
					cas_data_valores = '$cas_data_valores'::json,
					cas_modificado = now(),
					cas_usr_id = $cas_usr_id,
					cas_estado = '$cas_estado'
				where cas_id = $cas_id");
			return response()->json(["data" => $data, "success" => $success]);
		} catch (error $e) {
			return response()->json(["error" => $error]);
		}
	}

	public function archivarCasos(Request $request) {
		$cas_id = $request["cas_id"];
		$cas_data = $request["cas_data"];
		$cas_usr_id = $request["cas_usr_id"];

		$success = array("code"	=> 200, "mensaje"	=> 'OK',);
		$error   = array("message" => "error de instancia", "code" => 500);
		try {
			$cas_data = json_encode($cas_data, 0);
            $data = \DB::select("UPDATE rmx_vys_casos SET
					cas_data = '$cas_data'::json,
					cas_modificado = now(),
					cas_usr_id = $cas_usr_id,
					cas_estado = 'H'
				where cas_id = $cas_id");
			return response()->json(["data" => $data, "success" => $success]);
		} catch (error $e) {
			return response()->json(["error" => $error]);
		}
	}

	public function derivarCasos(Request $request) {
		$cas_id = $request["cas_id"];
		$cas_act_id = $request["cas_act_id"];
		$cas_nodo_id = $request["cas_nodo_id"];
		$cas_data = $request["cas_data"];
		$cas_data_valores = $request["cas_data_valores"];
		$cas_usr_id = $request["cas_usr_id"];
		$cas_estado = $request["cas_estado"];

		$success = array("code"	=> 200, "mensaje"	=> 'OK',);
		$error   = array("message" => "error de instancia", "code" => 500);
		try {
			$cas_data = json_encode($cas_data, 0);
			$cas_data_valores = json_encode($cas_data_valores, 0);
            $data2 = \DB::select("INSERT INTO rmx_vys_historico_casos (htc_cas_id, htc_cas_act_id, 
                                    htc_cas_nodo_id, htc_cas_data, htc_cas_data_valores, 
                                    htc_cas_registrado, htc_cas_modificado, htc_cas_usr_id, htc_cas_estado)
				                SELECT * FROM rmx_vys_casos WHERE cas_id = $cas_id");
            $data = \DB::select("UPDATE rmx_vys_casos SET
					cas_nodo_id = $cas_nodo_id,
					cas_act_id = $cas_act_id,
					cas_data = '$cas_data'::json,
					cas_data_valores = '$cas_data_valores'::json,
					cas_modificado = now(),
					cas_usr_id = $cas_usr_id,
					cas_estado = '$cas_estado'
				where cas_id = $cas_id");
			return response()->json(["data" => $data, "success" => $success]);
		} catch (error $e) {
			return response()->json(["error" => $error]);
		}
	}

	public function listarImpresiones(Request $request) {
		$_id = $request['act_id'];

		if (!empty($_id)) {
			$where = 'AND imp_act_id = ' . $_id;
		}
		$success = array('code'	=> 200, 'mensaje'	=> 'OK');
		$error   = array('message' => 'error de instancia', 'code' => 500);
		try {
			$data = \DB::select("SELECT imp_id, imp_nombre, imp_data, imp_estado
				FROM rmx_vys_impresiones
				WHERE imp_estado = 'A' $where
				ORDER BY imp_nombre");
			return response()->json(['data' => $data, 'success' => $success]);
		} catch (error $e) {
			return response()->json(['error' => $error]);
		}
	}

	public function eliminarImpresion(Request $request) {
		$_id = $request['imp_id'];

		$success = array('code' => 200, 'mensaje' => 'OK');
		$error   = array('message' => 'error de instancia', 'code' => 500);
		try {
			$data = \DB::select("UPDATE rmx_vys_impresiones SET
					imp_estado = 'X',
					imp_modificado = now()
				WHERE imp_id = $_id");
			return response()->json(['data' => $data, 'success' => $success]);
		} catch (error $e) {
			return response()->json(['error' => $error]);
		}
	}

	public function grabarImpresion(Request $request) {
		$_act_id = $request['imp_act_id'];
		$_nombre = $request['imp_nombre'];
		$_data = $request['imp_data'];

		$success = array('code' => 200, 'mensaje' => 'OK');
		$error   = array('message' => 'error de instancia', 'code' => 500);
		try {
			$data = \DB::select("INSERT INTO rmx_vys_impresiones (imp_act_id, imp_nombre, imp_data, imp_usr_id)
				VALUES ($_act_id, '$_nombre', '$_data', 1)");
			return response()->json(['data' => $data, 'success' => $success]);
		} catch (error $e) {
			return response()->json(['error' => $error]);
		}
	}

	public function actualizarImpresion(Request $request) {
		$_id = $request['imp_id'];
		$_nombre = $request['imp_nombre'];
		$_data = $request['imp_data'];

		$success = array('code' => 200, 'mensaje' => 'OK');
		$error   = array('message' => 'error de instancia', 'code' => 500);
		try {
			$data = \DB::select("UPDATE rmx_vys_impresiones SET
					imp_nombre = '$_nombre',
					imp_data = '$_data',
					imp_usr_id = 1,
					imp_modificado = now()
				WHERE imp_id = $_id");
			return response()->json(['data' => $data, 'success' => $success]);
		} catch (error $e) {
			return response()->json(['error' => $error]);
		}
	}

	public function listarUsrNodos(Request $request) {
		$success = array('code' => 200, 'mensaje' => 'OK');
		$error   = array('message' => 'error de instancia', 'code' => 500);
		try {
			$data = \DB::select("SELECT *
				FROM users u JOIN rmx_usr_nodos un ON u.id = un.usn_user_id
					JOIN rmx_vys_nodos n ON un.usn_nodo_id = n.nodo_id
				WHERE un.usn_estado = 'A'
					AND n.nodo_estado = 'A'");
			return response()->json(['data' => $data, 'success' => $success]);
		} catch (error $e) {
			return response()->json(['error' => $error]);
		}
	}

	public function listarUsrNodosXId(Request $request) {
		$id = $request["id"];
		$success = array('code' => 200, 'mensaje' => 'OK');
		$error   = array('message' => 'error de instancia', 'code' => 500);
		try {
			$data = \DB::select("SELECT *
				FROM users u 
					JOIN rmx_usr_nodos un ON u.id = un.usn_user_id
					JOIN rmx_vys_nodos n ON un.usn_nodo_id = n.nodo_id
				WHERE un.usn_estado = 'A'
					AND n.nodo_estado = 'A'
					AND u.id = $id ");
			return response()->json(['data' => $data, 'success' => $success]);
		} catch (error $e) {
			return response()->json(['error' => $error]);
		}
	}

	public function grabarUsrNodos(Request $request) {
		$_user_id = $request['usn_user_id'];
		$_nodo_id = $request['usn_nodo_id'];

		$success = array('code' => 200, 'mensaje' => 'OK');
		$error   = array('message' => 'error de instancia', 'code' => 500);
		try {
			$data = \DB::select("INSERT INTO rmx_usr_nodos (usn_user_id, usn_nodo_id, usn_usr_id)
				VALUES ($_user_id, $_nodo_id, 1)");
			return response()->json(['data' => $data, 'success' => $success]);
		} catch (error $e) {
			return response()->json(['error' => $error]);
		}
	}

	public function eliminarUsrNodos(Request $request) {
		$_usn_id = $request['usn_id'];

		$success = array('code' => 200, 'mensaje' => 'OK');
		$error   = array('message' => 'error de instancia', 'code' => 500);
		try {
			$data = \DB::select("UPDATE rmx_usr_nodos SET
					usn_estado = 'X'
				WHERE usn_id = $_usn_id");
			return response()->json(['data' => $data, 'success' => $success]);
		} catch (error $e) {
			return response()->json(['error' => $error]);
		}
	}

	public function actualizarUsrNodos(Request $request) {
		$_usn_id = $request['usn_id'];
		$_user_id = $request['usn_user_id'];
		$_nodo_id = $request['usn_nodo_id'];

		$success = array('code' => 200, 'mensaje' => 'OK');
		$error   = array('message' => 'error de instancia', 'code' => 500);
		try {
			$data = \DB::select("UPDATE rmx_usr_nodos SET
					usn_user_id = $_user_id,
					usn_nodo_id = $_nodo_id
				WHERE usn_id = $_usn_id");
			return response()->json(['data' => $data, 'success' => $success]);
		} catch (error $e) {
			return response()->json(['error' => $error]);
		}
	}

	public function exportarProceso(Request $request) {
		$_id = $request['prc_id'];

		try {
			// Proceso
			$proceso = \DB::select("SELECT prc_id, prc_data, prc_data_campos_clave, prc_data->>'prc_descripcion' AS nombre, prc_modelo
				FROM rmx_vys_procesos
				WHERE prc_id = $_id");
			$actividades = \DB::select("SELECT act_id, act_tact_id, act_nodo_id, act_data, act_data_reglas, act_data_form
				FROM rmx_vys_actividades
				WHERE act_prc_id = $_id
					AND act_estado = 'A'
					ORDER BY act_id");
            $formularios = \DB::select("SELECT act_id, frm_data, frm_data_campos, frm_funciones
				FROM rmx_vys_actividades a JOIN rmx_vys_formularios f ON act_id = frm_act_id
				WHERE act_prc_id = $_id
					AND act_estado = 'A'
					AND frm_estado = 'A'
				ORDER BY act_id");
			$impresiones = \DB::select("SELECT act_id, imp_nombre, imp_data
				FROM rmx_vys_actividades a JOIN rmx_vys_impresiones i ON act_id = imp_act_id
				WHERE act_prc_id = $_id
					AND act_estado = 'A'
					AND imp_estado = 'A'
				ORDER BY act_id");
			$file = '/tmp/' . str_replace(array(',', '/'), array('_', '-'), $proceso[0]->nombre) . '.json';
			unset($proceso[0]->nombre);
			$contenido = json_encode(['proceso' => $proceso, 'actividades' => $actividades, 'formularios' => $formularios, 'impresiones' => $impresiones]);
			$txt = fopen($file, 'w') or die('Unable to open file!');
			fwrite($txt, $contenido);
			fclose($txt);

			header('Content-Description: File Transfer');
			header('Content-Disposition: attachment; filename=' . basename($file));
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			header('Content-Type: application/json;charset=utf-8');
			readfile($file);
		} catch (error $e) {
			return response()->json(['error' => $error]);
		}
	}

	public function importarProceso(Request $request) {
		$_id = $request['cat_id'];

		header('Access-Control-Allow-Origin: *');
		$filename = $_FILES['file']['name'];
		$allowed_extensions = array('json');
		$extension = pathinfo($filename, PATHINFO_EXTENSION);
		if (in_array(strtolower($extension), $allowed_extensions)) {
			$json = json_decode(file_get_contents($_FILES['file']['tmp_name']));
			$proceso = $json->proceso;
			\DB::statement("INSERT INTO rmx_vys_procesos (prc_cat_id, prc_data, prc_data_campos_clave, prc_modelo, prc_usr_id) VALUES 
                                ($_id, '" . $proceso[0]->prc_data . "'::json, '" . $proceso[0]->prc_data_campos_clave . "'::json, '" . $proceso[0]->prc_modelo . "'::json, 1)");
			$prc_id = \DB::getPdo()->lastInsertId();
			\DB::statement("UPDATE rmx_vys_procesos SET
				prc_data = jsonb_set(prc_data, '{prc_descripcion}', CONCAT('\"', prc_data->>'prc_descripcion', ' - " . date('Y-m-d H:i:s') . "\"')::jsonb)
				WHERE prc_id = $prc_id");
			$actividades = $json->actividades;
			$i = $j = 0;
			foreach ($actividades as $actividad) {
				\DB::statement("INSERT INTO rmx_vys_actividades (act_prc_id, act_tact_id, act_nodo_id, act_data, act_data_reglas, act_usr_id)
					VALUES ($prc_id, $actividad->act_tact_id, 1, '$actividad->act_data', '$actividad->act_data_reglas', 1)");
				$act_id = \DB::getPdo()->lastInsertId();

				$sql = '';
				$formularios = $json->formularios;
				while ($i < count($formularios)) {
					if ($actividad->act_id == $formularios[$i]->act_id) {
						$sql .= ",($act_id, '" . $formularios[$i]->frm_data . "', '" . $formularios[$i]->frm_data_campos . "', '" . str_replace("'", "\"", $formularios[$i]->frm_funciones) . "', 1)";
						$i++;
					} else
						break;
				}
				if (!empty($sql))
					\DB::statement('INSERT INTO rmx_vys_formularios (frm_act_id, frm_data, frm_data_campos, frm_funciones, frm_usr_id) VALUES ' . substr($sql, 1));

				$sql = '';
				$impresiones = $json->impresiones;
				while ($j < count($impresiones)) {
					if ($actividad->act_id == $impresiones[$j]->act_id) {
						$sql .= ",($act_id, '" . $impresiones[$j]->imp_nombre . "', '" . $impresiones[$j]->imp_data . "', 1)";
						$j++;
					} else
						break;
				}
				if (!empty($sql))
					\DB::statement('INSERT INTO rmx_vys_impresiones (imp_act_id, imp_nombre, imp_data, imp_usr_id) VALUES ' . substr($sql, 1));

				echo 1;
			}
		} else {
			echo 0;
		}
	}
    
    public function subirAdjunto(Request $request)
    {
        $filepath = $request["fotoPath"];
        $dir = $request["fotoDir"];
        //$filepath = 'aaa.jpg';
        $foto = $request["foto"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            // Remover la parte de la cadena de texto que no necesitamos (data:image/png;base64,)
            // y usar base64_decode para obtener la información binaria de la imagen
            $img = str_replace(' ', '+', $foto);
	        $content = base64_decode($img);

            //$content = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $foto));
            if( !is_dir( $dir ) )
                mkdir( $dir, 0777, true );
            //$filepath = "img/vys2022/" . $cas_id . "/". $frm_campo . "/name.jpg"; // or image.jpg
            // Finalmente guarda la imágen en el directorio especificado y con la informacion dada
            file_put_contents($filepath, $content);
            return response()->json(["success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    public function modificarUltUsrNodo(Request $request)
    {
		$nodo_id = $request["nodo_id"];
		$ult_usr = $request["ult_usr"];
        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_usrnodos_roundrobin set 
                                    rr_ultimo_usr_id = $ult_usr 
                                    where rr_nodo_id = $nodo_id and rr_estado = 'A'");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarUltUsrNodo(Request $request)
    {
		$nodo_id = $request["nodo_id"];
        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_usrnodos_roundrobin
                                    where rr_nodo_id = $nodo_id and rr_estado = 'A'
                                    limit 1");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarUsrNodosXNodoId(Request $request)
    {
		$nodo_id = $request["nodo_id"];
		$success = array('code' => 200, 'mensaje' => 'OK');
		$error   = array('message' => 'error de instancia', 'code' => 500);
		try {
			$data = \DB::select("SELECT u.*, n.*
                                FROM users u
                                    JOIN rmx_usr_nodos un ON un.usn_user_id = u.id
                                    JOIN rmx_vys_nodos n ON n.nodo_id = un.usn_nodo_id
                                WHERE u.status = 'A'
                                    AND n.nodo_estado = 'A'
                                    AND un.usn_estado = 'A'
                                    AND n.nodo_id = $nodo_id
                                ORDER BY u.id ");
			return response()->json(['data' => $data, 'success' => $success]);
		} catch (error $e) {
			return response()->json(['error' => $error]);
		}
    }
}
