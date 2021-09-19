<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Author;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function all(Request $request)
    {
        /**
         * @OA\Get (
         *     path="/api/posts/",
         *     summary="Поиск статьи по названию, автору, категории",
         *     tags={"Posts"},
         *     description="Поиск статьи по названию, автору, категории",
         *     @OA\Parameter(
         *         name="category",
         *         in="query",
         *         description="ID категории",
         *         required=false,
         *     ),
         *     @OA\Parameter(
         *         name="author",
         *         in="query",
         *         description="ID автора",
         *         required=false,
         *     ),
         *     @OA\Parameter(
         *         name="title",
         *         in="query",
         *         description="Часть или полное название статьи",
         *         required=false,
         *     ),
         *     @OA\Parameter(
         *         name="limit",
         *         in="query",
         *         description="Количество записей",
         *         required=false,
         *     ),
         *     @OA\Parameter(
         *         name="offset",
         *         in="query",
         *         description="Смещение",
         *         required=false,
         *     ),
         *     @OA\Response(
         *         response="200",
         *         description="Success",
         *         @OA\JsonContent(
         *         @OA\Property(property="status", type="integer", example="200"),
         *            @OA\Property(property="data", type="array",
         *                  @OA\Items(type="object", ref="#/components/schemas/Post"),
         *            ),
         *            @OA\Property(property="pagination", type="object",
         *              @OA\Property(property="limit", type="integer", example="2"),
         *              @OA\Property(property="offset", type="integer", example="2"),
         *              @OA\Property(property="total", type="integer", example="200"),
         *            ),
         *         ),
         *     ),
         *     @OA\Response(
         *         response="204",
         *         description="No content"
         *     )
         * )
         */
        try {
            if ($request->has('category', 'title', 'author')) {
                $posts = Category::find($request->get('category'))->posts->where('author_id', $request->get('author'));
                $title = $request->get('title');
                $posts = collect($posts)->filter(function ($item) use ($title) {
                    return false !== stripos($item['title'], $title);
                });
            } elseif ($request->has('category', 'title')) {
                $posts = Category::find($request->get('category'))->posts;
                $title = $request->get('title');
                $posts = collect($posts)->filter(function ($item) use ($title) {
                    return false !== stripos($item['title'], $title);
                });
            } elseif ($request->has('category', 'author')) {
                $posts = Category::find($request->get('category'))->posts->where('author_id', $request->get('author'));
            } elseif ($request->has('title', 'author')) {
                $posts = Post::where('title', 'like', '%'.$request->get('title').'%')->where('author_id', $request->get('author'))->get();
            } elseif ($request->has('title')) {
                $posts = Post::where('title', 'like', '%'.$request->get('title').'%')->get();
            } elseif ($request->has('author')) {
                $posts = Post::where('author_id', $request->get('author'))->get();
            } elseif ($request->has('category')) {
                $posts = Category::find($request->get('category'))->posts;
            } else {
                $posts = Post::all();
            }
            $total_count = $posts->count();
            if ($total_count > 0) {
                $status = 200;
            } else {
                $status = 204;
            }
            if ($request->has('offset', 'limit')) {
                $posts = $posts->skip($request->get('offset'))->take($request->get('limit'));
            }
        } catch (\Throwable $exception) {
            return response()->json([
                'success' => false,
                'error'   => $exception->getMessage(),
            ], 400);
        }
        return response()->json([
            'status'     => 'success',
            'data'       => PostResource::collection($posts),
            'pagination' => [
                'total'  => $total_count,
                'offset' => $request->get('offset') ?? 0,
                'limit'  => $request->get('limit') ?? 0,
            ],
        ], $status);
    }
    /**
     * @OA\Get (
     *     path="/api/posts/{parameter}",
     *     summary="Запрос статьи по ID или slug",
     *     tags={"Posts"},
     *     description="Запрос статьи по ID или slug",
     *     @OA\Parameter(
     *         name="parameter",
     *         in="path",
     *         description="ID или slug статьи",
     *         required=true,
     *         example="30"
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Статус success и результат запроса",
     *         @OA\JsonContent(
     *            @OA\Property(property="status", type="integer", example="success"),
     *            @OA\Property(property="data", type="object", ref="#/components/schemas/Post"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad Request",

     *     )
     * )
     */
    public function getByParameter($param)
    {
        try {
            $post = Post::where('id', $param)
                ->orWhere('title_slug', $param)
                ->first();
            return response()->json([
                'status' => 'success',
                'data'   => new PostResource($post),
            ]);
        } catch (\Throwable $exception) {
            return response()->json([
            'status' => 'error',
            'error'   => $exception->getMessage(),
            ], 400);
        }
    }
}
