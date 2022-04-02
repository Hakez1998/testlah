<div x-data="execution" class="relative mt-3 lg:px-6 px-3">
    <div x-show="!invite && !view" class="w-full flex items-center justify-start text-base bg-white border-b border-gray-100 py-3 px-4 font-bold text-gray-900">
        <x-icon.pen size="16" color="#000000"/>
        <p class="pl-2">Execution List</p> 
    </div>

    <div x-show="!invite && !view" class="flex items-center justify-between bg-white px-4 py-3 rounded-t-md border-b border-gray-100">
        <div class="w-1/3">
            <div class="text-gray-900 font-bold text-base w-full">Execution Title</div>
        </div>
        <div class="w-2/3 flex justify-between lg:pl-20">
            <div class="flex text-gray-900 font-bold justify-center text-base w-28">Total Tester</div>
            <div class="text-gray-900 font-bold text-base w-32 flex justify-center">Execution Date</div>
            <div class="hidden sm:flex w-48"></div>
        </div>
    </div>
    @foreach($tests as $test)
    <div x-show="!invite && !view" class="flex items-center justify-between bg-white px-4 py-3 rounded-md sm:border-b sm:border-gray-100">
        <div class="w-1/3">
            <div class="text-gray-600 font-semibold text-base w-full">{{ $test->title }}</div>
        </div>
        
        <div class="w-2/3 flex justify-between lg:pl-20">
            <div class="text-gray-600 font-semibold text-base flex justify-center w-28">{{ $this->totalTester($test->title) }}</div>
            <div class="text-gray-600 text-sm w-32 flex justify-center">{{ $this->getDate($test->id) }}</div>
            <div class="hidden sm:flex w-52 justify-end">
                <button @click="view = true" wire:click="mountExecution({{ $test->id }}, '{{ $test->title }}')"  class="w-28 p-1 bg-green-500 rounded-md border border-green-500 text-white text-sm mr-4">View summary</button>
                <button @click="invite = true" wire:click="mountExecution({{ $test->id }}, '{{ $test->title }}')"  class="w-24 p-1 bg-blue-500 rounded-md border border-blue-500 text-white text-sm">Add tester</button>
            </div>     
        </div>                           
    </div>
    <div x-show="!invite && !view" class="flex sm:hidden w-full justify-start px-4 pb-3 bg-white border-b border-gray-100">
        <button @click="view = true" wire:click="mountExecution({{ $test->id }}, '{{ $test->title }}')" class="w-28 p-1 bg-green-500 rounded-md border border-green-500 text-white text-sm mr-4">View summary</button>
        <button @click="invite = true" wire:click="mountExecution({{ $test->id }}, '{{ $test->title }}')" class="w-24 p-1 bg-blue-500 rounded-md border border-blue-500 text-white text-sm">Add tester</button>
    </div>  
    @endforeach
    <div x-show="!invite && !view" class="p-4">{{ $tests->links() }}</div> 

    @if($mountedExecution)
        <div x-show="invite || view" class="w-full flex items-center justify-start text-base bg-white border-b border-gray-100 py-3 px-4 font-bold text-gray-900">
            <x-icon.member size="16" color="#000000"/>
            <p class="pl-2">{{ $executionTitle }}</p> 
        </div>
        <div x-show="invite || view" class="relative flex justify-start my-5">
            <div x-show="invite" class="absolute -top-3 right-5">
                <div class="relative ">
                    <input type="email" wire:model="email" placeholder="Email address" class="rounded-md w-96 pl-5 pr-28 h-11">
                    <button wire:click="invite" class="absolute right-0 bg-blue-500 rounded-md rounded-l-none text-white md:w-24 w-20 h-11">Add email</button>
                </div>
                @error('email') <span class="text-red-500 italic">{{ $message }}</span> @enderror        
            </div> 
        </div>
        <div x-show="invite" class="w-full flex items-center justify-between bg-white px-4 py-3 rounded-t-md border-b border-gray-100">
            <div class="w-2/3 flex">
                <div class="text-gray-900 font-bold text-base w-16">No</div>
                <div class="text-gray-900 font-bold text-base w-full">Email</div>
            </div>
            <div class="w-1/3 flex justify-end bg-yellow-200">
                <div class="w-48 bg-yellow-300"></div>
            </div>
        </div>   
        <div x-show="invite" class="overflow-auto h-64 w-full scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-gray-100 scrollbar-thumb-rounded-full scrollbar-track-rounded-full"> 
            @foreach($testers as $key => $tester)
            <div class="w-full flex items-center justify-between bg-white px-4 py-3 rounded-md sm:border-b sm:border-gray-100">
                <div class="flex w-2/3">
                    <div class="text-gray-600 font-semibold text-base w-16">{{ $key+1 }}</div>
                    <div class="text-gray-600 font-semibold text-base w-full">{{ $tester['email'] }}</div>
                </div> 
                @if($this->checkExistance($tester['email']))
                    <div class="w-1/3 flex justify-end">
                        <div class="w-48"></div>
                    </div>
                    @else
                    <div class="w-1/3 flex justify-end">
                        <div class="w-48 flex justify-between">
                            <button id="invite.{{$key}}" wire:click="additionalInvitation('{{ $tester['email']}}')" @click="document.getElementById('invite.{{$key}}').disabled = true, document.getElementById('remove.{{$key}}').disabled = true" class="w-24 p-1 bg-green-500 rounded-md border border-green-500 text-white text-sm mr-4 disabled:bg-green-300 disabled:cursor-not-allowed">Invite</button>
                            <button id="remove.{{$key}}" wire:click="removeEmail({{ $key }})" class="w-24 p-1 bg-red-500 rounded-md border border-red-500 text-white text-sm disabled:bg-red-300 disabled:cursor-not-allowed">Remove</button>
                        </div>
                    </div>
                @endif      
            </div>     
            @endforeach  
            <button x-show="invite" @click="window.location.reload()" class="w-44 h-11 bg-red-500 border border-red-500 font-bold text-white text-base rounded-md flex items-center justify-center hover:scale-105 ml-2 mt-2">Back</button>
        </div>
        <div x-show="view" class="flex flex-col h-32 w-full bg-white rounded-lg px-3 text-lg font-semibold mb-2">
            <div class="text-lg">Summary</div>
            <div class="w-full h-full flex justify-start items-center pl-10">
                <div class="flex flex-col items-center px-10">
                    <div class="text-2xl">{{ $this->totalExecution('all') }}</div>
                    <div class="text-sm">Tester/s</div>
                </div>
                <div class="flex flex-col items-center text-green-600 px-10">
                    <div class="text-2xl">{{ $this->totalExecution('submitted') }}</div>
                    <div class="text-sm">Submitted</div>
                </div>
                <div class="flex flex-col items-center text-yellow-500 px-10">
                    <div class="text-2xl">{{ $this->totalExecution('inprogress') }}</div>
                    <div class="text-sm">In Progress</div>
                </div>
            </div>
        </div>
        <div x-show="view" class="w-full flex items-center justify-between bg-white px-4 py-3 rounded-t-md border-b border-gray-100">
            <div class="text-gray-900 font-bold text-base w-16">No</div>    
            <div class="w-2/3 flex">
                <div class="text-gray-900 font-bold text-base w-1/2">Email</div>
                <div class="text-gray-900 font-bold text-base w-1/2">Tester Name</div>
            </div>
            <div class="w-1/3 flex justify-between">
                <div class="w-48 flex justify-start text-gray-900 font-bold text-base">Status</div>
                <div class="w-48"></div>
            </div>
        </div>
        <div x-show="view" class="overflow-auto h-96 w-full scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-gray-100 scrollbar-thumb-rounded-full scrollbar-track-rounded-full mb-5"> 
            @foreach($testers as $key => $tester)
            <div class="w-full flex items-center justify-between bg-white px-4 py-3 rounded-md sm:border-b sm:border-gray-100">
                <div class="text-gray-600 font-semibold text-base w-16">{{ $key+1 }}</div>
                <div class="flex w-2/3">
                    <div class="text-gray-600 font-semibold text-base w-1/2">{{ $tester['email'] }}</div>
                    <div class="text-gray-600 font-semibold text-base w-1/2">{{ $tester['name'] }}</div>
                </div> 
                <!-- add status -->
                <div class="w-1/3 flex justify-between">
                    <div class="w-48 flex justify-start">{{ $this->getSubmission($tester['id']) }}</div>
                    <div class="w-48 flex justify-start">
                        @if($this->getSubmission($tester['id']) == "submitted")
                        <a href="/test/view/result/{{ $this->encryptCode($tester['id'])}}" target="_blank" ><button class="w-24 p-1 bg-green-500 rounded-md border border-green-500 text-white text-sm mr-4 disabled:bg-green-300 disabled:cursor-not-allowed">View Result</button></a> 
                        @else
                        <button @click="binded = '{{ $tester['email'] }}'" x-bind:disabled="binded == '{{ $tester['email'] }}'" wire:click="resendInvitation('{{ $tester['id'] }}')" class="w-24 p-1 bg-green-500 rounded-md border border-green-500 text-white text-sm mr-4 disabled:bg-green-300 disabled:cursor-not-allowed">Resend</button> 
                        @endif
                    </div>
                </div>      
            </div>     
            @endforeach  
            <button x-show="view" @click="window.location.reload()" class="w-44 h-11 bg-red-500 border border-red-500 font-bold text-white text-base rounded-md flex items-center justify-center hover:scale-105 ml-2 mt-2">Back</button>
        </div>                                  
    @endif

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('execution', () => ({
                invite: false,
                view:false,
                binded: null,

                init()
                {
                    
                }
            }))
        })
    </script> 
</div>
