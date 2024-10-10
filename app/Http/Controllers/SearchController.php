<?php

namespace App\Http\Controllers;

class SearchController extends Controller
{
    public function index()
    {
        $data = [];
        return view('popup.search', $data);
    }
}
