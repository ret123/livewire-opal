<div>
    <div class="stepwizard">
        <div class="stepwizard-row setup-panel">
            <div class="multi-wizard-step">
                <a href="#step-1" type="button" class="btn {{ $currentStep != 1 ? 'btn-default' : 'btn-primary' }}">1</a>
                <p>Step 1</p>
            </div>
            <div class="multi-wizard-step">
                <a href="#step-2" type="button" class="btn {{ $currentStep != 2 ? 'btn-default' : 'btn-primary' }}">2</a>
                <p>Step 2</p>
            </div>
            <div class="multi-wizard-step">
                <a href="#step-3" type="button" class="btn {{ $currentStep != 3 ? 'btn-default' : 'btn-primary' }}" disabled="disabled">3</a>
                <p>Step 3</p>
            </div>
        </div>
    </div>
    <div class=" container setup-content  mt-4 {{ $currentStep != 1 ? 'display-none' : '' }}" id="step-1">
        <div class="row">
            <div class="col-md-10">


                <div class="card">
                    <div class="card-header">Contact Details</div>
                    <div class="card-body">

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>
                        <div class="form-inline mb-4">
                            <input class="form-check-input" type="radio" wire:model="company_type" name="company_type" value="NGO" id="flexRadioDefault1" checked">
                            <label class="form-check-label" for="flexRadioDefault1">
                                NGO
                            </label>

                            <input class="form-check-input ml-4" type="radio" wire:model="company_type" name="company_type"  value="Company" id="flexRadioDefault2" onclick="show2();">
                            <label class="form-check-label" for="flexRadioDefault2">
                                Company
                            </label>
                        </div>
                        <div class="row">
                            <div id="div1" class="form-group  col-md-4" style="display:none">
                                <label for="company_number">Company Registration Number:</label>
                                <input type="text" wire:model="company_number" class="form-control" id="company_number" name="company_number">
                            </div>
                        </div>

                        <div class="row">
                            <form action="">
                                <div class="form-group col-md-4">
                                    <label for="company_name">Company Name:</label>
                                    <input type="text" wire:model="company_name" class="form-control" id="company_name" name="company_name" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="email">Email:</label>
                                    <input type="text" wire:model="email" class="form-control" id="email" name="email" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="otp">Verify 
                                    <button class="btn btn-primary" wire:click="sendOTP" 
                                    wire:loading.attr="disabled"
                                    id="getOTP">
                                    <span wire:loading.remove wire.target="sendOTP">Request OTP</span>
                                    <span wire:loading wire.target="sendOTP" style="display:none">Sending..</span>
                                    </button>
                                  
                                </div>
                            </form>
                           
                        </div>

                        <div class="row">

                            <div class="form-group col-md-4">
                                <label for="otp">OTP:</label>
                                <input type="text" id="otp" class="form-control" name="otp" />
                            </div>
                        </div>
                        <button class="btn btn-primary" wire:click="verifyOTP">Validate OTP</button>
                        @if($showMainForm == true)
                        <div id="show-form" x-show="{showMainForm = true}" class="mt-4">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="first_name">First Name:</label>
                                    <input type="text" wire:model="first_name" value="{{ $company->first_name ?? '' }}" class="form-control" id="first_name" name="first_name" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="last_name">Last Name:</label>
                                    <input type="text" wire:model="last_name" value="{{ $company->last_name ?? '' }}" class="form-control" id="last_name" name="last_name" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="category">Country:</label>
                                    <select class="form-select" wire:model="country" form-control" aria-label="Default select example" name="country">

                                        <option value="India">India</option>
                                        <option value="US">US</option>
                                        <option value="Pakistan">Pakistan</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="phone">Phone Number:</label>
                                    <input type="text" wire:model="phone" value="{{ $company->phone ?? '' }}" class="form-control" id="phone" name="phone" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="website">Website:</label>
                                    <input type="text" wire:model="website" value="{{ $company->website ?? '' }}" class="form-control" id="website" name="website" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="description">Media Links:</label>


                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="twitter">Twitter:</label>
                                    <input type="text" wire:model="twitter" value="{{ $company->twitter ?? '' }}" class="form-control" id="twitter" name="twitter" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="linkedin">LinkedIn:</label>
                                    <input type="text" wire:model="linkedin" value="{{ $company->linkedin ?? '' }}" class="form-control" id="linkedin" name="linkedin" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="facebook">Facebook:</label>
                                    <input type="text" wire:model="facebook" value="{{ $company->facebook ?? '' }}" class="form-control" id="facebook" name="facebook" />
                                </div>
                            </div>

                            <button class="btn btn-primary" wire:click="firstStepSubmit">Next Step</button>



                        </div>
                        @endif



                    </div>

                    <div class="card-footer">

                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="setup-content container mt-4 {{ $currentStep != 2 ? 'display-none' : '' }}" id="step-2">
        <div class="row">
            <div class="col-md-10">
                <form>
                    @csrf

                    <div class="card">
                        <div class="card-header">
                            <h4>Organization Details</h4>
                        </div>



                        <div class="card-body">

                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <h6>Status of Organization</h6>

                            <div class="row">


                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="checkbox" name="organization_status[]" value="others">
                                    Others

                                </div>

                            </div>



                            <div class="row mt-4">
                                <div class="form-group col-md-12">
                                    <label for="organization_description">
                                        <h6>Organization Descriptions:</h6>
                                    </label>
                                    <textarea class="form-control" id="organization_description" name="organization_description" rows="4">{{ old('organization_description') ?? '' }}</textarea>

                                </div>
                            </div>


                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">Next Step</button>
                                    <button class="btn btn-danger  pull-right" type="button" wire:click="back(1)">Back</button>
                                </div>
                            </div>



                        </div>

                        <div class="card-footer">

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="setup-content  container mt-4 {{ $currentStep != 3 ? 'display-none' : '' }}" id="step-3">
        <div class="row">
            <div class="col-md-10">


                <div class="card">
                    <div class="card-header">
                        <h4>Supporting Documents</h4>
                    </div>



                    <div class="card-body">

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <h6>Document Name</h6>
                            </div>
                            <div class="col-md-4">
                                Upload File
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                Company Registration
                            </div>
                            <div class="col-md-4">
                                <div class="image-upload justify-content-center">
                                    <label for="file-input">
                                        <i class="fa-solid fa-upload" style="cursor:pointer"></i>
                                    </label>

                                    <input id="file-input" type="file" name="company_registration" />
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                VAT Registration
                            </div>
                            <div class="col-md-4">
                                <div class="image-upload justify-content-center">
                                    <label for="file-input">
                                        <i class="fa-solid fa-upload" style="cursor:pointer"></i>
                                    </label>

                                    <input id="file-input" type="file" name="vat_registration" />
                                </div>
                            </div>

                        </div>

                        <div class="row mt-4">
                            <div class="col-md-4">
                                <h6>Additional Documents</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                Additional Document 1
                            </div>
                            <div class="col-md-4">
                                <div class="image-upload justify-content-center">
                                    <label for="file-input">
                                        <i class="fa-solid fa-upload" style="cursor:pointer"></i>
                                    </label>

                                    <input id="file-input" type="file" name="doc_1" style="display:none" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                Additional Document 2
                            </div>
                            <div class="col-md-4">
                                <div class="image-upload justify-content-center">
                                    <label for="file-input">
                                        <i class="fa-solid fa-upload" style="cursor:pointer"></i>
                                    </label>

                                    <input id="file-input" type="file" name="doc_2" style="display:none" />
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">Next Step</button>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>


</div>