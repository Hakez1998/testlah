<div x-data="execution" class="relative w-full min-w-max h-full">
    
    <div  class="w-full h-96">
        <div class="w-full flex flex-col mt-10 pr-6">
            <div class="pl-8 w-full flex flex-col mt-3">
                <div class="w-full flex items-center justify-start text-base bg-white border-b border-gray-100 py-3 px-4 font-bold text-gray-900">
                    <x-icon.project size="16" color="#000000"/>
                    <p class="pl-2">Choose the test below to execute</p> 
                </div>
                <div class="flex items-center justify-between bg-white px-4 py-3 rounded-t-md border-b border-gray-100">
                    <div class="lg:w-1/2 w-1/3 flex">
                        <div class="text-gray-900 font-bold text-base w-1/2">Test Title</div>
                        <div class="text-gray-900 font-bold text-base w-1/2">Project Title</div>
                    </div>
                    <div class="lg:w-1/2 w-2/3 flex justify-between lg:pl-20">
                        <div class="text-gray-900 font-bold text-base w-32 flex justify-start">Test creation</div>
                        <div class="text-gray-900 font-bold text-base w-28 flex justify-start">Status</div>
                        <div class="hidden sm:flex w-32"></div>
                    </div>                        
                </div>
                @if(count($executions) < 1)
                <div class="flex justify-center py-2 bg-white"><i>You have no test to execute right now</i></div>
                @endif

                @foreach($executions as $execution)
                <div class="flex items-center justify-between bg-white hover:bg-gray-50 pl-4 pr-4 py-3 hover:scale-y-105 cursor-pointer">
                    <div class="lg:w-1/2 w-1/3 flex">
                        <div class="text-gray-600 font-semibold text-base w-1/2">{{ $execution->title }}</div>
                        <div class="text-gray-600 font-semibold text-base w-1/2">{{ $this->getProjectTitle($execution->project_id) }}</div>
                    </div>
                    <div class="lg:w-1/2 w-2/3 flex justify-between lg:pl-20">
                        <div class="text-gray-600 font-semibold text-base flex justify-start w-32">{{ $this->getDate($execution->created_at) }}</div>
                        <div class="text-gray-600 text-sm w-28 flex justify-start">{{ $this->getStatus($execution->submitted_at) }}</div>
                        <div class="hidden sm:flex w-32 justify-start">
                            <a href="/test/{{ $this->encryptCode($execution->invitation_code)}}"><button class="w-24 p-1 bg-green-500 rounded-md border border-green-500 text-white text-sm">Execute</button></a>
                        </div>
                    </div>                        
                </div>
                <div class="flex sm:hidden w-full justify-start px-4 pb-3 bg-white border-b border-gray-100">
                    <a href="/test/{{ $this->encryptCode($execution->invitation_code)}}"><button class="w-24 p-1 bg-green-500 rounded-md border border-green-500 text-white text-sm">Execute</button></a>
                </div>

                @endforeach
                <div class="p-4">{{ $executions->links() }}</div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('execution', () => ({
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
