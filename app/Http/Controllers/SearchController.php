<?php

namespace App\Http\Controllers;

use App\Core\Engines\SearchEngine;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $term = $request->get('q');
        $results = $term ? app(SearchEngine::class)->search($term) : [];

        return view('search.index', compact('term', 'results'));
    }
}
