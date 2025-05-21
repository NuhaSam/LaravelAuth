<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Account</title>
    <!-- Include Tailwind CSS for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md m-4">
        <!-- Page Header -->
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Verify Your Account</h1>

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

        <!-- OTP Verification Form -->
        <form action="{{ route('verifyAccount',['identifier'=> $identifier]) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="otp" class="block text-sm font-medium text-gray-600">Enter 6-Digit OTP Code</label>
                <input type="text" name="otp" id="otp" value="{{ old('otp') }}"
                       class="w-full mt-1 p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="123456" maxlength="6" required>
            </div>
            <div>
                <label for="identifier" class="block text-sm font-medium text-gray-600">Identifier</label>
                <input type="text" name="identifier" id="identifier" value="{{ old('identifier', $identifier) }}"
                       class="w-full mt-1 p-2 border rounded-md bg-gray-100 cursor-not-allowed"
                       readonly>
                <!-- <input type="text" name="identifier" value="{{ $identifier }}"> -->
                <input type="hidden" name="identifier" value="{{ $identifier }}">
            </div>
            <button type="submit"
                    class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 transition flex items-center justify-center">
                <i class="fas fa-check-circle mr-2"></i> Verify Account
            </button>
        </form>

        <!-- Try Another Way Link -->
        <div class="mt-4 text-center">
            {{$identifier}}
            <a href="{{ url('/verification-method', $identifier) }}"
               class="text-blue-500 hover:text-blue-700 font-medium">
                Try Another Way
            </a>
        </div>
    </div>
</body>
</html>