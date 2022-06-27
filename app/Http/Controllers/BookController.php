<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $books = Book::with(['booking' => function(HasMany $q) {
            return $q
                ->with('user')
                ->where('status', '=', 'booked');
        }])->paginate();



        return view('books.index', compact('books'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\never
     */
    public function create(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return abort(403, 'INSUFFICIENT CREDENTIALS');
        }

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
     * @return \Illuminate\Http\RedirectResponse|\never
     * @throws \Throwable
     */
    public function store(BookRequest $request)
    {
        if (!auth()->user()->isAdmin()) {
            return abort(403, 'INSUFFICIENT CREDENTIALS');
        }
        $books = new Book();
        $books->fill($request->all());
        $books->saveOrFail();
        return redirect()
            ->route('books.index')
            ->with('message', 'Success create book');
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\never
     */
    public function edit(Book $book)
    {
        if (!auth()->user()->isAdmin()) {
            return abort(403, 'INSUFFICIENT CREDENTIALS');
        }
        return view('books.edit', compact('book'));
    }

    /**
     * @param Request $request
     * @param Book $book
     * @return \Illuminate\Http\RedirectResponse|\never
     */
    public function update(Request $request, Book $book)
    {
        if (auth()->user()->isAdmin()) {
            $request->validate([
                'isbn' => ['required', 'unique:books,isbn,' . $book->id]
            ]);

            $book->fill($request->all());
            $book->save();
        } else if (!$book->getAttribute('is_booked')) {
            $booking = new Booking();
            $booking->setAttribute('user_id', auth()->user()->getAuthIdentifier());
            $booking->setAttribute('book_id', $book->id);
            $booking->setAttribute('status', 'booked');
            $booking->setAttribute('book_at', Carbon::now());
            if ($booking->save()) {
                //lock book
                $book->setAttribute('is_booked', 1);
                $book->save();
            }
        } else {
            /* kembalikan buku dan cari kembali booking */
            $booking = Booking::query()
                ->where('book_id', $book->id)
                ->where('user_id', auth()->user()->getAuthIdentifier())
                ->where('status', 'booked')
                ->firstOrFail();

            $booking->setAttribute('status', 'release');
            $booking->setAttribute('release_at', Carbon::now());
            if ($booking->save()) {
                $book->setAttribute('is_booked', 0);
                $book->save();
            }
        }

        return redirect()
            ->route('books.index')
            ->with('message', 'Success update book');
    }

    /**
     * @param Book $book
     * @return \Illuminate\Http\RedirectResponse|\never
     */
    public function destroy(Book $book)
    {
        if (!auth()->user()->isAdmin()) {
            return abort(403, 'INSUFFICIENT CREDENTIALS');
        }
        $book->delete();
        return redirect()
            ->route('books.index')
            ->with('message', 'Success delete a book');
    }
}
