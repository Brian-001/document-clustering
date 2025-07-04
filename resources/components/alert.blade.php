<div class="bg-{{ $type }}-50 border-l-4 border-{{ $type }}-500 text-{{ $type }}-700 p-4 mb-8 rounded-r-lg flex items-center transition-opacity duration-300 ease-in-out" role="alert" tabindex="0" aria-live="polite" x-data="{ show: true }" x-show="show" x-transition>
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
    </svg>
    <span>{{ $slot }}</span>
    <button class="ml-auto text-{{ $type }}-700 hover:text-{{ $type }}-900" @click="show = false" aria-label="Close alert">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
</div>