<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Investment Details') }}
            </h2>
            <a href="{{ route('investments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Investment Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">UID</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $investment->uid }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">API ID</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $investment->api_id ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Investor</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <a href="{{ route('investors.show', $investment->investor) }}" class="text-blue-600 hover:text-blue-900">
                                    {{ $investment->investor->name }}
                                </a>
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Investor Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $investment->investor->email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fund</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <a href="{{ route('funds.show', $investment->fund) }}" class="text-blue-600 hover:text-blue-900">
                                    {{ $investment->fund->name }}
                                </a>
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Start Date</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $investment->start_date->format('Y-m-d') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Capital Amount</label>
                            <p class="mt-1 text-sm text-gray-900 font-semibold text-lg">${{ number_format($investment->capital_amount, 2) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <p class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $investment->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($investment->status) }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Created At</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $investment->created_at->format('Y-m-d H:i:s') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Updated At</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $investment->updated_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

