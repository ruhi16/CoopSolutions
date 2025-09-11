<div>
    <div class="p-6 bg-white rounded-lg shadow-sm mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Loan Requests</h2>
            <div class="flex items-center space-x-2">
                <input type="text" wire:model.debounce.300ms="search" placeholder="Search..."
                       class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition ease-in-out duration-150 p-2 text-sm" />
                <button wire:click="create()"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center space-x-2 transition-colors duration-200">
                    <i class="fas fa-plus"></i>
                    <span>Add New</span>
                </button>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Period</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($loanRequests as $loanRequest)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loanRequest->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loanRequest->req_loan_amount }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loanRequest->time_period_months }} months</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loanRequest->req_date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $loanRequest->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $loanRequest->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="edit({{ $loanRequest->id }})" class="text-indigo-600 hover:text-indigo-900 mx-2">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button wire:click="delete({{ $loanRequest->id }})" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No loan requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $loanRequests->links() }}
        </div>
    </div>

    <!-- CRUD Modal -->
    @if($isOpen)
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ $loanRequestId ? 'Edit Loan Request' : 'Create New Loan Request' }}
                            </h3>
                            <div class="mt-2">
                                <form wire:submit.prevent="store">
                                    <div class="mb-4">
                                        <label for="req_loan_scheme_id" class="block text-sm font-medium text-gray-700">Loan Scheme</label>
                                        <select id="req_loan_scheme_id" wire:model="req_loan_scheme_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="">Select a Loan Scheme</option>
                                            @foreach($loanSchemas as $schema)
                                                <option value="{{ $schema->id }}">{{ $schema->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('req_loan_scheme_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="req_loan_amount" class="block text-sm font-medium text-gray-700">Loan Amount</label>
                                        <input type="number" step="0.01" id="req_loan_amount" wire:model="req_loan_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @error('req_loan_amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="time_period_months" class="block text-sm font-medium text-gray-700">Time Period (Months)</label>
                                        <input type="number" id="time_period_months" wire:model="time_period_months" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @error('time_period_months') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                            Save
                                        </button>
                                        <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Deletion Confirmation Modal -->
    @if($confirmingDeletion)
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Confirm Deletion
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Are you sure you want to delete this loan request?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="destroy()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                    <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
