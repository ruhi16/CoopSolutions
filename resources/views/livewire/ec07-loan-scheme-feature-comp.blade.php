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
                <h1 class="text-2xl font-bold text-gray-900"><span class="text-blue-700">Loan Scheme</span> Information</h1>
                <p class="text-gray-600 mt-1">Describe the <span class="text-blue-700">loan scheme</span> of this organisation.</p>
            </div>
            <button 
                wire:click="openModal"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors duration-200">
                <i class="fas fa-plus"></i>
                <span>Add New LoanScheme</span>
            </button>
        </div>

        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 rounded-md">
                <tr class="">
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="id">
                        SL <i class="fas fa-sort text-gray-400 ml-1"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="id">
                        Name <i class="fas fa-sort text-gray-400 ml-1"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="id">
                        Feature Name <i class="fas fa-sort text-gray-400 ml-1"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="id">
                        Feature Type <i class="fas fa-sort text-gray-400 ml-1"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="id">
                        Required <i class="fas fa-sort text-gray-400 ml-1"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="id">
                        Active <i class="fas fa-sort text-gray-400 ml-1"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="id">
                        Actions <i class="fas fa-sort text-gray-400 ml-1"></i>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($loanSchemeFeatures as $loanSchemeFeature)
                <tr bg-white border-b hover:bg-gray-100>
                    <td class="px-4 py-1 text-sm font-bold text-gray-900">{{ $loanSchemeFeature->id }}</td>
                    <td class="px-4 py-1 text-sm font-bold text-gray-900">{{ $loanSchemeFeature->name }}</td>
                    <td class="px-4 py-1 text-sm font-bold text-gray-900">{{ $loanSchemeFeature->loan_scheme_feature_name }}</td>
                    <td class="px-4 py-1 text-sm font-bold text-gray-900">{{ $loanSchemeFeature->loan_scheme_feature_type }}</td>
                    <td class="px-4 py-1 text-sm font-bold text-gray-900">{{ $loanSchemeFeature->is_required ? 'Yes' : 'No' }}</td>
                    <td class="px-4 py-1 text-sm font-bold text-gray-900">{{ $loanSchemeFeature->is_active ? 'Yes' : 'No' }}</td>
                    <td class="px-4 py-1 text-sm font-bold text-gray-900">
                        <button wire:click="edit({{ $loanSchemeFeature->id }})" class="text-blue-600 hover:text-blue-900 mr-2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button wire:click="delete({{ $loanSchemeFeature->id }})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    @if($isOpen)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-2xl mx-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">{{ $loan_scheme_feature_id ? 'Edit Loan Scheme Feature' : 'Add New Loan Scheme Feature' }}</h2>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form wire:submit.prevent="store">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input wire:model="name" type="text" id="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea wire:model="description" id="description" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-span-1">
                        <label for="loan_scheme_feature_name" class="block text-sm font-medium text-gray-700 mb-2">Feature Name</label>
                        <input wire:model="loan_scheme_feature_name" type="text" id="loan_scheme_feature_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('loan_scheme_feature_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-span-1">
                        <label for="loan_scheme_feature_type" class="block text-sm font-medium text-gray-700 mb-2">Feature Type</label>
                        <input wire:model="loan_scheme_feature_type" type="text" id="loan_scheme_feature_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('loan_scheme_feature_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-span-1">
                        <label for="loan_scheme_feature_unit" class="block text-sm font-medium text-gray-700 mb-2">Feature Unit</label>
                        <input wire:model="loan_scheme_feature_unit" type="text" id="loan_scheme_feature_unit" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('loan_scheme_feature_unit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-span-1">
                        <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                        <input wire:model="remarks" type="text" id="remarks" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('remarks') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-span-1">
                        <label class="flex items-center">
                            <input wire:model="is_required" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600">Required</span>
                        </label>
                    </div>
                    
                    <div class="col-span-1">
                        <label class="flex items-center">
                            <input wire:model="is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600">Active</span>
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button wire:click="closeModal" type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        {{ $loan_scheme_feature_id ? 'Update' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>