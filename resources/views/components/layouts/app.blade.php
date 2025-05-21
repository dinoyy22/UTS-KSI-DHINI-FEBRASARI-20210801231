<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: JSON.parse(localStorage.getItem('darkMode')) || false }" x-init="$watch('darkMode', value => { localStorage.setItem('darkMode', JSON.stringify(value)); document.documentElement.classList.toggle('dark', value); }); document.documentElement.classList.toggle('dark', darkMode);" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')

        <title>{{ $title ?? 'Page Title' }}</title>
        <style>
            /* Smooth scroll behavior for the entire page */
            html {
                scroll-behavior: smooth;
            }

            /* Remove any gaps between sections */
            section {
                margin: 0;
                padding: 0;
            }

            /* Fix nested component gaps */
            .container {
                width: 100%;
                margin-left: auto;
                margin-right: auto;
            }
        </style>
    </head>
    <body x-data="{ showScrollButton: false }"
          @scroll.window="showScrollButton = (window.pageYOffset > 300) ? true : false"
          class="min-h-screen">
        @include('livewire.nav', ['darkMode' => 'darkMode'])
        <main :class="{ 'bg-gray-900 text-white': darkMode, 'bg-white text-gray-900': !darkMode }">
            {{ $slot }}
        </main>
        @include('livewire.footer', ['darkMode' => 'darkMode'])

        <!-- Global scroll to top button (appears outside footer for global accessibility) -->
        <div x-show="showScrollButton"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-2"
             class="fixed bottom-8 right-8 z-50">
            <button @click="window.scrollTo({top: 0, behavior: 'smooth'})"
                class="p-3 rounded-full shadow-lg transition duration-300 ease-in-out transform hover:scale-110"
                :class="{ 'bg-gray-700 hover:bg-gray-600': darkMode, 'bg-indigo-600 hover:bg-indigo-500': !darkMode }">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </button>
        </div>
    </body>
</html>
