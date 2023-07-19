<?php

namespace App\Http\Livewire;

use App\Mail\SendOTP;
use App\Models\Country;
use App\Models\Organization;
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
    public $files = [];
    public $fileArray = [];


    protected $listeners = ['fileChange' => 'fileChange', 'fileRemoved' => 'fileRemoved'];


    public function mount()
    {
        $this->countries = Country::all();
        $this->orgTypes = OrganizationType::all();
    }

    protected $rules = [];


    public function render()
    {
        return view('livewire.applicant-register-component');
    }

    public function sendOTP()
    {
        $validatedData = $this->validate([
            'company_type' => 'required',
            'company_number' => 'exclude_if:company_type,"NGO"|required|unique:organizations',
            'company_name' => 'required|unique:organizations',
            'email' => 'required|email|unique:organizations',
        ]);
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
        // $this->phone = $this->country_code . $this->phone;
        $validatedData = $this->validate([

            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'country' => 'required',
            'country_code' => 'required',
            'phone' => 'required'

        ]);
        // Phone validation to check if code and phone number matches
        $matchingCode = Organization::where('country_code', $this->country_code)->get();
        foreach ($matchingCode as $code) {
            $match = $code->where('phone', $this->phone)->first();
            if ($match !== null) {
                return  session()->flash('error', 'Phone number already registered');
            }
        }

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

        // $this->emitSelf('countFiles');
    }

    public function countFiles()
    {
        // dd($this->files);
    }

    public function fileRemoved($filename)
    {
        // dd($this->fileArray);
        // dd($filename);
    }
    public function store()
    {
        $validatedData = $this->validate([
            'company_registration' => 'exclude_if:company_type,"NGO"|required|mimes:pdf|max:10240',
            'vat_registration' => 'exclude_if:company_type,"NGO"|required|mimes:pdf|max:10240',
            'additional_doc.*' => 'mimes:pdf|max:10240',
            'additional_doc' => 'array|max:4|distinct'
        ], [
            'additional_doc.max' => 'You can upload maximum 4 files'
        ]);

        $org  = new Organization();
        if ($this->company_registration != null) {

            $org->company_registration = $this->company_registration->store('documents/' . $this->company_name . '/registration', 'public');
        }
        if ($this->company_registration != null) {
            $org->vat_registration = $this->company_registration->store('documents/' . $this->company_name . '/vat', 'public');
        }

        if (!empty($this->additional_doc)) {
            foreach ($this->additional_doc as $key => $doc) {
                $filename = $doc->getClientOriginalName();
                if (in_array($filename, $this->fileArray)) {
                    return  session()->flash('fileError', 'Same files are not allowed!');
                }
                array_push($this->fileArray, $doc->getClientOriginalName());
                $this->files[$key] = $doc->store('documents/' . $this->company_name, 'public');
            }
            // $this->reset('fileArray');
        }

        // dd($this->fileArray);
        $org->company_type = $this->company_type;
        $org->company_name = $this->company_name;
        $org->company_number = $this->company_number;
        $org->email = $this->email;
        $org->first_name = $this->first_name;
        $org->last_name = $this->last_name;
        $org->country = $this->country;
        $org->country_code = $this->country_code;
        $org->phone = $this->phone;
        $org->website = $this->website;
        $org->twitter = $this->twitter;
        $org->linkedin = $this->linkedin;
        $org->facebook = $this->facebook;
        $org->organization_status = $this->organization_status;
        $org->organization_description = $this->organization_description;
        $org->files = $this->files;

        if ($org->save()) {
            $this->reset();
            session()->flash('message', 'Registration Successfull!');
            return redirect()->route('register.success');
        } else {
            $this->reset();
            session()->flash('error', 'Something went wrong. Try Again ');
        }
    }
}
