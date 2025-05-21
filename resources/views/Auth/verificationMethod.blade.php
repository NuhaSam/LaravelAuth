<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Verification Method</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png">
    <!-- Include Tailwind CSS for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md m-4">
        <!-- Page Header -->
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Choose Verification Method</h1>

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

        <!-- Verification Method Selection -->
        <div class="space-y-4">
            <!-- Email Verification Form -->
            <form action="{{ route('send.otp',['identifier' => $identifier]) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="method" value="email">
                <input type="hidden" name="identifier" value="{{ $identifier }}">
                <!-- <input type="text" name="identifier" id="email_identifier" value=" id {{ $identifier }}" -->
               > <!-- <div>
                    <label for="email_identifier" class="block text-sm font-medium text-gray-600">Email Address</label>
                           class="w-full mt-1 p-2 border rounded-md bg-gray-100 cursor-not-allowed"
                           readonly>
                </div> -->
                <button type="submit"
                        class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 transition flex items-center justify-center">
                    <i class="fas fa-envelope mr-2"></i> Verify with Email
                </button>
            </form>

            <!-- Phone Verification Form -->
            <form action="{{ route('send.otp') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="method" value="phone">
                <input type="hidden" name="identifier" value="{{ $identifier }}">
                <div>
                    <!-- <label for="phone_identifier" class="block text-sm font-medium text-gray-600">Phone Number</label> -->
                    <!-- <input type="text" name="identifier" id="phone_identifier" value="{{ $identifier }}"
                           class="w-full mt-1 p-2 border rounded-md bg-gray-100 "
                           readonly> -->
                </div>
                <button type="submit"
                        class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 transition flex items-center justify-center">
                    <i class="fas fa-phone mr-2"></i> Verify with Phone Number
                </button>
            </form>
        </div>

        <!-- Back to Login Link -->
        <div class="mt-4 text-center">
            <p class="text-sm">
                Already verified?
                <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-700 font-medium">
                    Log in
                </a>
            </p>
        </div>
    </div>
</body>
</html>