<div>

    <div class="rounded-lg shadow-sm overflow-hidden">
        <div id="alert-container" style="position: fixed; top: 10px; right: 10px; z-index: 1000;">
            @if (session()->has('success'))
            <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-200 dark:bg-gray-800 dark:text-blue-400"
                role="alert">
                <span class="font-bold">Info alert!</span> {{ session('success') }}
            </div>
            <script>
                setTimeout(function() {
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
                setTimeout(function() {
                        document.getElementById('alert-container').innerHTML = '';
                    }, 5000);
            </script>
            @endif
        </div>

        <div class="flex justify-between items-center">
            <div class="pb-2">
                <h1 class="text-2xl font-bold text-gray-900"><span class="text-blue-700">Loan Scheme</span> Information
                </h1>
                <p class="text-gray-600 mt-1">Describe the <span class="text-blue-700">loan scheme</span> of this
                    organisation.</p>
            </div>
            <button wire:click="openModal"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors duration-200">
                <i class="fas fa-plus"></i>
                <span>Add New Loan Scheme Detail</span>
            </button>
        </div>

        @php
        // Group loan scheme details by loan scheme ID and sort by ID
        $groupedDetails = [];
        foreach($loanSchemeDetails as $detail) {
        $groupedDetails[$detail->loan_scheme_id][] = $detail;
        }

        // Sort each group by ID
        foreach($groupedDetails as &$details) {
        usort($details, function($a, $b) {
        return $a->id <=> $b->id;
            });
            }
            @endphp

            @foreach($groupedDetails as $schemeId => $details)
            <div class="mb-8">
                <div class="bg-gray-100 px-4 py-2 rounded-t-lg">
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ $details[0]->loanScheme->name ?? 'Unknown Scheme' }}
                        <span class="text-sm font-normal text-gray-600">(Total: {{ count($details) }} items)</span>
                    </h3>
                </div>

                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Entry Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                LS Feature</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                LS Feature Details</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                LS Feature Value</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($details as $loanSchemeDetail)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{
                                $loanSchemeDetail->id }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $loanSchemeDetail->created_at ? date('d M Y',
                                strtotime($loanSchemeDetail->created_at)) : 'N/A' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($loanSchemeDetail->is_active)
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                                @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{
                                $loanSchemeDetail->loanSchemeFeature->name }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">
                                {{ $loanSchemeDetail->loanSchemeFeature->loan_scheme_feature_name }}
                                <div class="text-xs text-gray-500">
                                    ({{ $loanSchemeDetail->loanSchemeFeature->loan_scheme_feature_type }})
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{
                                $loanSchemeDetail->loan_scheme_feature_value }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <button wire:click="editLoanSchemeDetail({{ $loanSchemeDetail->id }})"
                                    class="text-blue-600 hover:text-blue-900 mr-2">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="confirmDelete({{ $loanSchemeDetail->id }})"
                                    class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endforeach

            @if(empty($loanSchemeDetails))
            <div class="text-center py-8">
                <p class="text-gray-500">No loan scheme details found.</p>
            </div>
            @endif
    </div>

    <!-- Loan Scheme Modal -->
    <div id="loanSchemeDetailModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 {{ $showLoanSchemeDetailModal ? 'block' : 'hidden' }}">
        <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-2xl mx-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">New <span class="text-blue-700">Loan Scheme Detail</span>
                    Details Entry</h2>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="loanSchemeScheme" wire:submit.prevent="saveLoanSchemeDetail" class="space-y-4">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div class="mb-4">
                        <label for="loanScheme" class="block text-sm font-medium text-gray-700 mb-2">Loan Scheme</label>
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

                    <div class="mb-4">
                        <label for="loanSchemeFeature" class="block text-sm font-medium text-gray-700 mb-2">Loan Scheme
                            Feature</label>
                        <select wire:model="selectedLoanSchemeFeatureId" name="selectedLoanSchemeFeatureId"
                            id="selectedLoanSchemeFeatureId"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Loan Scheme Feature</option>
                            @foreach ($loanSchemeFeatures as $loanSchemeFeature)
                            <option value="{{ $loanSchemeFeature->id }}">{{ $loanSchemeFeature->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedLoanSchemeFeatureId')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                    <div class="mb-4">
                        <label for="loanScheme" class="block text-sm font-medium text-gray-700 mb-2">LS Feature
                            Type</label>
                        <input disabled wire:model="selectedLoanSchemeFeatureType" type="text" id="description"
                            name="description"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('selectedLoanSchemeFeatureType')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="loanSchemeFeature" class="block text-sm font-medium text-gray-700 mb-2">LS Feature
                            Name</label>
                        <input disabled wire:model="selectedLoanSchemeFeatureName" type="text" id="description"
                            name="description"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('description')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="loanSchemeFeature" class="block text-sm font-medium text-gray-700 mb-2">LS Feature
                            Value</label>
                        <input wire:model="selectedLoanSchemeFeatureValue" type="text"
                            id="selectedLoanSchemeFeatureValue" name="selectedLoanSchemeFeatureValue"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('selectedLoanSchemeFeatureValue')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    Save Loan Scheme Detail
                </button>
            </form>
        </div>
    </div>
    {{-- End of Loan Scheme Modal --}}

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 {{ $showDeleteConfirmModal ? 'block' : 'hidden' }}">
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
                    Are you sure you want to delete this loan scheme detail? This action cannot be undone.
                </p>
            </div>

            <div class="flex justify-end space-x-3">
                <button wire:click="cancelDelete"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors duration-200">
                    Cancel
                </button>
                <button wire:click="deleteLoanSchemeDetail"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors duration-200">
                    Delete
                </button>
            </div>
        </div>
    </div>
    {{-- End of Delete Confirmation Modal --}}

</div>