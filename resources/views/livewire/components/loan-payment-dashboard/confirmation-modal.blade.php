<!-- Confirmation Modal -->
<div x-data="{ show: @entangle('showConfirmationModal') }" x-show="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 m-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Confirm Payment</h3>
            <button wire:click="closeConfirmationModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="space-y-4">
            <div class="bg-yellow-50 p-4 rounded-md">
                <p class="text-sm text-yellow-700">
                    Please confirm the payment details before proceeding:
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Member</label>
                <p class="mt-1 text-sm text-gray-900">{{ $selectedPayment['member_name'] ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Loan Scheme</label>
                <p class="mt-1 text-sm text-gray-900">{{ $selectedPayment['loan_scheme'] ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Due Date</label>
                <p class="mt-1 text-sm text-gray-900">{{ $selectedPayment['due_date'] ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Amount</label>
                <p class="mt-1 text-sm text-gray-900">{{ $selectedPayment['amount'] ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Payment Amount</label>
                <p class="mt-1 text-sm text-gray-900">{{ $paymentAmount ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Payment Date</label>
                <p class="mt-1 text-sm text-gray-900">{{ $paymentDate ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                <p class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $paymentMethod)) ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <button 
                wire:click="closeConfirmationModal" 
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md"
            >
                Cancel
            </button>
            <button 
                wire:click="completePayment" 
                class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md"
            >
                Complete Payment
            </button>
        </div>
    </div>
</div>