<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ReasonCategory;
use App\Http\Controllers\Controller;

class ReasonCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ReasonCategory::all();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $reasonCategory = ReasonCategory::findOrFail($id);
    }
}
