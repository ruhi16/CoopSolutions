<!-- Payment Modal -->
<div x-data="{ show: @entangle('showPaymentModal') }" x-show="show"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 m-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Process Payment</h3>
            <button wire:click="closePaymentModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="space-y-4">
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
                <label class="block text-sm font-medium text-gray-700">Payment Type</label>
                <p class="mt-1 text-sm text-gray-900">{{ $selectedPayment['type'] ?? 'N/A' }}</p>
            </div>

            <div>
                <label for="paymentAmount" class="block text-sm font-medium text-gray-700">Payment Amount</label>
                <input type="number" id="paymentAmount" wire:model="paymentAmount"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    min="0" step="0.01">
                @error('paymentAmount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="paymentDate" class="block text-sm font-medium text-gray-700">Payment Date</label>
                <input type="date" id="paymentDate" wire:model="paymentDate"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                @error('paymentDate')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="paymentMethod" class="block text-sm font-medium text-gray-700">Payment Method</label>
                <select id="paymentMethod" wire:model="paymentMethod"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Select Method</option>
                    <option value="cash">Cash</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="cheque">Cheque</option>
                    <option value="online">Online</option>
                </select>
                @error('paymentMethod')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <button wire:click="closePaymentModal"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md">
                Cancel
            </button>
            <button wire:click="confirmPayment"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                Confirm Payment
            </button>
        </div>
    </div>
</div>