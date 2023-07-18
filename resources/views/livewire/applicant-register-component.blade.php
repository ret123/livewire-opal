<div>
    @if (session()->has('message'))
    <div class="alert alert-success mt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        {{ session('message') }}
    </div>
    @endif
    @if (session()->has('error'))
    <div class="alert alert-danger mt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        {{ session('error') }}
    </div>
    @endif
    <div class="stepwizard mt-4">
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
    <div class=" container setup-content  mt-4  justify-content-center {{ $currentStep != 1 ? 'display-none' : '' }}" id="step-1">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Contact Details</div>
                    <div class="card-body">

                        @if ($errors->any())
                        <div class="alert alert-danger" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
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
                            <input class="form-check-input" type="radio" wire:model="company_type" name="company_type" value="NGO" id="ngo" checked wire:click="$set('show', false)" :errors="$errors">
                            <label class="form-check-label" for="ngo">
                                NGO
                            </label>

                            <input class="form-check-input ms-4" type="radio" wire:model="company_type" name="company_type" value="Company" id="company" wire:click="$set('show', true)" :errors="$errors">
                            <label class="form-check-label" for="company">
                                Company
                            </label>
                        </div>
                        @if($show)
                        <div class="row">
                            <div id="div1" class="form-group  col-md-4">
                                <label for="company_number">Company Registration Number:</label>
                                <input type="text" wire:model="company_number" class="form-control mt-2" id="company_number" name="company_number">
                                @error('company_number') <p class="text-danger">{{$message}}</p> @enderror
                            </div>
                        </div>
                        @endif
                        <form wire:submit.prevent="sendOTP">
                            <div class="row mt-2">

                                <div class="form-group col-md-4">
                                    <label for="company_name">Company Name:</label>
                                    <input type="text" wire:model="company_name" class="form-control mt-2" id="company_name" name="company_name" :errors="$errors" />
                                    @error('company_name') <p class="text-danger">{{$message}}</p> @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="email">Email:</label>
                                    <input type="text" wire:model="email" class="form-control mt-2" id="email" name="email" :errors="$errors" />
                                    @error('email') <p class="text-danger">{{$message}}</p> @enderror
                                </div>
                                @if(!$hideOTP)
                                <div class="form-group col-md-4">
                                    <label for="otp">Verify Email Address </label> <br>
                                    <button class="btn btn-primary mt-2" type="submit" id="getOTP" wire:loading.attr="disabled" wire:click="sendOTP">
                                        <span wire:loading.remove wire:target="sendOTP">Request OTP</span>
                                        <span wire:loading wire:target="sendOTP">Sending OTP...</span>
                                    </button>
                                </div>
                                @endif
                            </div>
                        </form>
                        @if(!$hideOTP)
                        <form wire:submit.prevent="verifyOTP">
                            <div class="row mt-2">
                                <div class="form-group col-md-4">
                                    <label for="otp">OTP:</label>
                                    <input type="text" id="otp" wire:model="otp" class="form-control mt-2" name="otp" />
                                </div>
                            </div>
                            <button class="btn btn-primary mt-4" wire:click="verifyOTP">Validate OTP</button>
                        </form>
                        @endif

                        @if($showMainForm)
                        <div id="show-form" class="mt-4">

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="first_name">First Name <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="first_name" value="{{ $company->first_name ?? '' }}" class="form-control mt-2" id="first_name" name="first_name" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="last_name" value="{{ $company->last_name ?? '' }}" class="form-control mt-2" id="last_name" name="last_name" />
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="form-group col-md-4">
                                    <label for="country">Country <span class="text-danger">*</span></label>
                                    <select class="form-select form-control mt-2" aria-label="Default select example" name="country" id="country" wire:model="country" wire:change="updateCountryCode">
                                        <option value="">Select Country</option>
                                        @foreach($countries as $country)
                                        <option value="{{$country->name}}">{{$country->name}}</option>
                                        @endforeach

                                    </select>
                                    @error('country')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-8">
                                    <label for="phone">Phone Number <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <input type="number" wire:model="country_code" class="form-control mt-2" id="country_code" name="country_code" />
                                        </div>
                                        <div class="col-md-6 ms-4">
                                            <input type="number" wire:model="phone" class="form-control mt-2" id="phone" name="phone" />
                                            <!-- @error('phone')
                                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                                            @enderror -->
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="row mt-2">
                                <div class="form-group col-md-4">
                                    <label for="website">Website:</label>
                                    <input type="text" wire:model="website" value="{{ $company->website ?? '' }}" class="form-control mt-2" id="website" name="website" />
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="twitter">Twitter:</label>
                                    <input type="text" wire:model="twitter" value="{{ $company->twitter ?? '' }}" class="form-control mt-2" id="twitter" name="twitter" />
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="form-group col-md-4">
                                    <label for="linkedin">LinkedIn:</label>
                                    <input type="text" wire:model="linkedin" value="{{ $company->linkedin ?? '' }}" class="form-control mt-2" id="linkedin" name="linkedin" />
                                </div>


                                <div class="form-group col-md-4">
                                    <label for="facebook">Facebook:</label>
                                    <input type="text" wire:model="facebook" value="{{ $company->facebook ?? '' }}" class="form-control mt-2" id="facebook" name="facebook" />
                                </div>
                            </div>

                            <button class="btn btn-primary mt-4" wire:click="firstStepSubmit">Next Step</button>



                        </div>
                        @endif

                    </div>

                    <div class="card-footer">

                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="setup-content container mt-4 justify-content-center {{ $currentStep != 2 ? 'display-none' : '' }}" id="step-2">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Organization Details</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                        <div class="alert alert-danger" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <h6>Status of Organization</h6>
                        <div class="row">
                            @foreach($orgTypes->split($orgTypes->count()/2) as $row)
                            <div class="col-md-6 ">
                                @foreach($row as $data)
                                <div class="form-check mt-4">
                                    <input type="checkbox" name="organization_status[]" value="{{$data->name}}" class="form-check-input" wire:model="organization_status">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        {{$data->name}}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                        <div class="row mt-2 ">
                            <div class="input-group">
                                <div class="col-md-1">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" name="organization_status[]" value="" id="otherCheck" class="form-check-input" wire:model="otherCheck">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Other
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-4 ms-2">
                                    <input type="text" class="form-control" name="other" id="other" value="{{$other?? old('other') }} " wire:model="other">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="form-group col-md-12">
                                <label for="organization_description">
                                    <h6>Organization Descriptions:</h6>
                                </label>
                                <textarea class="form-control" id="organization_description" name="organization_description" rows="4" wire:model="organization_description">{{ old('organization_description') ?? '' }}</textarea>

                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <button class="btn btn-primary" wire:click="secondStepSubmit">Next Step</button>
                                <button class="btn btn-danger  pull-right" type="button" wire:click="back(1)">Back</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="setup-content  container mt-4 justify-content-center {{ $currentStep != 3 ? 'display-none' : '' }}" id="step-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Supporting Documents</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                        <div class="alert alert-danger" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <form>


                            @if($company_type == "company")
                            <div class="row">
                                <div class="col-md-4">
                                    Company Registration <span class="text-danger">*</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="image-upload justify-content-center">
                                        <input id="file-input" type="file" class="form-control" name="company_registration" />
                                    </div>
                                </div>

                            </div>



                            <div class="row mt-4">
                                <div class="col-md-4">
                                    VAT Registration <span class="text-danger">*</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="image-upload justify-content-center">
                                        <input id="file-input" type="file" class="form-control" name="vat_registration" />
                                    </div>
                                </div>
                            </div>
                            @endif

                            @error('additional_doc.*') <span class="text-red-500 italic text-sm px-3">{{ $message }}</span> @enderror
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    Additional Attachment(s)
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            Additional Documents
                                        </div>
                                        <div class="card-body">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">No.</th>
                                                        <th scope="col">Filename</th>
                                                        <th scope="col">Remove</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @foreach($additional_doc as $key => $file)

                                                    <tr>
                                                        <td>{{++$key}}</td>
                                                        <td>{{$file->getClientOriginalName()}}</td>
                                                        <td><i class="bi bi-trash" @click="removeUpload('{{$file->getFilename()}}')" style="cursor:pointer"></i></td>
                                                    </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                    <input type="file" name="" id="myFiles" class="form-control mt-4 mb-4" wire:model="additional_doc" wire:change="fileChange">
                                </div>

                            </div>

                            <div class="card-footer">
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <button class="btn btn-danger  pull-right" type="button" wire:click="back(2)">Back</button>
                                        <button class="btn btn-success" wire:click="thirdStepSubmit">Submit</button>

                                    </div>
                                </div>
                            </div>
                        </form>
                        <script>
                            function removeUpload(filename) {
                                @this.removeUpload('additional_doc', filename)
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    // const filesSelector = document.querySelector('#myFiles');

    // filesSelector.addEventListener('change', () => {

    //     const fileList = [...filesSelector.files];
    //     fileList.forEach((file, index) => {
    //         console.log(file[index].name);
    //         @this.set('additional_doc.' + index + '.fileName', file.name)
    //         @this.set('additional_doc.' + index + '.fileSize', file.size)
    //         @this.upload('additional_doc.' + index + '.fileRef', file, (n) => {}, () => {}, (e) => {});

    //     });

    // });
</script>
@endpush