@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Header -->
    <x-page-header title="Dashboard" subtitle="Welcome back, John! Here's what's happening today.">
        <x-button variant="outline" icon="bi bi-download">Export</x-button>
        <x-button variant="gold" icon="bi bi-plus-lg">New Report</x-button>
    </x-page-header>

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

    <!-- Stats Cards Row -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stats-card 
                title="Total Revenue" 
                value="$48,592" 
                icon="bi bi-currency-dollar"
                change="+12.5%"
                trend="up"
            />
        </div>
        
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stats-card 
                title="Total Orders" 
                value="1,284" 
                icon="bi bi-cart-check"
                change="+8.2%"
                trend="up"
            />
        </div>
        
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stats-card 
                title="Total Customers" 
                value="5,842" 
                icon="bi bi-people"
                change="+24.1%"
                trend="up"
            />
        </div>
        
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stats-card 
                title="Conversion Rate" 
                value="94.2%" 
                icon="bi bi-graph-up-arrow"
                change="-2.4%"
                trend="down"
            />
        </div>
    </div>

    <!-- Charts & Activity Row -->
    <div class="row g-4 mb-4">
        <!-- Revenue Chart -->
        <div class="col-12 col-xl-8">
            <x-card title="Revenue Overview">
                <x-slot:actions>
                    <x-button-group>
                        <x-button variant="outline" size="sm">Weekly</x-button>
                        <x-button variant="outline" size="sm">Monthly</x-button>
                        <x-button variant="outline" size="sm">Yearly</x-button>
                    </x-button-group>
                </x-slot:actions>
                
                <div class="chart-placeholder">
                    <div class="text-center">
                        <x-icon name="bi bi-bar-chart-line" size="2xl" variant="muted" />
                        <p class="mt-2 mb-0">Chart will be rendered here</p>
                        <p class="small text-muted">Integrate with Chart.js or ApexCharts</p>
                    </div>
                </div>
            </x-card>
        </div>
        
        <!-- Recent Activity -->
        <div class="col-12 col-xl-4">
            <x-card title="Recent Activity" class="h-100">
                <x-slot:actions>
                    <x-button variant="outline" size="sm">View All</x-button>
                </x-slot:actions>
                
                <x-activity-item 
                    icon="bi bi-cart-plus" 
                    variant="blue" 
                    title="New order received" 
                    time="Order #1234 - 5 minutes ago"
                />
                
                <x-activity-item 
                    icon="bi bi-person-plus" 
                    variant="gold" 
                    title="New customer registered" 
                    time="Sarah Johnson - 15 minutes ago"
                />
                
                <x-activity-item 
                    icon="bi bi-check-circle" 
                    variant="green" 
                    title="Payment confirmed" 
                    time="Order #1230 - 32 minutes ago"
                />
                
                <x-activity-item 
                    icon="bi bi-box-seam" 
                    variant="blue" 
                    title="Product shipped" 
                    time="Order #1228 - 1 hour ago"
                />
                
                <x-activity-item 
                    icon="bi bi-star-fill" 
                    variant="gold" 
                    title="New 5-star review" 
                    time="Product: Premium Widget - 2 hours ago"
                />
            </x-card>
        </div>
    </div>

    <!-- Projects & Orders Row -->
    <div class="row g-4 mb-4">
        <!-- Active Projects -->
        <div class="col-12 col-xl-4">
            <x-card title="Active Projects">
                <x-slot:actions>
                    <x-icon-button icon="bi bi-plus-lg" variant="outline" size="sm" />
                </x-slot:actions>
                
                <!-- Project Item 1 -->
                <div class="mb-4">
                    <x-progress value="75" label="Website Redesign" show-value class="mb-2" />
                    <div class="d-flex justify-content-between align-items-center">
                        <x-avatar-group>
                            <x-avatar name="JD" size="sm" />
                            <x-avatar name="SA" size="sm" variant="gold" />
                            <x-avatar name="MK" size="sm" variant="light" />
                        </x-avatar-group>
                        <small class="text-muted">Due in 5 days</small>
                    </div>
                </div>
                
                <!-- Project Item 2 -->
                <div class="mb-4">
                    <x-progress value="45" variant="gold" label="Mobile App Development" show-value class="mb-2" />
                    <div class="d-flex justify-content-between align-items-center">
                        <x-avatar-group>
                            <x-avatar name="AB" size="sm" variant="gold" />
                            <x-avatar name="CD" size="sm" />
                        </x-avatar-group>
                        <small class="text-muted">Due in 12 days</small>
                    </div>
                </div>
                
                <!-- Project Item 3 -->
                <div>
                    <x-progress value="92" label="Marketing Campaign" show-value class="mb-2" />
                    <div class="d-flex justify-content-between align-items-center">
                        <x-avatar-group :max="3" :total="5">
                            <x-avatar name="EF" size="sm" variant="light" />
                            <x-avatar name="GH" size="sm" />
                            <x-avatar name="IJ" size="sm" variant="gold" />
                        </x-avatar-group>
                        <small class="text-muted">Due in 2 days</small>
                    </div>
                </div>
            </x-card>
        </div>
        
        <!-- Recent Orders Table -->
        <div class="col-12 col-xl-8">
            <x-card title="Recent Orders" :padding="false">
                <x-slot:actions>
                    <x-button variant="outline" size="sm">View All Orders</x-button>
                </x-slot:actions>
                
                <x-table>
                    <x-slot:head>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </x-slot:head>
                    
                    <tr>
                        <td><span class="fw-medium">#ORD-1234</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <x-avatar name="Emma Wilson" size="sm" />
                                <span>Emma Wilson</span>
                            </div>
                        </td>
                        <td>Premium Package</td>
                        <td><span class="fw-semibold">$299.00</span></td>
                        <td><x-status status="completed" /></td>
                        <td>Jan 6, 2026</td>
                    </tr>
                    <tr>
                        <td><span class="fw-medium">#ORD-1233</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <x-avatar name="James Carter" size="sm" variant="gold" />
                                <span>James Carter</span>
                            </div>
                        </td>
                        <td>Basic Plan</td>
                        <td><span class="fw-semibold">$99.00</span></td>
                        <td><x-status status="processing" /></td>
                        <td>Jan 6, 2026</td>
                    </tr>
                    <tr>
                        <td><span class="fw-medium">#ORD-1232</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <x-avatar name="Sarah Johnson" size="sm" variant="light" />
                                <span>Sarah Johnson</span>
                            </div>
                        </td>
                        <td>Enterprise Suite</td>
                        <td><span class="fw-semibold">$599.00</span></td>
                        <td><x-status status="completed" /></td>
                        <td>Jan 5, 2026</td>
                    </tr>
                    <tr>
                        <td><span class="fw-medium">#ORD-1231</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <x-avatar name="Michael Brown" size="sm" />
                                <span>Michael Brown</span>
                            </div>
                        </td>
                        <td>Starter Kit</td>
                        <td><span class="fw-semibold">$49.00</span></td>
                        <td><x-status status="cancelled" /></td>
                        <td>Jan 5, 2026</td>
                    </tr>
                    <tr>
                        <td><span class="fw-medium">#ORD-1230</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <x-avatar name="Lisa Davis" size="sm" variant="gold" />
                                <span>Lisa Davis</span>
                            </div>
                        </td>
                        <td>Premium Package</td>
                        <td><span class="fw-semibold">$299.00</span></td>
                        <td><x-status status="pending" /></td>
                        <td>Jan 4, 2026</td>
                    </tr>
                </x-table>
            </x-card>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row g-4">
        <!-- Top Products -->
        <div class="col-12 col-md-6 col-xl-4">
            <x-card title="Top Products">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <x-icon name="bi bi-box" variant="primary" background />
                        <div>
                            <div class="fw-medium" style="color: var(--bs-primary);">Premium Widget</div>
                            <small class="text-muted">Electronics</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-semibold">$12,450</div>
                        <small class="text-success"><i class="bi bi-arrow-up"></i> 15%</small>
                    </div>
                </div>
                
                <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <x-icon name="bi bi-laptop" variant="gold" background />
                        <div>
                            <div class="fw-medium" style="color: var(--bs-primary);">Pro Laptop Stand</div>
                            <small class="text-muted">Accessories</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-semibold">$8,920</div>
                        <small class="text-success"><i class="bi bi-arrow-up"></i> 8%</small>
                    </div>
                </div>
                
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <x-icon name="bi bi-headphones" variant="primary" background />
                        <div>
                            <div class="fw-medium" style="color: var(--bs-primary);">Wireless Headset</div>
                            <small class="text-muted">Audio</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-semibold">$6,340</div>
                        <small class="text-danger"><i class="bi bi-arrow-down"></i> 3%</small>
                    </div>
                </div>
            </x-card>
        </div>
        
        <!-- Traffic Sources -->
        <div class="col-12 col-md-6 col-xl-4">
            <x-card title="Traffic Sources">
                <x-progress value="45" label="Direct" show-value class="mb-3" />
                <x-progress value="30" variant="gold" label="Organic Search" show-value class="mb-3" />
                <x-progress value="15" label="Social Media" show-value class="mb-3" />
                <x-progress value="10" variant="gold" label="Referral" show-value />
            </x-card>
        </div>
        
        <!-- Quick Actions -->
        <div class="col-12 col-xl-4">
            <x-card title="Quick Actions">
                <div class="row g-3">
                    <div class="col-6">
                        <x-button variant="primary" block class="py-3">
                            <i class="bi bi-plus-circle d-block mb-1 fs-5"></i>
                            <span class="small">Add Product</span>
                        </x-button>
                    </div>
                    <div class="col-6">
                        <x-button variant="gold" block class="py-3">
                            <i class="bi bi-receipt d-block mb-1 fs-5"></i>
                            <span class="small">New Invoice</span>
                        </x-button>
                    </div>
                    <div class="col-6">
                        <x-button variant="outline" block class="py-3">
                            <i class="bi bi-person-plus d-block mb-1 fs-5"></i>
                            <span class="small">Add Customer</span>
                        </x-button>
                    </div>
                    <div class="col-6">
                        <x-button variant="outline" block class="py-3">
                            <i class="bi bi-file-earmark-text d-block mb-1 fs-5"></i>
                            <span class="small">Create Report</span>
                        </x-button>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
@endsection
