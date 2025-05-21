<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png">
    <!-- Include Tailwind CSS for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md m-4">
        <!-- Page Header -->
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Log In</h1>

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

        <!-- Login Form -->
        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="identifier" class="block text-sm font-medium text-gray-600">Email or Phone Number</label>
                <input type="text" name="identifier" id="identifier" value="{{ old('identifier') }}"
                       class="w-full mt-1 p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Enter email or phone number" autocomplete="off" autofocus required>
                @error('identifier')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
                <input type="password" name="password" id="password"
                       class="w-full mt-1 p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Enter your password" required>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember"
                       class="h-4 w-4 text-blue-500 focus:ring-blue-500 border-gray-300 rounded"
                       {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" class="ml-2 text-sm text-gray-600">Remember Me</label>
            </div>
            <button type="submit"
                    class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 transition flex items-center justify-center">
                <i class="fas fa-sign-in-alt mr-2"></i> Log In
            </button>
        </form>

        <!-- Forgot Password and Register Links -->
        <div class="mt-4 text-center space-y-2">
            <p class="text-sm">
                Forgot your password?
                <a href="{{ route('forget-password') }}" class="text-blue-500 hover:text-blue-700 font-medium">
                    Reset now
                </a>
            </p>
            <p class="text-sm">
                Don’t have an account?
                <a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-700 font-medium">
                    Register
                </a>
            </p>
        </div>

        <!-- Social Login Buttons -->
        @if (config('social.providers'))
            <div class="mt-6">
                <h3 class="text-sm font-medium text-gray-600 text-center mb-4">Or log in with</h3>
                <div class="flex justify-center gap-4">
                    @foreach (config('social.providers') as $provider)
                    <!-- <p>{{ $provider['url'] }}</p> -->
                        <a href="{{ $provider['url'] }}"
                           class="flex items-center justify-center w-12 h-12 rounded-full bg-{{ $provider['color'] }}-500 text-white hover:bg-{{ $provider['color'] }}-600 transition">
                            <i class="fab fa-{{ strtolower($provider['name']) }} text-lg"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</body>
</html>