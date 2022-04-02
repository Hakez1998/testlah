<div  class="flex justify-between items-center w-full">
    <input wire:model.lazy="step" wire:focusout="updateStep" placeholder="Test step" class="border-b-blue-600 border-t-0 border-r-0 border-l-0 content-input w-1/2 outline-none bg-transparent ring-0 focus:border-b-2 focus:ring-0 font-mont text-base font-semibold text-gray-900 placeholder-gray-400" type="text"/>
    <input wire:model.lazy="result" wire:focusout="updateResult" placeholder="Expected result" class="border-b-blue-600 border-t-0 border-r-0 border-l-0 content-input w-1/2 outline-none bg-transparent ring-0 focus:border-b-2 focus:ring-0 font-mont text-base font-semibold text-gray-900 placeholder-gray-400 mx-2" type="text"/>
    <div class="cursor-pointer" wire:click="deleteScript">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fb0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="15" y1="9" x2="9" y2="15"></line>
            <line x1="9" y1="9" x2="15" y2="15"></line>
        </svg>
    </div>
</div>
