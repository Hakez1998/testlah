<div x-show="{{ $alpineData }}" class="min-w-screen h-screen animated fadeIn faster  fixed  left-0 top-0 flex justify-center items-center inset-0 z-30 outline-none focus:outline-none bg-no-repeat bg-center bg-cover"  id="modal-id">
    <div class="absolute bg-black opacity-80 inset-0 z-0"></div>
    <div @click.outside="{{ $alpineData }} = false" class="w-full  max-w-lg p-5 relative mx-auto my-auto rounded-xl shadow-lg  bg-white ">

    <div>

        <div class="text-center p-5 flex-auto justify-center">
            <div class="w-full flex justify-center items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" viewBox="0 0 24 24" fill="none" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </div>     
            <p class="text-base text-gray-500 px-8 pt-4 pb-2">You are about to cancel your action<br></p>
            <h2 class="text-xl font-bold  ">This process cannot be undone </h3>
        </div>

        <div class="p-3  mt-2 text-center space-x-4 md:block">
            <button @click="window.location.reload()" class="mb-2 md:mb-0 bg-red-500 border border-red-500 px-5 py-2 text-sm shadow-sm font-medium tracking-wider text-white rounded-full hover:shadow-lg hover:bg-red-600">
                Continue
            </button>
            <button @click="{{ $alpineData }} = false" class="mb-2 md:mb-0 bg-white px-5 py-2 text-sm shadow-sm font-medium tracking-wider border text-gray-600 rounded-full hover:shadow-lg hover:bg-gray-100">
                Cancel
            </button>
        </div>
    </div>
    </div>
</div>