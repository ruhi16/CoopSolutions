<div>
    <div class="rounded-lg shadow-sm overflow-hidden">
        <div id="alert-container" style="position: fixed; top: 10px; right: 10px; z-index: 1000;">
            @if (session()->has('success'))
                <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-200 dark:bg-gray-800 dark:text-blue-400" role="alert">
                    <span class="font-bold">Info alert!</span> {{ session('success') }}
                </div>
                <script>
                    setTimeout(function() {
                        document.getElementById('alert-container').innerHTML = '';
                    }, 5000);
                </script>
            @endif
            @if (session()->has('error'))
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-200 dark:bg-gray-800 dark:text-red-400" role="alert">
                    <span class="font-bold">Danger alert!</span> {{ session('error') }}
                </div>
                <script>
                    setTimeout(function() {
                        document.getElementById('alert-container').innerHTML = '';
                    }, 5000);
                </script>
            @endif
        </div>

        <div class="flex justify-between items-center">
            <div class="pb-2">
                <h1 class="text-2xl font-bold text-gray-900">Member Information</h1>
                <p class="text-gray-600 mt-1">Welcome back, John! Here's what's happening with your admin panel.</p>
            </div>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors duration-200">
                <i class="fas fa-plus"></i>
                <span>Add New Member</span>
            </button>
        </div>

        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 rounded-md">
                <tr class="">
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="id">
                        SL <i class="fas fa-sort text-gray-400 ml-1"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="id">
                        Name <i class="fas fa-sort text-gray-400 ml-1"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="id">
                        Account No <i class="fas fa-sort text-gray-400 ml-1"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="id">
                        Address <i class="fas fa-sort text-gray-400 ml-1"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="id">
                        Actions <i class="fas fa-sort text-gray-400 ml-1"></i>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                <tr class="bg-white border-b hover:bg-gray-100">
                    <td class="px-4 py-1 text-left text-sm font-medium text-gray-900">
                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                            <span class="text-xs font-medium text-gray-600">{{ $loop->iteration }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-1 text-sm font-bold text-gray-900">
                        {{ $member->name ?? 'NA' }}
                    </td>
                    <td class="px-4 py-1 text-sm font-bold text-gray-900">
                        {{ $member->account_no ?? 'NA' }}
                    </td>
                    <td class="px-4 py-1 text-sm font-medium text-gray-900">
                        {{-- <span class="inline-flex items-center px-2.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Role Role
                        </span> --}}
                        {{-- @foreach($member->financialYears as $financialYear)
                            <span class="inline-flex items-center px-2.5 rounded-full text-xs font-medium {{ $financialYear->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $financialYear->name }}, {{ $financialYear->is_active }}
                            </span>
                        @endforeach --}}
                        {{-- <button
                            wire:click="doSomething"
                            class="text-blue-600 hover:text-blue-900 text-sm" >
                            call livewire method to JS func
                        </button>
                        <button
                            onclick="callLivewireMethod()"
                            class="text-blue-600 hover:text-blue-900 text-sm" >
                            call JS func to call livewire method
                        </button> --}}
                    </td>

                    <td class="px-4 py-1 text-sm">
                        {{-- <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Role</span> --}}
                        <div class="flex space-x-2">
                            <button id="editMemberButton({{ $member->id }})" 
                            {{-- onclick="editMember({{ $member->id }})"  --}}
                            wire:click="openModal({{ $member->id }})"
                            class="text-blue-600 hover:text-blue-900 text-sm" >
                                <i class="fas fa-edit"></i>
                            </button>
                            {{-- <button class="text-red-600 hover:text-red-900 text-sm" >
                                <i class="fas fa-trash"></i>
                            </button> --}}
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>





    <!-- Login Modal -->
    <div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 {{ $showMemberModal ? 'block' : 'hidden' }}">
        <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-2xl mx-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">New Member Details Entry</h2>
                <button
                    wire:click="closeModal" 
                    onclick="hideLoginModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="loginForm" wire:submit.prevent="saveMemberDetails" class="space-y-4">
                {{-- <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div> --}}

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input wire:model="name" type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="designation" class="block text-sm font-medium text-gray-700 mb-2">Designation</label>
                    {{-- <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"> --}}
                    <select wire:model="designation" 
                        id="designation" name="designation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" >
                        <option class="">Select One</option>
                        <option value="Asst Teacher">Asst Teacher</option>
                        <option value="Para Teacher">Para Teacher</option>
                        <option value="Contract Teacher">Contract Teacher</option>
                        <option value="Clerk">Clerk</option>
                    </select>
                    @error('designation')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror

                </div>
                
                <div class="mb-4">
                    <label for="accountNo" class="block text-sm font-medium text-gray-700 mb-2">Account No</label>
                    <input wire:model="accountNo" type="number" id="accountNo" name="account_no" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('accountNo')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                {{-- <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
                    </div>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-500">Forgot password?</a>
                </div> --}}
                
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    Save Member
                </button>
            </form>
        </div>
    </div>

    <!-- Overlay for mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>



    
    
    {{-- <script>
        function callLivewireMethod() {
            console.log('js method called!')
            // Method 1: Using window.livewire
            // window.livewire.emit('doSomething2', 'value1', 'value2');
            
            // Method 2: Using Livewire directly
            Livewire.emit('doSomething2', 'value1', 'value2');
            
            // Method 3: If you have the component instance
            // let component = Livewire.find('example-component');
            // component.call('doSomething', 'value1', 'value2');
        }

        // Listen for response
            document.addEventListener('livewire:load', function () {
                Livewire.on('methodCalled', (message) => {
                    console.log(message);
                    alert(message);
                });
            });

    </script> --}}
    {{-- <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('jsEvent', (data) => {
                // This JavaScript will run after doSomething() completes
                console.log('Received:', data);
                alert(data.data);
            });
        });
    </script> --}}
    
    <script>
        const mainContent = document.getElementById('mainContent');
        const overlay = document.getElementById('overlay');


        // function jsFunc(){
        //     console.log('JS function called!');
        // }
        
        // $js('jsFunc', () => {
        //     console.log('jsFunc called!')
        // })
    
    //     function editMember(memberId) {
    //         document.getElementById('loginModal').classList.remove('hidden');
    //     }

    // // Login modal functions
    //     function showLoginModal() {
    //         document.getElementById('loginModal').classList.remove('hidden');
    //     }

    //     function hideLoginModal() {
    //         document.getElementById('loginModal').classList.add('hidden');
    //     }

    //     // Close modal when clicking outside
    //     document.getElementById('loginModal').addEventListener('click', (e) => {
    //         if (e.target === document.getElementById('loginModal')) {
    //             hideLoginModal();
    //         }
    //     });

        // Login form submission
        // document.getElementById('loginForm').addEventListener('submit', (e) => {
        //     e.preventDefault();
        //     // Here you would typically send the form data to your Laravel backend
        //     console.log('Login form submitted');
        //     hideLoginModal();
        // });
    </script>
   
</div>
