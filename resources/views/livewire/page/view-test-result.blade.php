<div class="relative w-full h-screen overflow-y-scroll scrollbar-thin hover:scrollbar-thumb-gray-500 scrollbar-thumb-gray-200 scrollbar-track-gray-100">
    <div wire:click="generatePDF" class="absolute top-3 right-10 cursor-pointer group flex justify-center">
        <div class="opacity-0 group-hover:opacity-100 duration-300 inset-0 z-10 flex justify-center items-center text-xs text-white font-semibold">Export to PDF</div>
        <svg class="group-hover:scale-105" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/>
            <path d="M14 3v5h5M16 13H8M16 17H8M10 9H8"/>
        </svg>
    </div>
    <div class="flex items-center justify-center w-full h-16 bg-gray-500 text-white text-lg font-semibold">Test Report</div>
    <div class="w-full flex flex-col mb-10 mt-5 pl-10">
        <div>Test Title: {{ $this->getTestExecutionTitle() }}</div>
        <div>Tester Name: {{ $this->getTesterName() }}</div>
        <div class="max-w-2xl border-t border-r border-l border-gray-800 flex mt-5">
            <div class="flex items-center pl-12 w-60 border-gray-800 border-r">Executed</div>
            <div class="flex flex-col w-full">
                <div class="pl-10 h-8 flex items-center">Passed</div>
                <div class="w-full border-t border-gray-800"></div>
                <div class="pl-10 h-8 flex items-center">Failed</div>
            </div>
            <div class="flex flex-col w-28 items-center justify-center border-gray-800 border-l">
                <div class="h-8 flex items-center">{{ $this->totalCases('pass') }}</div>
                <div class="w-full border-t border-gray-800"></div>
                <div class="h-8 flex items-center">{{ $this->totalCases('fail') }}</div>
            </div>
        </div>
        <div class="max-w-2xl border-t border-r border-l border-gray-800 flex">
            <div class="flex items-center pl-12 w-60">Ignored</div>
            <div class="w-full">
            </div>
            <div class="flex flex-col w-28 items-center justify-center border-gray-800 border-l">
                <div class="flex items-center h-8">{{ $this->totalCases('ignore') }}</div>
            </div>
        </div>
        <div class="max-w-2xl border border-gray-800 flex">
            <div class="flex items-center pl-12 w-60 h-20">Total Test Cases</div>
            <div class="w-full">
            </div>
            <div class="flex flex-col w-28 items-center justify-center border-gray-800 border-l">
                <div class="flex items-center">{{ $this->totalCases('all') }}</div>
            </div>
        </div>
    </div>
    <div class="w-full flex flex-col pl-10">
        @foreach($cases as $case)
        <div class="max-w-7xl border border-gray-800 border-b-0 my-5">
            <div class="flex items-center justify-between px-8 bg-gray-500 text-white font-semibold w-full h-8 border-b border-gray-800">
                <div>Test Case: {{$case->title}}</div> 
                <div>{{ $this->getCaseSummary( $case->id) }}</div>
            </div>
            <div class="flex w-full border-b-2 even:border-gray-800">
                <div class="flex items-center font-bold w-full h-8 border-r border-gray-800 pl-8">Test Steps</div>
                <div class="flex items-center font-bold w-full h-8 border-r border-gray-800 pl-8">Expected Results</div>
                <div class="flex items-center justify-center font-bold w-1/2 h-8 border-r border-gray-800">Test Results</div>
                <div class="flex items-center font-bold w-full h-8 pl-8">Observation</div>
            </div>    
            
            @foreach($steps['case '.$case->id] as $step)
            <div class="flex w-full even:border-t even:border-b last:border-b-0  even:border-gray-800">
                <div class="flex items-center w-full h-8 border-r border-gray-800 pl-8">{{ ucfirst($step->title) }}</div>
                <div class="flex items-center w-full h-8 border-r border-gray-800 pl-8">{{ ucfirst($step->expected_result) }}</div>
                <div class="flex items-center justify-center w-1/2 h-8 border-r border-gray-800">{{ ucfirst($this->getTestResult( $step->id )) }}</div>
                <div class="flex items-center w-full h-8 pl-8">{{ ucfirst($this->getTestObservation( $step->id )) }}</div>
            </div>                
            @endforeach
            <div></div>
        </div>            
        @endforeach
    </div>
</div>
