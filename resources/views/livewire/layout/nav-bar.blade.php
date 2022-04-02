<div x-data="navBar" class="md:w-60 w-full h-screen flex flex-col justify-between bg-gray-800 text-white"> 
    <div class="flex flex-col">
        <div class="h-14 w-full bg-gray-900 flex items-center justify-start pl-3">{{ env('APP_NAME') }}</div>
        <div class="text-xs p-3 text-gray-400">GENERAL</div>
        <a href="/dashboard" class=" flex py-2 hover:bg-gray-700 hover:cursor-pointer" :class="{'bg-gray-700': page == 'Dashboard'}"> 
            <div class="w-12 inline-flex items-center justify-center h-6"> <x-icon.mac size="16"/> </div> 
            <div class="text-gray-300">Dashboard</div>  
        </a>
        <div class="flex py-2 hover:bg-gray-700 mt-1 cursor-default" @click="if(page == 'Project'){expand = !expand}" :class="{'bg-gray-700 cursor-pointer': page == 'Project'}"> 
            <div class="w-12 inline-flex items-center justify-center h-6"> <x-icon.project size="16" color="#d1d5db"/> </div> 
            <div class="text-gray-300 w-36 h-6">Project</div>  
            <div x-show="page == 'Project'  && expand" class="w-12 h-6 flex justify-center"> - </div>
            <div x-show="page == 'Project'  && !expand" class="w-12 h-6 flex justify-center"> + </div>
        </div>
        <a href="/project/{{ $projectId }}/description" x-show="page == 'Project' && expand" class=" flex py-2 bg-gray-600 hover:bg-gray-700 hover:cursor-pointer" :class="{'bg-gray-600': tab == 'description'}"> 
            <div class="text-gray-300 pl-4 font-light">Description</div>  
        </a>
        <a href="/project/{{ $projectId }}/members" x-show="page == 'Project' && expand" class=" flex py-2 bg-gray-600 hover:bg-gray-700 hover:cursor-pointer" :class="{'bg-gray-600': tab == 'members'}"> 
            <div class="text-gray-300 pl-4 font-light">Members</div>  
        </a>
        <a href="/project/{{ $projectId }}/testcases" x-show="page == 'Project' && expand" class=" flex py-2 bg-gray-600 hover:bg-gray-700 hover:cursor-pointer" :class="{'bg-gray-600': tab == 'testcases'}"> 
            <div class="text-gray-300 pl-4 font-light">Test Cases</div>  
        </a>
        <a href="/project/{{ $projectId }}/testsuites" x-show="page == 'Project' && expand" class=" flex py-2 bg-gray-600 hover:bg-gray-700 hover:cursor-pointer" :class="{'bg-gray-600': tab == 'testsuites'}"> 
            <div class="text-gray-300 pl-4 font-light">Test Suites</div>  
        </a>
        <a href="/project/{{ $projectId }}/testexecutes" x-show="page == 'Project' && expand" class=" flex py-2 bg-gray-600 hover:bg-gray-700 hover:cursor-pointer" :class="{'bg-gray-600': tab == 'testexecutes'}"> 
            <div class="text-gray-300 pl-4 font-light">Test Executes</div>  
        </a>

        <div class="text-xs p-3 text-gray-400">TESTING</div>
        <a href="/execution" class=" flex py-2 hover:bg-gray-700 hover:cursor-pointer" :class="{'bg-gray-700': page == 'Test'}"> 
            <div class="w-12 inline-flex items-center justify-center h-6"> <x-icon.pen size="16" color="#d1d5db"/> </div> 
            <div class="text-gray-300">Execute Now</div>  
        </a>
    </div>
    <a href="/logout" class="flex flex-col">
        <div class=" flex py-2 hover:bg-gray-700 hover:cursor-pointer"> 
            <div class="w-12 inline-flex items-center justify-center h-6"> <x-icon.logout size="16"/> </div> 
            <div class="text-gray-300">Logout</div>  
        </div>
    </a>
    

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('navBar', () => ({
                page: '{{ $page }}',
                expand: false,
                tab: '{{ $tab }}',

                init()
                {
                    if(this.page == 'Project')
                    {
                        this.expand = true
                    }
                }
            }))
        })
    </script>
</div>
