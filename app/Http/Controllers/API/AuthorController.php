<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorController extends Controller
{
    /**
     * @OA\Get (
     *     path="/api/authors/",
     *     summary="Поиск автора по фамилии, или получение списка авторов",
     *     tags={"Authors"},
     *     description="Поиск автора по фамилии, или получение списка авторов",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="ФИО автора",
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
     *                  @OA\Items(type="object", ref="#/components/schemas/Author"),
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
     *     ),
     * )
     */
    public function all(Request $request)
    {
        if($request->has('search')) {
            $authors = Author::where('full_name', 'like', '%'.$request->get('search').'%')->get();
        } else {
            $authors = Author::all();
        }
        $total_count = $authors->count();
        if ($request->has('offset', 'limit')) {
            $authors = $authors->skip($request->get('offset'))->take($request->get('limit'));
        }
        if($total_count > 0) {
            $status = 200;
        } else {
            $status = 204;
        }
        return response()->json([
            'status'     => $status,
            'data'       => AuthorResource::collection($authors),
            'pagination' => [
                'total'  => $total_count,
                'offset' => $request->get('offset') ?? 0,
                'limit'  => $request->get('limit') ?? 0,
            ],
        ]);
    }
    /**
     * @OA\Get (
     *     path="/api/authors/{parameter}",
     *     summary="Запрос автора по ID или slug",
     *     tags={"Authors"},
     *     description="Get blog post by id",
     *     @OA\Parameter(
     *         name="parameter",
     *         in="path",
     *         description="ID или slug автора",
     *         required=true,
     *         example="30"
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Статус 200 и результат запроса",
     *         @OA\JsonContent(
     *            @OA\Property(property="status", type="integer", example="success"),
     *            @OA\Property(property="data", type="object", ref="#/components/schemas/Author"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad Request"
     *     )
     * )
     */
    public function getByParameter($param)
    {
        try {
            $author = Author::where('id', $param)
                ->orWhere('slug_name', $param)
                ->first();
            return response()->json([
                'status' => 200,
                'data'   => new AuthorResource($author),
            ]);
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'error'   => $exception->getMessage(),
            ], 400);
        }

    }
}
