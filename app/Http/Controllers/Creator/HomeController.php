<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Podcast;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): \Illuminate\Contracts\Support\Renderable
    {
        $podcasts = Podcast::all();
        return view('creator.home', compact('podcasts'));
    }


    public function create()
    {
        $categories = Category::all();
        $users = User::all();
        return view('creator.podcasts.create', compact('categories', 'users'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|unique:podcasts,name',
            'description' => 'required',
            'category' => 'required|exists:categories,id',
            'image' => 'required|file|max:5120|mimes:jpg,bmp,png',
        ]);

        $podcast = new Podcast();
        $podcast->name = $request->title;
        $podcast->description = $request->description;
        $podcast->category_id = $request->category;
        $podcast->user_id = auth()->id();
        $file = $request->file('image');
        $name = Str::snake($request->title) . time() . '.' . $file->getClientOriginalExtension();
        if (!Storage::disk('public')->exists('images/podcasts')) {
            Storage::disk('public')->makeDirectory('images/podcasts');
        }
        if (Storage::disk('public')->putFileAs('images/podcasts', $file, $name)) {
            $podcast->image = 'images/podcasts/' . $name;
        } else {
            return back()->withErrors(["image" => "SERVER ENCOUNTER ERROR, PLEASE TRY AGAIN LATER"])->withInput($request->all());
        }
        $podcast->save();
        return redirect()->route('creator.podcasts.index');
    }

    public function edit($id)
    {
        $categories = Category::all();
        $podcast = Podcast::query()->with('episodes')->findOrFail($id);
        return view('creator.podcasts.edit', compact('categories', 'podcast'));
    }


    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id): \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'title' => 'required|unique:podcasts,name,' . $id,
            'description' => 'required',
            'category' => 'required|exists:categories,id',
            'image' => 'sometimes|file|size:5120|mimes:jpg,bmp,png',
        ]);

        $podcast = Podcast::query()->findOrFail($id);
        $podcast->name = $request->title;
        $podcast->description = $request->description;
        $podcast->category_id = $request->category;
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $name = Str::snake($request->title) . time() . '.' . $file->getClientOriginalExtension();
            if (!Storage::disk('public')->exists('images/podcasts')) {
                Storage::disk('public')->makeDirectory('images/podcasts');
            }
            if (Storage::disk('public')->putFileAs('images/podcasts', $file, $name)) {
                $podcast->image = 'images/podcasts/' . $name;
            } else {
                return $this->index();
            }
        }
        $podcast->save();
        return redirect()->route('creator.podcasts.index');
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        try{
            Podcast::query()->where('id', $id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }
}
