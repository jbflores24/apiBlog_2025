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
        try {
            $request -> validate([
                'titulo' => 'required|min:|max255',
                'texto' => 'required',
                'imagen' => ['nullable','image','mimes:jpg, jpeg, gif, svg, bmp', 'max:10240'],
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
                $file->move(public_path('storage/'),$picture);
                $article->imagen = '/storage/'.$picture;
            }
            $article->save();
            return ApiResponse::success('Articulo Agregado', 200, $article);
        } catch (ValidationException $e) {
            return ApiResponse::error($e->getMessage(), 422);
        } catch (Exception $e) {
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
    public function destroy(Article $article)
    {
        //
    }
}
