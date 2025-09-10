<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <!-- Tabs -->
    <div class="border-b border-gray-200 mb-6">
        <ul class="flex flex-wrap -mb-px">
            <li class="mr-2">
                <button 
                    wire:click="changeTab('schemes')" 
                    class="inline-block py-4 px-4 text-sm font-medium text-center border-b-2 rounded-t-lg {{ $activeTab === 'schemes' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300' }}"
                >
                    <i class="fas fa-file-invoice-dollar mr-2"></i>Loan Schemes & Specifications
                </button>
            </li>
            <li class="mr-2">
                <button 
                    wire:click="changeTab('particulars')" 
                    class="inline-block py-4 px-4 text-sm font-medium text-center border-b-2 rounded-t-lg {{ $activeTab === 'particulars' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300' }}"
                >
                    <i class="fas fa-list-alt mr-2"></i>Schema Particulars
                </button>
            </li>
        </ul>
    </div>

    <!-- Tab 1: Loan Schemes & Specifications -->
    @if($activeTab === 'schemes')
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-file-invoice-dollar mr-2 text-blue-600"></i>Loan Schemes Management
            </h2>
            <button 
                wire:click="showSchemeModal" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors duration-200"
            >
                <i class="fas fa-plus"></i>
                <span>Add New Scheme</span>
            </button>
        </div>

        <!-- Schemes Table -->
        <div class="overflow-x-auto rounded-lg shadow">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Description</th>
                        <th scope="col" class="px-6 py-3">Bank ID</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Effected On</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schemes as $scheme)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $scheme->name }}</td>
                            <td class="px-6 py-4">{{ Str::limit($scheme->description, 50) }}</td>
                            <td class="px-6 py-4">{{ $scheme->bank_id }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $scheme->status === 'running' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $scheme->status === 'suspended' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $scheme->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                ">
                                    {{ ucfirst($scheme->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $scheme->effected_on ? \Carbon\Carbon::parse($scheme->effected_on)->format('M d, Y') : 'N/A' }}</td>
                            <td class="px-6 py-4 flex space-x-2">
                                <button wire:click="editScheme({{ $scheme->id }})" class="text-blue-600 hover:text-blue-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="deleteScheme({{ $scheme->id }})" class="text-red-600 hover:text-red-900" title="Delete" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <!-- Specifications for this scheme -->
                        <tr class="bg-gray-50">
                            <td colspan="6" class="px-6 py-4">
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg shadow-inner">
                                    <h4 class="font-medium text-gray-700 mb-3 flex items-center">
                                        <i class="fas fa-list-check mr-2 text-indigo-600"></i>Specifications
                                    </h4>
                                    <table class="w-full text-sm text-left text-gray-500">
                                        <thead class="text-xs text-gray-700 uppercase bg-indigo-100">
                                            <tr>
                                                <th class="px-4 py-2">Particular</th>
                                                <th class="px-4 py-2">Value</th>
                                                <th class="px-4 py-2">Type</th>
                                                <th class="px-4 py-2">Regular</th>
                                                <th class="px-4 py-2">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($scheme->specifications as $spec)
                                                <tr class="border-b hover:bg-indigo-50 transition-colors">
                                                    <td class="px-4 py-2 font-medium">
                                                        {{ $spec->particular->name ?? 'N/A' }}
                                                        @if($spec->particular && $spec->particular->is_optional)
                                                            <span class="ml-1 text-xs text-blue-600">(Optional)</span>
                                                        @else
                                                            <span class="ml-1 text-xs text-red-600">(Mandatory)</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-2">{{ $spec->bank_loan_schema_particular_value }}</td>
                                                    <td class="px-4 py-2">{{ $spec->is_percent_on_current_balance ? 'Percentage' : 'Fixed' }}</td>
                                                    <td class="px-4 py-2">{{ $spec->is_regular ? 'Yes' : 'No' }}</td>
                                                    <td class="px-4 py-2">
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                            {{ $spec->status === 'running' ? 'bg-green-100 text-green-800' : '' }}
                                                            {{ $spec->status === 'suspended' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                        ">
                                                            {{ ucfirst($spec->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="px-4 py-4 text-center text-gray-500 bg-indigo-50 rounded-lg">
                                                        <i class="fas fa-info-circle mr-2"></i>No specifications found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 bg-blue-50 rounded-lg">
                                <i class="fas fa-inbox text-3xl mb-2 text-blue-400"></i>
                                <p class="text-lg">No loan schemes found</p>
                                <p class="text-sm mt-1">Click the "Add New Scheme" button to create your first loan scheme</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $schemes->links() }}
        </div>

    <!-- Tab 2: Schema Particulars -->
    @elseif($activeTab === 'particulars')
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-list-alt mr-2 text-blue-600"></i>Schema Particulars Management
            </h2>
            <button 
                wire:click="showParticularModal" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors duration-200"
            >
                <i class="fas fa-plus"></i>
                <span>Add Schema Particular</span>
            </button>
        </div>

        <!-- Particulars Table -->
        <div class="overflow-x-auto rounded-lg shadow">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Description</th>
                        <th scope="col" class="px-6 py-3">Type</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($particulars as $particular)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $particular->name }}
                                @if($particular->is_optional)
                                    <span class="ml-1 text-xs text-blue-600">(Optional)</span>
                                @else
                                    <span class="ml-1 text-xs text-red-600">(Mandatory)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ Str::limit($particular->description, 50) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $particular->is_optional ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}
                                ">
                                    {{ $particular->is_optional ? 'Optional' : 'Mandatory' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $particular->status === 'published' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $particular->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $particular->status === 'archived' ? 'bg-gray-100 text-gray-800' : '' }}
                                ">
                                    {{ ucfirst($particular->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 flex space-x-2">
                                <button wire:click="editParticular({{ $particular->id }})" class="text-blue-600 hover:text-blue-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="deleteParticular({{ $particular->id }})" class="text-red-600 hover:text-red-900" title="Delete" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 bg-blue-50 rounded-lg">
                                <i class="fas fa-inbox text-3xl mb-2 text-blue-400"></i>
                                <p class="text-lg">No schema particulars found</p>
                                <p class="text-sm mt-1">Click the "Add Schema Particular" button to create your first particular</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $particulars->links() }}
        </div>
    @endif

    <!-- Scheme Modal -->
    @if($showSchemeModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-3xl max-h-screen overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-900">{{ $editMode ? 'Edit' : 'Create' }} Loan Scheme</h2>
                    <button wire:click="$set('showSchemeModal', false)" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form wire:submit.prevent="saveScheme">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Scheme Name *</label>
                            <input type="text" wire:model="name" id="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="bank_id" class="block text-sm font-medium text-gray-700 mb-1">Bank ID</label>
                            <input type="number" wire:model="bank_id" id="bank_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                            @error('bank_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="effected_on" class="block text-sm font-medium text-gray-700 mb-1">Effected On</label>
                            <input type="date" wire:model="effected_on" id="effected_on" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                            @error('effected_on') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select wire:model="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="suspended">Suspended</option>
                                <option value="running">Running</option>
                                <option value="completed">Completed</option>
                                <option value="upcoming">Upcoming</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea wire:model="description" id="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"></textarea>
                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-md font-medium text-gray-900">Specifications</h3>
                            <button type="button" wire:click="addSpecificationRow" class="bg-blue-100 hover:bg-blue-200 text-blue-800 px-2 py-1 rounded text-xs flex items-center">
                                <i class="fas fa-plus mr-1 text-xs"></i> Add Specification
                            </button>
                        </div>

                        <div class="space-y-2 max-h-60 overflow-y-auto p-1">
                            @foreach($specifications as $index => $specification)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 p-3 border border-gray-200 rounded-lg bg-gray-50">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Particular *</label>
                                        <select wire:model="specifications.{{ $index }}.bank_loan_schema_particular_id" required class="w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent text-sm">
                                            <option value="">Select Particular</option>
                                            @foreach($allParticulars as $particular)
                                                <option value="{{ $particular->id }}">
                                                    {{ $particular->name }} 
                                                    ({{ $particular->is_optional ? 'Optional' : 'Mandatory' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Value *</label>
                                        <input type="number" step="0.01" wire:model="specifications.{{ $index }}.bank_loan_schema_particular_value" required class="w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent text-sm">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Value Type</label>
                                        <select wire:model="specifications.{{ $index }}.is_percent_on_current_balance" class="w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent text-sm">
                                            <option value="1">Percentage</option>
                                            <option value="0">Fixed Amount</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Regular</label>
                                        <select wire:model="specifications.{{ $index }}.is_regular" class="w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent text-sm">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Effected On</label>
                                        <input type="date" wire:model="specifications.{{ $index }}.effected_on" class="w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent text-sm">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                        <select wire:model="specifications.{{ $index }}.status" class="w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent text-sm">
                                            <option value="suspended">Suspended</option>
                                            <option value="running">Running</option>
                                            <option value="completed">Completed</option>
                                            <option value="upcoming">Upcoming</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                    </div>

                                    @if($index > 0)
                                        <div class="md:col-span-2 flex justify-end">
                                            <button type="button" wire:click="removeSpecificationRow({{ $index }})" class="text-red-600 hover:text-red-900 text-xs">
                                                <i class="fas fa-trash mr-1"></i> Remove
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2 pt-2 border-t border-gray-200">
                        <button type="button" wire:click="$set('showSchemeModal', false)" class="px-3 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors duration-200 text-sm">
                            Cancel
                        </button>
                        <button type="submit" class="px-3 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm">
                            {{ $editMode ? 'Update' : 'Create' }} Scheme
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Particular Modal -->
    @if($showParticularModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl p-4 w-full max-w-md">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-lg font-bold text-gray-900">{{ $editMode ? 'Edit' : 'Create' }} Schema Particular</h2>
                    <button wire:click="$set('showParticularModal', false)" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit.prevent="saveParticular">
                    <div class="mb-3">
                        <label for="particular_name" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                        <input type="text" wire:model="particular_name" id="particular_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        @error('particular_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="particular_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea wire:model="particular_description" id="particular_description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"></textarea>
                        @error('particular_description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="particular_is_optional" id="particular_is_optional" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="particular_is_optional" class="ml-2 block text-sm text-gray-900">Optional Particular</label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="particular_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select wire:model="particular_status" id="particular_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="archived">Archived</option>
                        </select>
                        @error('particular_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-2 pt-2 border-t border-gray-200">
                        <button type="button" wire:click="$set('showParticularModal', false)" class="px-3 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors duration-200 text-sm">
                            Cancel
                        </button>
                        <button type="submit" class="px-3 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm">
                            {{ $editMode ? 'Update' : 'Create' }} Particular
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('message') }}
        </div>
    @endif
</div>