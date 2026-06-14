<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Short URL') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <header class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Shorten a New URL') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Paste your long link below to generate a short code.') }}
                        </p>
                    </header>

                    <form method="POST" action="{{ route('short-urls.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="original_url" :value="__('Original URL')" />
                            <x-text-input id="original_url" name="original_url" type="url" class="mt-1 block w-full" :value="old('original_url')" required autofocus placeholder="https://google.com" />
                            <x-input-error class="mt-2" :messages="$errors->get('original_url')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Generate') }}</x-primary-button>
                            <a href="{{ route('short-urls.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>