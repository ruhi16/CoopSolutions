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
                <h1 class="text-2xl font-bold text-gray-900"><span class="text-blue-700">Thrift Fund</span> Specifications</h1>
                <p class="text-gray-600 mt-1">Manage thrift fund specification settings.</p>
            </div>
            <button 
                wire:click="openModal"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors duration-200">
                <i class="fas fa-plus"></i>
                <span>Add New Specification</span>
            </button>
        </div>

        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 rounded-md">
                <tr class="">
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SL</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Particular</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Effected On</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($specifications as $specification)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $specification->name ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $specification->particular ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                        @if($specification->particular_value)
                            {{ $specification->particular_value }}
                            @if(Str::contains(strtolower($specification->particular ?? ''), ['percent', 'rate', '%']))
                                %
                            @elseif(Str::contains(strtolower($specification->particular ?? ''), ['amount', 'rupee', 'rs', '₹']))
                                ₹
                            @endif
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                        {{ $specification->effected_on ? \Carbon\Carbon::parse($specification->effected_on)->format('d-m-Y') : 'N/A' }}
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $specification->status == 'published' ? 'bg-green-100 text-green-800' : 
                               ($specification->status == 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($specification->status ?? 'N/A') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div class="flex space-x-2">
                            <button wire:click="openModal({{ $specification->id }})"
                                class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="confirmDelete({{ $specification->id }})" 
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

    <!-- Specification Modal -->
    <div id="specificationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 {{ $showSpecificationModal ? 'block' : 'hidden' }}">
        <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-2xl mx-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">{{ $specificationId ? 'Edit' : 'New' }} Specification</h2>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form wire:submit.prevent="saveSpecification" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Specification Name *</label>
                        <input wire:model="name" type="text" id="name" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('name')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="particular" class="block text-sm font-medium text-gray-700 mb-2">Particular</label>
                        <input wire:model="particular" type="text" id="particular" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="e.g., Interest Rate, Minimum Amount, etc.">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="particularValue" class="block text-sm font-medium text-gray-700 mb-2">Particular Value</label>
                        <input wire:model="particularValue" type="number" step="0.01" id="particularValue" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div class="mb-4">
                        <label for="effectedOn" class="block text-sm font-medium text-gray-700 mb-2">Effected On</label>
                        <input wire:model="effectedOn" type="date" id="effectedOn" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                    {{ $specificationId ? 'Update' : 'Save' }} Specification
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
                    Are you sure you want to delete this thrift fund specification? This action cannot be undone.
                </p>
            </div>

            <div class="flex justify-end space-x-3">
                <button wire:click="cancelDelete"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors duration-200">
                    Cancel
                </button>
                <button wire:click="deleteSpecification"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors duration-200">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>