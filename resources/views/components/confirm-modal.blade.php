<div x-data="{ open: false, formEl: null, title: 'Konfirmasi', message: 'Yakin lanjut?', okText: 'Lanjut', cancelText: 'Batal' }"
    @open-confirm.window="
        formEl = $event.detail?.form ?? null;
        title = $event.detail?.title ?? 'Konfirmasi';
        message = $event.detail?.message ?? 'Yakin ingin melanjutkan tindakan ini?';
        okText = $event.detail?.okText ?? 'Ya';
        cancelText = $event.detail?.cancelText ?? 'Batal';
        open = true;
     ">
    <!-- Backdrop -->
    <div x-show="open" x-transition.opacity class="fixed inset-0 z-[10000] bg-black/40"></div>

    <!-- Modal -->
    <div x-show="open" x-transition class="fixed inset-0 z-[10001] flex items-center justify-center p-4">
        <div class="w-full max-w-sm rounded-xl bg-white shadow-xl ring-1 ring-black/5">
            <div class="p-5">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5">
                        <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01M5.07 19h13.86A2 2 0 0021 17.09L13.93 4.58a2 2 0 00-3.46 0L3 17.09A2 2 0 005.07 19z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-base font-semibold text-gray-900" x-text="title"></h3>
                        <p class="mt-1 text-sm text-gray-600" x-text="message"></p>
                    </div>
                </div>

                <div class="mt-5 flex items-center justify-end gap-2">
                    <button type="button" @click="open = false; formEl = null"
                        class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        x-text="cancelText"></button>

                    <button type="button"
                        @click="
                    open = false;
                    window.AppLoader?.on && window.AppLoader.on();
                    formEl?.submit();
                    formEl = null;
                  "
                        class="rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-700"
                        x-text="okText"></button>
                </div>
            </div>
        </div>
    </div>
</div>
