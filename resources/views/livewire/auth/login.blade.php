<div class="flex items-center justify-center w-full h-screen bg-gray-200">
    <div class="max-w-5xl flex flex-col mt-10 pr-6">
        <div class="pl-8 w-full flex flex-col mt-3">
            <div class="w-full flex items-center justify-start text-base bg-white border-b rounded-t-md border-gray-100 py-3 px-4 font-bold text-gray-900">
                <x-icon.lock size="16" color="#000000"/>
                <p class="pl-2">Login</p> 
            </div>
            <div class="flex flex-col items-center justify-center bg-white px-4 py-3 border-b border-gray-100">
                <div class="w-96 flex flex-col justify-center items-center pb-10 pt-5">                    
                    <div class="mb-5"><x-text.h1 text="Welcome to {{ env('APP_NAME') }}!!!"/></div>
                    <x-href.blue route="/auth/redirect" text="Login with Google"/>
                </div>       
            </div>

            <div class="flex w-full justify-start px-4 py-3 bg-white border-b rounded-b-md border-gray-100">
                <div class="flex justify-center items-center">
                    <label class="mr-2" >Unable to login? Please contact</label>
                    <a href="mailto:{{ env('MAIL_CONTACT_ADDRESS') }}" class=" text-blue-500 transition duration-500 ease-in-out  transform hover:-translate-x hover:scale-105">
                        <u>{{ env('MAIL_CONTACT_NAME') }}</u>
                    </a>
                </div>
            </div> 
        </div>
    </div>
</div>