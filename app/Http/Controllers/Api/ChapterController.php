<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChapterRequest;
use App\Http\Resources\ChapterResource;
use App\Models\Chapter;

class ChapterController extends Controller
{
    public function index()
    {
        return ChapterResource::collection(Chapter::with('choices')->get());
    }

    public function show($id)
    {
        $chapter = Chapter::with('choices')->findOrFail($id);
        return new ChapterResource($chapter);
    }

    public function store(ChapterRequest $request)
    {
        $chapter = Chapter::create($request->validated());
        return new ChapterResource($chapter);
    }

    public function update(ChapterRequest $request, $id)
    {
        $chapter = Chapter::findOrFail($id);
        $chapter->update($request->validated());
        return new ChapterResource($chapter);
    }

    public function destroy($id)
    {
        $chapter = Chapter::findOrFail($id);
        $chapter->delete();
        return response()->json(['message' => 'Chapitre supprimé avec succès']);
    }
}
