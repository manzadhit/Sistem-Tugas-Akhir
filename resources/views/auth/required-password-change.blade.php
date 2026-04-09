<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Password akun Anda masih menggunakan password bawaan. Demi keamanan, silakan ganti password terlebih dahulu
        sebelum melanjutkan.
    </div>

    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
        @csrf
    </form>

    <form method="POST" action="{{ route('password.change-required.update') }}">
        @csrf
        @method('PUT')

        <div>
            <x-input-label for="password" :value="__('Password Baru')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autofocus
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6 flex items-center justify-between gap-3">
            <button type="submit" form="logout-form"
                class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Keluar
            </button>

            <x-primary-button>
                {{ __('Simpan Password Baru') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
