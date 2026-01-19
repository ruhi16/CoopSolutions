<div>
    <div class="rounded-lg shadow-sm overflow-hidden">
        <div id="alert-container" style="position: fixed; top: 10px; right: 10px; z-index: 1000;">
            @if (session()->has('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-200 dark:bg-gray-800 dark:text-green-400" role="alert">
                    <span class="font-bold">Success!</span> {{ session('success') }}
                </div>
                <script>
                    setTimeout(function() {
                        document.getElementById('alert-container').innerHTML = '';
                    }, 5000);
                </script>
            @endif
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900"><span class="text-blue-700">Loan Scheme</span> Management</h1>
                <p class="text-gray-600 mt-1">Manage loan schemes and their details</p>
            </div>
            <button 
                wire:click="openModal"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors duration-200 whitespace-nowrap">
                <i class="fas fa-plus"></i>
                <span>Add New Loan Scheme</span>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 rounded-lg">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Short Name</th>
                        <th class="px-4 py-3">Start Date</th>
                        <th class="px-4 py-3">End Date</th>
                        <th class="px-4 py-3">EMI Enabled</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Active</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loanSchemes as $loanScheme)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ Str::limit($loanScheme->name, 30) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $loanScheme->name_short }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $loanScheme->start_date ? $loanScheme->start_date->format('M d, Y') : 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $loanScheme->end_date ? $loanScheme->end_date->format('M d, Y') : 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            <span class="{{ $loanScheme->is_emi_enabled ? 'text-green-600 font-semibold' : 'text-gray-500' }}">
                                {{ $loanScheme->is_emi_enabled ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $this->getStatusClass($loanScheme->status) }}">
                                {{ ucfirst($loanScheme->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            <span class="{{ $loanScheme->is_active ? 'text-green-600 font-semibold' : 'text-gray-500' }}">
                                {{ $loanScheme->is_active ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-700">
                            <button wire:click="edit({{ $loanScheme->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="delete({{ $loanScheme->id }})" class="text-red-600 hover:text-red-900" 
                                onclick="return confirm('Are you sure you want to delete this loan scheme?')"> 
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-6 text-center text-gray-500">
                            No loan schemes found. Click "Add New Loan Scheme" to create one.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    @if($isOpen)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-3xl mx-4 my-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ $loan_scheme_id ? 'Edit Loan Scheme' : 'Add New Loan Scheme' }}
                </h2>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form wire:submit.prevent="store">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                        <input 
                            wire:model="name" 
                            type="text" 
                            id="name" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter loan scheme name">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="name_short" class="block text-sm font-medium text-gray-700 mb-2">Short Name *</label>
                        <input 
                            wire:model="name_short" 
                            type="text" 
                            id="name_short" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter short name">
                        @error('name_short') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea 
                            wire:model="description" 
                            id="description" 
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter loan scheme description"></textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input 
                            wire:model="start_date" 
                            type="date" 
                            id="start_date" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input 
                            wire:model="end_date" 
                            type="date" 
                            id="end_date" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select 
                            wire:model="status" 
                            id="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="running">Running</option>
                            <option value="completed">Completed</option>
                            <option value="upcoming">Upcoming</option>
                            <option value="suspended">Suspended</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                        <input 
                            wire:model="remarks" 
                            type="text" 
                            id="remarks" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter remarks">
                        @error('remarks') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="flex items-center">
                        <label class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input wire:model="is_emi_enabled" type="checkbox" class="sr-only">
                                <div class="block w-10 h-6 rounded-full bg-gray-300 border-2 border-transparent transition-colors duration-200 ease-in-out checked:bg-blue-600 checked:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500" :class="{'bg-blue-600': is_emi_enabled, 'bg-gray-300': !is_emi_enabled}"></div>
                                <div class="absolute left-1 top-1 bg-white border-2 border-gray-300 rounded-full w-4 h-4 transition-transform duration-200 ease-in-out transform checked:translate-x-4" :class="{'translate-x-4': is_emi_enabled, 'translate-x-0': !is_emi_enabled}"></div>
                            </div>
                            <span class="ml-3 text-sm text-gray-700">EMI Enabled</span>
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <label class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input wire:model="is_active" type="checkbox" class="sr-only">
                                <div class="block w-10 h-6 rounded-full bg-gray-300 border-2 border-transparent transition-colors duration-200 ease-in-out checked:bg-blue-600 checked:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500" :class="{'bg-blue-600': is_active, 'bg-gray-300': !is_active}"></div>
                                <div class="absolute left-1 top-1 bg-white border-2 border-gray-300 rounded-full w-4 h-4 transition-transform duration-200 ease-in-out transform checked:translate-x-4" :class="{'translate-x-4': is_active, 'translate-x-0': !is_active}"></div>
                            </div>
                            <span class="ml-3 text-sm text-gray-700">Is Active</span>
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-8">
                    <button 
                        wire:click="closeModal" 
                        type="button" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        {{ $loan_scheme_id ? 'Update' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>