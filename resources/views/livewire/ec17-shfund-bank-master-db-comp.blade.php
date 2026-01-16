<div>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold flex items-center">
                    <i class="fas fa-database mr-2"></i>
                    Bank Share Fund Master DB
                </h2>
                <button 
                    wire:click="openModal()"
                    class="bg-white text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-lg flex items-center transition duration-200"
                >
                    <i class="fas fa-plus mr-2"></i>
                    Add New
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bank</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan Assign</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Previous Loan Balance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Previous Share Balance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Operational Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Operational Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Balance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($masterDbs as $masterDb)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $masterDb->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $masterDb->bank ? $masterDb->bank->name : 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $masterDb->loanAssign ? $masterDb->loanAssign->id : 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($masterDb->bank_loan_previous_balnce ?? 0, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($masterDb->bank_share_previous_balnce ?? 0, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($masterDb->bank_share_operational_amount ?? 0, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $masterDb->bank_share_operational_type === 'deposit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($masterDb->bank_share_operational_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($masterDb->bank_share_current_balnce ?? 0, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $masterDb->status === 'published' ? 'bg-green-100 text-green-800' : 
                                       ($masterDb->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($masterDb->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button 
                                    wire:click="openModal({{ $masterDb->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button 
                                    wire:click="confirmDelete({{ $masterDb->id }})"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-4 text-center text-gray-500">
                                No records found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showMasterDbModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center pb-3 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $masterDbId ? 'Edit' : 'Create' }} Bank Share Fund Master DB
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
                    <form wire:submit.prevent="saveMasterDb" class="mt-4">
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
                                <label class="block text-sm font-medium text-gray-700">Bank *</label>
                                <select 
                                    wire:model="bankId"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('bankId') border-red-500 @enderror"
                                >
                                    <option value="">Select Bank</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                                @error('bankId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Loan Assign *</label>
                                <select 
                                    wire:model="loanAssignId"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('loanAssignId') border-red-500 @enderror"
                                >
                                    <option value="">Select Loan Assign</option>
                                    @foreach($loanAssigns as $loanAssign)
                                        <option value="{{ $loanAssign->id }}">ID: {{ $loanAssign->id }} - {{ $loanAssign->member->name ?? 'N/A' }}</option>
                                    @endforeach
                                </select>
                                @error('loanAssignId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Previous Loan Balance</label>
                                <input 
                                    type="number" 
                                    step="0.01"
                                    wire:model="previousLoanBalance"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('previousLoanBalance') border-red-500 @enderror"
                                />
                                @error('previousLoanBalance') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Previous Share Balance</label>
                                <input 
                                    type="number" 
                                    step="0.01"
                                    wire:model="previousShareBalance"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('previousShareBalance') border-red-500 @enderror"
                                />
                                @error('previousShareBalance') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Operational Amount</label>
                                <input 
                                    type="number" 
                                    step="0.01"
                                    wire:model="operationalAmount"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('operationalAmount') border-red-500 @enderror"
                                />
                                @error('operationalAmount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Operational Type</label>
                                <select 
                                    wire:model="operationalType"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                                >
                                    <option value="">Select Type</option>
                                    <option value="deposit">Deposit</option>
                                    <option value="withdrawal">Withdrawal</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Operational Date</label>
                                <input 
                                    type="datetime-local" 
                                    wire:model="operationalDate"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                                />
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Current Balance</label>
                                <input 
                                    type="number" 
                                    step="0.01"
                                    wire:model="currentBalance"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('currentBalance') border-red-500 @enderror"
                                />
                                @error('currentBalance') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status *</label>
                                <select 
                                    wire:model="status"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('status') border-red-500 @enderror"
                                >
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                    <option value="archived">Archived</option>
                                </select>
                                @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Is Active</label>
                                <input 
                                    type="checkbox" 
                                    wire:model="isActive"
                                    class="mt-1"
                                />
                            </div>
                        </div>
                        
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
                                {{ $masterDbId ? 'Update' : 'Create' }}
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
                            wire:click="deleteMasterDb()"
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