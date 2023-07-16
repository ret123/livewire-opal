<?php

namespace App\Http\Livewire;

use App\Mail\SendOTP;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Tzsk\Otp\Facades\Otp;

class ApplicantRegisterComponent extends Component
{
    public $currentStep = 1;
    public $button = "Request OTP";
    public $otp = "";
    public $showMainForm  =false;
    public  $company_type = "NGO", $company_name, $company_number, $email, $first_name, $last_name, $country, $phone, $website, $twitter, $linkedin, $facebook, $organization_status, $organization_description, $company_registration, $vat_registration, $additional_doc;

    public $successMsg = '';


    public function render()
    {
        return view('livewire.applicant-register-component');

    }

    public function sendOTP() {
        $validatedData = $this->validate([
            'company_type' => 'required',
            'company_number' => 'exclude_if:company_number,null|required|unique:organizations',
            'company_name' => 'required|unique:organizations',
            'email' => 'required|email|unique:organizations',
            
        ]);
        $current_timestamp = Carbon::now()->timestamp;
        $otp = Otp::generate($this->email.$current_timestamp);
        $this->button="Sending OTP .....";
        try {
            
            Mail::to($this->email)->send(new SendOTP($otp));
            $this->button="Resend OTP";
            
        } catch (Exception $e) {
            return response()->route('home')->with('errors','Something went wrong. Try again!');
        }
        
    }

    public function verifyOTP() {
        $this->showMainForm = true;
    }

    public function firstStepSubmit()
    {
        $validatedData = $this->validate([
           
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
