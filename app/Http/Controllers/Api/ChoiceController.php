<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChoiceRequest;
use App\Http\Resources\ChoiceResource;
use App\Models\Choice;

class ChoiceController extends Controller
{
    public function index()
    {
        return ChoiceResource::collection(Choice::all());
    }

    public function show($id)
    {
        $choice = Choice::findOrFail($id);
        return new ChoiceResource($choice);
    }

    public function store(ChoiceRequest $request)
    {
        $choice = Choice::create($request->validated());
        return new ChoiceResource($choice);
    }

    public function update(ChoiceRequest $request, $id)
    {
        $choice = Choice::findOrFail($id);
        $choice->update($request->validated());
        return new ChoiceResource($choice);
    }

    public function destroy($id)
    {
        $choice = Choice::findOrFail($id);
        $choice->delete();
        return response()->json(['message' => 'Choix supprimé avec succès']);
    }
} 