<div>
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Loan Requests Management</h1>
                <p class="text-gray-600 mt-1">Manage loan applications from members</p>
            </div>
            <button wire:click="create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors duration-200">
                <i class="fas fa-plus"></i>
                <span>New Loan Request</span>
            </button>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input 
                    type="text" 
                    id="search" 
                    wire:model.debounce.300ms="search" 
                    placeholder="Search by member name or code..." 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select 
                    id="status" 
                    wire:model="selectedStatus" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="closed">Closed</option>
                    <option value="expired">Expired</option>
                    <option value="overdue">Overdue</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div class="flex items-end">
                <button wire:click="$set('search', ''); $set('selectedStatus', '')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition-colors duration-200">
                    Reset Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Status Messages -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
            <button class="absolute top-0 right-0 p-3" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Loan Requests Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Member
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Loan Scheme
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Requested Amount
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Request Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Approved Amount
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($loanRequests as $request)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $request->member->name ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $request->member->member_code ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $request->loanScheme->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">₹{{ number_format($request->req_loan_amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($request->req_date)->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'cancelled' => 'bg-gray-100 text-gray-800',
                                        'closed' => 'bg-blue-100 text-blue-800',
                                        'expired' => 'bg-orange-100 text-orange-800',
                                        'overdue' => 'bg-red-100 text-red-800',
                                        'completed' => 'bg-purple-100 text-purple-800',
                                    ];
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($request->approved_loan_amount)
                                        ₹{{ number_format($request->approved_loan_amount, 2) }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button wire:click="view({{ $request->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button wire:click="edit({{ $request->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button wire:click="confirmDelete({{ $request->id }})" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                No loan requests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $loanRequests->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div x-data="{ open: @entangle('isModalOpen') }" x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-4xl mx-4 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">{{ $editingId ? 'Edit Loan Request' : 'Create New Loan Request' }}</h2>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label for="member_id" class="block text-sm font-medium text-gray-700 mb-2">Member</label>
                        <select 
                            id="member_id" 
                            wire:model="member_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                            <option value="">Select Member</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->member_code }})</option>
                            @endforeach
                        </select>
                        @error('member_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-span-2">
                        <label for="req_loan_scheme_id" class="block text-sm font-medium text-gray-700 mb-2">Loan Scheme</label>
                        <select 
                            id="req_loan_scheme_id" 
                            wire:model="req_loan_scheme_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                            <option value="">Select Loan Scheme</option>
                            @foreach($loanSchemes as $scheme)
                                <option value="{{ $scheme->id }}">{{ $scheme->name }}</option>
                            @endforeach
                        </select>
                        @error('req_loan_scheme_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="req_loan_amount" class="block text-sm font-medium text-gray-700 mb-2">Requested Loan Amount</label>
                        <input 
                            type="number" 
                            id="req_loan_amount" 
                            wire:model="req_loan_amount" 
                            step="0.01" 
                            min="0" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                        @error('req_loan_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="time_period_months" class="block text-sm font-medium text-gray-700 mb-2">Time Period (Months)</label>
                        <input 
                            type="number" 
                            id="time_period_months" 
                            wire:model="time_period_months" 
                            min="1" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                        @error('time_period_months') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="req_date" class="block text-sm font-medium text-gray-700 mb-2">Request Date</label>
                        <input 
                            type="date" 
                            id="req_date" 
                            wire:model="req_date" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                        @error('req_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select 
                            id="status" 
                            wire:model="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            @foreach($statusOptions as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-span-2">
                        <label for="status_instructions" class="block text-sm font-medium text-gray-700 mb-2">Status Instructions</label>
                        <textarea 
                            id="status_instructions" 
                            wire:model="status_instructions" 
                            rows="3" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter any instructions or reasons for status change..."
                        ></textarea>
                        @error('status_instructions') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="approved_loan_amount" class="block text-sm font-medium text-gray-700 mb-2">Approved Loan Amount</label>
                        <input 
                            type="number" 
                            id="approved_loan_amount" 
                            wire:model="approved_loan_amount" 
                            step="0.01" 
                            min="0" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        @error('approved_loan_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="approved_loan_amount_date" class="block text-sm font-medium text-gray-700 mb-2">Approval Date</label>
                        <input 
                            type="date" 
                            id="approved_loan_amount_date" 
                            wire:model="approved_loan_amount_date" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        @error('approved_loan_amount_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-span-2">
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                id="member_concent" 
                                wire:model="member_concent" 
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            >
                            <label for="member_concent" class="ml-2 block text-sm text-gray-900">Member Consent</label>
                        </div>
                        @error('member_concent') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-span-2">
                        <label for="member_concent_note" class="block text-sm font-medium text-gray-700 mb-2">Member Consent Note</label>
                        <textarea 
                            id="member_concent_note" 
                            wire:model="member_concent_note" 
                            rows="2" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter consent details..."
                        ></textarea>
                        @error('member_concent_note') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="member_concent_date" class="block text-sm font-medium text-gray-700 mb-2">Consent Date</label>
                        <input 
                            type="date" 
                            id="member_concent_date" 
                            wire:model="member_concent_date" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        @error('member_concent_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-span-2">
                        <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>







                        </div>
                    </div>
                </div>