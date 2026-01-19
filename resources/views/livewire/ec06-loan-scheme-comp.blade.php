<div>
    <div class="rounded-lg shadow-sm overflow-hidden">
        <div id="alert-container" style="position: fixed; top: 10px; right: 10px; z-index: 1000;">
            @if (session()->has('success'))
                <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-200 dark:bg-gray-800 dark:text-blue-400" role="alert">
                    <span class="font-bold">Success!</span> {{ session('success') }}
                </div>
                <script>
                    setTimeout(function() {
                        document.getElementById('alert-container').innerHTML = '';
                    }, 5000);
                </script>
            @endif
            @if (session()->has('error'))
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-200 dark:bg-gray-800 dark:text-red-400" role="alert">
                    <span class="font-bold">Error!</span> {{ session('error') }}
                </div>
                <script>
                    setTimeout(function() {
                        document.getElementById('alert-container').innerHTML = '';
                    }, 5000);
                </script>
            @endif
        </div>

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 p-6 bg-white border-b">
            <div>
                <h1 class="text-2xl font-bold text-gray-900"><span class="text-blue-700">Bank Loan Schemes</span></h1>
                <p class="text-gray-600 mt-1">Manage bank loan schemes for your organisation</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <!-- Search Box -->
                <div class="relative flex-1 sm:flex-none">
                    <input wire:model.debounce.300ms="search" 
                           type="text" 
                           placeholder="Search schemes..." 
                           class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                
                <!-- Add Button -->
                <button wire:click="openModal()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors duration-200 whitespace-nowrap">
                    <i class="fas fa-plus"></i>
                    <span>Add New Scheme</span>
                </button>
            </div>
        </div>



        <!-- Table Section -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-4 py-3">SL</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Bank</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">EMI Enabled</th>
                        <th class="px-4 py-3">Effective Date</th>
                        <th class="px-4 py-3">Organization</th>
                        <th class="px-4 py-3">Finalized</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loanSchemesData as $loanScheme)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                {{ $loop->iteration + (isset($loanSchemesPaginator) ? ($loanSchemesPaginator->currentPage() - 1) * $loanSchemesPaginator->perPage() : 0) }}
                            </td>
                            <td class="px-4 py-3">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $loanScheme->name }}</div>
                                    <div class="text-sm text-gray-500 truncate max-w-xs">{{ \Illuminate\Support\Str::limit($loanScheme->description, 50) }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $loanScheme->bank ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $loanScheme->bank && $loanScheme->bank->name ? $loanScheme->bank->name : 'N/A' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColors = [
                                        'running' => 'bg-green-100 text-green-800',
                                        'completed' => 'bg-blue-100 text-blue-800',
                                        'upcoming' => 'bg-yellow-100 text-yellow-800',
                                        'suspended' => 'bg-red-100 text-red-800',
                                        'cancelled' => 'bg-gray-100 text-gray-800'
                                    ];
                                    $statusColor = $statusColors[$loanScheme->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                    {{ ucfirst($loanScheme->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if(property_exists($loanScheme, 'is_emi_enabled') || isset($loanScheme->is_emi_enabled))
                                    @if($loanScheme->is_emi_enabled)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Yes
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>No
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        N/A
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                {{ $loanScheme->effected_on && $loanScheme->effected_on instanceof \DateTime ? $loanScheme->effected_on->format('d M Y') : 'N/A' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                {{ $loanScheme->organisation && $loanScheme->organisation->name ? $loanScheme->organisation->name : 'N/A' }}
                            </td>
                            <td class="px-4 py-3">
                                @if($loanScheme->is_finalized)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Yes
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-clock mr-1"></i>No
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex space-x-2">
                                    <button wire:click="openModal({{ $loanScheme->id }})" 
                                            class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:click="deleteLoanScheme({{ $loanScheme->id }})" 
                                            wire:confirm="Are you sure you want to deactivate this loan scheme?"
                                            class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-inbox text-4xl mb-2"></i>
                                    <p>No loan schemes found</p>
                                    @if($search)
                                        <p class="text-sm">Try adjusting your search term</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if(isset($loanSchemesPaginator) && $loanSchemesPaginator && $loanSchemesPaginator->hasPages())
            <div class="px-6 py-4 border-t bg-gray-50">
                {{ $loanSchemesPaginator->links() }}
            </div>
        @endif
    </div>



    <!-- Bank Loan Scheme Modal -->
    <div id="loanSchemeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 {{ $showLoanSchemeModal ? 'block' : 'hidden' }}">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-4xl mx-4 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ $loanSchemeId ? 'Edit' : 'Add New' }} <span class="text-blue-700">Bank Loan Scheme</span>
                </h2>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form wire:submit.prevent="saveLoanScheme" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Scheme Name *</label>
                            <input wire:model="name" type="text" id="name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                            <textarea wire:model="description" id="description" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bank -->
                        <div>
                            <label for="bank_id" class="block text-sm font-medium text-gray-700 mb-2">Bank</label>
                            <select wire:model="bank_id" id="bank_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Bank</option>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach
                            </select>
                            @error('bank_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Effective Date -->
                        <div>
                            <label for="effected_on" class="block text-sm font-medium text-gray-700 mb-2">Effective Date</label>
                            <input wire:model="effected_on" type="date" id="effected_on"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('effected_on')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select wire:model="status" id="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Status</option>
                                <option value="running">Running</option>
                                <option value="completed">Completed</option>
                                <option value="upcoming">Upcoming</option>
                                <option value="suspended">Suspended</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Organization -->
                        <div>
                            <label for="organisation_id" class="block text-sm font-medium text-gray-700 mb-2">Organization *</label>
                            <select wire:model="organisation_id" id="organisation_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Organization</option>
                                @foreach($organisations as $org)
                                    <option value="{{ $org->id }}">{{ $org->name }}</option>
                                @endforeach
                            </select>
                            @error('organisation_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Financial Year -->
                        <div>
                            <label for="financial_year_id" class="block text-sm font-medium text-gray-700 mb-2">Financial Year *</label>
                            <select wire:model="financial_year_id" id="financial_year_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Financial Year</option>
                                @foreach($financialYears as $fy)
                                    <option value="{{ $fy->id }}">{{ $fy->name }}</option>
                                @endforeach
                            </select>
                            @error('financial_year_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Finalized Status -->
                        <div class="flex items-center">
                            <input wire:model="is_finalized" type="checkbox" id="is_finalized"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_finalized" class="ml-2 block text-sm text-gray-700">Mark as Finalized</label>
                        </div>

                        <!-- EMI Enabled -->
                        <div class="flex items-center">
                            <input wire:model="is_emi_enabled" type="checkbox" id="is_emi_enabled"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_emi_enabled" class="ml-2 block text-sm text-gray-700">EMI Enabled</label>
                        </div>

                        <!-- Remarks -->
                        <div>
                            <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                            <textarea wire:model="remarks" id="remarks" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            @error('remarks')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <button type="button" wire:click="closeModal"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        {{ $loanSchemeId ? 'Update' : 'Create' }} Loan Scheme
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- End of Bank Loan Scheme Modal -->
    
</div>

