<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegistrationFormRequest;

class ApiController extends Controller
{
    /**
     * @var bool
     */
    public $loginAfterSignUp = true;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(Request $request)
    {
        //$input = $request->only('email', 'password'); // application/x-www-form-urlencoded
        $input = json_decode($request->getContent(), true); // application/json
        $token = null;

        if (!$token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }

    /**
     * @param RegistrationFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegistrationFormRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }

        return response()->json([
            'success'   =>  true,
            'data'      =>  $user
        ], 200);
    }

    public function find(Request $request)
    {
        $email = $request["email"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select * from users u
                                    inner join rmx_ig_dptos d on d.dpt_id = u.emp_id
                                where u.status = 'A'
                                    and u.email = '$email'
                                order by u.name
                                ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarRoles()
    {
        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select r.id as role_id, r.code as role_code, 
                                    r.description as role_description
                                from roles r
                                order by r.id");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarUsersXJrs(Request $request)
    {
        $jrs_id = $request["jrs_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $sql2 = ($jrs_id !== 'null') ? " where branch_id = $jrs_id " : "";

            $data = \DB::select("select u.*, j.*,
                                r.id as role_id, r.code as role_code, r.description as role_description
                                from users u
                                    inner join roles r on r.id = u.role_id 
                                    inner join rmx_ig_jurisdicciones j on j.jrs_id = u.branch_id 
                                $sql2
                                    -- and u.status <> 'X' 
                                    -- lista incluidos los eliminados
                                order by u.name
                                ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarUsers()
    {
        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("SELECT u.id, name, email, u.created_at, u.updated_at, u.role_id, u.branch_id, r.description, u.status
				FROM users u 
                JOIN roles r ON u.role_id = r.id
                JOIN branches b ON u.branch_id = b.id
				ORDER BY name, description");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function buscarUser(Request $request)
    {
        $id = $request["id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select u.*,
                                r.id as role_id, r.code as role_code, r.description as role_description
                                from users u
                                    inner join roles r on r.id = u.role_id 
                                where u.id = $id 
                                -- u.status <> 'X'
                                -- lista incluidos los eliminados
                                order by u.name
                                ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarUser(Request $request)
    {
        $role_id = $request["role_id"];
        $branch_id = $request["branch_id"];
        $name = $request["name"];
        $email = $request["email"];
        $password = Hash::make($request["password"]);
        $status = $request["status"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("insert into users (role_id, branch_id, name, email, password, status) values
                                    ('$role_id', 1, '$name', '$email', '$password', 'A') ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarUser(Request $request)
    {
        $id = $request["id"];
        $role_id = $request["role_id"];
        $branch_id = $request["branch_id"];
        $name = $request["name"];
        $email = $request["email"];
        //$password = $request["password"];
        $password = Hash::make($request["password"]);

        //print_r(name);
        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update users
                            set     role_id = $role_id,
                                    branch_id = $branch_id,
                                    name = '$name',
                                    email = '$email',
                                    password = '$password'
                                where id = $id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarUser(Request $request)
    {
        $id = $request["id"];
        $status = 'X';

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update users
                                    set status = '$status'
                                where id = $id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
}
