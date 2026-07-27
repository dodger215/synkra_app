<x-layouts.guest title="Register | {{ $store->store_name }}">
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Join {{ $store->store_name }}
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('storefront.login', $store->id) }}" class="font-medium text-orange-600 hover:text-orange-500">
                        Sign in
                    </a>
                </p>
            </div>
            <form class="mt-8 space-y-6" action="{{ route('storefront.register', $store->id) }}" method="POST">
                @csrf
                <div class="rounded-md shadow-sm -space-y-px">
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <input name="first_name" type="text" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm" placeholder="First Name">
                        <input name="last_name" type="text" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm" placeholder="Last Name">
                    </div>
                    <div>
                        <input name="email" type="email" required class="appearance-none rounded-t-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm" placeholder="Email address">
                    </div>
                    <div>
                        <input name="password" type="password" required class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm" placeholder="Password">
                    </div>
                    <div>
                        <input name="password_confirmation" type="password" required class="appearance-none rounded-b-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm" placeholder="Confirm Password">
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.guest>
