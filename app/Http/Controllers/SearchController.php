<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class SearchController extends SiteController
{
    public function index(Request $request)
    {
        return view('search.index', [
            'query' => $request->get('q'),
            'domain' => env('GSEARCH_DOMAIN')
        ]);
    }
}