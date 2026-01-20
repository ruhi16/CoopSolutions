<div>
    <!-- Notification Messages -->
    @if(session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif
    
    @if(session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Loan <span class="text-blue-700">Payment</span> Dashboard</h1>
    </div>

    <!-- Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <button 
                wire:click="switchTab('overview')" 
                class="whitespace-nowrap pb-3 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'overview' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
            >
                Overview
            </button>
            <button 
                wire:click="switchTab('pending-payments')" 
                class="whitespace-nowrap pb-3 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'pending-payments' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
            >
                Pending Payments ({{ count($pendingPayments) }})
            </button>
            <button 
                wire:click="switchTab('payment-history')" 
                class="whitespace-nowrap pb-3 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'payment-history' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
            >
                Payment History
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div>
        @if($activeTab === 'overview')
            @include('livewire.components.loan-payment-dashboard.overview-tab')
        @elseif($activeTab === 'pending-payments')
            @include('livewire.components.loan-payment-dashboard.pending-payments-tab')
        @elseif($activeTab === 'payment-history')
            @include('livewire.components.loan-payment-dashboard.payment-history-tab')
        @endif
    </div>

    <!-- Payment Modal -->
    @include('livewire.components.loan-payment-dashboard.payment-modal')

    <!-- Payment Confirmation Modal -->
    @include('livewire.components.loan-payment-dashboard.confirmation-modal')
</div>