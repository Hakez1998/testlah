<div x-data="testerDashboard">
    @if($page != 'test' && $page != 'submit')
    <div class="w-full h-screen flex flex-col justify-center bg-gray-200">
        <div class="font-sans">
            <div class="relative flex flex-col justify-center items-center h-full mt-12 px-48">
                <div class="flex justify-center md:w-full min-h-96 w-84">                
    @else
    <div class="relative w-full h-screen flex flex-col justify-center ">
        <div class="absolute w-full h-14 z-30 top-0">
            <div class="w-full h-full bg-white flex justify-between items-center px-5 shadow-md">
                <div class="hidden md:flex md:w-1/3"></div>
                <div class="flex w-1/2 md:w-1/3 items-center justify-center text-xl font-semibold">Welcome to {{ env('APP_NAME') }}, {{ $this->getTesterName() }}</div>
                <div class="flex w-1/2 md:w-1/3 items-center justify-end">
                    @if($page == 'submit')
                        <div wire:click="withdrawExecution" class="px-5 hover:font-semibold cursor-pointer">Withdraw</div>
                    @else
                        @if($this->checkResult())
                            <div @click="confirmBox = true" class="px-5 hover:font-semibold cursor-pointer">Submit</div>
                        @endif
                    @endif
                    @if(Auth::check())
                    <div wire:click="logout" class="px-5 hover:font-semibold cursor-pointer">Exit</div>
                    @else
                    <div wire:click="logout" class="px-5 hover:font-semibold cursor-pointer">Logout</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="w-full h-full bg-gray-100">
            <div class="font-sans ">
                <div class="mt-36">  
                    
    @endif
                    
                    @if($page == 'complete')
                    <div class="relative w-full px-6 min-h-96 bg-white shadow-md py-10">                    
                        <div class="w-full h-full items-center justify-center flex flex-col">
                                <div class="w-full h-full max-w-7xl flex flex-col items-center justify-center">
                                    <div class="w-full mb-5">
                                        <x-text.h1 text="Welcome to Testlah, {{ $this->getTesterName() }}" />
                                    </div>
                                    <div class="mx-5 w-full border border-gray-500 shadow-lg h-96 rounded-2xl flex flex-col justify-evenly px-4">
                                        <x-text.h4 text="
                                            Before you begin, please make sure that you are about to participate a Quality Assurance Programme by Testlah. Please ensure that any information must be kept from outsource or thirt party programme. Please check the test name and title. Any issues regarded, please contact our Administrator 
                                        " />
                                        <x-text.h4 text="
                                            Test title: {{ $this->getTest()->title }}
                                        "/>
                                    </div>
                                    <div class="w-full h-auto px-3 mt-4">
                                        <button wire:click="beginTest" class="bg-blue-500 hover:bg-blue-600 hover:text-xl text-lg text-white rounded-md shadow-md w-full py-2">Begin the Test</button>
                                    </div>
                                </div>
                    @elseif($page == 'incomplete')
                    <div class="max-w-3xl px-6 h-96 bg-white shadow-md pb-10">    
                        <div class="w-full flex items-center justify-start text-base bg-white border-b rounded-t-md border-gray-100 py-3 font-bold text-gray-900">
                            <x-icon.lock size="16" color="#000000"/>
                            <p class="pl-2">Login</p> 
                        </div>                
                        <div class="w-full h-full items-center justify-center flex flex-col">
                                <div class="w-full h-full max-w-lg flex flex-col items-center pt-10">
                                    <div class="mb-20">
                                        <x-text.h1 text="Please provide your name to get started"/>
                                    </div>                                
                                    <div class="w-full flex items-center justify-center mb-4">
                                        <div class="mr-2">
                                            Name:
                                        </div>
                                        <div class="w-full">
                                            <input  id="username" type="text" placeholder="Fullname" class="h-6 border-b-blue-800 border-t-0 border-r-0 border-l-0 content-input w-full outline-none bg-transparent ring-0 focus:border-b-2 focus:ring-0 font-mont text-base font-semibold text-gray-900 placeholder-gray-700">
                                        </div>
                                    </div>                                
                                    <div class="w-full flex justify-end items-center mt-2">
                                        <button class="w-full bg-blue-500 text-base text-white font-mont rounded-md py-1" wire:click="updateUsername(document.getElementById('username').value)">Submit</button>
                                    </div>
                                    
                                </div>
                    @elseif($page == 'test' || $page == 'submit')
                    <div class="relative w-full px-6 min-h-96 bg-white shadow-md py-10">                    
                        <div class="w-full h-full items-center justify-center flex flex-col">    
                            @livewire('page.test-sheet', ['invitation_id' => $invitation_id, 'test_id'=>$this->getTest()->id], key($invitation_id))
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- confirmation box-->
    <div x-show="confirmBox" class="min-w-screen h-screen animated fadeIn faster  fixed  left-0 top-0 flex justify-center items-center inset-0 z-50 outline-none focus:outline-none bg-no-repeat bg-center bg-cover"  id="modal-id">
        <div @click="confirmBox = false" class="absolute bg-black opacity-80 inset-0 z-0"></div>
        <div class="w-full  max-w-lg p-5 relative mx-auto my-auto rounded-xl shadow-lg  bg-white ">

            <div>

                <div class="text-center p-5 flex-auto justify-center">
                    <div class="flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#4a90e2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                    </div>
                            <h2 class="text-xl font-bold py-4 ">Are you sure?</h3>
                            <p class="text-sm text-gray-500 px-8">Do you really want to submit this test execution?
                    After submission, you need to withdraw to make a change and submit again.</p>
                </div>

                <div class="p-3  mt-2 text-center space-x-4 md:block">
                    <button @click="confirmBox = false" class="mb-2 md:mb-0 bg-white px-5 py-2 text-sm shadow-sm font-medium tracking-wider border text-gray-600 rounded-full hover:shadow-lg hover:bg-gray-100">
                        Cancel
                    </button>
                    <button wire:click="submitExecution" class="mb-2 md:mb-0 bg-blue-500 border border-blue-500 px-5 py-2 text-sm shadow-sm font-medium tracking-wider text-white rounded-full hover:shadow-lg hover:bg-blue-600">Continue</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('testerDashboard', () => ({
                confirmBox: false,
                summary:false,

                init(){
                    console.log(this.confirmBox)
                }
    
            }))
        })
    </script>
<div>
