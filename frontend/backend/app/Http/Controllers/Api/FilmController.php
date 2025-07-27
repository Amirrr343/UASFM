<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/films",
     *     tags={"Film"},
     *     summary="Get list of films",
     *     @OA\Response(response=200, description="Success")
     * )
     */
    public function index()
    {
        return Film::paginate(5); // menampilkan 5 data per halaman
    }

    /**
     * @OA\Post(
     *     path="/api/films",
     *     tags={"Film"},
     *     summary="Create a new film",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","description","genre","release_year"},
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="genre", type="string"),
     *             @OA\Property(property="release_year", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Created")
     * )
     */
    public function store(Request $request)
    {
        $film = Film::create($request->only('title', 'description', 'genre', 'release_year'));
        return response()->json($film, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/films/{film}",
     *     tags={"Film"},
     *     summary="Update a film",
     *     @OA\Parameter(
     *         name="film",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="genre", type="string"),
     *             @OA\Property(property="release_year", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Updated")
     * )
     */
    public function update(Request $request, Film $film)
    {
        $film->update($request->only('title', 'description', 'genre', 'release_year'));
        return response()->json($film);
    }

    /**
     * @OA\Delete(
     *     path="/api/films/{film}",
     *     tags={"Film"},
     *     summary="Delete a film",
     *     @OA\Parameter(
     *         name="film",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Deleted")
     * )
     */
    public function destroy(Film $film)
    {
        $film->delete();
        return response()->json(null, 204);
    }
}
