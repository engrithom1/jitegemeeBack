<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RelationTo;

class RelationToController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return RelationTo::all(); 
    }
}
