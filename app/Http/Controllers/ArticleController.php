<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleCollection;
use App\Http\Responses\ApiResponse;
use App\Models\Article;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $articles = new ArticleCollection(Article::all());
            return ApiResponse::success('Listado de articulos', 200, $articles);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //return response()->json($request);
        try {
            $request -> validate([
                'titulo' => 'required|min:3|max:255',
                'texto' => 'required',
                'imagen' => ['nullable','image','mimes:jpg,jpeg,gif,svg,bmp', 'max:10240'],
                'user_id' => 'required'
            ]);
            $article = new Article;
            $article->titulo = $request->input('titulo');
            $article->texto = $request->input('texto');
            $article->user_id = $request->input('user_id');
            if ($request->hasFile('imagen')) {
                $file = $request->file('imagen');
                $filename = $file->getClientOriginalName();
                $filename = pathinfo($filename, PATHINFO_FILENAME);
                $name_file = str_replace(" ", "_", $filename);
                $extension = $file->getClientOriginalExtension();
                $picture = date('His').'-'.$name_file.'.'.$extension;
                $file->move(public_path('uploads/'),$picture);
                $article->imagen = '/uploads/'.$picture;
            }
            $article->save();
            return ApiResponse::success('Articulo Agregado', 200, $article);
        } catch (ValidationException $e) {
            return ApiResponse::error($e->getMessage(), 422);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    /*public function actualizar (Request $request, $id) {
        try {
            $article = Article::findOrFail ($id);
            $request -> validate(
                [
                    'titulo' => 'required|min:3|max:255',
                    'texto' => 'required',
                    'imagen' => ['nullable', 'image', 'mimes:jpeg,jpg,gif,svg,bmp', 'max:10240'],
                    'user_id' => 'required'
                ]
            );
            $article = new Article;
            $article->titulo = $request->input('titulo');
            $article->texto = $request->input('texto');
            $article->user_id = $request->input('user_id');
            if ($request->hasFile('imagen')) {
                $file = $request->file('imagen');
                $filename = $file->getClientOriginalName();
                $filename = pathinfo($filename, PATHINFO_FILENAME);
                $name_file = str_replace(" ", "_", $filename);
                $extension = $file->getClientOriginalExtension();
                $picture = date('His').'-'.$name_file.'.'.$extension;
                $file->move(public_path('uploads/'),$picture);
                $article->imagen = '/uploads/'.$picture;
            }
            $article->update();
            return ApiResponse::success('Articulo actualizado', 200, $article);
        }catch (ValidationException $e) {
            return ApiResponse :: error ($e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse :: error ($e->getMessage(), 500);
        } catch (ModelNotFoundException $e) {
            return ApiResponse :: error ($e->getMessage(), 404);

        }
    }*/

    public function actualizar(Request $request, $id)
{
    try {
        // Buscar el artículo por ID
        $article = Article::findOrFail($id);

        // Validar los datos del request
        $request->validate([
            'titulo' => 'required|min:3|max:255',
            'texto' => 'required',
            'imagen' => ['nullable', 'image', 'mimes:jpeg,jpg,gif,svg,bmp', 'max:10240'],
            'user_id' => 'required|exists:users,id',  // Asegúrate de que el user_id exista
        ]);

        // Actualizar los campos del artículo
        $article->titulo = $request->input('titulo');
        $article->texto = $request->input('texto');
        $article->user_id = $request->input('user_id');

        // Si hay un archivo de imagen
        if ($request->hasFile('imagen')) {
            // Eliminar la imagen anterior si existe (opcional)
            if ($article->imagen && file_exists(public_path($article->imagen))) {
                unlink(public_path($article->imagen));
            }

            // Subir la nueva imagen
            $file = $request->file('imagen');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $name_file = str_replace(" ", "_", $filename);
            $extension = $file->getClientOriginalExtension();
            $picture = date('His') . '-' . $name_file . '.' . $extension;

            // Asegúrate de que el directorio 'uploads' existe
            $uploadPath = public_path('uploads');
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true); // Crear directorio si no existe
            }

            // Mover la imagen a la carpeta 'uploads'
            $file->move($uploadPath, $picture);
            $article->imagen = '/uploads/' . $picture;
        }

        // Guardar los cambios
        $article->save();

        // Responder con éxito
        return ApiResponse::success('Artículo actualizado con éxito', 200, $article);

    } catch (ValidationException $e) {
        // Manejo de error de validación
        return ApiResponse::error($e->getMessage(), 422); // Código de error 422 para validación
    } catch (ModelNotFoundException $e) {
        // Si no se encuentra el artículo
        return ApiResponse::error('Artículo no encontrado', 404);
    } catch (Exception $e) {
        // Manejo de otros errores
        return ApiResponse::error($e->getMessage(), 500);
    }
}


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $article = new ArticleCollection(Article::query()->where('id',$id)->get());
            if ($article->isEmpty()) throw new ModelNotFoundException('Articulo no encontrado');
            return ApiResponse::success('Información del artículo', 200, $article);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(),500);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Articulo no encontrado',404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $article = Article::findOrFail ($id);
            $article->delete();
            return ApiResponse::success('Articulo eliminado', 200, $article);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Articulo no encontrado',404);
        } catch (Exception $e) {
            return ApiResponse::error ($e->getMessage(), 500);
        }
    }
}
