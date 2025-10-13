<div>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Organization Officials</h2>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" wire:model.debounce.300ms="search" placeholder="Search officials..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                <button 
                    wire:click="openModal"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors duration-200">
                    <i class="fas fa-plus"></i>
                    <span>Add Official</span>
                </button>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('message') }}</p>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('id')">
                            ID
                            @if ($sortField === 'id')
                                <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                            @endif
                        </th>
                        <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('organisation_id')">
                            Organization
                            @if ($sortField === 'organisation_id')
                                <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                            @endif
                        </th>
                        <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('member_id')">
                            Member
                            @if ($sortField === 'member_id')
                                <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                            @endif
                        </th>
                        <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('designation')">
                            Designation
                            @if ($sortField === 'designation')
                                <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                            @endif
                        </th>
                        <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description
                        </th>
                        <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('is_active')">
                            Status
                            @if ($sortField === 'is_active')
                                <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span>
                            @endif
                        </th>
                        <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($officials as $official)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $official->id }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $official->organisation->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $official->member->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $official->designation }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $official->description }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $official->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $official->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-900">
                                <div class="flex items-center space-x-2">
                                    <button wire:click="edit({{ $official->id }})" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:click="$emit('delete', {{ $official->id }})" onclick="confirm('Are you sure you want to delete this official?') || event.stopImmediatePropagation()" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-2 text-sm text-gray-500 text-center">No officials found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $officials->links() }}
        </div>
    </div>

    <!-- Modal for Add/Edit Official -->
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 {{ $isOpen ? '' : 'hidden' }}">
        <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-2xl mx-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">{{ $officialId ? 'Edit' : 'Add' }} Organization Official</h2>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form wire:submit.prevent="store">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="organisation_id" class="block text-sm font-medium text-gray-700 mb-2">Organization *</label>
                        <select wire:model="organisation_id" id="organisation_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Organization</option>
                            @foreach($organisations as $organisation)
                                <option value="{{ $organisation->id }}">{{ $organisation->name }}</option>
                            @endforeach
                        </select>
                        @error('organisation_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="member_id" class="block text-sm font-medium text-gray-700 mb-2">Member</label>
                        <select wire:model="member_id" id="member_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Member</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                        @error('member_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="designation" class="block text-sm font-medium text-gray-700 mb-2">Designation *</label>
                    <input type="text" wire:model="designation" id="designation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('designation') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea wire:model="description" id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-6">
                    <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                    <textarea wire:model="remarks" id="remarks" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    @error('remarks') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="is_active" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                        {{ $officialId ? 'Update' : 'Save' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
