<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                @foreach( $results as $result)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg mt-2 text-gray-600 dark:text-gray-400 text-sm">
                        <a href="{{ route('story.view', ['story' =>  $result]) }}">
                            {{ $result->published_at }} - <b>{{ $result->id }}</b> -    {{ $result->headline }}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
