<div>
    <div class="rounded-lg shadow-sm overflow-hidden">
        <div class="flex justify-between items-center">
            <div class="pb-2">
                <h1 class="text-2xl font-bold text-gray-900">Financial Year Information</h1>
                <p class="text-gray-600 mt-1">Welcome back, John! Here's what's happening with your admin panel.</p>
            </div>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors duration-200">
                <i class="fas fa-plus"></i>
                <span>Add New Financial Year</span>
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
                        Address <i class="fas fa-sort text-gray-400 ml-1"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="id">
                        Actions <i class="fas fa-sort text-gray-400 ml-1"></i>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($financialYears as $financialYear)
                <tr class="bg-white border-b hover:bg-gray-100">
                    <td class="px-4 py-1 text-left text-sm font-medium text-gray-900">
                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                            <span class="text-xs font-medium text-gray-600">{{ $loop->iteration }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-1 text-sm font-bold text-gray-900">
                        {{ $financialYear->name }}
                    </td>
                    <td class="px-4 py-1 text-sm font-medium text-gray-900">
                        <span class="inline-flex items-center px-2.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Role Role
                        </span>
                        {{-- {{ $financialYear->organisation->name }} --}}

                    </td>

                    <td class="px-4 py-1 text-sm">
                        {{-- <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Role</span> --}}
                        <div class="flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-900 text-sm" >
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-900 text-sm" >
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>
