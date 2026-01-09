@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
    <!-- Breadcrumb -->
    <x-breadcrumb class="mb-4">
        <x-breadcrumb-item href="/admin">Dashboard</x-breadcrumb-item>
        <x-breadcrumb-item active>Profile</x-breadcrumb-item>
    </x-breadcrumb>

    <!-- Profile Header Card -->
    <div class="profile-header mb-4 animate-slide-up">
        <div class="profile-cover">
            <div class="profile-cover-actions">
                <x-button variant="outline" size="sm" style="background: rgba(255,255,255,0.2); color: white; backdrop-filter: blur(10px); border: none;">
                    <i class="bi bi-camera me-1"></i> Change Cover
                </x-button>
            </div>
        </div>
        <div class="profile-info-section">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="d-flex flex-column flex-md-row align-items-center align-items-md-end gap-4">
                        <div class="profile-avatar-wrapper">
                            <div class="profile-avatar">
                                <img src="https://ui-avatars.com/api/?name=John+Doe&size=200&background=d4a94c&color=1a2b4a&bold=true&font-size=0.4" alt="John Doe">
                            </div>
                            <span class="profile-avatar-badge">
                                <i class="bi bi-check-lg"></i>
                            </span>
                        </div>
                        <div class="text-center text-md-start">
                            <h1 class="profile-name">John Doe</h1>
                            <p class="profile-title"><i class="bi bi-shield-check me-2"></i>Senior Administrator</p>
                            <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-3 mt-2">
                                <span class="d-flex align-items-center gap-1" style="color: rgba(255,255,255,0.7); font-size: 0.875rem;">
                                    <i class="bi bi-geo-alt"></i> San Francisco, CA
                                </span>
                                <span class="d-flex align-items-center gap-1" style="color: rgba(255,255,255,0.7); font-size: 0.875rem;">
                                    <i class="bi bi-calendar3"></i> Joined March 2022
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="profile-stats justify-content-center justify-content-lg-end">
                        <div class="profile-stat">
                            <div class="profile-stat-value">1,284</div>
                            <div class="profile-stat-label">Projects</div>
                        </div>
                        <div class="profile-stat">
                            <div class="profile-stat-value">24.5K</div>
                            <div class="profile-stat-label">Followers</div>
                        </div>
                        <div class="profile-stat">
                            <div class="profile-stat-value">892</div>
                            <div class="profile-stat-label">Following</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-lg-4">
            <!-- About Card -->
            <x-card title="About" icon="bi bi-person" class="mb-4">
                <x-slot:actions>
                    <x-icon-button icon="bi bi-pencil" variant="outline" size="sm" />
                </x-slot:actions>
                
                <p class="text-muted mb-4" style="line-height: 1.8;">
                    Passionate developer and designer with over 8 years of experience creating beautiful, functional digital products. I love turning complex problems into simple, intuitive solutions.
                </p>
                
                <div class="profile-about-item">
                    <x-icon name="bi bi-briefcase" variant="muted" />
                    <div>
                        <div class="label">Position</div>
                        <div class="value">Senior Administrator</div>
                    </div>
                </div>
                
                <div class="profile-about-item">
                    <x-icon name="bi bi-building" variant="muted" />
                    <div>
                        <div class="label">Company</div>
                        <div class="value">Blueside Technologies</div>
                    </div>
                </div>
                
                <div class="profile-about-item">
                    <x-icon name="bi bi-envelope" variant="muted" />
                    <div>
                        <div class="label">Email</div>
                        <div class="value">john.doe@blueside.com</div>
                    </div>
                </div>
                
                <div class="profile-about-item">
                    <x-icon name="bi bi-telephone" variant="muted" />
                    <div>
                        <div class="label">Phone</div>
                        <div class="value">+1 (555) 123-4567</div>
                    </div>
                </div>
                
                <div class="profile-about-item">
                    <x-icon name="bi bi-globe" variant="muted" />
                    <div>
                        <div class="label">Website</div>
                        <div class="value"><a href="#" class="text-gold">www.johndoe.com</a></div>
                    </div>
                </div>
            </x-card>

            <!-- Skills Card -->
            <x-card title="Skills" icon="bi bi-lightbulb" class="mb-4">
                <div class="d-flex flex-wrap gap-2">
                    <x-badge variant="primary">Laravel</x-badge>
                    <x-badge variant="gold">Vue.js</x-badge>
                    <x-badge variant="primary">React</x-badge>
                    <x-badge variant="gold">PHP</x-badge>
                    <x-badge variant="primary">JavaScript</x-badge>
                    <x-badge variant="gold">TypeScript</x-badge>
                    <x-badge variant="primary">Node.js</x-badge>
                    <x-badge variant="gold">MySQL</x-badge>
                    <x-badge variant="primary">PostgreSQL</x-badge>
                    <x-badge variant="gold">Docker</x-badge>
                    <x-badge variant="primary">AWS</x-badge>
                    <x-badge variant="gold">UI/UX Design</x-badge>
                    <x-badge variant="primary">Figma</x-badge>
                    <x-badge variant="gold">TailwindCSS</x-badge>
                </div>
            </x-card>

            <!-- Social Links Card -->
            <x-card title="Social Links" icon="bi bi-share">
                <div class="d-flex flex-wrap gap-2">
                    <a href="#" class="btn btn-icon" style="background: #1877F2; color: white;">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="btn btn-icon" style="background: #1DA1F2; color: white;">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                    <a href="#" class="btn btn-icon" style="background: linear-gradient(45deg, #405DE6, #5851DB, #833AB4, #C13584, #E1306C, #FD1D1D); color: white;">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="btn btn-icon" style="background: #0A66C2; color: white;">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <a href="#" class="btn btn-icon" style="background: #333; color: white;">
                        <i class="bi bi-github"></i>
                    </a>
                    <a href="#" class="btn btn-icon" style="background: #FF0000; color: white;">
                        <i class="bi bi-youtube"></i>
                    </a>
                    <a href="#" class="btn btn-icon" style="background: #ea4c89; color: white;">
                        <i class="bi bi-dribbble"></i>
                    </a>
                </div>
            </x-card>
        </div>

        <!-- Right Column -->
        <div class="col-lg-8">
            <!-- Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <x-stats-card 
                        title="Tasks Done" 
                        value="248" 
                        icon="bi bi-check-circle"
                        variant="success"
                    />
                </div>
                <div class="col-sm-6 col-xl-3">
                    <x-stats-card 
                        title="Pending" 
                        value="12" 
                        icon="bi bi-clock"
                        variant="gold"
                    />
                </div>
                <div class="col-sm-6 col-xl-3">
                    <x-stats-card 
                        title="Projects" 
                        value="86" 
                        icon="bi bi-diagram-3"
                        variant="primary"
                    />
                </div>
                <div class="col-sm-6 col-xl-3">
                    <x-stats-card 
                        title="Awards" 
                        value="15" 
                        icon="bi bi-trophy"
                        variant="gold"
                    />
                </div>
            </div>

            <!-- Activity Timeline -->
            <x-card title="Recent Activity" icon="bi bi-clock-history" class="mb-4">
                <x-slot:actions>
                    <x-button variant="outline" size="sm">View All</x-button>
                </x-slot:actions>
                
                <x-activity-item 
                    icon="bi bi-code-slash" 
                    variant="blue" 
                    title="Pushed 23 commits to <strong>blueside-admin</strong> repository" 
                    time="2 hours ago"
                />
                
                <x-activity-item 
                    icon="bi bi-star-fill" 
                    variant="gold" 
                    title="Received 5-star rating on <strong>E-commerce Dashboard</strong> project" 
                    time="5 hours ago"
                />
                
                <x-activity-item 
                    icon="bi bi-check-lg" 
                    variant="green" 
                    title="Completed task: <strong>Implement user authentication</strong>" 
                    time="Yesterday at 4:30 PM"
                />
                
                <x-activity-item 
                    icon="bi bi-chat-dots" 
                    variant="blue" 
                    title="Commented on <strong>Sarah's</strong> design proposal" 
                    time="Yesterday at 2:15 PM"
                />
                
                <x-activity-item 
                    icon="bi bi-people" 
                    variant="gold" 
                    title="Joined team <strong>Product Development</strong>" 
                    time="2 days ago"
                />
            </x-card>

            <!-- Recent Projects -->
            <x-card title="Recent Projects" icon="bi bi-folder2-open">
                <x-slot:actions>
                    <x-button variant="gold" size="sm" icon="bi bi-plus">New Project</x-button>
                </x-slot:actions>
                
                <div class="row g-4">
                    <!-- Project Card 1 -->
                    <div class="col-md-6">
                        <x-card class="shadow-sm h-100">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <x-avatar name="E" size="lg" variant="primary" icon="bi bi-cart" />
                                <x-status status="completed" />
                            </div>
                            <h5 class="fw-semibold mb-2" style="color: var(--bs-primary);">E-commerce Platform</h5>
                            <p class="text-muted small mb-3">Building a modern e-commerce solution with Laravel and Vue.js</p>
                            <x-progress value="75" show-value class="mb-3" />
                            <div class="d-flex justify-content-between align-items-center">
                                <x-avatar-group>
                                    <x-avatar name="JD" size="sm" />
                                    <x-avatar name="SA" size="sm" variant="gold" />
                                    <x-avatar name="MK" size="sm" variant="light" />
                                </x-avatar-group>
                                <small class="text-muted">75% Complete</small>
                            </div>
                        </x-card>
                    </div>
                    
                    <!-- Project Card 2 -->
                    <div class="col-md-6">
                        <x-card class="shadow-sm h-100">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <x-avatar name="M" size="lg" variant="gold" icon="bi bi-phone" />
                                <x-status status="processing" />
                            </div>
                            <h5 class="fw-semibold mb-2" style="color: var(--bs-primary);">Mobile App UI</h5>
                            <p class="text-muted small mb-3">Designing intuitive mobile interfaces for iOS and Android</p>
                            <x-progress value="45" variant="gold" show-value class="mb-3" />
                            <div class="d-flex justify-content-between align-items-center">
                                <x-avatar-group>
                                    <x-avatar name="AB" size="sm" variant="gold" />
                                    <x-avatar name="CD" size="sm" />
                                </x-avatar-group>
                                <small class="text-muted">45% Complete</small>
                            </div>
                        </x-card>
                    </div>
                    
                    <!-- Project Card 3 -->
                    <div class="col-md-6">
                        <x-card class="shadow-sm h-100">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <x-avatar name="A" size="lg" variant="success" icon="bi bi-bar-chart" />
                                <x-status status="completed" />
                            </div>
                            <h5 class="fw-semibold mb-2" style="color: var(--bs-primary);">Analytics Dashboard</h5>
                            <p class="text-muted small mb-3">Real-time data visualization with interactive charts</p>
                            <x-progress value="100" variant="success" show-value class="mb-3" />
                            <div class="d-flex justify-content-between align-items-center">
                                <x-avatar-group :max="3" :total="5">
                                    <x-avatar name="EF" size="sm" variant="success" />
                                    <x-avatar name="GH" size="sm" />
                                    <x-avatar name="IJ" size="sm" variant="gold" />
                                </x-avatar-group>
                                <small class="text-muted">100% Complete</small>
                            </div>
                        </x-card>
                    </div>
                    
                    <!-- Project Card 4 -->
                    <div class="col-md-6">
                        <x-card class="shadow-sm h-100">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <x-avatar name="AI" size="lg" variant="info" icon="bi bi-robot" />
                                <x-status status="pending" />
                            </div>
                            <h5 class="fw-semibold mb-2" style="color: var(--bs-primary);">AI Integration</h5>
                            <p class="text-muted small mb-3">Implementing machine learning features for smart automation</p>
                            <x-progress value="15" variant="info" show-value class="mb-3" />
                            <div class="d-flex justify-content-between align-items-center">
                                <x-avatar-group>
                                    <x-avatar name="KL" size="sm" variant="info" />
                                </x-avatar-group>
                                <small class="text-muted">15% Complete</small>
                            </div>
                        </x-card>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
@endsection
