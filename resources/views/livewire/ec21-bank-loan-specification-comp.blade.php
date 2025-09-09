<div>
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Bank Loan Scheme Specifications</h2>
        
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif
        
        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div class="w-1/3">
                    <input wire:model="search" type="text" placeholder="Search schemes..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button wire:click="resetForm" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Add New Specification</span>
                </button>
            </div>
        </div>

        @if($isEditing || !$isEditing && !count($selectedParticulars))
            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $isEditing ? 'Edit' : 'Add New' }} Scheme Specifications</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Loan Scheme</label>
                        <select wire:model="selectedSchemeId" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select a scheme</option>
                            @foreach($schemes as $scheme)
                                <option value="{{ $scheme->id }}">{{ $scheme->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedSchemeId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                @if($selectedSchemeId)
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-md font-semibold text-gray-700">Scheme Particulars</h4>
                            <button wire:click="addParticularRow" type="button" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                <i class="fas fa-plus mr-1"></i> Add Particular
                            </button>
                        </div>

                        @foreach($selectedParticulars as $index => $particular)
                            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-4 p-4 border border-gray-200 rounded-lg">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Particular</label>
                                    <select wire:model="selectedParticulars.{{ $index }}.particular_id" class="w-full px-3 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm">
                                        <option value="">Select particular</option>
                                        @foreach($particulars as $part)
                                            <option value="{{ $part->id }}">{{ $part->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('selectedParticulars.'.$index.'.particular_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Value</label>
                                    <input wire:model="selectedParticulars.{{ $index }}.value" type="number" step="0.01" 
                                           class="w-full px-3 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm">
                                    @error('selectedParticulars.'.$index.'.value') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="flex items-end">
                                    <label class="flex items-center text-sm text-gray-700">
                                        <input wire:model="selectedParticulars.{{ $index }}.is_percent_on_current_balance" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2">Is %</span>
                                    </label>
                                </div>
                                
                                <div class="flex items-end">
                                    <label class="flex items-center text-sm text-gray-700">
                                        <input wire:model="selectedParticulars.{{ $index }}.is_regular" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2">Regular</span>
                                    </label>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Effected On</label>
                                    <input wire:model="selectedParticulars.{{ $index }}.effected_on" type="date" 
                                           class="w-full px-3 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm">
                                    @error('selectedParticulars.'.$index.'.effected_on') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="flex items-end space-x-2">
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select wire:model="selectedParticulars.{{ $index }}.status" class="w-full px-3 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm">
                                            <option value="suspended">Suspended</option>
                                            <option value="running">Running</option>
                                            <option value="completed">Completed</option>
                                            <option value="upcoming">Upcoming</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                    </div>
                                    <button wire:click="removeParticularRow({{ $index }})" type="button" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-6 flex justify-end space-x-4">
                            <button wire:click="resetForm" type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                                Cancel
                            </button>
                            <button wire:click="save" type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                {{ $isEditing ? 'Update' : 'Save' }} Specifications
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scheme</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Particular</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($specs as $spec)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $spec->scheme->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $spec->particular->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $spec->bank_loan_schema_particular_value }} 
                            {{ $spec->is_percent_on_current_balance ? '%' : '' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $spec->is_regular ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $spec->is_regular ? 'Regular' : 'Scheduled' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($spec->status === 'running') bg-green-100 text-green-800
                                @elseif($spec->status === 'suspended') bg-red-100 text-red-800
                                @elseif($spec->status === 'upcoming') bg-blue-100 text-blue-800
                                @elseif($spec->status === 'completed') bg-gray-100 text-gray-800
                                @elseif($spec->status === 'cancelled') bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($spec->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button wire:click="edit({{ $spec->bank_loan_scheme_id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="delete({{ $spec->id }})" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No specifications found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $specs->links() }}
        </div>
    </div>
</div>