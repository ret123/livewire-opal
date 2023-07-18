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
use Livewire\WithFileUploads;



class ApplicantRegisterComponent extends Component
{
    use WithFileUploads;

    public $currentStep = 1;
    public $button = "Request OTP";
    public $show = false;
    public $secret = "";
    public $showMainForm = false;
    public $company_type = "NGO", $company_name, $company_number, $email, $first_name, $last_name, $country, $country_code, $phone, $website, $twitter, $linkedin, $facebook, $organization_status = [], $organization_description, $company_registration, $vat_registration, $additional_doc = [];
    public $other;
    public $otp;
    public $successMsg = '';
    public $hideOTP = false;
    public $countries;
    public $orgTypes;
    public $otherCheck;
    public $i = 1;


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
        'additional_doc.*' => 'mimes:pdf|max:10000',
    ];


    public function render()
    {
        return view('livewire.applicant-register-component');
    }

    public function sendOTP()
    {
        $validatedData = $this->validate();
        $current_timestamp = Carbon::now()->timestamp;
        $this->secret = $this->email . $current_timestamp;
        $otp = Otp::generate($this->secret);
        $this->button = "Sending OTP .....";
        try {

            Mail::to($this->email)->send(new SendOTP($otp));
            $this->button = "Resend OTP";

            session()->flash('message', 'OTP sent successfully!');
        } catch (Exception $e) {

            session()->flash('error', 'Something went wrong. Try again!');
        }
    }

    public function verifyOTP()
    {
        $validator = $this->validate([
            'otp' => 'required',

        ]);

        $valid = Otp::match($this->otp, $this->secret);
        if ($valid) {
            $this->showMainForm = true;
            $this->hideOTP = true;
            session()->flash('message', 'Email verified successfully!');
        } else {
            session()->flash('error', 'Invalid OTP. Try again!');
        }
    }

    public function updateCountryCode()
    {
        $this->country_code = Country::where('name', $this->country)->value('phonecode');
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
        $validatedData = $this->validate([
            'organization_status' => 'required',
            'organization_description' => 'required',
        ]);
        if ($this->other != null && $this->otherCheck && $this->i <= 1) {
            array_push($this->organization_status, $this->other);
            $this->i++;
        }

        $this->currentStep = 3;
    }
    public function back($step)
    {
        $this->currentStep = $step;
    }

    public function fileChange()
    {
        $this->validate();

        // dd($this->additional_doc);

        foreach ($this->additional_doc as $key => $doc) {
            $this->additional_doc[$key] = $doc->store('documents', 'public');
        }
    }
}
