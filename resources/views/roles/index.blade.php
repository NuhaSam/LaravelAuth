<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Roles</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png">
    <!-- Include Tailwind CSS for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="w-full max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
        <!-- Page Header -->
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Manage Roles</h1>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Roles Table -->
        @if ($roles->isEmpty())
            <p class="text-gray-600 text-center">No roles found.</p>
        @else
            <div class="overflow-x-auto">
                <a href="{{ route('roles.create') }}" class="text-blue-500 hover:text-blue-700 mr-2">
    <i class="fas fa-plus"></i> Create New Role
</a><table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="px-4 py-2 text-left">#</th>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Permissions</th>
                            <th class="px-4 py-2 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $role->id }}</td>
                                <td class="px-4 py-2">{{ $role->name }}</td>
                                <td class="px-4 py-2">
                                    {{ $role->permissions ."a"}}
                                </td>

                                <td class="px-4 py-2">
                                    <!-- Update Button -->
                                    <a href="{{ route('roles.edit', $role->id) }}"
                                       class="text-blue-500 hover:text-blue-700 mr-2">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <!-- Delete Button -->
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-500 hover:text-red-700"
                                                onclick="return confirm('Are you sure you want to delete this role?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Pagination Links -->
        @if ($roles->hasPages())
            <div class="mt-6">
                {{ $roles->links() }}
            </div>
        @endif

        <!-- Back to Users Link -->
        <div class="mt-6 text-center">
            <a href="{{ route('users') }}"
               class="text-blue-500 hover:text-blue-700 font-medium">
                Back to Users
            </a>
        </div>
    </div>
</body>
</html>