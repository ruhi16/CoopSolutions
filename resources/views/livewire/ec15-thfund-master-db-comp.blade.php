<div>
    <div class="rounded-lg shadow-sm overflow-hidden">
        <div id="alert-container" style="position: fixed; top: 10px; right: 10px; z-index: 1000;">
            @if (session()->has('success'))
                <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-200 dark:bg-gray-800 dark:text-blue-400" role="alert">
                    <span class="font-bold">Info alert!</span> {{ session('success') }}
                </div>
                <script>
                    setTimeout(function() {
                        document.getElementById('alert-container').innerHTML = '';
                    }, 5000);
                </script>
            @endif
            @if (session()->has('error'))
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-200 dark:bg-gray-800 dark:text-red-400" role="alert">
                    <span class="font-bold">Danger alert!</span> {{ session('error') }}
                </div>
                <script>
                    setTimeout(function() {
                        document.getElementById('alert-container').innerHTML = '';
                    }, 5000);
                </script>
            @endif
        </div>

        <div class="flex justify-between items-center">
            <div class="pb-2">
                <h1 class="text-2xl font-bold text-gray-900"><span class="text-blue-700">Thrift Fund</span> Master Database</h1>
                <p class="text-gray-600 mt-1">Manage thrift fund master database records.</p>
            </div>
            <button 
                wire:click="openModal"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors duration-200">
                <i class="fas fa-plus"></i>
                <span>Add New Record</span>
            </button>
        </div>

        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 rounded-md">
                <tr class="">
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SL</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Operational Amount</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Balance</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($masterDbs as $masterDb)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $masterDb->member->name ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                        @if($masterDb->thfund_operational_amount)
                            ₹{{ number_format($masterDb->thfund_operational_amount, 2) }}
                            <br><small class="text-gray-500">{{ ucfirst($masterDb->thfund_operational_type ?? '') }}</small>
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $masterDb->thfund_operational_type == 'deposit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($masterDb->thfund_operational_type ?? 'N/A') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                        ₹{{ number_format($masterDb->thfund_current_balnce ?? 0, 2) }}
                    </td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                        @if($masterDb->start_at && $masterDb->end_at)
                            {{ \Carbon\Carbon::parse($masterDb->start_at)->format('M Y') }} - {{ \Carbon\Carbon::parse($masterDb->end_at)->format('M Y') }}
                            <br><small class="text-gray-500">{{ $masterDb->no_of_months ?? 0 }} months</small>
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $masterDb->status == 'published' ? 'bg-green-100 text-green-800' : 
                               ($masterDb->status == 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($masterDb->status ?? 'N/A') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div class="flex space-x-2">
                            <button wire:click="openModal({{ $masterDb->id }})"
                                class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="confirmDelete({{ $masterDb->id }})" 
                                class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Master DB Modal -->
    <div id="masterDbModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 {{ $showMasterDbModal ? 'block' : 'hidden' }}">
        <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-3xl mx-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">{{ $masterDbId ? 'Edit' : 'New' }} Master Database Record</h2>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form wire:submit.prevent="saveMasterDb" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Record Name *</label>
                        <input wire:model="name" type="text" id="name" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('name')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="memberId" class="block text-sm font-medium text-gray-700 mb-2">Member *</label>
                        <select wire:model="memberId" id="memberId" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Member</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                        @error('memberId')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="mb-4">
                        <label for="operationalAmount" class="block text-sm font-medium text-gray-700 mb-2">Operational Amount</label>
                        <input wire:model="operationalAmount" type="number" step="0.01" id="operationalAmount" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div class="mb-4">
                        <label for="operationalType" class="block text-sm font-medium text-gray-700 mb-2">Operation Type</label>
                        <select wire:model="operationalType" id="operationalType" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Type</option>
                            <option value="deposit">Deposit</option>
                            <option value="withdrawal">Withdrawal</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="operationalDate" class="block text-sm font-medium text-gray-700 mb-2">Operation Date</label>
                        <input wire:model="operationalDate" type="date" id="operationalDate" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="currentBalance" class="block text-sm font-medium text-gray-700 mb-2">Current Balance</label>
                        <input wire:model="currentBalance" type="number" step="0.01" id="currentBalance" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div class="mb-4">
                        <label for="numberOfMonths" class="block text-sm font-medium text-gray-700 mb-2">Number of Months</label>
                        <input wire:model="numberOfMonths" type="number" id="numberOfMonths" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="startDate" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input wire:model="startDate" type="date" id="startDate" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div class="mb-4">
                        <label for="endDate" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input wire:model="endDate" type="date" id="endDate" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('endDate')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select wire:model="status" id="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="archived">Archived</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="isActive" class="block text-sm font-medium text-gray-700 mb-2">Active</label>
                        <select wire:model="isActive" id="isActive" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea wire:model="description" id="description" rows="2"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>

                <div class="mb-4">
                    <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                    <input wire:model="remarks" type="text" id="remarks" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    {{ $masterDbId ? 'Update' : 'Save' }} Record
                </button>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 {{ $showDeleteConfirmModal ? 'block' : 'hidden' }}">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-gray-900">Confirm Deletion</h3>
                </div>
            </div>

            <div class="mb-6">
                <p class="text-sm text-gray-600">
                    Are you sure you want to delete this thrift fund master database record? This action cannot be undone.
                </p>
            </div>

            <div class="flex justify-end space-x-3">
                <button wire:click="cancelDelete"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors duration-200">
                    Cancel
                </button>
                <button wire:click="deleteMasterDb"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors duration-200">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>