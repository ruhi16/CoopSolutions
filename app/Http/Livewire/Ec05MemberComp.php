<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Ec05MemberComp extends Component{


    public $members;
    public $memberId = null, $name=null, $designation=null, $accountNo=null;

    public $showMemberModal = false;

    public function mount(){
        $this->members = \App\Models\Ec04Member::all();
        // dd($this->members);

    }


    public function openModal($memberId = null){
        if($memberId){
            $this->member = \App\Models\Ec04Member::find($memberId);
            // dd($this->members);  
            $this->memberId = $this->member->id;        
            $this->name = $this->member->name;
            $this->designation = $this->member->name_short;
            $this->accountNo = $this->member->is_active;
        }

        $this->showMemberModal = true;
    }

    public function closeModal(){
        $this->showMemberModal = false;
    }


    public function saveMemberDetails($memberId = null){
        // $this->closeModal();
        if($memberId){
            $this->members = \App\Models\Ec04Member::find($memberId);
            // dd($this->members);        
            $this->name = $this->members->name;
            $this->designation = $this->members->designation;
            $this->accountNo = $this->members->accountNo;
        }


        $member_data = $this->validate([
            'name' => 'required|string',
            'designation' => 'required|string',    
            'accountNo' => 'required|string',
        ]);

        try{
            $updated_member_data = \App\Models\Ec04Member::updateOrCreate([
                'id'    => $this->memberId,
            ], [
                'name'  => $member_data['name'],
                'designation'  => $member_data['designation'],
                'organisation_id'   => 1,
                'account_no'  => $member_data['accountNo'],
                'updated_at'  => date('Y-m-d H:i:s'),
                'created_at'  => date('Y-m-d H:i:s'),
            ]);

            // dd($updated_member_data);

            session()->flash('success', 'Member Data Updated Successfully'. $memberId ?? 'na');
            

        }catch(Exception $e){

            session()->flash('error', $e->getMessage());

        }


    $this->closeModal();
        // dd($member_data);
    }

    // public function doSomething()
    // {
    //     // Your PHP logic here
        
    //     // Optional: emit event to JavaScript
    //     $this->emit('jsEvent', ['data' => 'some value']);
    // }


    // public function doSomething2($param1 = null, $param2 = null)
    // {
    //     // Your logic here
    //     // logger("Method called with: ".$param1.", ".$param2);
    //     $this->emit('methodCalled', 'Success!');
    // }





    public function render()
    {
        return view('livewire.ec05-member-comp');
    }
}
