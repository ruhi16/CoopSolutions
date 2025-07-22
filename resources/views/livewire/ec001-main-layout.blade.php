<div>    
    {{-- EC Organisation --}}
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Laravel Livewire</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .menu-item-active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        .submenu-open {
            transition: max-height 0.5s ease-in;
        }
        .fa-chevron-down {
            transition: transform 0.3s ease;
        }
        /* Ensure menu items have proper hover states */
        .menu-toggle:hover, .submenu-toggle:hover {
            background-color: rgba(243, 244, 246, 0.8);
        }
        /* Active menu styling */
        .menu-toggle.active {
            background-color: rgba(243, 244, 246, 1);
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 bg-gradient-to-r from-blue-600 to-purple-600 text-white h-16 flex items-center justify-between px-6 shadow-lg z-50">
        <div class="flex items-center space-x-4">
            <button id="sidebarToggle" class="p-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-all duration-200">
                <i class="fas fa-bars"></i>
            </button>
            <div class="flex items-center space-x-2">
                <i class="fas fa-crown text-2xl"></i>
                <span class="text-xl font-bold">AdminPanel</span>
            </div>
        </div>
        
        <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <div class="relative">
                <button class="p-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-all duration-200">
                    <i class="fas fa-bell"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                </button>
            </div>
            
            <!-- User Profile -->
            <div class="relative">
                <button id="userMenuToggle" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-all duration-200">
                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-blue-600 font-bold">
                        JD
                    </div>
                    <span class="hidden md:block">John Doe</span>
                    <i class="fas fa-chevron-down text-sm"></i>
                </button>
                
                <!-- User Dropdown -->
                <div id="userDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 hidden">
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user mr-2"></i>Profile
                    </a>
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-cog mr-2"></i>Settings
                    </a>
                    <hr class="my-2">
                    <a href="#" onclick="showLoginModal()" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed left-0 top-16 h-full w-64 bg-white shadow-lg transform transition-transform duration-300 z-40">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-700">Navigation</h3>
        </div>
        
        <nav class="mt-4">
            <!-- Level 1 Menu Items -->
            <div class="px-4 py-2">
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition-all duration-200 menu-item-active">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <!-- Level 2 Menu - User Management -->
            <div class="px-4 py-2">
                <button class="menu-toggle flex items-center justify-between w-full px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition-all duration-200">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-users"></i>
                        <span>Organisation</span>
                    </div>
                    <i class="fas fa-chevron-down transform transition-transform duration-200"></i>
                </button>
                
                <!-- Level 2 Submenu -->
                <div class="submenu mt-2 ml-4 space-y-1">
                    @foreach($organisationMenus as $organisationMenu)
                    <button 
                        wire:click="setActiveMenu('{{ $organisationMenu['name'] }}')"
                        class="menu-item flex items-center w-full space-x-3 px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-all duration-200 {{ $organisationMenu['name'] == $activeMenu ? 'bg-gray-200' : ''}}">
                        <i class="{{ $organisationMenu['icon']}} text-sm"></i>
                        <span>{{ $organisationMenu['label'] }}</span>
                    </button>
                    @endforeach
                    {{-- <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-all duration-200">
                        <i class="fas fa-list text-sm"></i>
                        <span>Financial Year</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-all duration-200">
                        <i class="fas fa-shield text-sm"></i>
                        <span>Officials</span>
                    </a> --}}
                    
                    <!-- Level 3 Menu - Roles -->
                    {{-- <div class="ml-4">
                        <button class="submenu-toggle flex items-center justify-between w-full px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-shield-alt text-sm"></i>
                                <span>Roles & Permissions</span>
                            </div>
                            <i class="fas fa-chevron-down transform transition-transform duration-200 text-xs"></i>
                        </button>
                        
                        <!-- Level 3 Submenu -->
                        <div class="submenu mt-2 ml-4 space-y-1">
                            <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-all duration-200">
                                <i class="fas fa-plus-circle text-xs"></i>
                                <span>Create Role</span>
                            </a>
                            <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-all duration-200">
                                <i class="fas fa-edit text-xs"></i>
                                <span>Manage Roles</span>
                            </a>
                            <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-all duration-200">
                                <i class="fas fa-key text-xs"></i>
                                <span>Permissions</span>
                            </a>
                        </div>
                    </div> --}}

                </div>
            </div>

            <!-- Level 2 Menu - Content Management -->
            <div class="px-4 py-2">
                <button class="menu-toggle flex items-center justify-between w-full px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition-all duration-200">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-file-alt"></i>
                        <span>Content</span>
                    </div>
                    <i class="fas fa-chevron-down transform transition-transform duration-200"></i>
                </button>
                
                <!-- Level 2 Submenu -->
                <div class="submenu mt-2 ml-4 space-y-1">
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-all duration-200">
                        <i class="fas fa-plus text-sm"></i>
                        <span>Create Post</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-all duration-200">
                        <i class="fas fa-list text-sm"></i>
                        <span>All Posts</span>
                    </a>
                    
                    <!-- Level 3 Menu - Categories -->
                    <div class="ml-4">
                        <button class="submenu-toggle flex items-center justify-between w-full px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-tags text-sm"></i>
                                <span>Categories</span>
                            </div>
                            <i class="fas fa-chevron-down transform transition-transform duration-200 text-xs"></i>
                        </button>
                        
                        <!-- Level 3 Submenu -->
                        <div class="submenu mt-2 ml-4 space-y-1">
                            <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-all duration-200">
                                <i class="fas fa-plus-circle text-xs"></i>
                                <span>Add Category</span>
                            </a>
                            <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-all duration-200">
                                <i class="fas fa-list text-xs"></i>
                                <span>All Categories</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Level 1 Menu Items -->
            <div class="px-4 py-2">
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition-all duration-200">
                    <i class="fas fa-chart-bar"></i>
                    <span>Analytics</span>
                </a>
            </div>

            <div class="px-4 py-2">
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition-all duration-200">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </div>

        </nav>
    </aside>

    <!-- Main Content -->
    <main id="mainContent" class="ml-64 mt-16 p-6 transition-all duration-300">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="#" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="#" class="text-sm font-medium text-gray-700 hover:text-blue-600">Analytics</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500">Overview</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Overview</h1>
                    <p class="text-gray-600 mt-1">Welcome back, John! Here's what's happening with your admin panel.</p>
                </div>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors duration-200">
                    <i class="fas fa-plus"></i>
                    <span>Add New</span>
                </button>
            </div>
        </div>
        

        @if($activeMenu == 'dashboard')

        @elseif($activeMenu == 'organisation')
            {{-- @livewire('ec01-organisation') --}}
            @livewire($organisationMenus['organisation']['component'])
        @elseif($activeMenu == 'finyear')
            @livewire($organisationMenus['finyear']['component']))
        @elseif($activeMenu == 'officials')
            {{-- @livewire('ec02-financial-year') --}}
            @livewire($organisationMenus['officials']['component'])
        @elseif($activeMenu == 'loanscheme')
            @livewire($organisationMenus['loanscheme']['component'])
        @elseif($activeMenu == 'loanschemefeature')

        @elseif($activeMenu == 'loanschemedetail')
        
        @endif
        {{-- <!-- Organisations Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">        
            @livewire('ec01-organisation')
        </div>

        
        <!-- Members Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">        
            @livewire('ec05-member-type-comp')
        </div>
        

        
        <!-- Financial year Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">        
            @livewire('ec02-financial-year')
        </div>

        <!-- Members Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">        
            @livewire('ec05-member-comp')
        </div>

 --}}























        <!-- Stats Cards -->
        {{-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">1,234</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <span class="text-green-600 text-sm font-medium">+12%</span>
                    <span class="text-gray-600 text-sm ml-2">from last month</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Revenue</p>
                        <p class="text-2xl font-bold text-gray-900">$45,678</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-dollar-sign text-green-600"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <span class="text-green-600 text-sm font-medium">+8%</span>
                    <span class="text-gray-600 text-sm ml-2">from last month</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Orders</p>
                        <p class="text-2xl font-bold text-gray-900">892</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-shopping-cart text-purple-600"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <span class="text-red-600 text-sm font-medium">-3%</span>
                    <span class="text-gray-600 text-sm ml-2">from last month</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Conversion Rate</p>
                        <p class="text-2xl font-bold text-gray-900">3.2%</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-chart-line text-yellow-600"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <span class="text-green-600 text-sm font-medium">+15%</span>
                    <span class="text-gray-600 text-sm ml-2">from last month</span>
                </div>
            </div>
        </div> --}}

        <!-- Content Area -->
        {{-- <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Recent Activity</h2>
            <div class="space-y-4">
                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-plus text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">New user registered</p>
                        <p class="text-sm text-gray-600">jane.doe@example.com joined the platform</p>
                    </div>
                    <span class="text-sm text-gray-500">2 hours ago</span>
                </div>
                
                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-green-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Order completed</p>
                        <p class="text-sm text-gray-600">Order #12345 has been processed</p>
                    </div>
                    <span class="text-sm text-gray-500">4 hours ago</span>
                </div>
            </div>
        </div> --}}
    </main>

    <!-- Login Modal -->
    <div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Login</h2>
                <button onclick="hideLoginModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="loginForm">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
                    </div>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-500">Forgot password?</a>
                </div>
                
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    Sign In
                </button>
            </form>
        </div>
    </div>

    <!-- Overlay for mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>

    <script>
        // Function to initialize menu state
        function initializeMenuState() {
            // Get the active menu from PHP if available
            const activeMenu = '{{ $activeMenu }}';
            
            // Find and open the menu containing the active item
            document.querySelectorAll('.menu-toggle').forEach(button => {
                const submenu = button.nextElementSibling;
                if (submenu) {
                    // Check if this submenu contains the active menu item
                    const hasActiveItem = Array.from(submenu.querySelectorAll('button'))
                        .some(item => item.classList.contains('bg-gray-200'));
                    
                    if (hasActiveItem) {
                        // Open this menu
                        const chevron = button.querySelector('.fa-chevron-down');
                        submenu.classList.add('submenu-open');
                        submenu.style.maxHeight = submenu.scrollHeight + 'px';
                        if (chevron) chevron.style.transform = 'rotate(180deg)';
                        button.classList.add('active');
                    }
                }
            });
        }
        
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize menu state
            initializeMenuState();
            // Toggle sidebar
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('overlay');

            if (sidebarToggle && sidebar && mainContent && overlay) {
                sidebarToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('-translate-x-full');
                    if (window.innerWidth < 1024) {
                        overlay.classList.toggle('hidden');
                    }
                });

                // Close sidebar when clicking overlay
                overlay.addEventListener('click', () => {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });

                // Responsive sidebar
                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 1024) {
                        sidebar.classList.remove('-translate-x-full');
                        overlay.classList.add('hidden');
                    } else {
                        sidebar.classList.add('-translate-x-full');
                    }
                });

                // Initialize responsive behavior
                if (window.innerWidth < 1024) {
                    sidebar.classList.add('-translate-x-full');
                    mainContent.classList.remove('ml-64');
                }
            }

            // Menu toggle functionality
            document.querySelectorAll('.menu-toggle').forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    const submenu = button.nextElementSibling;
                    const chevron = button.querySelector('.fa-chevron-down');
                    
                    // Close all other menus first
                    document.querySelectorAll('.menu-toggle').forEach(otherButton => {
                        if (otherButton !== button) {
                            const otherSubmenu = otherButton.nextElementSibling;
                            const otherChevron = otherButton.querySelector('.fa-chevron-down');
                            if (otherSubmenu && otherSubmenu.classList.contains('submenu-open')) {
                                otherSubmenu.classList.remove('submenu-open');
                                otherSubmenu.style.maxHeight = '0';
                                if (otherChevron) otherChevron.style.transform = 'rotate(0deg)';
                            }
                        }
                    });
                    
                    // Toggle the clicked menu
                    if (submenu) {
                        if (submenu.classList.contains('submenu-open')) {
                            submenu.classList.remove('submenu-open');
                            submenu.style.maxHeight = '0';
                            if (chevron) chevron.style.transform = 'rotate(0deg)';
                        } else {
                            submenu.classList.add('submenu-open');
                            submenu.style.maxHeight = submenu.scrollHeight + 'px';
                            if (chevron) chevron.style.transform = 'rotate(180deg)';
                        }
                    }
                });
            });

            // Submenu toggle functionality (level 3 menus)
            document.querySelectorAll('.submenu-toggle').forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    const submenu = button.nextElementSibling;
                    const chevron = button.querySelector('.fa-chevron-down');
                    
                    // Close all other submenus at the same level
                    const parentSubmenu = button.closest('.submenu');
                    if (parentSubmenu) {
                        parentSubmenu.querySelectorAll('.submenu-toggle').forEach(otherButton => {
                            if (otherButton !== button) {
                                const otherSubmenu = otherButton.nextElementSibling;
                                const otherChevron = otherButton.querySelector('.fa-chevron-down');
                                if (otherSubmenu && otherSubmenu.classList.contains('submenu-open')) {
                                    otherSubmenu.classList.remove('submenu-open');
                                    otherSubmenu.style.maxHeight = '0';
                                    if (otherChevron) otherChevron.style.transform = 'rotate(0deg)';
                                }
                            }
                        });
                    }
                    
                    // Toggle the clicked submenu
                    if (submenu) {
                        if (submenu.classList.contains('submenu-open')) {
                            submenu.classList.remove('submenu-open');
                            submenu.style.maxHeight = '0';
                            if (chevron) chevron.style.transform = 'rotate(0deg)';
                        } else {
                            submenu.classList.add('submenu-open');
                            submenu.style.maxHeight = submenu.scrollHeight + 'px';
                            if (chevron) chevron.style.transform = 'rotate(180deg)';
                        }
                    }
                });
            });

            // User menu toggle
            const userMenuToggle = document.getElementById('userMenuToggle');
            const userDropdown = document.getElementById('userDropdown');

            if (userMenuToggle && userDropdown) {
                userMenuToggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    userDropdown.classList.toggle('hidden');
                });

                // Close user dropdown when clicking outside
                document.addEventListener('click', (e) => {
                    if (!userMenuToggle.contains(e.target) && !userDropdown.contains(e.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }

            // Login form submission
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    // Here you would typically send the form data to your Laravel backend
                    console.log('Login form submitted');
                    hideLoginModal();
                });
            }
        });

        // Login modal functions (global scope for onclick handlers)
        function showLoginModal() {
            const loginModal = document.getElementById('loginModal');
            if (loginModal) {
                loginModal.classList.remove('hidden');
            }
        }

        function hideLoginModal() {
            const loginModal = document.getElementById('loginModal');
            if (loginModal) {
                loginModal.classList.add('hidden');
            }
        }

        // Close modal when clicking outside
        document.addEventListener('click', (e) => {
            const loginModal = document.getElementById('loginModal');
            if (loginModal && e.target === loginModal) {
                hideLoginModal();
            }
        });
        
        // Listen for Livewire events to update menu state
        document.addEventListener('livewire:load', function() {
            Livewire.hook('message.processed', (message, component) => {
                // Re-initialize menu state after Livewire updates
                initializeMenuState();
            });
            
            // Listen for custom menu state update event
            window.addEventListener('menu-state-updated', event => {
                // Close all menus first
                document.querySelectorAll('.menu-toggle').forEach(button => {
                    const submenu = button.nextElementSibling;
                    const chevron = button.querySelector('.fa-chevron-down');
                    if (submenu && submenu.classList.contains('submenu-open')) {
                        submenu.classList.remove('submenu-open');
                        submenu.style.maxHeight = '0';
                        if (chevron) chevron.style.transform = 'rotate(0deg)';
                    }
                });
                
                // Then initialize the menu state again
                setTimeout(initializeMenuState, 100);
            });
        });
    </script>
</body>

</div>
