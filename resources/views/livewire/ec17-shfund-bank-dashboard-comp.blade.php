<div>
    <div class="rounded-lg shadow-sm overflow-hidden">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8" aria-label="Tabs">
                <button 
                    wire:click="switchTab('transactions')"
                    class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'transactions' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                >
                    <i class="fas fa-exchange-alt mr-2"></i>
                    Transactions
                </button>
                
                <button 
                    wire:click="switchTab('masterdb')"
                    class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'masterdb' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                >
                    <i class="fas fa-database mr-2"></i>
                    Master Database
                </button>
                
                <button 
                    wire:click="switchTab('specifications')"
                    class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'specifications' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                >
                    <i class="fas fa-cog mr-2"></i>
                    Specifications
                </button>
            </nav>
        </div>
        
        <!-- Tab Content -->
        <div class="p-6">
            @if($activeTab === 'transactions')
                @livewire('ec17-shfund-bank-transaction-comp')
            @elseif($activeTab === 'masterdb')
                @livewire('ec17-shfund-bank-master-db-comp')
            @elseif($activeTab === 'specifications')
                @livewire('ec17-shfund-bank-specification-comp')
            @endif
        </div>
    </div>
</div>