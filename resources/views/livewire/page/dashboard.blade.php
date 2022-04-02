<div x-data="dashboard" class="relative w-full min-w-max h-full">
    <button x-show="!form" @click="form = true" class="absolute -top-16 right-10 bg-blue-500 rounded-md h-11 w-44 text-white flex items-center justify-center"> 
        <x-icon.project size="16" color="#ffffff"/>
        <p class="ml-2">Create Project</p>
    </button>
    <!-- Cancel Card -->
    <x-card.cancel-alert alpineData="cancelAlert"/>
    
    <!-- Delete/Remove Card -->
    @if($projectID)
    <x-card.delete-alert alpineData="deleteAlert" function="deleteProject({{$projectID}})" title="project"/>
    @endif

    <div x-show="!form" class="w-full h-96">
        <div class="w-full flex flex-col">
            <div class="p-6 w-full flex items-center lg:justify-start justify-center text-3xl font-semibold text-black bg-white">Dashboard</div>
            <!-- Notification Card -->
            @if (session('success'))
            <div class="px-6 mt-1">
                <div class="w-full flex items-center justify-between px-5 bg-green-500 h-16 rounded-md mb-2">
                    <div class="flex items-center">
                        
                        <div><x-icon.project size="20" color="#ffffff"/></div>
                        <div class="text-white text-base font-semibold ml-5">{{ session('success') }}</div>
                    </div>        
                    <div wire:click="clearSession('success')" class="text-white underline hover:scale-105 cursor-pointer">Dismiss</div>
                </div>
            </div>
            @elseif(session('delete'))
            <div class="px-6 mt-1">
                <div class="w-full flex items-center justify-between px-5 bg-red-500 h-16 rounded-md mb-2">
                    <div class="flex items-center">
                        
                        <div><x-icon.bin size="20"/></div>
                        <div class="text-white text-base font-semibold ml-5">{{ session('delete') }}</div>
                    </div>        
                    <div wire:click="clearSession('delete')" class="text-white underline hover:scale-105 cursor-pointer">Dismiss</div>
                </div>
            </div>
            @endif                   
            <!-- Info Card Card -->
            <div class="mt-3 px-3 w-full lg:flex items-center justify-between">
                <div class="w-full lg:w-1/3 p-3 h-36 ">
                    <div class="px-5 flex justify-between w-full h-full">
                        <div class="h-full w-full bg-white border-t border-l border-b border-gray-100 flex flex-col items-center justify-center">
                            <div class="text-gray-500 text-xl font-semibold font-mont">Projects</div>
                            <div class="text-gray-500 text-3xl font-semibold font-mont">{{ $this->countProject() }}</div>
                        </div>
                        <div class="h-full w-full flex bg-white border-t border-r border-b border-gray-100 items-center justify-center">
                            <x-icon.project size="48" color="#62d321"/>
                        </div>
                        
                    </div>
                </div>
                <div class="w-full lg:w-1/3 p-3 h-36 ">
                    <div class="px-5 flex justify-between w-full h-full">
                        <div class="h-full w-full bg-white border-t border-l border-b border-gray-100 flex flex-col items-center justify-center">
                            <div class="text-gray-500 text-xl font-semibold font-mont">Teams</div>
                            <div class="text-gray-500 text-3xl font-semibold font-mont">{{ $this->countTeam() }}</div>
                        </div>
                        <div class="h-full w-full flex bg-white border-t border-r border-b border-gray-100 items-center justify-center">
                            <x-icon.member size="48" color="#3d93f8"/>
                        </div>
                        
                    </div>
                </div>
                <div class="w-full lg:w-1/3 p-3 h-36 ">
                    <div class="px-5 flex justify-between w-full h-full">
                        <div class="h-full w-full bg-white border-t border-l border-b border-gray-100 flex flex-col items-center justify-center">
                            <div class="text-gray-500 text-xl font-semibold font-mont">Test Running</div>
                            <div class="text-gray-500 text-3xl font-semibold font-mont">{{ $this->countExecution() }}</div>
                        </div>
                        <div class="h-full w-full flex bg-white border-t border-r border-b border-gray-100 items-center justify-center">
                            <x-icon.pen size="48" color="#ec1933"/>
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
        <!-- Project List -->
        <div class="w-full flex flex-col mt-10 pr-6">
            <div class="pl-8 w-full flex flex-col mt-3">
                <div class="w-full flex items-center justify-start text-base bg-white border-b border-gray-100 py-3 px-4 font-bold text-gray-900">
                    <x-icon.project size="16" color="#000000"/>
                    <p class="pl-2">Projects</p> 
                </div>
                <div class="flex items-center justify-between bg-white px-4 py-3 rounded-t-md border-b border-gray-100">
                    <div class="lg:w-1/2 w-1/3">
                        <div class="text-gray-900 font-bold text-base w-full">Title</div>
                    </div>
                    <div class="lg:w-1/2 w-2/3 flex justify-between lg:pl-20">
                        <div class="text-gray-900 font-bold text-base w-28 flex justify-center">Members</div>
                        <div class="text-gray-900 font-bold text-base w-32 flex justify-center">Last Updated</div>
                        <div class="hidden sm:flex w-48"></div>
                    </div>                        
                </div>
                @if(count($projects) < 1)
                <div class="flex justify-center py-2 bg-white"><i>Project not created yet</i></div>
                @endif

                @foreach($projects as $project)                

                <div class="relative flex items-center justify-between bg-white hover:bg-gray-50 pl-6 pr-4 py-3 hover:scale-y-105 cursor-pointer">
                    <div wire:click="viewProject({{$project->id}})" class="absolute h-full w-9/12"></div>
                    <div class="lg:w-1/2 w-1/3">
                        <div class="text-gray-600 font-semibold text-base w-full">{{ $project->name }}</div>
                    </div>
                    <div class="lg:w-1/2 w-2/3 flex justify-between lg:pl-20">
                        <div class="text-gray-600 font-semibold text-base flex justify-center w-28">{{ $this->countMember($project->id) }}</div>
                        <div class="text-gray-600 text-sm w-32 flex justify-center">{{ $this->getDate($project->id) }}</div>
                        <div class="hidden sm:flex w-48 justify-end ">
                            <button wire:click="viewProject({{$project->id}})"  class="w-20 p-1 bg-green-500 rounded-md border border-green-500 text-white text-sm mr-4">View</button>
                            <button @click="deleteAlert = true" wire:click="mountId({{ $project->id }})" class="w-20 p-1 bg-red-500 rounded-md border border-red-500 text-white text-sm">Remove</button>
                        </div>
                    </div>                        
                </div>
                <div class="flex sm:hidden w-full justify-start px-4 pb-3 bg-white border-b border-gray-100">
                    <button wire:click="viewProject({{$project->id}})" class="w-24 p-1 bg-green-500 rounded-md border border-green-500 text-white text-sm mr-4">View</button>
                    <button @click="deleteAlert = true" class="w-24 p-1 bg-red-500 rounded-md border border-red-500 text-white text-sm">Remove</button>
                </div> 
                @endforeach
                <div class="p-4">{{ $projects->links() }}</div>
            </div>
        </div>
    </div>
    <!-- Create Form -->
    <div x-show="form" class="w-full h-full">
        <div class="w-full flex flex-col">
            <div class="p-6 w-full flex items-center justify-start text-3xl font-semibold text-black bg-white">Create Project</div>
        </div>     
        
        <div class="w-full flex flex-col mt-10 pr-6">
            <div class="pl-8 w-full flex flex-col mt-3">
                <div class="w-full shadow-sm flex items-center justify-start text-base bg-white border-b border-gray-100 py-3 px-4 font-bold text-gray-900">
                    <x-icon.project size="16" color="#000000"/>
                    <p class="pl-2">Create Project</p> 
                </div>
                <div class="w-full shadow-sm h-auto rounded-sm flex flex-col bg-white border border-gray-100 p-6">
                    <div class="font-bold text-gray-900 text-base mb-2">Project Title</div>
                    <div class="relative mb-3">
                        <input wire:model="title" class="w-full rounded-md placeholder-gray-400 pl-10 py-2 pr-3 appearance-none focus:border-gray-600 focus:outline-none active:outline-none active:border-gray-600" placeholder="Project title" type="text">
                        <div class="absolute top-3 left-3"><x-icon.title size="16" color="#000000"/></div>  
                        @error('title') <span class="text-red-500 italic">{{ $message }}</span> @enderror                          
                    </div> 
                    <div class="font-bold text-gray-900 text-base mb-2">Project Description</div>
                    <textarea wire:model="description" class=" mb-3 w-full h-28 rounded-md placeholder-gray-400 py-2 px-3 appearance-none focus:border-gray-600 focus:outline-none active:outline-none active:border-gray-600" placeholder="Project description" ></textarea>
                </div>
                <div class="w-full flex items-center justify-start bg-white py-5 pl-8">
                    <button wire:click="createProject" class="bg-green-500 px-4 py-2 rounded-md text-white mr-3">Submit</button>
                    <button @click="cancelAlert = true" class="bg-red-500 px-4 py-2 rounded-md text-white">Cancel</button>
                </div>
            </div>
            
        </div>

        
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dashboard', () => ({
                form: false,
                cancelAlert: false,
                deleteAlert:false,

                init()
                {
                    
                }
            }))
        })
    </script>
</div>
