<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\Podcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EpisodeController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'name' => ['required',
                Rule::unique('episodes')->where(function ($query) use($request) {
                    return $query->where('name', $request->name)
                        ->where('podcast_id', $request->podcast_id);
                }),
                ],
            'description' => 'required',
            'podcast_id' => 'required|exists:podcasts,id',
            'audio' => 'required|file|mimes:mp3',
        ]);
        if (Podcast::query()->findOrFail($request->podcast_id)->user_id != auth()->id())
            return response()->json(['success' => false], 403);
//        try {
            $episode = new Episode();
            $episode->name = $request->name;
            $episode->description = $request->description;
            $episode->podcast_id = $request->podcast_id;
            $file = $request->file('audio');
            $name = Str::snake($request->title) . time() . '.' . $file->getClientOriginalExtension();
            if (!Storage::disk('public')->exists('episodes')) {
                Storage::disk('public')->makeDirectory('episodes');
            }
            if (Storage::disk('public')->putFileAs('episodes', $file, $name)) {
                $episode->audio = 'episodes/' . $name;
            }
//            else {
//                return response()->json(['success' => false], 500);
//            }
            $episode->save();
            return response()->json(['success' => true, 'episode' => $episode]);
//        } catch (\Exception $e) {
//            return response()->json(['success' => false], 500);
//        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'name' => ['required',
                Rule::unique('episodes')->where(function ($query) use($request, $id) {
                    return $query->where('name', $request->name)
                        ->where('podcast_id', $request->podcast_id)
                        ->where("id", "!=", $id);
                }),
            ],
            'description' => 'required',
        ]);
        try {
            $episode = Episode::query()->findOrFail($id);
            if ($episode->podcast->user_id != auth()->id())
                return response()->json(['success' => false], 403);
            $episode->name = $request->title;
            $episode->description = $request->description;
            $episode->save();
            return response()->json(['success' => true, "name" => $episode->name]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        try {
            $episode = Episode::query()->findOrFail($id);
            if ($episode->podcast->user_id != auth()->id())
                return response()->json(['success' => false], 403);
            $episode->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }
}
