<div x-data="description" class="relative mt-3 lg:px-6 px-3">
    <button x-show="!form" @click="form = true" class="absolute -top-40 right-10 bg-blue-500 rounded-md h-11 w-44 text-white flex items-center justify-center"> 
        <x-icon.project size="16" color="#ffffff"/>
        <p class="ml-2">Update Description</p>
    </button>
    <!-- Cancel Card -->
    <x-card.cancel-alert alpineData="cancelAlert"/>

    <div class="w-full flex items-center justify-start text-base bg-white border-b border-gray-100 py-3 px-4 font-bold text-gray-900">
        <x-icon.project size="16" color="#000000"/>
        <p class="pl-2">Description</p> 
    </div>
    <div x-show="!form" class="w-full bg-white border border-gray-100 h-96">
        <div class="w-full h-full bg-white">
            <div class="pt-4 px-4">
                <div class="font-mont text-xl font-semibold text-gray-900 pl-3">{{ $projectTitle }}</div>
            </div>
            <div class="pt-4 pl-4 flex items-center justify-start">
                <div class="pl-3 w-48">Created by:</div>
                <div>{{ $this->getCreator() }}</div>
            </div>
            <div class="pt-4 pl-4 flex items-center justify-start">
                <div class="pl-3 w-48">Created at:</div>
                <div>{{ $this->getDate() }}</div>
            </div>
            <div class="pt-4 pl-4 flex items-center justify-start">
                <div class="pl-3 w-48">Project description:</div>
                <textarea x-show="form" wire:model.lazy="projectDescription" wire:focusout="updateProjectDescription" placeholder="Project description" class="border-gray-400 border rounded-xl content-input w-1/2 outline-none bg-transparent ring-0 focus:border-blue-600 hover:border-2 focus:border-2 focus:ring-0 text-base font-semibold text-gray-900 placeholder-gray-400"></textarea>
                <div>{{ $projectDescription }}</div>
            </div>
            <div class="lg:w-2/3 w-full lg:flex items-center justify-between lg:px-7 px-1 pt-10">
                <div @click="window.location.href='/project/{{ $project_id }}/members'" class=" lg:w-52 w-full flex flex-col items-center justify-center border-2 border-gray-300 rounded-xl cursor-pointer">
                    <div class="text-lg font-semibold text-gray-600">Project's Members</div> 
                    <div class="text-2xl font-semibold text-gray-600">{{ $this->totalProjectMembers()}}</div>
                </div>
                <div @click="window.location.href='/project/{{ $project_id }}/testcases'" class=" lg:w-52 w-full flex flex-col items-center justify-center border-2 border-gray-300 rounded-xl my-2 cursor-pointer">
                    <div class="text-lg font-semibold text-gray-600">Test Cases</div> 
                    <div class="text-2xl font-semibold text-gray-600">{{ $this->totalTestCases()}}</div>
                </div>
                <div @click="window.location.href='/project/{{ $project_id }}/testsuites'" class=" lg:w-52 w-full flex flex-col items-center justify-center border-2 border-gray-300 rounded-xl cursor-pointer">
                    <div class="text-lg font-semibold text-gray-600">Test Suites</div> 
                    <div class="text-2xl font-semibold text-gray-600">{{ $this->totalTestSuites()}}</div>
                </div>
            </div>
        </div>
    </div>
    <div x-show="form" class="w-full bg-white border border-gray-100 h-72">
        <div class="w-full h-full bg-white">
            <div class="pl-4 flex items-center justify-start pt-10">
                <div class="pl-3 w-48">Project Title:</div>
                <input x-show="form" type="text" wire:model="projectTitle" placeholder="Place a title" class="w-96 rounded-md placeholder-gray-400 pl-3 py-2 pr-3 appearance-none focus:border-gray-600 focus:outline-none active:outline-none active:border-gray-600">
            </div>
            <div class="pl-4 flex items-center justify-start pt-10">
                <div class="pl-3 w-48">Project Description:</div>
                <textarea wire:model="projectDescription" class=" mb-3 w-96 h-28 rounded-md placeholder-gray-400 py-2 px-3 appearance-none focus:border-gray-600 focus:outline-none active:outline-none active:border-gray-600" placeholder="Project description" ></textarea>
            </div>
        </div>
    </div>
    <div x-show="form" class="w-full flex items-center justify-start bg-white py-5 pl-8">
        <button wire:click="updateProjectDescription" class="bg-green-500 px-4 py-2 rounded-md text-white mr-3">Update</button>
        <button @click="cancelAlert = true" class="bg-red-500 px-4 py-2 rounded-md text-white">Cancel</button>
    </div>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('description', () => ({
                form: false,
                cancelAlert: false,

                init()
                {
                    
                }
            }))
        })
    </script>                  
</div>
 