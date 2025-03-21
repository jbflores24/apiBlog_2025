<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = new UserCollection(User::all());
            return ApiResponse::success('Listado de usuarios',200,$users);
        } catch (Exception $e) {
            return ApiResponse::error('No encuetro los datos', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|min:3|max:45|string',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'rol_id' => 'required'
            ]);
            $user = User::create($request->all());
            return ApiResponse::success('Usuario creado',200,$user);
        } catch(ValidationException $e) {
            return ApiResponse::error($e->getMessage(),400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $user = new UserCollection(User::query()->where('id', $id)->get());
            if ($user->isEmpty()) throw new ModelNotFoundException('Error');
            return ApiResponse::success('Usuario encontrado',200,$user);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('No existe el usuario',404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail ($id);
            $request->validate([
                'name' => 'required|min:3|max:45|string',
                'email' => ['required','email',Rule::unique('users')->ignore($user)],
                'password' => 'nullable',
                'rol_id' => 'required'
            ]);
            $user->update($request->all());
            return ApiResponse::success('Usuario actualizado',200,$user);
        } catch (ModelNotFoundException $ex) {
            return ApiResponse::error ($ex->getMessage(),400);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(),500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return ApiResponse::success('Usuario eliminado',200,$user);
        } catch(ModelNotFoundException $e) {
            return ApiResponse::error('No se encontro el usuario', 400);
        } catch (Exception $err) {
            return ApiResponse::error($err->getMessage(),500);
        }
    }
}
