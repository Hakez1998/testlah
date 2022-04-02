<div class="w-screen h-full max-w-7xl pt-10">
    <div class="absolute -top-16 flex justify-start">
        <div class="flex items-center justify-start p-3">
        <div class="h-8 text-2xl text-gray-500 ">  {{ env('APP_NAME') }} / {{ $this->getTestExecutionTitle() }} / <b class="text-black font-bold"> {{ $test_cases->title }}</b></div>
        </div>
    </div>    
    <div class="w-full h-full flex flex-col pb-10">
        <div class="w-full h-full px-3">
            <div class="w-full flex justify-between mb-5 text-lg font-semibold border-gray-700 border-b px-1 shadow-lg">
                <div class="w-1/4 flex justify-start">Case Step</div>
                <div class="w-1/4 flex justify-center">Expected Output</div>
                <div class="w-1/4 flex justify-center">Pass or Fail</div>
                <div class="w-1/4 flex justify-end">Observation</div>
            </div>
            @foreach($test_steps as $test_step)
            <div class="my-2">
                <div class="flex justify-between">
                    <div class="w-1/4">{{ $test_step->title }}</div>
                    <div class="w-1/4 flex items-start justify-evenly">{{ $test_step->expected_result }}</div>
                    <div class="w-1/4 flex items-start justify-center">
                        @livewire('layout.autoload-pass-fail', ['test_step_id' => $test_step->id], key($test_step->id))
                    </div> 
                    <div class="w-1/4">
                        @livewire('layout.autosave-observation', ['test_step_id' => $test_step->id], key($test_step->id))
                    </div>                   
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="w-full h-full mt-5 flex justify-center">
        <div class="px-3 h-12 flex items-center justify-center rounded-md bg-gray-200 shadow-md">
            @if($pagination > 1)
            <button wire:click="paginationDecrease" class="w-12 text-lg font-bold hover:text-2xl active:text-xl"><</button>
            @else
            <div class="w-12"></div>
            @endif
            <div>{{ $pagination }}</div>
            @if($pagination < $totalPagination)
            <button wire:click="paginationIncrease" class="w-12 text-lg font-bold hover:text-2xl active:text-xl">></button>
            @else
            <div class="w-12"></div>
            @endif
        </div>
    </div>
      
</div>
