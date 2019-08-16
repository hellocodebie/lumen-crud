<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use \Illuminate\Validation\ValidationException;

class TodosController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(Todo::all(), Response::HTTP_OK);
    }

    /**
     * @param $id
     *
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            $todo = Todo::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'error' => 'Unable to find todo'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($todo, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'completed' => 'boolean'
        ]);

        Todo::create($request->all());

        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }

    /**
     * @param         $id
     * @param Request $request
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'completed' => 'boolean'
        ]);

        try {
            $todo = Todo::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'error' => 'Unable to find todo'
            ], Response::HTTP_NOT_FOUND);
        }

        $todo->update($request->all());

        return response()->json($todo, Response::HTTP_OK);
    }

    /**
     * @param $id
     *
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            $todo = Todo::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'error' => 'Unable to find todo'
            ], Response::HTTP_NOT_FOUND);
        }

        $todo->delete();

        return response()->json([], 204);
    }
}
