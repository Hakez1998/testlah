<div class="w-96 group relative">
    <div class="opacity-0 group-hover:opacity-100 absolute -top-7 right-0 cursor-pointer duration-100 ease-in-out delay-300 transition-opacity">
        <div wire:click="$emitTo('page.projects', 'removeProject', {{$projectId}})" class="px-3 py-1 bg-blue-600 text-white text-sm flex items-center justify-center rounded-t-md">
            Remove 
        </div>
    </div>
    
    <div class=" grid grid-cols-3 grid-rows-7 grid-flow-row overflow-hidden rounded-lg group-hover:rounded-tr-none shadow-md bg-white hover:shadow-xl transition-shadow duration-300 ease-in-out">
        
        <div class="col-span-3 row-span-1">
            <header
                class="flex items-center justify-between leading-tight p-2 md:p-4"
            >
                <h1 class="text-lg">
                    <div class="no-underline hover:underline text-black text-xl font-semibold">{{$title}}</div>
                </h1>
                <p class="text-grey-darker text-sm">{{$time}}</p>
            </header>
        </div>

        <!-- Project Manager -->
        <div class="col-span-3 row-span-1">
            <div class="flex align-bottom flex-col leading-none p-2 md:p-4">
                <div class="flex flex-row justify-start items-center">
                    <div class="flex items-center text-black">                        
                        <span class="ml-2 text-sm line-clamp-3"> {{$description}} </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-96">
            <a href="{{ $redirect }}" class="bg-blue-600 rounded-br-lg rounded-bl-lg w-full py-1 text-white flex items-center justify-center">Manage or View</a>
        </div>
    </div>
    
</div>



