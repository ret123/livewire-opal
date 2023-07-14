<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ApplicantRegisterComponent extends Component
{
    public $currentStep = 1;
    public  $company_type, $company_name, $company_number, $email, $first_name, $last_name, $country, $phone, $website, $twitter, $linkedin, $facebook, $organization_status, $organization_description, $company_registration, $vat_registration, $additional_doc;

    public $successMsg = '';


    public function render()
    {
        return view('livewire.applicant-register-component');
    }
    public function firstStepSubmit()
    {
        $validatedData = $this->validate([
            'company_type' => 'required',
            'company_number' => 'exclude_if:company_number,null|required|unique:organizations',
            'company_name' => 'required|unique:organizations',
            'email' => 'required|email|unique:organizations',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required'
        ]);

        $this->currentStep = 2;
    }
    public function secondStepSubmit()
    {


        $this->currentStep = 3;
    }
    public function back($step)
    {
        $this->currentStep = $step;
    }
}
