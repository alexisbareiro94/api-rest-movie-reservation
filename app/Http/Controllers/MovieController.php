<?php

namespace App\Http\Controllers;

//use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Movie;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::all()->toArray();
        $count = count($movies);
        return response()->json([
            'count' => $count,
            'movies' => $movies,
        ]);
    }

    public function store(Request $request)
    {
        if (!$request->user()->tokenCan('create')) {
            return response()->json([
                'message' => 'no estas autorizado'
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'duration' => 'required',
            'image' => 'required|image',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages(),
            ], 400);
        }
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('uploads/portada'), $imageName);
        }
        $categories = explode(',', $request->category_id);

        try {
            $movie =  Movie::create([
                'title' => $request->title,
                'slug' => Str::slug($request->title, '_'),
                'category_id' => $categories,
                'description' => $request->description,
                'duration' => $request->duration,
                'image' => $imageName,
            ]);
            return response()->json([
                'message' => 'Movie successfully created',
                'movie' => $movie,
            ], 201);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function show(string $id)
    {
        return Movie::findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        if (!$request->user()->tokenCan('edit')) {
            return response()->json([
                'message' => 'sin autorización'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes',
            'description' => 'sometimes',
            'duration' => 'sometimes',
            'image' => 'sometimes|image',
            'category_id' => 'sometimes'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages(),
            ], 400);
        }

        $movie = Movie::findOrFail($id);
        try {
            if ($request->category_id) {
                $category = explode(',', $request->category_id);
                $newCategories = array_unique($category);
            }
            //dd($newCategories, $movie->category_id);
            if ($request->hasFile('image')) {
                if (file_exists(public_path("uploads/portada/$movie->image"))) {
                    @unlink(public_path("uploads/portada/$movie->image"));
                }
                $newImageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(public_path('uploads/portada', $newImageName));
            }

            $movie->update([
                'title' => $request->title ?? $movie->title,
                'description' => $request->description ?? $movie->description,
                'duration' => $request->duration ?? $movie->duration,
                'image' => $newImageName ?? $movie->image,
                'category_id' => $newCategories,
            ]);

            return response()->json([
                'message' => 'película actualizado correctamente',
                $movie,
            ]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function destroy(string $id, Request $request)
    {
        if(!$request->user()->tokenCan('destroy')){
            return response()->json([
                'message' => 'sin autorización'
            ]);
        }
        try{
            $movie = Movie::find($id);
            if(file_exists(public_path("uploads/portada/$movie->image"))){
                unlink(public_path("uploads/portada/$movie->image"));
            }
            $movie->delete();
            return response()->json([
                'message' => 'pelicula borrada correctamente'
            ]);
        }catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }
}
