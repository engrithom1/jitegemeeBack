<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EntryType;

class EntryTypeController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return EntryType::all(); 
    }
}
