<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- Include Tailwind CSS for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-2xl p-6 bg-white rounded-lg shadow-md m-4">
        <!-- Page Header -->
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Welcome, {{ Auth::user()->name }}</h1>

        <!-- Success Message -->
        @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
            {{ session('success') }}
        </div>
        @endif

        <!-- Error Messages -->
        @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Update Profile Form -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Update Profile</h2>
            <form action="{{ route('update-profile') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-600">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                        class="w-full mt-1 p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                        class="w-full mt-1 p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-600">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                        class="w-full mt-1 p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit"
                    class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 transition">
                    Update Profile
                </button>
            </form>
        </div>

        <!-- Change Password Button -->
        <div class="mb-8">
            <form action="{{ route('change-password') }}" method="POST">
                @csrf
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-600">Current Password</label>
                    <input type="password" name="current_password" id="current_password"
                        class="w-full mt-1 p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="passowrd" class="block text-sm font-medium text-gray-600">New Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full mt-1 p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-600">Password Confirmation</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full mt-1 p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit"
                    class="w-full bg-yellow-500 text-white p-2 rounded-md hover:bg-yellow-600 transition">
                    Change Password
                </button>
            </form>
        </div>

        <!-- Logout Button -->
        <div class="mb-8">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full bg-red-500 text-white p-2 rounded-md hover:bg-red-600 transition">
                    Logout
                </button>
            </form>
        </div>

        <!-- Active Sessions -->
        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Active Sessions</h2>
            @foreach (auth()->user()->sessions()->orderBy('last_activity', 'desc')->get() as $session)
            <div class="border p-4 mb-4 rounded-md bg-gray-50">
                <p class="text-sm text-gray-600">
                    <strong>Platform:</strong> {{ $session->user_agent['platform'] ?? 'Unknown' }} |
                    <strong>Browser:</strong> {{ $session->user_agent['browser'] ?? 'Unknown' }}
                </p>
                <p class="text-sm text-gray-600">
                    <strong>IP Address:</strong> {{ $session->ip_address }}
                    @if ($session->is_this_device)
                    <span class="text-green-600 font-semibold">(This Device)</span>
                    @endif
                </p>
                <p class="text-sm text-gray-600">
                    <strong>Last Activity:</strong> {{ $session->last_activity }}
                </p>
                @if ($session->is_this_device)
                <form action="{{ route('logout.device', $session) }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit"
                        class="bg-red-500 text-white px-4 py-1 rounded-md hover:bg-red-600 transition">
                        Logout Device
                    </button>
                </form>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</body>

</html>