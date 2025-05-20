<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoryRequest;
use App\Http\Resources\StoryResource;
use App\Models\Story;

class StoryController extends Controller
{
    public function index()
    {
        return StoryResource::collection(Story::with('chapters')->get());
    }

    public function show(Story $story)
    {
        return new StoryResource($story->load('chapters'));
    }
}
