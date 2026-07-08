<div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
    <div class="max-w-xl">
        <h3 class="text-lg font-medium text-gray-900">{{ __('Logout') }}</h3>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Click the button below to logout from your account.') }}
        </p>
        <div class="mt-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Logout') }}
                </button>
            </form>
        </div>
    </div>
</div>