<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        @livewireStyles

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    </head>
    <body>
        <div x-data="appLayout" class="font-inherit text-gray-900 antialiased min-h-screen max-w-screen bg-black">
            <div id="loading_state" class="fixed top-0 z-50 w-full min-h-screen bg-black opacity-90 flex items-center justify-center transform duration-700">
                <div class="">
                    <x-state.loading />
                </div>
            </div>
            <div class="flex w-full">
                <div id="navBar" class="hidden lg:flex w-full md:w-60 h-full" :class="{'hidden':! nav, 'lg:inline-flex': nav}">
                <div @click="nav = !nav" class="fixed right-6 top-3 md:hidden py-2 px-3 flex items-center cursor-pointer"><x-icon.menu size="20" color="#ffffff"/></div>
                    @livewire('layout.nav-bar', ['page' => $page, 'projectId' => $projectId, 'tab' => $tab])
                
                </div>
                
                <div class="flex flex-col h-screen w-full bg-gray-50 overflow-y-scroll scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-gray-100 scrollbar-thumb-rounded-full scrollbar-track-rounded-full" :class="{'hidden': nav, 'md:flex': nav}">
                    <div class="h-auto">
                        <div class="flex justify-between w-full h-14 bg-white shadow-sm">
                            <div @click="nav = !nav" class="lg:hidden py-2 px-3 flex items-center cursor-pointer"><x-icon.menu size="20" color="#000000" /></div>
                            <div class="w-full flex items-center justify-center font-semibold text-lg">Welcome to {{ env('APP_NAME') }}, {{ $user }}</div>
                            <div class="lg:hidden py-2 px-3 flex items-center"> <x-icon.dots size="20"/> </div>
                        </div>
                        <div class="p-6 flex items-center justify-start">
                            <div class="h-8 text-2xl text-gray-500 ">  {{ env('APP_NAME') }} / <b class="text-black font-bold"> {{ $page }}</b> </div>
                        </div>
                    </div>                    
                    
                    <div class="flex flex-col justify-center items-center h-full">
                        {{ $slot }}
                    </div>                
                </div>
            </div>
            
              
            
            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('appLayout', () => ({
                        nav: true,

                        init()
                        {
                            if(window.matchMedia('(max-width: 1024px)'))
                            {
                                this.nav = false
                            }
                        }
                    }))
                })
            </script>              
        </div>
        @livewireScripts
    </body>
    <script>
        window.addEventListener('load', (event) => {
            document.getElementById('loading_state').classList.add('opacity-0');
            document.getElementById('loading_state').classList.remove('opacity-90');
            timeFunction();

            function timeFunction()
            {
                setTimeout(function(){
                    document.getElementById('loading_state').classList.add('hidden');
                }, 1200);
            }
        });
    </script>
</html>
