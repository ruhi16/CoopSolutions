<div class="bg-white rounded-lg shadow-sm p-6">
    <!-- Flash Messages -->
    @if (session()->has('message'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-4 flex items-center"
        role="alert">
        <i class="fas fa-check-circle mr-2"></i>
        <span>{{ session('message') }}</span>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-4 flex items-center"
        role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-handshake text-blue-600 mr-3"></i>
                Loan Assignments
            </h1>
            <p class="text-gray-600 mt-1">Manage loan assignments to approved loan requests</p>
        </div>
        <button wire:click="openModal"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors duration-200 shadow-md">
            <i class="fas fa-plus"></i>
            <span>Add Loan Assignment</span>
        </button>
    </div>

    <!-- Search and Filters -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" wire:model.debounce.300ms="search" placeholder="Search by member or loan scheme..."
                class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div class="flex space-x-2">
            <select wire:model="perPage"
                class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
            </select>
        </div>
    </div>

    <!-- Loan Assignments Table -->
    <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('id')">
                            <div class="flex items-center">
                                <span>ID</span>
                                @if ($sortField === 'id')
                                <i
                                    class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1 text-blue-500"></i>
                                @else
                                <i class="fas fa-sort ml-1 text-gray-300"></i>
                                @endif
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Member
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Loan Request
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('loan_amount')">
                            <div class="flex items-center">
                                <span>Amount</span>
                                @if ($sortField === 'loan_amount')
                                <i
                                    class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1 text-blue-500"></i>
                                @else
                                <i class="fas fa-sort ml-1 text-gray-300"></i>
                                @endif
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Period
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('emi_amount')">
                            <div class="flex items-center">
                                <span>EMI</span>
                                @if ($sortField === 'emi_amount')
                                <i
                                    class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1 text-blue-500"></i>
                                @else
                                <i class="fas fa-sort ml-1 text-gray-300"></i>
                                @endif
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('is_active')">
                            <div class="flex items-center">
                                <span>Status</span>
                                @if ($sortField === 'is_active')
                                <i
                                    class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1 text-blue-500"></i>
                                @else
                                <i class="fas fa-sort ml-1 text-gray-300"></i>
                                @endif
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($loanAssigns as $loanAssign)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $loanAssign->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $loanAssign->member->name ?? 'N/A' }}
                            </div>
                            <div class="text-sm text-gray-500">{{ $loanAssign->member->memberType->name ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $loanAssign->loanScheme->name ?? 'N/A' }}
                            </div>
                            <div class="text-sm text-gray-500">Request #{{ $loanAssign->loan_request_id }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ₹{{ number_format($loanAssign->loan_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($loanAssign->start_date)->format('M Y') }} - {{
                            \Carbon\Carbon::parse($loanAssign->end_date)->format('M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ₹{{ number_format($loanAssign->emi_amount, 2) }}
                            <div class="text-xs text-gray-500">on {{ $loanAssign->emi_payment_date }}th</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($loanAssign->is_active)
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                            @else
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Inactive
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <button wire:click="edit({{ $loanAssign->id }})"
                                    class="text-blue-600 hover:text-blue-900 p-1 rounded-full hover:bg-blue-100 transition-colors duration-150"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="openDeleteModal({{ $loanAssign->id }})"
                                    class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-100 transition-colors duration-150"
                                    title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">No loan assignments found</h3>
                                <p class="text-gray-500">Get started by creating a new loan assignment.</p>
                                <button wire:click="openModal"
                                    class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors duration-200">
                                    <i class="fas fa-plus"></i>
                                    <span>Add Loan Assignment</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($loanAssigns->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $loanAssigns->links() }}
        </div>
        @endif
    </div>

    <!-- Add/Edit Modal -->
    @if($isModalOpen)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-screen overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-900">
                    {{ $editingId ? 'Edit Loan Assignment' : 'Create Loan Assignment' }}
                </h2>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form wire:submit.prevent="save" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Organization (Disabled) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Organization *</label>
                        <div class="px-3 py-2 bg-gray-100 rounded-lg border border-gray-300 text-gray-700">
                            {{ App\Models\Ec01Organisation::find($organisation_id)->name ?? 'Organization' }}
                        </div>
                        <input type="hidden" wire:model="organisation_id">
                    </div>

                    <!-- Loan Request Selection -->
                    <div class="md:col-span-2">
                        <label for="loan_request_id" class="block text-sm font-medium text-gray-700 mb-2">Select Loan
                            Request *</label>
                        <select wire:model="loan_request_id" id="loan_request_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('loan_request_id') border-red-500 @enderror"
                            {{ $editingId ? 'disabled' : '' }}>
                            <option value="">Select a loan request</option>
                            @foreach($allLoanRequests as $loanRequest)
                            <option value="{{ $loanRequest->id }}">
                                #{{ $loanRequest->id }} - {{ $loanRequest->member->name ?? 'N/A' }}
                                (₹{{ number_format($loanRequest->approved_loan_amount ?: $loanRequest->req_loan_amount,
                                2) }} for {{ $loanRequest->time_period_months }} months)
                                - Status: {{ ucfirst($loanRequest->status) }}
                            </option>
                            @endforeach
                        </select>
                        @error('loan_request_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Select one of the loan requests to auto-populate member
                            and loan details</p>
                    </div>

                    <!-- Member (Auto-filled) -->
                    <div>
                        <label for="member_id" class="block text-sm font-medium text-gray-700 mb-2">Member *</label>
                        <div class="px-3 py-2 bg-gray-100 rounded-lg border border-gray-300 text-gray-700">
                            {{ $member_id ? App\Models\Ec04Member::find($member_id)->name ?? 'N/A' : 'Select a loan
                            request first' }}
                        </div>
                        <input type="hidden" wire:model="member_id">
                        @error('member_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Loan Scheme (Auto-filled) -->
                    <div>
                        <label for="loan_scheme_id" class="block text-sm font-medium text-gray-700 mb-2">Loan Scheme
                            *</label>
                        <div class="px-3 py-2 bg-gray-100 rounded-lg border border-gray-300 text-gray-700">
                            {{ $loan_scheme_id ? App\Models\Ec06LoanScheme::find($loan_scheme_id)->name ?? 'N/A' :
                            'Select a loan request first' }}
                        </div>
                        <input type="hidden" wire:model="loan_scheme_id">
                        @error('loan_scheme_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Loan Amount (Editable) -->
                    <div>
                        <label for="loan_amount" class="block text-sm font-medium text-gray-700 mb-2">Loan Amount
                            *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₹</span>
                            </div>
                            <input type="number" step="0.01" wire:model="loan_amount" id="loan_amount"
                                class="pl-8 w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('loan_amount') border-red-500 @enderror"
                                placeholder="0.00">
                        </div>
                        @error('loan_amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date
                            *</label>
                        <input type="date" wire:model="start_date" id="start_date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('start_date') border-red-500 @enderror">
                        @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date *</label>
                        <input type="date" wire:model="end_date" id="end_date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('end_date') border-red-500 @enderror">
                        @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- EMI Payment Date -->
                    <div>
                        <label for="emi_payment_date" class="block text-sm font-medium text-gray-700 mb-2">EMI Payment
                            Date *</label>
                        <div class="relative">
                            <input type="number" min="1" max="31" wire:model="emi_payment_date" id="emi_payment_date"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('emi_payment_date') border-red-500 @enderror"
                                placeholder="Day of month (1-31)">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">th</span>
                            </div>
                        </div>
                        @error('emi_payment_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- EMI Amount -->
                    <div>
                        <label for="emi_amount" class="block text-sm font-medium text-gray-700 mb-2">EMI Amount
                            *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₹</span>
                            </div>
                            <input type="number" step="0.01" wire:model="emi_amount" id="emi_amount"
                                class="pl-8 w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('emi_amount') border-red-500 @enderror"
                                placeholder="0.00">
                        </div>
                        @error('emi_amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <button type="button" wire:click="calculateEMI"
                            class="mt-1 text-xs text-blue-600 hover:text-blue-800">
                            Calculate EMI
                        </button>
                    </div>

                    <!-- Loan Scheme Details Selection -->
                    @if(count($loanSchemeDetails) > 0)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Loan Scheme Details *</label>
                        <div class="border border-gray-300 rounded-lg p-4">
                            <p class="text-sm text-gray-500 mb-3">Select all applicable loan scheme details:</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($loanSchemeDetails as $detail)
                                <div class="flex items-center pl-3">
                                    <input type="checkbox" wire:model="selectedLoanSchemeDetails"
                                        value="{{ $detail->id }}" id="detail_{{ $detail->id }}"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="detail_{{ $detail->id }}" class="ml-2 block text-sm text-gray-900">
                                        {{ $detail->loanSchemeFeature->name ?? 'Feature' }}:
                                        @if($detail->min_amount)
                                        Min: ₹{{ number_format($detail->min_amount, 2) }}
                                        @endif
                                        @if($detail->max_amount)
                                        Max: ₹{{ number_format($detail->max_amount, 2) }}
                                        @endif
                                        @if($detail->main_interest_rate)
                                        Rate: {{ $detail->main_interest_rate }}%
                                        @endif
                                        @if($detail->terms_in_month)
                                        Term: {{ $detail->terms_in_month }} months
                                        @endif
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @error('selectedLoanSchemeDetails')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif

                    <!-- Remarks -->
                    <div class="md:col-span-2">
                        <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                        <textarea wire:model="remarks" id="remarks" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('remarks') border-red-500 @enderror"
                            placeholder="Additional notes about this loan assignment..."></textarea>
                        @error('remarks')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="md:col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="is_active" id="is_active"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                Active Loan Assignment
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" wire:click="closeModal"
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-md">
                        {{ $editingId ? 'Update Assignment' : 'Create Assignment' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($isDeleteModalOpen)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-900">Confirm Delete</h2>
                <button wire:click="closeDeleteModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Delete Loan Assignment</h3>
                        <p class="text-sm text-gray-500">Are you sure you want to delete this loan assignment? This
                            action cannot be undone.</p>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" wire:click="closeDeleteModal"
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="button" wire:click="delete"
                        class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors duration-200">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>