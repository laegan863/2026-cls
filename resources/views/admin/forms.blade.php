@extends('layouts.admin')

@section('title', 'Form Elements')

@section('content')
    <!-- Page Header -->
    <x-page-header title="Form Elements" subtitle="Beautiful, accessible form components with custom styling">
        <x-breadcrumb>
            <x-breadcrumb-item href="/admin">Dashboard</x-breadcrumb-item>
            <x-breadcrumb-item href="#">Forms</x-breadcrumb-item>
            <x-breadcrumb-item active>Form Elements</x-breadcrumb-item>
        </x-breadcrumb>
    </x-page-header>

    <div class="row g-4">
        <!-- Basic Input Fields -->
        <div class="col-lg-6">
            <x-card title="Basic Input Fields" icon="bi bi-input-cursor-text">
                <x-field label="Username" required hint="Your unique identifier for login">
                    <x-input type="text" placeholder="Enter your username" />
                </x-field>
                
                <x-field label="Email Address" required>
                    <x-input type="email" placeholder="name@example.com" />
                </x-field>
                
                <x-field label="Password">
                    <x-input type="password" placeholder="Enter your password" />
                </x-field>
                
                <x-field label="Disabled Input">
                    <x-input type="text" placeholder="This is disabled" disabled />
                </x-field>
                
                <x-field label="Read Only" class="mb-0">
                    <x-input type="text" value="This is read only" readonly />
                </x-field>
            </x-card>
        </div>

        <!-- Input with Icons -->
        <div class="col-lg-6">
            <x-card title="Input with Icons" icon="bi bi-stars">
                <x-field label="With Left Icon">
                    <x-input type="text" placeholder="Username" prepend-icon="bi bi-person" />
                </x-field>
                
                <x-field label="With Right Icon">
                    <x-input type="email" placeholder="Email address" append-icon="bi bi-envelope" />
                </x-field>
                
                <x-field label="Search Input">
                    <x-input type="text" placeholder="Search..." prepend-icon="bi bi-search" />
                </x-field>
                
                <x-field label="Currency Input">
                    <x-input type="number" placeholder="0.00" prepend-text="$" />
                </x-field>
                
                <x-field label="Website URL" class="mb-0">
                    <x-input type="text" placeholder="www.example.com" prepend-text="https://" />
                </x-field>
            </x-card>
        </div>

        <!-- Textarea -->
        <div class="col-lg-6">
            <x-card title="Textarea" icon="bi bi-text-left">
                <x-field label="Description">
                    <x-textarea rows="4" placeholder="Enter your description here..." />
                </x-field>
                
                <x-field label="Message with Character Count" class="mb-0">
                    <x-textarea rows="4" placeholder="Type your message..." maxlength="200" id="charCountTextarea" />
                    <div class="d-flex justify-content-between mt-2">
                        <span class="form-text">Maximum 200 characters</span>
                        <span class="form-text"><span id="charCount">0</span>/200</span>
                    </div>
                </x-field>
            </x-card>
        </div>

        <!-- Select & Dropdowns -->
        <div class="col-lg-6">
            <x-card title="Select & Dropdowns" icon="bi bi-list-ul">
                <x-field label="Basic Select">
                    <x-select placeholder="Choose an option...">
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                        <option value="3">Option 3</option>
                    </x-select>
                </x-field>
                
                <x-field label="Country">
                    <x-select placeholder="Select country...">
                        <option value="us">🇺🇸 United States</option>
                        <option value="uk">🇬🇧 United Kingdom</option>
                        <option value="ca">🇨🇦 Canada</option>
                        <option value="au">🇦🇺 Australia</option>
                        <option value="de">🇩🇪 Germany</option>
                        <option value="fr">🇫🇷 France</option>
                    </x-select>
                </x-field>
                
                <x-field label="Category" class="mb-0">
                    <x-select placeholder="Select category...">
                        <optgroup label="Electronics">
                            <option value="phones">Phones</option>
                            <option value="laptops">Laptops</option>
                            <option value="tablets">Tablets</option>
                        </optgroup>
                        <optgroup label="Fashion">
                            <option value="clothing">Clothing</option>
                            <option value="shoes">Shoes</option>
                            <option value="accessories">Accessories</option>
                        </optgroup>
                    </x-select>
                </x-field>
            </x-card>
        </div>

        <!-- Checkboxes & Radios -->
        <div class="col-lg-6">
            <x-card title="Checkboxes & Radios" icon="bi bi-check-square">
                <label class="form-label">Checkboxes</label>
                <x-checkbox id="check1" checked label="Default Checkbox (Checked)" />
                <x-checkbox id="check2" label="Unchecked Checkbox" />
                <x-checkbox id="check3" label="Disabled Checkbox" disabled />
                
                <hr class="my-4">
                
                <label class="form-label">Radio Buttons</label>
                <x-radio-group name="radioGroup">
                    <x-radio id="radio1" value="1" checked label="Option One (Selected)" />
                    <x-radio id="radio2" value="2" label="Option Two" />
                    <x-radio id="radio3" value="3" label="Option Three" />
                </x-radio-group>
            </x-card>
        </div>

        <!-- Toggle Switches -->
        <div class="col-lg-6">
            <x-card title="Toggle Switches" icon="bi bi-toggle-on">
                <x-toggle 
                    id="switch1" 
                    checked 
                    label="Email Notifications" 
                    hint="Receive email about account activity"
                />
                
                <x-toggle 
                    id="switch2" 
                    label="Push Notifications" 
                    hint="Receive push notifications on desktop"
                />
                
                <x-toggle 
                    id="switch3" 
                    checked 
                    label="Two-Factor Authentication" 
                    hint="Add extra security to your account"
                />
                
                <x-toggle 
                    id="switch4" 
                    label="Dark Mode" 
                    hint="Enable dark theme"
                    class="mb-0"
                />
            </x-card>
        </div>

        <!-- File Upload -->
        <div class="col-lg-6">
            <x-card title="File Upload" icon="bi bi-cloud-upload">
                <x-field label="Basic File Input">
                    <x-file-upload id="fileBasic" />
                </x-field>
                
                <x-field label="Drag & Drop Zone" class="mb-0">
                    <x-file-upload 
                        id="fileDragDrop" 
                        dropzone 
                        multiple 
                        accept=".jpg,.png,.pdf"
                        icon="bi bi-cloud-arrow-up"
                        title="Drop files here or click to upload"
                        subtitle="Support for JPG, PNG, PDF files. Max size: 10MB"
                    />
                </x-field>
            </x-card>
        </div>

        <!-- Range Sliders -->
        <div class="col-lg-6">
            <x-card title="Range Sliders" icon="bi bi-sliders">
                <div class="form-group">
                    <div class="d-flex justify-content-between mb-2">
                        <label class="form-label mb-0">Volume</label>
                        <span class="text-muted">75%</span>
                    </div>
                    <input type="range" class="form-range" min="0" max="100" value="75" style="accent-color: var(--bs-gold);">
                </div>
                
                <div class="form-group">
                    <div class="d-flex justify-content-between mb-2">
                        <label class="form-label mb-0">Brightness</label>
                        <span class="text-muted">50%</span>
                    </div>
                    <input type="range" class="form-range" min="0" max="100" value="50" style="accent-color: var(--bs-primary);">
                </div>
                
                <div class="form-group mb-0">
                    <div class="d-flex justify-content-between mb-2">
                        <label class="form-label mb-0">Price Range</label>
                        <span class="text-muted">$250</span>
                    </div>
                    <input type="range" class="form-range" min="0" max="500" value="250" step="10" style="accent-color: var(--bs-gold);">
                </div>
            </x-card>
        </div>

        <!-- Validation States -->
        <div class="col-lg-6">
            <x-card title="Validation States" icon="bi bi-exclamation-circle">
                <x-field label="Valid Input">
                    <x-input type="text" value="Correct value" state="valid" />
                    <div class="form-text text-success"><i class="bi bi-check-circle me-1"></i> Looks good!</div>
                </x-field>
                
                <x-field label="Invalid Input">
                    <x-input type="text" value="Wrong value" state="invalid" />
                    <div class="form-text text-danger"><i class="bi bi-x-circle me-1"></i> Please enter a valid value.</div>
                </x-field>
                
                <x-field label="Warning State" class="mb-0">
                    <x-input type="text" value="Almost there" state="warning" />
                    <div class="form-text" style="color: var(--bs-warning);"><i class="bi bi-exclamation-triangle me-1"></i> This field needs attention.</div>
                </x-field>
            </x-card>
        </div>

        <!-- Date & Time -->
        <div class="col-lg-6">
            <x-card title="Date & Time" icon="bi bi-calendar-event">
                <x-field label="Date">
                    <x-input type="date" />
                </x-field>
                
                <x-field label="Time">
                    <x-input type="time" />
                </x-field>
                
                <x-field label="Date & Time">
                    <x-input type="datetime-local" />
                </x-field>
                
                <x-field label="Month" class="mb-0">
                    <x-input type="month" />
                </x-field>
            </x-card>
        </div>

        <!-- Color Picker -->
        <div class="col-lg-6">
            <x-card title="Color Picker" icon="bi bi-palette">
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label">Primary Color</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" value="#1a2b4a" class="form-control form-control-color" style="width: 50px; height: 50px; border-radius: var(--radius);">
                            <span class="text-muted">#1a2b4a</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Accent Color</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" value="#d4a94c" class="form-control form-control-color" style="width: 50px; height: 50px; border-radius: var(--radius);">
                            <span class="text-muted">#d4a94c</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Success Color</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" value="#10b981" class="form-control form-control-color" style="width: 50px; height: 50px; border-radius: var(--radius);">
                            <span class="text-muted">#10b981</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Danger Color</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" value="#ef4444" class="form-control form-control-color" style="width: 50px; height: 50px; border-radius: var(--radius);">
                            <span class="text-muted">#ef4444</span>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Complete Form Example -->
    <x-card title="Complete Form Example" icon="bi bi-send" class="mt-4">
        <form>
            <div class="row g-4">
                <div class="col-md-6">
                    <x-field label="First Name" required class="mb-0">
                        <x-input type="text" placeholder="Enter first name" />
                    </x-field>
                </div>
                <div class="col-md-6">
                    <x-field label="Last Name" required class="mb-0">
                        <x-input type="text" placeholder="Enter last name" />
                    </x-field>
                </div>
                <div class="col-md-6">
                    <x-field label="Email Address" required class="mb-0">
                        <x-input type="email" placeholder="name@example.com" />
                    </x-field>
                </div>
                <div class="col-md-6">
                    <x-field label="Phone Number" class="mb-0">
                        <x-input type="tel" placeholder="+1 (555) 123-4567" />
                    </x-field>
                </div>
                <div class="col-md-6">
                    <x-field label="Country" class="mb-0">
                        <x-select placeholder="Select country...">
                            <option value="us">🇺🇸 United States</option>
                            <option value="uk">🇬🇧 United Kingdom</option>
                            <option value="ca">🇨🇦 Canada</option>
                        </x-select>
                    </x-field>
                </div>
                <div class="col-md-6">
                    <x-field label="Role" class="mb-0">
                        <x-select placeholder="Select role...">
                            <option value="admin">Administrator</option>
                            <option value="editor">Editor</option>
                            <option value="viewer">Viewer</option>
                        </x-select>
                    </x-field>
                </div>
                <div class="col-12">
                    <x-field label="Bio" class="mb-0">
                        <x-textarea rows="4" placeholder="Tell us about yourself..." />
                    </x-field>
                </div>
                <div class="col-12">
                    <x-checkbox id="agreeTerms">
                        I agree to the <a href="#" class="text-gold">Terms of Service</a> and <a href="#" class="text-gold">Privacy Policy</a>
                    </x-checkbox>
                </div>
            </div>
        </form>
        
        <x-slot:footer>
            <div class="d-flex justify-content-end gap-2">
                <x-button variant="outline" icon="bi bi-x">Cancel</x-button>
                <x-button variant="primary" icon="bi bi-send">Submit Form</x-button>
            </div>
        </x-slot:footer>
    </x-card>
@endsection

@push('scripts')
<script>
    // Character counter for textarea
    const textarea = document.getElementById('charCountTextarea');
    const charCount = document.getElementById('charCount');
    
    textarea?.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
</script>
@endpush
