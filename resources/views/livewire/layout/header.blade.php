<div x-data="header" class="w-full h-32 flex flex-col justify-between items-center"> 
    <div class="h-full flex flex-col items-center justify-center bg-gray-200">
        <x-text.h1 text="Testlah!!!"/>
        <x-text.h4 text="QA Management System"/>
    </div> 
    
    <div class="h-24 w-full border-t border-b border-gray-400 px-10 md:px-24 lg:px-52 flex items-center justify-between text-lg bg-white shadow-md">
        <div class="w-4/5 h-full flex justify-start">
            <button @click="changePage('dashboard')" class="text-blue-500 mr-5 ring-0 hover:text-blue-700 border-0" :class="{ 'font-semibold border-b-4 border-blue-300' : page == 'dashboard'}">Dashboard</button> 
            <button @click="changePage('project')" class="text-blue-500 mx-5 ring-0 hover:text-blue-700 border-0" :class="{ 'font-semibold border-b-4 border-blue-300' : page == 'project'}">Projects</button>
            <button @click="changePage('team')" class="text-blue-500 mx-5 ring-0 hover:text-blue-700 border-0" :class="{ 'font-semibold border-b-4 border-blue-300' : page == 'team'}">Teams</button>  
            <button @click="changePage('test')" class="text-blue-500 px-2 ml-5 ring-0 hover:text-blue-700 border-0" :class="{ 'font-semibold border-b-4 border-blue-300' : page == 'test'}">Test</button> 
        </div>

        <div class="w-1/5 flex justify-end items-center " :class="{ ' justify-between' : page == 'project'}">
            <button @click="modal = true; modal_type = 'new project'" class="px-4 py-2 rounded-xl bg-blue-500 text-white text-sm font-semibold hover:shadow-md hover:font-bold" :class="{ 'hidden' : page != 'project'}">+ New Project</button>
            <a href="/logout" class="font-semibold text-blue-500">Logout</a> 
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('header', () => ({
                page: '{{ $page }}',
    
                changePage(page)
                {
                    this.page = page;
                    Livewire.emit('changePage', page);
                }
            }))
        })
    </script>
</div>
