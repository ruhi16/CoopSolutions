<div>

    <div class="rounded-lg shadow-sm overflow-hidden">
        <div id="alert-container" style="position: fixed; top: 10px; right: 10px; z-index: 1000;">
            @if (session()->has('success'))
                <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-200 dark:bg-gray-800 dark:text-blue-400"
                    role="alert">
                    <span class="font-bold">Info alert!</span> {{ session('success') }}
                </div>
                <script>
                    setTimeout(function () {
                        document.getElementById('alert-container').innerHTML = '';
                    }, 5000);
                </script>
            @endif
            @if (session()->has('error'))
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-200 dark:bg-gray-800 dark:text-red-400"
                    role="alert">
                    <span class="font-bold">Danger alert!</span> {{ session('error') }}
                </div>
                <script>
                    setTimeout(function () {
                        document.getElementById('alert-container').innerHTML = '';
                    }, 5000);
                </script>
            @endif
        </div>
    </div>

    <div class="flex justify-between items-center">
        <div class="pb-2">
            <h1 class="text-2xl font-bold text-gray-900"><span class="text-blue-700">Loan Request</span> Information
            </h1>
            <p class="text-gray-600 mt-1">Describe the <span class="text-blue-700">loan request</span> of this
                organisation.</p>
        </div>
        <button wire:click="openModal"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors duration-200">
            <i class="fas fa-plus"></i>
            <span>Add New Loan Request</span>
        </button>
    </div>

    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 rounded-md">
            <tr class="">
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                    data-sort="id">SL <i class="fas fa-sort text-gray-400 ml-1"></i> </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                    data-sort="id">Memeber Name <i class="fas fa-sort text-gray-400 ml-1"></i> </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                    data-sort="id">Loan Scheme<i class="fas fa-sort text-gray-400 ml-1"></i> </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                    data-sort="id">Time Period(Yrs)<i class="fas fa-sort text-gray-400 ml-1"></i> </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                    data-sort="id">Request Date<i class="fas fa-sort text-gray-400 ml-1"></i> </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                    data-sort="id">Request Amount <i class="fas fa-sort text-gray-400 ml-1"></i> </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                    data-sort="id">Status <i class="fas fa-sort text-gray-400 ml-1"></i> </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                    data-sort="id">Actions <i class="fas fa-sort text-gray-400 ml-1"></i> </th>
            </tr>
        </thead>
        <tbody>
            @foreach($loanRequests as $loanRequest)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $loanRequest->member->name ?? 'X' }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $loanRequest->loanScheme->name ?? 'X' }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ (int) $loanRequest->time_period_months/12 ?? 'X' }} Years
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $loanRequest->req_date ?? 'X' }}
                        {{-- {{ \Carbon\Carbon::parse($loanRequest->req_date)->format('Y-m-d') }} --}}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $loanRequest->req_loan_amount ?? 'X' }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $loanRequest->status ?? 'X' }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                        <button wire:click="openModal({{ $loanRequest->id }})"
                            class="text-green-600 hover:text-green-900 mr-2">
                            <i class="fas fa-snowflake"></i>
                        </button>
                        <button wire:click="openModal({{ $loanRequest->id }})"
                            class="text-blue-600 hover:text-blue-900 mr-2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button wire:click="confirmDelete({{ $loanRequest->id }})"
                            class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>


    <!-- Loan Scheme Modal -->
    <div id="loanSchemeDetailModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 {{ $showLoanRequestModal ? 'block' : 'hidden' }}">
        <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-2xl mx-4">

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">New <span class="text-blue-700">Loan Request </span>Detail
                    Entry</h2>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="loanRequest" wire:submit.prevent="saveLoanRequest" class="space-y-4">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div class="mb-4">
                        <label for="selectedMemberId" class="block text-sm font-medium text-gray-700 mb-2">Member
                            Name</label>
                        <select wire:model="selectedMemberId" name="selectedMemberId" id="selectedMemberId"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Member</option>
                            @foreach ($members as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedMemberId')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="selectedLoanSchemeId" class="block text-sm font-medium text-gray-700 mb-2">Loan
                            Scheme</label>
                        <select wire:model="selectedLoanSchemeId" name="selectedLoanSchemeId" id="selectedLoanSchemeId"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Loan Scheme</option>
                            @foreach ($loanSchemes as $loanScheme)
                                <option value="{{ $loanScheme->id }}">{{ $loanScheme->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedLoanSchemeId')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div class="mb-4">
                        <label for="selectedTimePeriod" class="block text-sm font-medium text-gray-700 mb-2">Time in
                            Years</label>
                        <select wire:model="selectedTimePeriod" name="selectedTimePeriod" id="selectedTimePeriod"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="0">Select Time Period</option>
                            <option value="1">One Year</option>
                            <option value="2">Two Year</option>
                            <option value="3">Three Year</option>
                            <option value="4">Four Year</option>
                            <option value="5">Five Year</option>
                            <option value="6">Six Year</option>
                            <option value="7">Seven Year</option>
                            {{-- @foreach ($loanSchemes as $loanScheme)
                            <option value="{{ $loanScheme->id }}">{{ $loanScheme->name }}</option>
                            @endforeach --}}
                        </select>
                        @error('selectedTimePeriod')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="expectedAmount" class="block text-sm font-medium text-gray-700 mb-2">Expected
                            Value</label>
                        <input wire:model="expectedAmount" type="text" id="expectedAmount" name="expectedAmount"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('expectedAmount')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="relative overflow-x-auto bg-fuchsia-400">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-2">Key</th>
                                <th scope="col" class="px-6 py-2">Description</th>
                                <th scope="col" class="px-6 py-2">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($selectedLoanScheme)
                                @foreach($selectedLoanScheme->loanSchemeDetails as $loanSchemeDetail)
                                    <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                        <th scope="row"
                                            class="px-6 py-2 font-medium text-gray-500 whitespace-nowrap dark:text-gray-400">
                                            {{ $loanSchemeDetail->loanSchemeFeature->loan_scheme_feature_name }}<br />
                                            <span
                                                class="text-xs text-gray-500">{{ $loanSchemeDetail->loanSchemeFeature->loan_scheme_feature_type }}</span>
                                        </th>
                                        <td class="px-6 py-2">
                                            {{ $loanSchemeDetail->loan_scheme_feature_value }}
                                        </td>
                                        <td class="px-6 py-2">
                                            {{ $loanSchemeDetail->loan_scheme_feature_remarks ?? 'No Remarks' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    Save Loan Scheme Detail
                </button>
            </form>
        </div>
    </div>
    {{-- End of Loan Scheme Mo --}}


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
                    Are you sure you want to delete this loan request detail? This action cannot be undone.
                </p>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button 
                    wire:click="cancelDelete"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors duration-200">
                    Cancel
                </button>
                <button 
                    wire:click="deleteLoanRequest"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors duration-200">
                    Delete
                </button>
            </div>
        </div>
    </div>
    {{-- End of Delete Confirmation Modal --}}


</div>