<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Role</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="w-full max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Edit Role</h1>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-2">Role Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}"
                       class="w-full border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Permissions</label>
                <input type="text" name="permissions" id="permissions" value="{{ old('permissions', $role->permissions) }}"
                       class="w-full border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
            
            </div>

            <div class="flex justify-end">
                <a href="{{ route('roles.index') }}"
                   class="text-gray-500 hover:text-gray-700 mr-4 font-medium">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                    Update Role
                </button>
            </div>
        </form>
    </div>
</body>
</html>