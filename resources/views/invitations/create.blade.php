<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invite User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('invitations.store') }}" class="max-w-xl">
                        @csrf
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                           
                            <select name="email" id="email" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                <option value="" disabled selected>Select an email</option>
                                @foreach($emaildatas as $emaildata)
                                    <option value="{{ $emaildata->email }}" {{ old('email') == $emaildata->email ? 'selected' : '' }}>
                                        {{ $emaildata->email }}
                                    </option>
                                @endforeach
                            </select>

                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                       
                        <div class="mt-4">
                            <x-input-label for="company_id" :value="__('Company')" />
                            
                            @if(Auth::user()->isSuperAdmin())
                                <select name="company_id" id="company_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                    <option value="" disabled selected>Select a company</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <select name="company_id" id="company_id" class="border-gray-300 bg-gray-100 rounded-md shadow-sm block mt-1 w-full" required>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" selected>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                            <x-input-error :messages="$errors->get('company_id')" class="mt-2" />
                        </div>
                       

                       <div class="mt-4">
                            <x-input-label for="role" :value="__('Role')" />
                            <select name="role" id="role" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                <option value="" disabled selected>Select a role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                        {{ $role }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div class="flex items-center mt-6">
                            <x-primary-button>
                                {{ __('Send Invitation') }}
                            </x-primary-button>

                            @if (session('success'))
                                <p class="ms-4 text-sm text-green-600 font-medium">{{ session('success') }}</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>