<x-layouts.auth>
    <div class="flex flex-col items-center gap-2 text-center">
    <img src="{{ asset('storage/logo.png') }}" alt="Logo" class="h-10 w-10 rounded-full shadow-md">
        <h1 class="text-white text-lg font-semibold">Al-Management</h1>
        <h2 class="text-2xl font-bold text-white">Sign up</h2>
    </div>

    <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-4 mt-6">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm text-white mb-1">Name<span class="text-red-500">*</span></label>
            <input id="name" type="text" name="name" required autofocus
                class="w-full rounded-md border border-gray-500 bg-transparent text-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm text-white mb-1">Email address<span class="text-red-500">*</span></label>
            <input id="email" type="email" name="email" required
                class="w-full rounded-md border border-gray-500 bg-transparent text-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm text-white mb-1">Password<span class="text-red-500">*</span></label>
            <div class="relative">
                <input id="password" type="password" name="password" required
                    class="w-full rounded-md border border-gray-500 bg-transparent text-white px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-300">
                    👁️
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm text-white mb-1">Confirm Password<span class="text-red-500">*</span></label>
            <div class="relative">
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="w-full rounded-md border border-gray-500 bg-transparent text-white px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-300">
                    👁️
                </button>
            </div>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center space-x-2">
            <input type="checkbox" id="remember" name="remember" class="rounded border-gray-700 bg-gray-900 text-blue-600 focus:ring-blue-500" />
            <label for="remember" class="text-sm text-gray-300">Remember me</label>
        </div>

        <!-- Submit -->
        <div>
            <button type="submit"
                class="w-full rounded-md bg-blue-600 hover:bg-blue-700 text-white py-2 font-semibold shadow transition duration-200">
                Sign up
            </button>
        </div>
    </form>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === "password" ? "text" : "password";
        }
    </script>
</x-layouts.auth>
