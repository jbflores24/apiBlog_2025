<?php

namespace App\Http\Controllers;

use App\Http\Resources\RolCollection;
use App\Http\Responses\ApiResponse;
use App\Models\Rol;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $roles = new RolCollection(Rol::all());
            return ApiResponse::success('Listado de los roles',201, $roles);
        } catch (Exception $e) {
            return ApiResponse::error('Error al obtener los datos',500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate(
                [
                    'nombre' => 'required|string|min:3|max:45',
                ]
            );
            $rol = Rol::create($request->all());
            return ApiResponse::success('Se ha creado el rol correctamente', 200, $rol);
        }catch (ValidationException $e) {
            return ApiResponse::error ($e->getMessage(),404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            //$rol = new RolCollection(Rol::findOrFail($id));
            $rol = new RolCollection(Rol::query()->where('id', $id)->get());
            if ($rol->isEmpty()) throw new ModelNotFoundException("Rol no encontrado");
            return ApiResponse::success('Información del rol',200,$rol);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('No existe el rol solicitado', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try{
            $rol = Rol::findOrFail($id);
            $request->validate([
                'nombre' => 'required|string|min:3|max:45',
            ]);
            $rol->update($request->all());
            return ApiResponse::success('Registro actualizado',200,$rol);
        } catch (ModelNotFoundException $ex) {
            return ApiResponse::error($ex->getMessage(),404);
        }catch (Exception $e) {
            return ApiResponse::error($e->getMessage(),500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $rol = Rol::findOrFail($id);
            $rol->delete();
            return ApiResponse::success('Registro eliminado',200, $rol);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error($e->getMessage(),404);
        }
    }
}
