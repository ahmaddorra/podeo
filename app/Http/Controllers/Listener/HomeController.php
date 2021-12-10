<?php

namespace App\Http\Controllers\Listener;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Podcast;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $categories = Category::query()->with('podcasts')->whereHas("podcasts")->get();
        return view('listener.podcasts.index', compact('categories'));
    }

    public function show($id)
    {
        $podcast = Podcast::query()->with('episodes')->findOrFail($id);
        return view('listener.podcasts.show', compact('podcast'));
    }


}
