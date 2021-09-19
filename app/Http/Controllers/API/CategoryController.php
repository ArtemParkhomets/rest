<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @OA\Get (
     *     path="/api/categories/",
     *     summary="Вывод списка категорий",
     *     tags={"Categories"},
     *     description="Вывод списка категорий",
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
     *         @OA\Property(property="status", type="integer", example="success"),
     *            @OA\Property(property="data", type="array",
     *                  @OA\Items(type="object", ref="#/components/schemas/Category"),
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
        $categories = Category::all();
        $total_count = $categories->count();
        if ($request->has('offset', 'limit')) {
            $categories = $categories->skip($request->get('offset'))->take($request->get('limit'));
        }
        return response()->json([
            'status'     => 'success',
            'data'       => CategoryResource::collection($categories),
            'pagination' => [
                'total'  => $total_count,
                'offset' => $request->get('offset') ?? 0,
                'limit'  => $request->get('limit') ?? 0,
            ],
        ]);
    }
    /**
     * @OA\Get (
     *     path="/api/categories/{parameter}",
     *     summary="Запрос категории по ID или slug",
     *     tags={"Categories"},
     *     description="Запрос категории по ID или slug",
     *     @OA\Parameter(
     *         name="parameter",
     *         in="path",
     *         description="ID или slug категории",
     *         required=true,
     *         example="30"
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Статус 200 и результат запроса",
     *         @OA\JsonContent(
     *            @OA\Property(property="status", type="integer", example="success"),
     *            @OA\Property(property="data", type="object", ref="#/components/schemas/Category"),
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
            $category = Category::where('id', $param)
                ->orWhere('slug_title', $param)
                ->first();
            return response()->json([
                'status' => 'success',
                'data'   => new CategoryResource($category),
            ]);
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'error'   => $exception->getMessage(),
            ], 400);
        }

    }
}
