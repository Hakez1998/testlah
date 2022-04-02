<div x-data="test_suite" class="relative mt-3 lg:px-6 px-3">
    <button x-show="!form && !update && !release" @click="getForm" class="absolute -top-40 right-10 bg-blue-500 rounded-md h-11 w-44 text-white flex items-center justify-center hover:scale-105"> 
        <x-icon.project size="16" color="#ffffff"/>
        <p class="ml-2">Create Test Suite</p>
    </button>

    <!-- Cancel Card -->
    <x-card.cancel-alert alpineData="cancelAlert"/>

    <!-- Delete/Remove Card -->
    @if($suiteToDelete)
    <x-card.delete-alert alpineData="deleteAlert" function="deleteTestSuite({{$suiteToDelete}})" title="test suite"/>
    @endif

    <div x-show="!form && !update && !release" class="w-full flex items-center justify-start text-base bg-white border-b border-gray-100 py-3 px-4 font-bold text-gray-900">
        <x-icon.pen size="16" color="#000000"/>
        <p class="pl-2">Test Suites</p> 
    </div>
    
    <div x-show="!form && !update && !release" class="flex items-center justify-between bg-white px-4 py-3 rounded-t-md border-b border-gray-100">
        <div class="w-64">
            <div class="text-gray-900 font-bold text-base w-full">Test Title</div>
        </div>
        
        <div class="text-gray-900 font-bold text-base w-64 flex justify-center">Number of Test Cases</div>
        <div class="hidden sm:flex w-60"></div>
                                        
    </div>
    @if(count($suites) < 1)
    <div x-show="!form && !update && !release" class="flex justify-center py-2 bg-white"><i>Test suite not created yet</i></div>
    @endif

    @foreach($suites as $suite)
    <div x-show="!form && !update && !release" class="flex items-center justify-between bg-white px-4 py-3 rounded-t-md sm:border-b sm:border-gray-100">
        <div class="w-64">
            <div class="text-gray-600 font-semibold text-base w-full">{{ $suite->title }}</div>
        </div>
        
        <div class="text-gray-600 text-sm w-64 flex justify-center">{{ $this->getTotalTestCases($suite->id) }}</div>
        <div class="hidden sm:flex w-60 justify-between">
            <button wire:click="mountToRelease({{ $suite->id }})" @click="getRelease" class="w-24 p-1 bg-green-500 rounded-md border border-green-500 text-white text-sm">Release</button>
            <button wire:click="mountToUpdate({{ $suite->id }})" @click="getUpdate" class="w-24 p-1 bg-blue-500 rounded-md border border-blue-500 text-white text-sm mx-4">Update</button>
            <button @click="deleteAlert = true" wire:click="mountToDelete({{ $suite->id }})" class="w-24 p-1 bg-red-500 rounded-md border border-red-500 text-white text-sm">Remove</button>
        </div>                                
    </div>
    <div x-show="!form && !update && !release" class="flex sm:hidden w-full justify-start px-4 pb-3 bg-white border-b border-gray-100">
        <button wire:click="mountToRelease({{ $suite->id }})" @click="getRelease" class="w-24 p-1 bg-green-500 rounded-md border border-green-500 text-white text-sm mr-4">Release</button>
        <button wire:click="mountToUpdate({{ $suite->id }})" @click="getUpdate" class="w-24 p-1 bg-blue-500 rounded-md border border-blue-500 text-white text-sm mr-4">Update</button>
        <button @click="deleteAlert = true" wire:click="mountToDelete({{ $suite->id }})" class="w-24 p-1 bg-red-500 rounded-md border border-red-500 text-white text-sm">Remove</button>
    </div>  
    @endforeach
    <div x-show="!form && !update && !release" class="p-4">{{ $suites->links() }}</div> 

    <!-- Create Form -->
    <div x-show="form" class="w-full flex items-center justify-start text-base bg-white border-b border-gray-100 py-3 px-4 font-bold text-gray-900">
        <x-icon.project size="16" color="#000000"/>
        <p class="pl-2">Create Test Suite</p> 
    </div>    
    <div x-show="form" class="w-full shadow-sm h-auto rounded-sm flex flex-col bg-white border border-gray-100 p-6">
        <div class="font-bold text-gray-900 text-base mb-2">Suite Title</div>
        <div class="relative mb-5">
            <input wire:model="title" class="w-full rounded-md placeholder-gray-400 pl-10 py-2 pr-3 appearance-none focus:border-gray-600 focus:outline-none active:outline-none active:border-gray-600" placeholder="Suite title" type="text">
            <div class="absolute top-3 left-3"><x-icon.title size="16" color="#000000"/></div>                        
        </div>
        @if(count($cases) < 1)
            <div class="flex justify-center py-2 bg-white"><i>Test case not created yet</i></div>
        @endif

        @foreach($cases as $case)
            <div wire:click="selectCase({{ $case['count'] }})" class="flex items-center group cursor-pointer group">
                @if($case['selected'])
                <div class="w-3 h-3 rounded-full border-2 border-blue-500 flex items-center justify-center group-hover:scale-125">
                    <div class="w-1 h-1 bg-blue-500 rounded-full"></div>
                </div>
                @else
                <div class="w-3 h-3 rounded-full border-2 border-blue-500 flex items-center justify-center group-hover:scale-125">
                    <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                </div>
                @endif
                <div class="pl-3">{{ $case['title'] }}</div>
            </div>
        @endforeach
        
    </div>
    <div x-show="form" class="w-full flex items-center justify-start bg-white py-5 pl-8">
        <button wire:click="createTestSuite" class="bg-green-500 px-4 py-2 rounded-md text-white mr-3">Submit</button>
        <button @click="cancelAlert = true" class="bg-red-500 px-4 py-2 rounded-md text-white">Cancel</button>
    </div>
     
    <!-- Update Form -->
    @if($suiteToUpdate)
        <div x-show="update" class="w-full flex items-center justify-start text-base bg-white border-b border-gray-100 py-3 px-4 font-bold text-gray-900">
            <x-icon.pen size="16" color="#000000"/>
            <p class="pl-2">Update Test Case</p> 
        </div>
        <div x-show="update" class="w-full shadow-sm h-auto rounded-sm flex flex-col bg-white border border-gray-100 p-6">
            <div class="font-bold text-gray-900 text-base mb-2">Case Title</div>
            <div class="relative mb-5">
                <input wire:model="title" class="w-full rounded-md placeholder-gray-400 pl-10 py-2 pr-3 appearance-none focus:border-gray-600 focus:outline-none active:outline-none active:border-gray-600" placeholder="Suite title" type="text">
                <div class="absolute top-3 left-3"><x-icon.title size="16" color="#000000"/></div>                        
            </div> 
            @foreach($cases as $case)
                <div wire:click="selectCase({{ $case['count'] }})" class="flex items-center group cursor-pointer group">
                    @if($case['selected'])
                    <div class="w-3 h-3 rounded-full border-2 border-blue-500 flex items-center justify-center group-hover:scale-125">
                        <div class="w-1 h-1 bg-blue-500 rounded-full"></div>
                    </div>
                    @else
                    <div class="w-3 h-3 rounded-full border-2 border-blue-500 flex items-center justify-center group-hover:scale-125">
                        <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                    </div>
                    @endif
                    <div class="pl-3">{{ $case['title'] }}</div>
                </div>
            @endforeach
            
        </div>
        <div x-show="update" class="w-full flex items-center justify-start bg-white py-5 pl-8">
            <button wire:click="updateTestSuite" class="bg-green-500 px-4 py-2 rounded-md text-white mr-3">Update</button>
            <button @click="cancelAlert = true" class="bg-red-500 px-4 py-2 rounded-md text-white">Cancel</button>
        </div>
    @endif

    @if($suiteToRelease)
        <div x-show="release" class="w-full flex items-center justify-start text-base bg-white border-b border-gray-100 py-3 px-4 font-bold text-gray-900">
            <x-icon.pen size="16" color="#000000"/>
            <p class="pl-2">Create Execution</p> 
        </div>
        <div x-show="release" class="w-full shadow-sm h-auto rounded-sm flex flex-col bg-white border border-gray-100 p-6">
            <div class="font-bold text-gray-900 text-base mb-2">Execution Title</div>
            <div class="relative mb-5">
                <input wire:model="title" x-model="title" @click="buttonDisabled = false" class="w-full rounded-md placeholder-gray-400 pl-10 py-2 pr-3 appearance-none focus:border-gray-600 focus:outline-none active:outline-none active:border-gray-600" placeholder="Execution title" type="text">
                <div class="absolute top-3 left-3"><x-icon.title size="16" color="#000000"/></div>       
                @error('title') <span class="text-red-500 italic">{{ $message }}</span> @enderror                  
            </div> 

            <div class="w-full flex flex-col items-end">
                <div class="relative ">
                    <input type="email" wire:model="email" placeholder="Email address" class="rounded-md w-96 pl-5 pr-28 h-11">
                    <button wire:click="invite" class="absolute right-0 bg-blue-500 rounded-md rounded-l-none text-white md:w-24 w-20 h-11">Invite +</button>
                </div>
                @error('email') <span class="text-red-500 italic">{{ $message }}</span> @enderror        
            </div>

            @foreach($invitees as $key => $invitee)
                <div class="flex items-center justify-between w-96 cursor-pointer my-2 py-1 border-gray-300 border-b group">
                    <div class="flex">
                        <div class="w-3 group-hover:font-semibold">{{ $key + 1}}</div>
                        <div class="text-base font-light pl-3 group-hover:font-semibold">{{ $invitee['email'] }}</div>
                    </div> 
                    @if($key > 0)                   
                    <button wire:click="removeEmail({{ $invitee['count'] }})" class="w-auto p-1 bg-red-500 rounded-md border border-red-500 text-white text-sm">Remove</button>
                    @endif
                </div>
            @endforeach

        </div>
        <div x-show="release" class="w-full flex items-center justify-start bg-white py-5 pl-8">
            <button wire:click="createExecution" x-bind:disabled="buttonDisabled" @click="loadState" class="bg-green-500 px-4 py-2 rounded-md text-white mr-3" :class="{ 'bg-green-300 cursor-default': buttonDisabled }">Create Execution</button>
            <button @click="cancelAlert = true" x-bind:disabled="buttonDisabled" class="bg-red-500 px-4 py-2 rounded-md text-white" :class="{ 'bg-red-300 cursor-default': buttonDisabled }">Cancel</button>
        </div>

    @endif
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('test_suite', () => ({
                form: false,
                update: false,
                release: false,
                buttonDisabled: false,
                cancelAlert: false,
                deleteAlert: false,
                title: null,

                getForm()
                {
                    this.form = true
                    Livewire.emitTo('page.manage-project', 'setTitle', 'Create Test Suite')
                },
                
                loadState(){
                    console.log(this.title)
                    if( this.title == '')
                    {
                        this.buttonDisabled = true
                    }
                    else
                    {
                        console.log('load')
                        document.getElementById('loading_state').classList.remove('hidden');
                        document.getElementById('loading_state').classList.add('opacity-90');
                        document.getElementById('loading_state').classList.remove('opacity-0');
                    }              
                },

                getUpdate()
                {
                    this.update = true
                    Livewire.emitTo('page.manage-project', 'setTitle', 'Update Test Suite')
                },

                getRelease()
                {
                    this.release = true
                    Livewire.emitTo('page.manage-project', 'setTitle', 'Make an Execution')
                },

                init()
                {
                    Livewire.on('buttonAction', result => {
                        this.buttonDisabled = true
                    })
                }
            }))
        })
    </script>
</div>
