<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Books') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="text-right">
                                    @if(auth()->user()->isAdmin())
                                    <a href="{{ route('search') }}" class="inline-flex items-center px-4 py-2 bg-blue-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Add Book</a>
                                    @endif
                                </div>
                                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg mt-5">
                                    <x-success-message />
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Cover
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Title
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    ISBN
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Author
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Status
                                                </th>
                                                <th scope="col" class="relative px-6 py-3">
                                                    <span class="sr-only">Add To Collection</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($books as $book)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    @if(!empty($book->cover_path))
                                                        <img src="{{ $book->cover_path }}" alt="cover" class="object-contain h-20" />
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    {{ $book->title }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $book->isbn }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $book->author }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    @if($book->is_booked)
                                                        <span class="bg-yellow-100 px-2 py-1 rounded-md">Dipinjam</span>
                                                    @else
                                                        <span class="bg-green-300 px-2 py-1 rounded-md ">Tersedia</span>
                                                    @endif

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    @if(auth()->user()->isAdmin())
                                                    <x-link class="hover:bg-yellow-500" href="{{ route('books.edit', $book) }}">Edit</x-link>
                                                    <form class="inline" action="{{ route('books.destroy', $book) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <x-button-delete class="bg-red-400 hover:bg-red-600" onclick="return confirm('Are you sure delete this?');">Delete</x-button-delete>
                                                    </form>
                                                    @else
                                                        @if(!$book->is_booked)
                                                            <form class="inline" action="{{ route('books.update', $book) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <x-button-delete class="bg-blue-400 hover:bg-blue-600" onclick="return confirm('apakah anda yakin akan meminjam buku ini?');">Pinjam</x-button-delete>
                                                            </form>
                                                        @elseif($book->booking[0] && $book->booking[0]->user_id == auth()->user()->getAuthIdentifier())
                                                            <form class="inline" action="{{ route('books.update', $book) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <x-button-delete class="bg-red-400 hover:bg-blue-600" onclick="return confirm('apakah anda yakin akan meminjam buku ini?');">Kembalikan</x-button-delete>
                                                            </form>
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>
                                <div class="mt-4">
                                    {{ $books->links() }}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
