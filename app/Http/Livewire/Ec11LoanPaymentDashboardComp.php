<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ec08LoanAssign;
use App\Models\Ec06LoanScheme;
use App\Models\Ec08LoanAssignSchedule;
use App\Models\Ec09LoanAssignParticular;
use App\Models\Ec11LoanPayment;
use App\Models\Ec12LoanPaymentDetail;
use Carbon\Carbon;

class Ec11LoanPaymentDashboardComp extends Component
{
    public $activeTab = 'overview';
    
    public $showPaymentModal = false;
    public $showConfirmationModal = false;
    public $selectedPayment = [];
    public $paymentAmount;
    public $paymentDate;
    public $paymentMethod;
    
    // Data properties
    public $totalOutstanding = 0;
    public $totalPendingEmis = 0;
    public $activeLoansCount = 0;
    public $recentActivities = [];
    public $pendingPayments = [];
    public $paymentHistory = [];
    
    protected $listeners = [
        'paymentCompleted' => 'refreshData',
    ];
    
    public function mount()
    {
        $this->refreshData();
    }
    
    public function refreshData()
    {
        $this->loadOverviewData();
        $this->loadPendingPayments();
        $this->loadPaymentHistory();
    }
    
    private function loadOverviewData()
    {
        // Calculate total outstanding amount
        $this->totalOutstanding = Ec08LoanAssignSchedule::where('is_paid', false)
            ->where('payment_schedule_date', '<=', Carbon::today())
            ->sum('payment_schedule_total_amount');
        
        // Count total pending EMIs
        $this->totalPendingEmis = Ec08LoanAssignSchedule::where('is_paid', false)
            ->where('payment_schedule_date', '<=', Carbon::today())
            ->count();
        
        // Count active loans
        $this->activeLoansCount = Ec08LoanAssign::where('is_active', true)->count();
        
        // Load recent activities
        $this->recentActivities = [];
        $recentSchedules = Ec08LoanAssignSchedule::with(['loanAssign.member', 'loanAssign.loanScheme'])
            ->where('is_paid', false)
            ->orderBy('payment_schedule_date', 'asc')
            ->limit(5)
            ->get();
            
        foreach ($recentSchedules as $schedule) {
            $this->recentActivities[] = [
                'loan' => $schedule->loanAssign->loanScheme->name ?? 'N/A',
                'member' => $schedule->loanAssign->member->name ?? 'N/A',
                'due_date' => $schedule->payment_schedule_date->format('Y-m-d'),
                'amount' => $schedule->payment_schedule_total_amount,
                'status' => 'Pending'
            ];
        }
    }
    
    private function loadPendingPayments()
    {
        $this->pendingPayments = [];
        
        // Get loan assignments with pending EMIs
        $loanAssignments = Ec08LoanAssign::with([
            'member',
            'loanScheme',
            'loanAssignSchedules' => function($query) {
                $query->where('is_paid', false)
                      ->where('payment_schedule_date', '<=', Carbon::today());
            }
        ])->get();
        
        foreach ($loanAssignments as $assignment) {
            if ($assignment->loanAssignSchedules->count() > 0) {
                foreach ($assignment->loanAssignSchedules as $schedule) {
                    $this->pendingPayments[] = [
                        'id' => $schedule->id,
                        'loan_assign_id' => $assignment->id,
                        'member_name' => $assignment->member->name,
                        'loan_scheme' => $assignment->loanScheme->name,
                        'due_date' => $schedule->payment_schedule_date->format('Y-m-d'),
                        'amount' => $schedule->payment_schedule_total_amount,
                        'type' => 'EMI',
                        'schedule_id' => $schedule->id
                    ];
                }
            }
        }
        
        // Get loan assignments with other pending payments from Ec09LoanAssignParticular
        $particulars = Ec09LoanAssignParticular::with([
            'loanAssign.member',
            'loanAssign.loanScheme',
            'loanSchemeFeature'
        ])->whereDoesntHave('loanPaymentDetails')
          ->limit(10)
          ->get();
          
        foreach ($particulars as $particular) {
            if ($particular->loanAssign && $particular->loanAssign->member && $particular->loanAssign->loanScheme) {
                $this->pendingPayments[] = [
                    'id' => $particular->id,
                    'loan_assign_id' => $particular->loan_assign_id,
                    'member_name' => $particular->loanAssign->member->name,
                    'loan_scheme' => $particular->loanAssign->loanScheme->name,
                    'due_date' => Carbon::today()->format('Y-m-d'),
                    'amount' => $particular->amount ?? 0,
                    'type' => 'Other',
                    'particular_id' => $particular->id
                ];
            }
        }
    }
    
    private function loadPaymentHistory()
    {
        $this->paymentHistory = [];
        
        $payments = Ec11LoanPayment::with([
            'loanAssign.member',
            'loanAssign.loanScheme'
        ])->orderBy('payment_date', 'desc')
          ->limit(10)
          ->get();
          
        foreach ($payments as $payment) {
            if ($payment->loanAssign && $payment->loanAssign->member && $payment->loanAssign->loanScheme) {
                $this->paymentHistory[] = [
                    'member_name' => $payment->loanAssign->member->name,
                    'loan_scheme' => $payment->loanAssign->loanScheme->name,
                    'payment_date' => $payment->payment_date->format('Y-m-d'),
                    'amount' => $payment->payment_total_amount,
                    'type' => 'EMI'
                ];
            }
        }
    }
    
    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }
    
    public function processPayment($paymentId)
    {
        // Find the payment in pending payments
        $payment = collect($this->pendingPayments)->firstWhere('id', $paymentId);
        
        if ($payment) {
            $this->selectedPayment = $payment;
            $this->paymentAmount = $payment['amount'];
            $this->paymentDate = Carbon::today()->format('Y-m-d');
            $this->paymentMethod = '';
            $this->showPaymentModal = true;
        }
    }
    
    public function confirmPayment()
    {
        $this->validate([
            'paymentAmount' => 'required|numeric|min:0',
            'paymentDate' => 'required|date',
            'paymentMethod' => 'required|string',
        ]);
        
        $this->showPaymentModal = false;
        $this->showConfirmationModal = true;
    }
    
    public function completePayment()
    {
        try {
            // Create the main loan payment record
            $loanPayment = Ec11LoanPayment::create([
                'loan_assign_id' => $this->selectedPayment['loan_assign_id'],
                'member_id' => Ec08LoanAssign::find($this->selectedPayment['loan_assign_id'])->member_id,
                'payment_schedule_id' => $this->selectedPayment['schedule_id'] ?? null,
                'payment_total_amount' => $this->paymentAmount,
                'payment_principal_amount' => 0, // Will be calculated based on schedule
                'regular_amount_total' => 0,
                'scheduled_amount_total' => $this->paymentAmount,
                'payment_date' => $this->paymentDate,
                'is_paid' => true,
                'principal_balance_amount' => 0, // Will be calculated
                'is_active' => true,
                'remarks' => 'Payment made via ' . ucfirst(str_replace('_', ' ', $this->paymentMethod)),
            ]);
            
            // Create payment detail record
            Ec12LoanPaymentDetail::create([
                'loan_assign_id' => $this->selectedPayment['loan_assign_id'],
                'loan_payment_id' => $loanPayment->id,
                'loan_schedule_id' => $this->selectedPayment['schedule_id'] ?? null,
                'loan_assign_particular_id' => $this->selectedPayment['particular_id'] ?? null,
                'loan_assign_current_balance_copy' => 0,
                'loan_assign_particular_amount' => $this->paymentAmount,
                'is_scheduled' => isset($this->selectedPayment['schedule_id']),
                'is_fixed_amount' => false,
                'is_active' => true,
                'remarks' => 'Payment made via ' . ucfirst(str_replace('_', ' ', $this->paymentMethod)),
            ]);
            
            // Update the schedule item as paid if it's an EMI payment
            if (isset($this->selectedPayment['schedule_id'])) {
                $schedule = Ec08LoanAssignSchedule::find($this->selectedPayment['schedule_id']);
                if ($schedule) {
                    $schedule->update(['is_paid' => true]);
                }
            }
            
            // Close modals and refresh data
            $this->closeConfirmationModal();
            $this->refreshData();
            
            session()->flash('message', 'Payment completed successfully!');
            
            // Emit event for other components
            $this->emit('paymentCompleted');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error processing payment: ' . $e->getMessage());
            $this->closeConfirmationModal();
        }
    }
    
    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->resetForm();
    }
    
    public function closeConfirmationModal()
    {
        $this->showConfirmationModal = false;
        $this->resetForm();
    }
    
    private function resetForm()
    {
        $this->selectedPayment = [];
        $this->paymentAmount = null;
        $this->paymentDate = null;
        $this->paymentMethod = null;
    }
    
    public function render()
    {
        return view('livewire.ec11-loan-payment-dashboard-comp');
    }
}
