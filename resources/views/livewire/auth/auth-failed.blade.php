<div class="font-sans">
    <div class="relative min-h-screen flex flex-col justify-center items-center bg-gray-200 ">
        <div class="relative sm:max-w-md w-84">
            <div class="card bg-blue-400 shadow-lg  w-full h-full rounded-3xl absolute  transform -rotate-6"></div>
            <div class="card bg-red-400 shadow-lg  w-full h-full rounded-3xl absolute  transform rotate-6"></div>
            <div class="relative w-full rounded-3xl  px-6 bg-gray-100 shadow-md pb-24 pt-20">
                <div class="block mt-3">
                    <x-text.h1 text="Authorization Error"/>
                </div>
                <div class="flex flex-col items-center justify-between py-5 px-6 mt-3 h-36 w-full">
                    <x-text.h4 text="Error 403: org_internal"/>
                    <x-text.h4 text="Your credential are not authorized by this organisation"/>
                </div>
                <div class="mt-12">
                    <div class="flex mt-7 justify-center w-full">  
                        <x-href.red route="/login" text="Back to Login"/>
                    </div>
                        <div class="mt-7">
                            <div class="flex justify-center items-center">
                                <label class="mr-2" >Unable to login? Please contact</label>
                                <a href="#" class=" text-blue-500 transition duration-500 ease-in-out  transform hover:-translate-x hover:scale-105">
                                    <u>Admin</u>
                                </a>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>