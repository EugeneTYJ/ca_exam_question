<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Investor Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('investors.index') }}" class="text-blue-600 hover:text-blue-800">
                            ← Back to Investors
                        </a>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-2xl font-bold mb-4">{{ $investor->name }}</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">API ID</p>
                                <p class="text-lg font-semibold">{{ $investor->api_id ?? 'N/A' }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="text-lg font-semibold">{{ $investor->email }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Contact Number</p>
                                <p class="text-lg font-semibold">{{ $investor->contact_number }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Total Investments</p>
                                <p class="text-lg font-semibold">{{ $investor->investments->count() }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Created At</p>
                                <p class="text-lg font-semibold">{{ $investor->created_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Updated At</p>
                                <p class="text-lg font-semibold">{{ $investor->updated_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2 mb-6">
                        <a href="{{ route('investors.edit', $investor) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit
                        </a>
                        <form action="{{ route('investors.destroy', $investor) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this investor?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Delete
                            </button>
                        </form>
                    </div>

                    @if($investor->investments->count() > 0)
                        <div class="mt-8">
                            <h4 class="text-xl font-bold mb-4">Investments</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">UID</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fund</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capital Amount</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($investor->investments as $investment)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $investment->uid }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <a href="{{ route('funds.show', $investment->fund) }}" class="text-blue-600 hover:text-blue-800">
                                                        {{ $investment->fund->name }}
                                                    </a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $investment->start_date->format('Y-m-d') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    ${{ number_format($investment->capital_amount, 2) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $investment->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                        {{ ucfirst($investment->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="mt-8 text-center text-gray-500">
                            <p>No investments found for this investor.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

