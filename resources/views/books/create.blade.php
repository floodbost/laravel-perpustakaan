<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Book') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <x-validation-errors class="mb-4" />
                    <x-success-message />

                    <form method="POST" action="{{ route('books.store') }}">
                        @method('POST')
                        @csrf


                        <div class="grid grid-cols-3 gap-6">
                            <div class="h-64 grid">
                                @if(isset($books['cover']))
                                    <img src="{{ $books['cover']['medium'] }}" alt="cover" class="object-contain h-50" />
                                    <input type="hidden" value="{{ $books['cover']['medium'] }}" name="cover_path" />
                                @else
                                    <div class="border flex ">
                                        <div class="m-auto">
                                            No cover
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="grid grid-rows-2 gap-6">
                                <div>
                                    <x-label for="isbn" :value="__('ISBN')" />
                                    <x-input id="isbn" class="block mt-1 w-full" type="text" name="isbn" value="{{ request('isbn') }}" autofocus />
                                </div>
                                <div>
                                    <x-label for="title" :value="__('Title')" />
                                    <x-input id="title" class="block mt-1 w-full" type="text" name="title" value="{{ $books['title'] }}" autofocus />
                                </div>
                                <div>
                                    <x-label for="author" :value="__('Author')" />
                                    <x-input id="author" class="block mt-1 w-full" type="text" name="author" value="{{ $books['authors'][0]['name'] }}" autofocus />
                                </div>
                            </div>
                            <div class="grid grid-rows-2 gap-6">
                                <div>
                                    <x-label for="publisher" :value="__('Publisher')" />
                                    <x-input id="publisher" class="block mt-1 w-full" type="text" name="publisher" value="{{ $books['publishers'][0]['name'] }}" autofocus />
                                </div>
                                <div>
                                    <x-label for="publisher-date" :value="__('Publish Date')" />
                                    <x-input id="publisher-date" class="block mt-1 w-full" type="text" name="publish_date" value="{{ $books['publish_date'] }}" autofocus />
                                </div>
                                <div>
                                    <x-label for="description" :value="__('Description')" />
                                    <x-input id="description" class="block mt-1 w-full" type="text" name="description" value="{{ $books['notes'] ?? '' }}" autofocus />
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ml-3">
                                {{ __('Create') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
