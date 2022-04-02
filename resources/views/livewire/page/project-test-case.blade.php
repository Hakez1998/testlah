<div x-data="test_case" class="relative mt-3 lg:px-6 px-3">
    <button x-show="!form && !update" @click="getForm" class="absolute -top-40 right-10 bg-blue-500 rounded-md h-11 w-44 text-white flex items-center justify-center"> 
        <x-icon.project size="16" color="#ffffff"/>
        <p class="ml-2">Create Test Case</p>
    </button>

    <!-- Cancel Card -->
    <x-card.cancel-alert alpineData="cancelAlert"/>

    <!-- Delete/Remove Card -->
    @if($caseToDelete)
    <x-card.delete-alert alpineData="deleteAlert" function="deleteTestCase({{$caseToDelete}})" title="test case"/>
    @endif

    <div x-show="!form && !update" class="w-full flex items-center justify-start text-base bg-white border-b border-gray-100 py-3 px-4 font-bold text-gray-900">
        <x-icon.pen size="16" color="#000000"/>
        <p class="pl-2">Test Cases</p> 
    </div>
    
    <div x-show="!form && !update" class="flex items-center justify-between bg-white px-4 py-3 rounded-t-md border-b border-gray-100">
        <div class="w-64">
            <div class="text-gray-900 font-bold text-base w-full">Test Title</div>
        </div>
        
        <div class="text-gray-900 font-bold text-base w-64 flex justify-start">Number of Test Steps</div>
        <div class="hidden sm:flex w-48"></div>
                                        
    </div>
    @if(count($cases) < 1)
    <div x-show="!form && !update" class="flex justify-center py-2 bg-white"><i>Test case not created yet</i></div>
    @endif

    @foreach($cases as $case)
    <div x-show="!form && !update" class="flex items-center justify-between bg-white px-4 py-3 rounded-t-md sm:border-b sm:border-gray-100">
        <div class="w-64">
            <div class="text-gray-600 font-semibold text-base w-full">{{ $case->title }}</div>
        </div>
        
        <div class="text-gray-600 text-sm w-28 flex justify-start">{{ $this->getTotalTestSteps($case->id) }}</div>
        <div class="hidden sm:flex w-48 justify-end">
            <button wire:click="mountToUpdate({{ $case->id }})" @click="getUpdate" class="w-24 p-1 bg-green-500 rounded-md border border-green-500 text-white text-sm mr-4">Update</button>
            <button @click="deleteAlert = true" wire:click="mountToDelete({{ $case->id }})" class="w-24 p-1 bg-red-500 rounded-md border border-red-500 text-white text-sm">Remove</button>
        </div>                                
    </div>
    <div x-show="!form && !update" class="flex sm:hidden w-full justify-start px-4 pb-3 bg-white border-b border-gray-100">
        <button wire:click="mountToUpdate({{ $case->id }})" @click="getUpdate" class="w-24 p-1 bg-green-500 rounded-md border border-green-500 text-white text-sm mr-4">Update</button>
        <button @click="deleteAlert = true" wire:click="mountToDelete({{ $case->id }})" class="w-24 p-1 bg-red-500 rounded-md border border-red-500 text-white text-sm">Remove</button>
    </div>  
    @endforeach
    <div x-show="!form && !update" class="p-4">{{ $cases->links() }}</div> 

    <!-- Create Form -->
    <div x-show="form" class="w-full flex items-center justify-start text-base bg-white border-b border-gray-100 py-3 px-4 font-bold text-gray-900">
        <x-icon.project size="16" color="#000000"/>
        <p class="pl-2">Create Test Case</p> 
    </div>    
    <div x-show="form" class="w-full shadow-sm h-auto rounded-sm flex flex-col bg-white border border-gray-100 p-6">
        <div class="font-bold text-gray-900 text-base mb-2">Case Title</div>
        <div class="relative mb-5">
            <input wire:model="title" class="w-full rounded-md placeholder-gray-400 pl-10 py-2 pr-3 appearance-none focus:border-gray-600 focus:outline-none active:outline-none active:border-gray-600" placeholder="Case title" type="text">
            <div class="absolute top-3 left-3"><x-icon.title size="16" color="#000000"/></div>                        
        </div> 
        <div class="font-bold text-gray-900 text-base mb-1">Test Steps</div>
        <div class="flex mb-3 text-lg font-semibold">
            <div class="w-full flex justify-center">Test step</div>
            <div class="w-full flex justify-center">Expected output</div>
        </div>
        @if(count($steps) > 0)
            @foreach($steps as $step)
            <div class="relative flex mb-3 text-lg font-light">
                <div class="w-full flex justify-start">{{ $step['step'] }}</div>
                <div class="w-full flex justify-start pl-5">{{ $step['output'] }}</div>
                <div wire:click="removeStep('{{$step['count']}}')" class="absolute right-0 hover:scale-105 cursor-pointer"><x-icon.trash size="22" color="#ff0000"/></div>
            </div>
            @endforeach
        @endif
        
        <div class="flex ">
            <div class="w-1/2 pr-5">            
                <input wire:model="step" placeholder="Step" class="w-full rounded-md placeholder-gray-400 pl-5 py-2 pr-3 appearance-none focus:border-gray-600 focus:outline-none active:outline-none active:border-gray-600" type="text">
                @error('step') <span class="text-red-500 italic">{{ $message }}</span> @enderror    
            </div>
            <div class="w-1/2 pl-5">
                <input wire:model="output" wire:keydown.tab="pushStep" placeholder="Expected output" class="w-full rounded-md placeholder-gray-400 pl-5 py-2 pr-3 appearance-none focus:border-gray-600 focus:outline-none active:outline-none active:border-gray-600" type="text">
                @error('output') <span class="text-red-500 italic">{{ $message }}</span> @enderror    
            </div>
        </div>
        <div class="w-full flex justify-end mt-3">
            <button wire:click="pushStep" class="bg-blue-500 px-4 py-2 rounded-md text-white mr-3">Update step</button>
        </div>
    </div>
    <div x-show="form" class="w-full flex items-center justify-start bg-white py-5 pl-8">
        <button wire:click="createTestCase" class="bg-green-500 px-4 py-2 rounded-md text-white mr-3">Submit</button>
        <button @click="cancelAlert = true" class="bg-red-500 px-4 py-2 rounded-md text-white">Cancel</button>
    </div>
     
    <!-- Update Form -->
    @if($caseToUpdate)
        <div x-show="update" class="w-full flex items-center justify-start text-base bg-white border-b border-gray-100 py-3 px-4 font-bold text-gray-900">
            <x-icon.pen size="16" color="#000000"/>
            <p class="pl-2">Update Test Case</p> 
        </div>
        <div x-show="update" class="w-full shadow-sm h-auto rounded-sm flex flex-col bg-white border border-gray-100 p-6">
            <div class="font-bold text-gray-900 text-base mb-2">Case Title</div>
            <div class="relative mb-5">
                <input wire:model="title" class="w-full rounded-md placeholder-gray-400 pl-10 py-2 pr-3 appearance-none focus:border-gray-600 focus:outline-none active:outline-none active:border-gray-600" placeholder="Case title" type="text">
                <div class="absolute top-3 left-3"><x-icon.title size="16" color="#000000"/></div>                        
            </div> 
            <div class="font-bold text-gray-900 text-base mb-1">Test Steps</div>
            <div class="flex mb-3 text-lg font-semibold">
                <div class="w-full flex justify-center">Test step</div>
                <div class="w-full flex justify-center">Expected output</div>
            </div>
            @if(count($steps) > 0)
                @foreach($steps as $step)
                <div class="relative flex mb-3 text-lg font-light">
                    <div class="w-full flex justify-start">{{ $step['step'] }}</div>
                    <div class="w-full flex justify-start pl-5">{{ $step['output'] }}</div>
                    <div wire:click="removeStep('{{$step['count']}}')" class="absolute right-0 hover:scale-105 cursor-pointer"><x-icon.trash size="22" color="#ff0000"/></div>
                </div>
                @endforeach
            @endif
            
            <div class="flex ">
                <div class="w-1/2 pr-5">            
                    <input wire:model="step" placeholder="Step" class="w-full rounded-md placeholder-gray-400 pl-5 py-2 pr-3 appearance-none focus:border-gray-600 focus:outline-none active:outline-none active:border-gray-600" type="text">
                    @error('step') <span class="text-red-500 italic">{{ $message }}</span> @enderror    
                </div>
                <div class="w-1/2 pl-5">
                    <input wire:model="output" wire:keydown.tab="pushStep" placeholder="Expected output" class="w-full rounded-md placeholder-gray-400 pl-5 py-2 pr-3 appearance-none focus:border-gray-600 focus:outline-none active:outline-none active:border-gray-600" type="text">
                    @error('output') <span class="text-red-500 italic">{{ $message }}</span> @enderror    
                </div>
            </div>
            <div class="w-full flex justify-end mt-3">
                <button wire:click="pushStep" class="bg-blue-500 px-4 py-2 rounded-md text-white mr-3">Add step</button>
            </div>
        </div>
        <div x-show="update" class="w-full flex items-center justify-start bg-white py-5 pl-8">
            <button wire:click="updateTestCase" class="bg-green-500 px-4 py-2 rounded-md text-white mr-3">Update</button>
            <button @click="cancelAlert = true" class="bg-red-500 px-4 py-2 rounded-md text-white">Cancel</button>
        </div>
    @endif
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('test_case', () => ({
                form: false,
                update: false,
                cancelAlert: false,
                deleteAlert: false,

                getForm()
                {
                    this.form = true
                    Livewire.emitTo('page.manage-project', 'setTitle', 'Create Test Case')
                },

                getUpdate()
                {
                    this.update = true
                    Livewire.emitTo('page.manage-project', 'setTitle', 'Update Test Case')
                },

                init()
                {
                    
                }
            }))
        })
    </script>
</div>
