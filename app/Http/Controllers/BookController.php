<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $books = Book::paginate();
        return view('books.index', compact('books'));
    }

    /**
     *
     */
    public function create(Request $request)
    {
        $isbn = $request->input('isbn');
        $books = Cache::get($isbn);
        if (!$books) {
            return redirect()->route('search')->with('error', 'No data');
        }
        //shift key
        $books = $books['ISBN:' . $isbn];
        return view('books.create', compact('books', 'isbn'));
    }

    /**
     * @param BookRequest $request
     * @throws \Throwable
     */
    public function store(BookRequest $request)
    {
        //
        $books = new Book();
        $books->fill($request->all());
        $books->saveOrFail();
        return redirect()->route('books.index')->with('message', 'Success create book');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * @param Book $book
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    /**
     * @param BookRequest $request
     * @param Book $book
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'isbn' => ['required', 'unique:books,isbn,' . $book->id]
        ]);

        $book->fill($request->all());
        $book->save();
        return redirect()->route('books.index')->with('message', 'Success update book');
    }

    /**
     * @param Book $book
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('message', 'Success delete a book');
    }
}
