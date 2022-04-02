<div x-data="project" class="relative w-full h-full">
    <div class="w-full h-96">
        <div class="w-full flex flex-col">
            <div class="p-6 w-full flex items-center sm:justify-start justify-center text-3xl font-semibold text-black bg-white border border-gray-100">{{ $projectTitle}}</div>
            @if (session('success'))
            <div class="absolute top-3 w-full z-30 px-6">
                <div  class="w-full flex items-center justify-between px-5 bg-green-500 h-16 rounded-md mb-2">
                    <div class="flex items-center">
                        <div><x-icon.success size="20" color="#ffffff"/></div>
                        <div class="text-white text-base font-semibold ml-5">{{ session('success') }}</div>
                    </div>        
                    <div wire:click="clearSession('success')" class="text-white underline hover:scale-105 cursor-pointer">Dismiss</div>
                </div>
            </div>
            @elseif (session('invitation'))
            <div class="absolute top-3 w-full z-30 px-6">
                <div  class="w-full flex items-center justify-between px-5 bg-blue-500 h-16 rounded-md mb-2">
                    <div class="flex items-center">
                        <div><x-icon.mail size="20" color="#ffffff"/></div>
                        <div class="text-white text-base font-semibold ml-5">{{ session('invitation') }}</div>
                    </div>        
                    <div wire:click="clearSession('invitation')" class="text-white underline hover:scale-105 cursor-pointer">Dismiss</div>
                </div>
            </div>
            @endif
            
            @if($tab == 'description')
                @livewire('page.project-description', ['project_id' => $project_id]) 
            @elseif($tab == 'members') 
                @livewire('page.project-members', ['project_id' => $project_id]) 
            @elseif($tab == 'testcases')
                @livewire('page.project-test-case', ['project_id' => $project_id]) 
            @elseif($tab == 'testsuites')
                @livewire('page.project-test-suite', ['project_id' => $project_id]) 
            @elseif($tab == 'testexecutes')
                @livewire('page.project-test-execution', ['project_id' => $project_id]) 
            @endif
        </div>
    </div> 

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('project', () => ({
                title: '{{ $projectTitle}}',

                init()
                {

                }

            }))
        })
    </script> 
</div>
