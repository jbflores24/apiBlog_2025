<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentCollection;
use App\Http\Responses\ApiResponse;
use App\Models\Comment;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            //$comments = new CommentCollection(Comment::all());
            $comments = Comment::with ('user','article')->get();
            return ApiResponse::success('Listado de comentarios', 200, $comments);
        } catch (Exception $e) {
            return ApiResponse::error ("No se encontraron comentarios", 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request -> validate (
                [
                    'comentario' => 'required|min:1|max:250',
                    'estado' => 'required',
                    'user_id' => 'required',
                    'article_id' => 'required'
                ]
            );
            $comment = Comment::create ($request->all());
            return  ApiResponse::success('Comentario creado', 200, $comment);
        } catch (ModelNotFoundException $err) {
            return ApiResponse::error ($err->getMessage(), 400);
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
            $comment = new CommentCollection(Comment::query()->where('id', $id)->get());
            return ApiResponse::success('Comentario obtenido', 200, $comment);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error ('Comentario no existente', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $comment = Comment::findOrFail ($id);
            $request->validate([
                'comentario' => 'required|min:1|max:250',
                'estado' => 'required',
                'user_id' => 'required',
                'article_id' => 'required'
            ]);
            $comment->update($request->all());
            return ApiResponse::success('Comentario actualizado', 200, $comment);

        } catch (ValidationException $e) {
            return ApiResponse::error($e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $comment = Comment::findOrFail ($id);
            $comment -> delete();
            return ApiResponse::success('Comentario borrado', 200, $comment);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error($e->getMessage(), 404);
        }
    }
}
