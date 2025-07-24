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
                        Account No <i class="fas fa-sort text-gray-400 ml-1"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="id">
                        Address <i class="fas fa-sort text-gray-400 ml-1"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="id">
                        Address <i class="fas fa-sort text-gray-400 ml-1"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="id">
                        Address <i class="fas fa-sort text-gray-400 ml-1"></i>
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
                    <td class="px-4 py-1 text-sm font-bold text-gray-900">{{ $loanSchemeFeature->is_required }}</td>
                    <td class="px-4 py-1 text-sm font-bold text-gray-900">{{ $loanSchemeFeature->is_active }}</td>
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









</div>
