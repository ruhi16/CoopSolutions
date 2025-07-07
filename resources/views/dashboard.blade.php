<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> --}}

    {{-- <div class="p-0"> --}}
        {{-- <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    You're logged in!
                    This is the story
                </div>
            </div>
        </div>
         --}}

        @livewire('ec001-main-layout')
        {{-- @livewire('ec01-organisation') --}}

    {{-- </div> --}}

    {{-- Hello --}}
</x-app-layout>
