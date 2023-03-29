<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Family;

class FamilyController extends Controller
{
    public function allFamily()
    {
        return response()->json(Family::with('parent')->get(['id', 'parent_id', 'name', 'gender']), 200);
    }

    public function sonBudi($id)
    {
        $sonOfBudi = Family::with('children')->find($id);
        return response()->json($sonOfBudi, 200);
    }

    public function grandsonBudi($id)
    {
        $sonOfBudi = Family::with('children.children')->find($id);
        return response()->json($sonOfBudi, 200);
    }

    public function granddaughterBudi($id) {
        $sonOfBudi = Family::with(['children.children' => function ($q) {
            $q->where('gender', 'female');
        }])->find($id);
        return response()->json($sonOfBudi, 200);
    }

    public function auntyFarah($id)
    {
        $result = Family::with('parent.parent.children')->find($id);
        $auntyFarah = $result->parent->parent->children->where('gender', 'female');
        return response()->json($auntyFarah, 200);
    }

    public function maleCousinHani($id)
    {
        $result = Family::with('parent.parent.children.children')->find($id);
        $maleCousinHani = $result->parent->parent->children;
        return response()->json($maleCousinHani, 200);
    }
}
