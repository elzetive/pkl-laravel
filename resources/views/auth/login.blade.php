<x-guest-layout>
    <div x-data="{ showModal: false, errorMessage: '', pinError: '', loading: false }">
        <form id="loginForm" @submit.prevent="
        loading = true;
        errorMessage = '';
        let formData = new FormData($el);

        fetch('{{ route('login') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json'},
            body: formData
        })
        .then(res => res.json().then(data => ({ status : res.status, body: data})))
        .then(res => {
            if (res.status === 200 && res.body.status === 'success') {
            showModal = true;
            $nextTick( () => $refs.pinInput.focus());
            } else {
             errorMessage = res.body.errors?.username?.[0] || 'Login gagal.';
             }
        })
        .catch(() => {
            errorMessage = 'Terjadi kesalahan pada sistem.';
        })
        .finally(() => {
        loading = false;
        });
        ">

        @csrf

        <template x-if="errorMessage">
            <div class="mb-4 text-sm text-red-600" x-text="errorMessage"></div>
        </template>
        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus />
        </div>
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
        </div>
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input type="checkbox" id="remember_me" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" ::disabled="loading" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md shadow-sm transition-colors text-sm">
                <span x-text="loading ? 'Memeriksa...' : 'Login'">
                </span>
            </button>
        </div>

        <div x-show="showModal" class="fixed inset-0 z-50 flex items-start justify-center pt-20 bg-black/50" style="display: none;">
            <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-sm">
            <h2 class="text-lg font-bold mb-2">Verifikasi Keamanan</h2>
            <p class="text-sm text-gray-600 mb-4">Masukkan PIN Anda untuk melanjutkan.</p>

            <template x-if="pinError">
                <div class="mb-3 text-sm text-red-600 font-semibold" x-text="pinError"></div>
            </template>

            <input type="password" name="pin" x-ref="pinInput" placeholder="Masukkan PIN" class="text-center w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 mb-4" maxlength="10">

            <div class="flex justify-end gap-2">
                <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-300 rounded-md text-sm">Batal</button>
                <button type="button" @click="
                    pinError = '';
                    let formData = new FormData(document.getElementById('loginForm'));
                    formData.append('pin', $refs.pinInput.value);

                    fetch('{{ route('login') }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json'},
                        body: formData
                    })
                    .then(res => res.json().then(data => ({ status: res.status, body:data })))
                    .then(res => {
                        if (res.status === 200 && res.body.status === 'authenticated') {
                            window.location.href = res.body.redirect;
                        } else {
                            pinError = res.body.errors?.pin?.[0] || 'PIN Salah!';
                            $refs.pinInput.value = '';
                        }
                    });
                " class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold">Verifikasi</button>
            </div>
            </div>
        </div>

        </form>
    </div>
</x-guest-layout>
