<div>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold flex items-center">
                    <i class="fas fa-money-bill-wave mr-2"></i>
                    Bank Loan Borrowed
                </h2>
                <button 
                    wire:click="openModal()"
                    class="bg-white text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-lg flex items-center transition duration-200"
                >
                    <i class="fas fa-plus mr-2"></i>
                    New Bank Loan Borrow
                </button>
            </div>
        </div>

        <!-- Alerts -->
        @if(session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan Scheme</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Borrowed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Installment Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. of Installments</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($borrowedLoans as $loan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $loan->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $loan->loanScheme ? $loan->loanScheme->name : 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($loan->loan_borrowed_amount ?? 0, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $loan->loan_borrowed_date ? date('d M Y', strtotime($loan->loan_borrowed_date)) : 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($loan->installment_amount ?? 0, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $loan->no_of_installments ?? 0 }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $loan->status === 'running' ? 'bg-green-100 text-green-800' : 
                                       ($loan->status === 'completed' ? 'bg-blue-100 text-blue-800' : 
                                          ($loan->status === 'upcoming' ? 'bg-yellow-100 text-yellow-800' : 
                                             ($loan->status === 'suspended' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button 
                                    wire:click="openModal({{ $loan->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button 
                                    wire:click="confirmDelete({{ $loan->id }})"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <!-- Expandable row for specifications -->
                        @if(isset($expandedLoanId) && $expandedLoanId == $loan->id)
                        <tr class="bg-gray-50">
                            <td colspan="8" class="px-6 py-4">
                                <div class="mb-4">
                                    <h4 class="text-md font-semibold text-gray-700 mb-2">Specifications:</h4>
                                    @if($loan->specifications->count() > 0)
                                        <div class="border rounded-lg overflow-hidden">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Particular</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Is Regular</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Effected On</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($loan->specifications as $spec)
                                                    <tr>
                                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $spec->particular->name ?? 'N/A' }}</td>
                                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $spec->bank_loan_schema_particular_value }}</td>
                                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                {{ $spec->is_regular ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                                {{ $spec->is_regular ? 'Yes' : 'No' }}
                                                            </span>
                                                        </td>
                                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $spec->effected_on ? date('d M Y', strtotime($spec->effected_on)) : 'N/A' }}</td>
                                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                {{ $spec->status === 'active' ? 'bg-green-100 text-green-800' : 
                                                                   ($spec->status === 'inactive' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                                                {{ ucfirst($spec->status) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-gray-500 italic">No specifications assigned</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="8" class="px-6 py-2 bg-gray-50">
                                <button 
                                    wire:click="toggleSpecifications({{ $loan->id }})"
                                    class="text-sm text-blue-600 hover:text-blue-800 flex items-center"
                                >
                                    <i class="fas fa-chevron-down mr-1 {{ isset($expandedLoanId) && $expandedLoanId == $loan->id ? 'transform rotate-180' : '' }}"></i>
                                    <span>{{ isset($expandedLoanId) && $expandedLoanId == $loan->id ? 'Hide Specifications' : 'Show Specifications' }}</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                No records found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showBorrowedLoanModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center pb-3 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $borrowedLoanId ? 'Edit' : 'Create' }} Bank Loan Borrowed
                        </h3>
                        <button 
                            wire:click="closeModal()"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    @if(session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
                    
                    <form wire:submit.prevent="saveBorrowedLoan" class="mt-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name *</label>
                                <input 
                                    type="text" 
                                    wire:model="name"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('name') border-red-500 @enderror"
                                />
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <input 
                                    type="text" 
                                    wire:model="description"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                                />
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Loan Scheme *</label>
                                <select 
                                    wire:change="loadSchemeSpecifications($event.target.value)"
                                    wire:model="selectedSchemeId"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('selectedSchemeId') border-red-500 @enderror"
                                >
                                    <option value="">Select Loan Scheme</option>
                                    @foreach($loanSchemes as $scheme)
                                        <option value="{{ $scheme->id }}">{{ $scheme->name }} ({{ $scheme->bank->name }})</option>
                                    @endforeach
                                </select>
                                @error('selectedSchemeId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Loan Assign Ref ID</label>
                                <input 
                                    type="number" 
                                    wire:model="loanAssignRefId"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('loanAssignRefId') border-red-500 @enderror"
                                />
                                @error('loanAssignRefId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Amount Borrowed *</label>
                                <input 
                                    type="number" 
                                    step="0.01"
                                    wire:model="loanBorrowedAmount"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('loanBorrowedAmount') border-red-500 @enderror"
                                />
                                @error('loanBorrowedAmount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Borrowed Date</label>
                                <input 
                                    type="date" 
                                    wire:model="loanBorrowedDate"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('loanBorrowedDate') border-red-500 @enderror"
                                />
                                @error('loanBorrowedDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status *</label>
                                <select 
                                    wire:model="status"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('status') border-red-500 @enderror"
                                >
                                    <option value="upcoming">Upcoming</option>
                                    <option value="running">Running</option>
                                    <option value="completed">Completed</option>
                                    <option value="suspended">Suspended</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Installment Amount</label>
                                <input 
                                    type="number" 
                                    step="0.01"
                                    wire:model="installmentAmount"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('installmentAmount') border-red-500 @enderror"
                                />
                                @error('installmentAmount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. of Installments</label>
                                <input 
                                    type="number" 
                                    wire:model="noOfInstallments"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('noOfInstallments') border-red-500 @enderror"
                                />
                                @error('noOfInstallments') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Previous Balance</label>
                                <input 
                                    type="number" 
                                    step="0.01"
                                    wire:model="bankLoanBorrowedPreviousBalance"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('bankLoanBorrowedPreviousBalance') border-red-500 @enderror"
                                />
                                @error('bankLoanBorrowedPreviousBalance') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <!-- Loan Scheme Specifications Sub-table -->
                        @if($selectedSchemeId)
                            <div class="mt-6">
                                <h3 class="text-md font-semibold text-gray-700 mb-3">Loan Scheme Specifications</h3>
                                <div class="border rounded-lg overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Particular</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Is Regular (Optional)</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Selected</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($schemeSpecifications as $spec)
                                                <tr>
                                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $spec->particular->name ?? 'N/A' }}</td>
                                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $spec->bank_loan_schema_particular_value }}</td>
                                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            {{ $spec->is_regular ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                            {{ $spec->is_regular ? 'Yes' : 'No' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                                        <input 
                                                            type="checkbox" 
                                                            wire:click="toggleSpecification({{ $spec->id }})"
                                                            {{ in_array($spec->id, $selectedSpecifications) ? 'checked' : '' }}
                                                            {{ !$spec->is_regular ? 'disabled' : '' }}
                                                            class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                                                        />
                                                        @if(!$spec->is_regular)
                                                            <span class="text-xs text-red-500 ml-2">(Mandatory)</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="px-4 py-2 text-center text-gray-500">
                                                        No specifications found for this loan scheme
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                        
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Remarks</label>
                            <textarea 
                                wire:model="remarks"
                                rows="3"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                            ></textarea>
                        </div>
                        
                        <div class="items-center gap-2 mt-6">
                            <button 
                                type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none"
                            >
                                {{ $borrowedLoanId ? 'Update' : 'Create' }}
                            </button>
                            <button 
                                type="button"
                                wire:click="closeModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteConfirmModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/3 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center pb-3 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">Confirm Delete</h3>
                        <button 
                            wire:click="cancelDelete()"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="p-4">
                        <p class="text-gray-700">Are you sure you want to delete this record?</p>
                    </div>
                    
                    <div class="flex justify-end gap-2 p-4">
                        <button 
                            wire:click="cancelDelete()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none"
                        >
                            Cancel
                        </button>
                        <button 
                            wire:click="deleteBorrowedLoan()"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>