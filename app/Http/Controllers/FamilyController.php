<?php

namespace App\Http\Controllers;

use App\Models\Family;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;

class FamilyController extends Controller
{
    public function index()
    {
        return view('families.index', [
            'parents' => Family::get(),
            'budiFamilies' => Family::with('children.children')->where('name', 'Budi')->first(),
        ]);
    }

    public function create()
    {
        return view('families.create');
    }

    public function show($id)
    {
        return view('families.show', ['id' => $id]);
    }

    public function store()
    {
        $itemId = request('item_id');

        try {
            DB::transaction(function () use ($itemId) {
                Family::updateOrCreate(['id' => $itemId], request()->all());
            });
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }

        return response()->json([
            'message' => 'Data berhasil disimpan',
        ]);
    }

    public function edit($id)
    {
        $person = Family::find($id);
        return response()->json($person);
    }

    public function destroy($id)
    {
        $person = Family::find($id);
        $person->delete();
        return redirect()->back();
    }
}
