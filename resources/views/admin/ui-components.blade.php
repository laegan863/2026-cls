@extends('layouts.admin')

@section('title', 'Component Library')

@section('content')
    <!-- Breadcrumb using component -->
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => url('/admin')],
        ['label' => 'Component Library'],
    ]" />

    <!-- Page Header using component -->
    <x-page-header title="Component Library" subtitle="Reusable Blade components with slot support">
        <x-button variant="outline" icon="fas fa-code">View Source</x-button>
        <x-button variant="gold" icon="fas fa-download">Download</x-button>
    </x-page-header>

    <!-- Alerts Section -->
    <x-card title="Alerts" icon="fas fa-bell" class="mb-4">
        <x-alert type="success" title="Success!">Your changes have been saved successfully.</x-alert>
        <x-alert type="danger">Something went wrong. Please try again.</x-alert>
        <x-alert type="warning" :dismissible="false">Please review your information before proceeding.</x-alert>
        <x-alert type="info" icon="fas fa-lightbulb">Pro tip: You can customize these alerts with different icons!</x-alert>
        <x-alert type="gold" :dismissible="false">This is a special gold-themed alert for important highlights.</x-alert>
    </x-card>

    <div class="row g-4 mb-4">
        <!-- Buttons Section -->
        <div class="col-lg-6">
            <x-card title="Buttons" icon="fas fa-hand-pointer" class="h-100">
                <h6 class="text-muted mb-3">Solid Buttons</h6>
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <x-button variant="primary">Primary</x-button>
                    <x-button variant="gold">Gold</x-button>
                    <x-button variant="success">Success</x-button>
                    <x-button variant="danger">Danger</x-button>
                    <x-button variant="warning">Warning</x-button>
                    <x-button variant="info">Info</x-button>
                </div>

                <h6 class="text-muted mb-3">Outline Buttons</h6>
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <x-button variant="outline">Outline</x-button>
                    <x-button variant="outline-gold">Outline Gold</x-button>
                </div>

                <h6 class="text-muted mb-3">With Icons</h6>
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <x-button variant="primary" icon="fas fa-plus">Add New</x-button>
                    <x-button variant="gold" icon="fas fa-download">Download</x-button>
                    <x-button variant="outline" icon="fas fa-cog">Settings</x-button>
                </div>

                <h6 class="text-muted mb-3">Loading State</h6>
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <x-button variant="primary" :loading="true">Processing...</x-button>
                    <x-button variant="gold" :loading="true">Saving...</x-button>
                </div>

                <h6 class="text-muted mb-3">Icon Buttons</h6>
                <div class="d-flex flex-wrap gap-2">
                    <x-icon-button icon="fas fa-plus" variant="primary" />
                    <x-icon-button icon="fas fa-edit" variant="gold" />
                    <x-icon-button icon="fas fa-trash" variant="danger" />
                    <x-icon-button icon="fas fa-cog" variant="outline" />
                    <x-icon-button icon="fas fa-heart" variant="danger" size="lg" />
                </div>
            </x-card>
        </div>

        <!-- Badges Section -->
        <div class="col-lg-6">
            <x-card title="Badges & Status" icon="fas fa-tag" class="h-100">
                <h6 class="text-muted mb-3">Badges</h6>
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <x-badge type="primary">Primary</x-badge>
                    <x-badge type="gold">Gold</x-badge>
                    <x-badge type="success">Success</x-badge>
                    <x-badge type="danger">Danger</x-badge>
                    <x-badge type="warning">Warning</x-badge>
                    <x-badge type="info">Info</x-badge>
                </div>

                <h6 class="text-muted mb-3">Pill Badges</h6>
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <x-badge type="primary" pill>Primary</x-badge>
                    <x-badge type="gold" pill>Gold</x-badge>
                    <x-badge type="success" pill icon="fas fa-check">Verified</x-badge>
                </div>

                <h6 class="text-muted mb-3">Status Badges</h6>
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <x-status status="active" />
                    <x-status status="pending" />
                    <x-status status="completed" />
                    <x-status status="cancelled" />
                    <x-status status="draft" />
                </div>

                <h6 class="text-muted mb-3">Avatars</h6>
                <div class="d-flex flex-wrap gap-3 align-items-center mb-4">
                    <x-avatar name="John Doe" size="xs" />
                    <x-avatar name="Jane Smith" size="sm" variant="gold" />
                    <x-avatar name="Bob Wilson" />
                    <x-avatar name="Alice Brown" size="lg" status="online" />
                    <x-avatar name="User" size="xl" image="https://ui-avatars.com/api/?name=User&background=d4a94c&color=1a2b4a" />
                </div>

                <h6 class="text-muted mb-3">Avatar Group</h6>
                <x-avatar-group :max="3" :total="5">
                    <x-avatar name="A" />
                    <x-avatar name="B" variant="gold" />
                    <x-avatar name="C" />
                </x-avatar-group>
            </x-card>
        </div>
    </div>

    <!-- Form Fields Section -->
    <x-card title="Form Components" icon="fab fa-wpforms" class="mb-4">
        <div class="row g-4">
            <div class="col-md-6">
                <x-field label="Full Name" name="name" required>
                    <x-input name="name" placeholder="Enter your full name" />
                </x-field>

                <x-field label="Email Address" name="email" hint="We'll never share your email.">
                    <x-input name="email" type="email" icon="fas fa-envelope" placeholder="you@example.com" />
                </x-field>

                <x-field label="Password" name="password" required>
                    <x-input name="password" type="password" icon="fas fa-lock" placeholder="Enter password" />
                </x-field>

                <x-field label="Search" name="search">
                    <x-input name="search" icon="fas fa-search" placeholder="Search..." />
                </x-field>
            </div>

            <div class="col-md-6">
                <x-field label="Country" name="country">
                    <x-select name="country" placeholder="Select a country" :options="[
                        'us' => 'United States',
                        'uk' => 'United Kingdom',
                        'ca' => 'Canada',
                        'au' => 'Australia',
                    ]" />
                </x-field>

                <x-field label="Message" name="message">
                    <x-textarea name="message" placeholder="Type your message here..." rows="3" />
                </x-field>

                <x-field label="Profile Picture" name="avatar">
                    <x-file-upload name="avatar" accept="image/*" />
                </x-field>
            </div>
        </div>

        <hr class="my-4">

        <div class="row g-4">
            <div class="col-md-4">
                <h6 class="text-muted mb-3">Checkboxes</h6>
                <x-checkbox name="terms" label="I agree to the terms and conditions" />
                <x-checkbox name="newsletter" label="Subscribe to newsletter" checked />
                <x-checkbox name="updates" label="Receive product updates" />
            </div>

            <div class="col-md-4">
                <h6 class="text-muted mb-3">Radio Buttons</h6>
                <x-radio name="plan" value="basic" label="Basic Plan" checked />
                <x-radio name="plan" value="pro" label="Pro Plan" />
                <x-radio name="plan" value="enterprise" label="Enterprise Plan" />
            </div>

            <div class="col-md-4">
                <h6 class="text-muted mb-3">Toggle Switches</h6>
                <x-toggle name="notifications" label="Enable notifications" checked />
                <x-toggle name="dark_mode" label="Dark mode" />
                <x-toggle name="auto_save" label="Auto-save documents" />
            </div>
        </div>
    </x-card>

    <!-- Progress & Spinners -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <x-card title="Progress Bars" icon="fas fa-tasks" class="h-100">
                <x-progress value="25" label="Project Alpha" show-value class="mb-3" />
                <x-progress value="50" variant="gold" label="Project Beta" show-value class="mb-3" />
                <x-progress value="75" label="Project Gamma" show-value class="mb-3" />
                <x-progress value="100" label="Completed" show-value />
            </x-card>
        </div>

        <div class="col-lg-6">
            <x-card title="Spinners" icon="fas fa-spinner" class="h-100">
                <div class="d-flex gap-4 align-items-center mb-4">
                    <x-spinner size="sm" />
                    <x-spinner />
                    <x-spinner variant="gold" />
                    <x-spinner type="grow" />
                    <x-spinner type="grow" variant="gold" />
                </div>
                
                <x-button variant="primary" :loading="true">Loading...</x-button>
            </x-card>
        </div>
    </div>

    <!-- Modals Section -->
    <x-card title="Modals" icon="fas fa-window-restore" class="mb-4">
        <div class="d-flex flex-wrap gap-2">
            <x-modal-trigger target="basicModal" variant="primary">Basic Modal</x-modal-trigger>
            <x-modal-trigger target="centeredModal" variant="gold">Centered Modal</x-modal-trigger>
            <x-modal-trigger target="largeModal" variant="outline">Large Modal</x-modal-trigger>
            <x-modal-trigger target="formModal" variant="outline" icon="fas fa-edit">Form Modal</x-modal-trigger>
            <x-modal-trigger target="successAlert" variant="success">Success Alert</x-modal-trigger>
            <x-modal-trigger target="dangerAlert" variant="danger">Danger Alert</x-modal-trigger>
        </div>

        <!-- Basic Modal -->
        <x-modal id="basicModal" title="Basic Modal">
            <p>This is a basic modal dialog. You can put any content here.</p>
            <p class="mb-0">Use the <code>&lt;x-modal&gt;</code> component to create modals easily.</p>
            
            <x-slot:footer>
                <x-button variant="outline" data-bs-dismiss="modal">Close</x-button>
                <x-button variant="primary">Save Changes</x-button>
            </x-slot:footer>
        </x-modal>

        <!-- Centered Modal -->
        <x-modal id="centeredModal" title="Centered Modal" centered>
            <p>This modal is vertically centered on the page.</p>
            <p class="mb-0">Add the <code>centered</code> prop to center the modal.</p>
            
            <x-slot:footer>
                <x-button variant="gold">Got it!</x-button>
            </x-slot:footer>
        </x-modal>

        <!-- Large Modal -->
        <x-modal id="largeModal" title="Large Modal" size="lg">
            <p>This is a large modal with more space for content.</p>
            <p>You can use size="sm", "lg", "xl", or "fullscreen".</p>
            
            <x-slot:footer>
                <x-button variant="outline" data-bs-dismiss="modal">Cancel</x-button>
                <x-button variant="primary">Continue</x-button>
            </x-slot:footer>
        </x-modal>

        <!-- Form Modal -->
        <x-modal id="formModal" title="Contact Form" centered>
            <form>
                <x-field label="Name" name="modal_name" required>
                    <x-input name="modal_name" placeholder="Your name" />
                </x-field>
                
                <x-field label="Email" name="modal_email" required>
                    <x-input name="modal_email" type="email" placeholder="your@email.com" />
                </x-field>
                
                <x-field label="Message" name="modal_message">
                    <x-textarea name="modal_message" placeholder="Your message..." rows="3" />
                </x-field>
            </form>
            
            <x-slot:footer>
                <x-button variant="outline" data-bs-dismiss="modal">Cancel</x-button>
                <x-button variant="gold" type="submit">Send Message</x-button>
            </x-slot:footer>
        </x-modal>

        <!-- Alert Modals -->
        <x-modal-alert id="successAlert" type="success" title="Success!" message="Your action was completed successfully." confirm="Continue" />
        <x-modal-alert id="dangerAlert" type="danger" title="Delete Item?" message="Are you sure you want to delete this item? This action cannot be undone." confirm="Delete" cancel="Cancel" />
    </x-card>

    <!-- Dropdowns & Tabs -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <x-card title="Dropdowns" icon="fas fa-caret-down" class="h-100">
                <div class="d-flex gap-2 flex-wrap">
                    <x-dropdown>
                        <x-slot:trigger>
                            <x-button variant="primary">Dropdown <i class="bi bi-chevron-down ms-1"></i></x-button>
                        </x-slot:trigger>
                        
                        <x-dropdown-item href="#" icon="fas fa-user">Profile</x-dropdown-item>
                        <x-dropdown-item href="#" icon="fas fa-cog">Settings</x-dropdown-item>
                        <x-dropdown-item href="#" icon="fas fa-file">Documents</x-dropdown-item>
                        <x-dropdown-divider />
                        <x-dropdown-item href="#" icon="fas fa-sign-out-alt" class="text-danger">Logout</x-dropdown-item>
                    </x-dropdown>

                    <x-dropdown align="end">
                        <x-slot:trigger>
                            <x-button variant="gold">Actions <i class="bi bi-chevron-down ms-1"></i></x-button>
                        </x-slot:trigger>
                        
                        <x-dropdown-item href="#" icon="fas fa-edit">Edit</x-dropdown-item>
                        <x-dropdown-item href="#" icon="fas fa-copy">Duplicate</x-dropdown-item>
                        <x-dropdown-item href="#" icon="fas fa-archive">Archive</x-dropdown-item>
                        <x-dropdown-divider />
                        <x-dropdown-item href="#" icon="fas fa-trash" class="text-danger">Delete</x-dropdown-item>
                    </x-dropdown>
                </div>
            </x-card>
        </div>

        <div class="col-lg-6">
            <x-card title="Tabs" icon="fas fa-folder" class="h-100">
                <x-tabs>
                    <x-slot:nav>
                        <x-tab-item id="home" active icon="fas fa-home">Home</x-tab-item>
                        <x-tab-item id="profile" icon="fas fa-user">Profile</x-tab-item>
                        <x-tab-item id="messages" icon="fas fa-envelope">Messages</x-tab-item>
                    </x-slot:nav>
                    
                    <x-tab-panel id="home" active>
                        <h5>Home Tab</h5>
                        <p class="mb-0">This is the home tab content. Use tabs to organize related content.</p>
                    </x-tab-panel>
                    
                    <x-tab-panel id="profile">
                        <h5>Profile Tab</h5>
                        <p class="mb-0">Profile information and settings would go here.</p>
                    </x-tab-panel>
                    
                    <x-tab-panel id="messages">
                        <h5>Messages Tab</h5>
                        <p class="mb-0">Your messages and notifications would appear here.</p>
                    </x-tab-panel>
                </x-tabs>
            </x-card>
        </div>
    </div>

    <!-- Accordion -->
    <x-card title="Accordion" icon="fas fa-bars" class="mb-4">
        <x-accordion id="demoAccordion">
            <x-accordion-item id="acc1" title="What is this component library?" parent="demoAccordion" show icon="fas fa-question-circle">
                <p class="mb-0">This is a collection of reusable Blade components built with Laravel's component system. Each component uses slots similar to Vue.js, making them highly flexible and reusable across your application.</p>
            </x-accordion-item>
            
            <x-accordion-item id="acc2" title="How do I use these components?" parent="demoAccordion" icon="fas fa-code">
                <p>Simply use the <code>&lt;x-component-name&gt;</code> syntax in your Blade templates. For example:</p>
                <pre class="bg-light p-3 rounded"><code>&lt;x-button variant="gold" icon="fas fa-star"&gt;Click Me&lt;/x-button&gt;</code></pre>
            </x-accordion-item>
            
            <x-accordion-item id="acc3" title="Can I customize these components?" parent="demoAccordion" icon="fas fa-palette">
                <p class="mb-0">Yes! All components support additional attributes via <code>$attributes</code> bag. You can also modify the component files directly in <code>resources/views/components/</code> to customize their appearance and behavior.</p>
            </x-accordion-item>
        </x-accordion>
    </x-card>

    <!-- Tables -->
    <x-card title="Tables" icon="fas fa-table" class="mb-4" :padding="false">
        <x-table>
            <x-slot:head>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </x-slot:head>
            
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <x-avatar name="John Doe" size="sm" />
                        <span>John Doe</span>
                    </div>
                </td>
                <td>john@example.com</td>
                <td><x-badge type="primary">Admin</x-badge></td>
                <td><x-status status="active" /></td>
                <td>
                    <x-dropdown align="end">
                        <x-slot:trigger>
                            <x-icon-button icon="fas fa-ellipsis-v" variant="light" size="sm" />
                        </x-slot:trigger>
                        <x-dropdown-item href="#" icon="fas fa-eye">View</x-dropdown-item>
                        <x-dropdown-item href="#" icon="fas fa-edit">Edit</x-dropdown-item>
                        <x-dropdown-divider />
                        <x-dropdown-item href="#" icon="fas fa-trash" class="text-danger">Delete</x-dropdown-item>
                    </x-dropdown>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <x-avatar name="Jane Smith" size="sm" variant="gold" />
                        <span>Jane Smith</span>
                    </div>
                </td>
                <td>jane@example.com</td>
                <td><x-badge type="gold">Manager</x-badge></td>
                <td><x-status status="active" /></td>
                <td>
                    <x-dropdown align="end">
                        <x-slot:trigger>
                            <x-icon-button icon="fas fa-ellipsis-v" variant="light" size="sm" />
                        </x-slot:trigger>
                        <x-dropdown-item href="#" icon="fas fa-eye">View</x-dropdown-item>
                        <x-dropdown-item href="#" icon="fas fa-edit">Edit</x-dropdown-item>
                        <x-dropdown-divider />
                        <x-dropdown-item href="#" icon="fas fa-trash" class="text-danger">Delete</x-dropdown-item>
                    </x-dropdown>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <x-avatar name="Bob Wilson" size="sm" />
                        <span>Bob Wilson</span>
                    </div>
                </td>
                <td>bob@example.com</td>
                <td><x-badge type="light">User</x-badge></td>
                <td><x-status status="pending" /></td>
                <td>
                    <x-dropdown align="end">
                        <x-slot:trigger>
                            <x-icon-button icon="fas fa-ellipsis-v" variant="light" size="sm" />
                        </x-slot:trigger>
                        <x-dropdown-item href="#" icon="fas fa-eye">View</x-dropdown-item>
                        <x-dropdown-item href="#" icon="fas fa-edit">Edit</x-dropdown-item>
                        <x-dropdown-divider />
                        <x-dropdown-item href="#" icon="fas fa-trash" class="text-danger">Delete</x-dropdown-item>
                    </x-dropdown>
                </td>
            </tr>
        </x-table>
    </x-card>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <x-stats-card 
                title="Total Revenue" 
                value="$48,592" 
                icon="bi bi-currency-dollar"
                change="+12.5%"
                trend="up"
                variant="primary"
            />
        </div>
        <div class="col-sm-6 col-xl-3">
            <x-stats-card 
                title="Total Orders" 
                value="1,284" 
                icon="bi bi-cart-check"
                change="+8.2%"
                trend="up"
            />
        </div>
        <div class="col-sm-6 col-xl-3">
            <x-stats-card 
                title="Total Customers" 
                value="5,842" 
                icon="bi bi-people"
                change="+24.1%"
                trend="up"
            />
        </div>
        <div class="col-sm-6 col-xl-3">
            <x-stats-card 
                title="Conversion Rate" 
                value="94.2%" 
                icon="bi bi-graph-up-arrow"
                change="-2.4%"
                trend="down"
                variant="gold"
            />
        </div>
    </div>

    <!-- Empty State -->
    <x-card title="Empty State" icon="fas fa-inbox" class="mb-4">
        <x-empty-state 
            title="No results found" 
            message="Try adjusting your search or filter to find what you're looking for."
            icon="fas fa-search"
        >
            <x-button variant="gold" icon="fas fa-redo">Reset Filters</x-button>
        </x-empty-state>
    </x-card>
@endsection
