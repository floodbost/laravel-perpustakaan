<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $books = [];
        if ($isbn = $request->query('isbn')) {
            if (!($books = Cache::get($isbn))) {
                $response = Http::get('https://openlibrary.org/api/books?bibkeys=ISBN:'.$isbn.'&format=json&jscmd=data');
                $books = $response->json();
                if ($books) {
                    Cache::put($isbn, $books, now()->addMinutes(10));
                }
            }
        }

        return view('search.index', compact('books'));
    }
}
