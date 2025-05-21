<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png">
    <!-- Include Tailwind CSS for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="w-full max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
        <!-- Page Header -->
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Manage Users</h1>

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

        <!-- Users Table -->
        @if ($users->isEmpty())
            <p class="text-gray-600 text-center">No users found.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="px-4 py-2 text-left">#</th>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">Verified</th>
                            <th class="px-4 py-2 text-left">Role</th>
                            <th class="px-4 py-2 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $user->id }}</td>
                                <td class="px-4 py-2">{{ $user->name }}</td>
                                <td class="px-4 py-2">{{ $user->email }}</td>
                                <td class="px-4 py-2">
                                    {{ $user->email_verified_at ? 'Yes' : 'No' }}
                                </td>
                                <td class="px-4 py-2">
                                    @if($user->roles->isEmpty())
                                        None
                                    @else
                                        @foreach($user->roles as $role)
                                            <div class="flex items-center justify-between">
                                                <span>{{ $role->name }}</span>
                                                <form action="{{ route('users.remove-role', ['user' => $user->id, 'role' => $role->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove the role {{ $role->name }} from {{ $user->name }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 ml-2" title="Remove Role">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <form action="{{ route('users.change-role', $user->id) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <select name="role_id" class="p-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ $user->roles->contains($role->id) ? 'selected' : '' }}>{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit"
                                                class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 transition flex items-center">
                                            <i class="fas fa-sync-alt mr-1"></i> Change
                                        </button>
                                    </form>
                                    @error('role_id')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Pagination Links -->
        @if ($users->hasPages())
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        @endif

        <!-- Back to Profile Link -->
        <div class="mt-6 text-center">
            <a href="{{ route('profile', ['user' => $user]) }}"
               class="text-blue-500 hover:text-blue-700 font-medium">
                Back to Profile
            </a>
        </div>
    </div>
</body>
</html>