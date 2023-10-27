<?php

namespace App\Http\Controllers;

use App\Models\ParentStatus;
use Illuminate\Http\Request;

class ParentStatusController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ParentStatus::all(); 
    }

}
