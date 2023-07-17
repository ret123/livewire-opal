<?php

namespace App\Http\Livewire;

use App\Mail\SendOTP;
use App\Models\Country;
use App\Models\OrganizationType;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Tzsk\Otp\Facades\Otp;
use Tzsk\Otp\Otp as OtpOtp;

class ApplicantRegisterComponent extends Component
{
    public $currentStep = 1;
    public $button = "Request OTP";
    public $show = false;
    public $secret = "";
    public $showMainForm = false;
    public  $company_type = "NGO", $company_name, $company_number, $email, $first_name, $last_name, $country,$country_code, $phone, $website, $twitter, $linkedin, $facebook, $organization_status=[], $organization_description, $company_registration, $vat_registration, $additional_doc;
    public $other;
    public $otp;
    public $successMsg = '';
    public $hideOTP = false;
    public $countries;
    public $orgTypes;
    public $otherCheck;

    public function mount()
    {
        $this->countries = Country::all();
        $this->orgTypes = OrganizationType::all();
    }

    protected $rules = [
        'company_type' => 'required',
        'company_number' => 'exclude_if:company_type,"NGO"|required|unique:organizations',
        'company_name' => 'required|unique:organizations',
        'email' => 'required|email|unique:organizations',
    ];


    public function render()
    {
        return view('livewire.applicant-register-component');

    }

    public function sendOTP() {
        $validatedData = $this->validate();
        $current_timestamp = Carbon::now()->timestamp;
        $this->secret = $this->email.$current_timestamp;
        $otp = Otp::generate($this->secret);
        $this->button="Sending OTP .....";
        try {
            
            Mail::to($this->email)->send(new SendOTP($otp));
            $this->button="Resend OTP";

           session()->flash('message','OTP send successfully!');
            
        } catch (Exception $e) {
            
            session()->flash('error','Something went wrong. Try again!');
        }
        
    }

    public function verifyOTP() {
        $validator = $this->validate([
            'otp' => 'required',
           
        ]);
     
        $valid = Otp::match($this->otp, $this->secret);
        if ($valid) {
            $this->showMainForm = true;
            $this->hideOTP =true;
            session()->flash('message','Email verified successfully!');

        } else {
            session()->flash('error', 'Invalid OTP. Try again!');
        }

       
    }

    public function updateCountryCode() {
        $this->country_code = Country::where('name',$this->country)->value('phonecode');
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
      
        if($this->other != null && $this->otherCheck) {
            array_push($this->organization_status,$this->other);
        }

        $this->currentStep = 3;
    }
    public function back($step)
    {
        $this->currentStep = $step;
    }
}
