<div>
    @if($form)
        @if($result == "null")
        <button id="pass_{{$test_step_id}}" wire:click="testCaseResult({{$test_step_id}}, 'pass_{{$test_step_id}}', true)" class="flex-no-shrink py-1 w-14 mx-1 border-2 rounded hover:text-white text-green-400 border-green-400 hover:bg-green-400 text-sm">Pass</button>
        <button id="fail_{{$test_step_id}}" wire:click="testCaseResult({{$test_step_id}}, 'fail_{{$test_step_id}}', false)" class="flex-no-shrink p-1 w-14 mx-1 border-2 rounded hover:text-white text-red-400 border-red-400 hover:bg-red-400 text-sm">Fail</button>
        @elseif($result == true)
        <button id="pass_{{$test_step_id}}" wire:click="testCaseResult({{$test_step_id}}, 'pass_{{$test_step_id}}', true)" class="flex-no-shrink py-1 w-14 mx-1 border-2 rounded text-white border-green-400 bg-green-400 text-sm">Pass</button>
        <button id="fail_{{$test_step_id}}" wire:click="testCaseResult({{$test_step_id}}, 'fail_{{$test_step_id}}', false)" class="flex-no-shrink p-1 w-14 mx-1 border-2 rounded hover:text-white text-red-400 border-red-400 hover:bg-red-400 text-sm">Fail</button>
        @elseif($result == false)
        <button id="pass_{{$test_step_id}}" wire:click="testCaseResult({{$test_step_id}}, 'pass_{{$test_step_id}}', true)" class="flex-no-shrink py-1 w-14 mx-1 border-2 rounded hover:text-white text-green-400 border-green-400 hover:bg-green-400 text-sm">Pass</button>
        <button id="fail_{{$test_step_id}}" wire:click="testCaseResult({{$test_step_id}}, 'fail_{{$test_step_id}}', false)" class="flex-no-shrink p-1 w-14 mx-1 border-2 rounded text-white border-red-400 bg-red-400 text-sm">Fail</button>
        @endif
    @else
        @if($result == "null")
        <button class="flex-no-shrink py-1 w-14 mx-1 border-2 rounded cursor-default text-green-400 border-green-400">Pass</button>
        <button class="flex-no-shrink p-1 w-14 mx-1 border-2 rounded cursor-default text-red-400 border-red-400">Fail</button>
        @elseif($result == true)
        <button class="flex-no-shrink py-1 w-14 mx-1 border-2 rounded cursor-default text-white border-green-400 bg-green-400 text-sm">Pass</button>
        <button class="flex-no-shrink p-1 w-14 mx-1 border-2 rounded cursor-default text-red-400 border-red-400">Fail</button>
        @elseif($result == false)
        <button class="flex-no-shrink py-1 w-14 mx-1 border-2 rounded cursor-default text-green-400 border-green-400">Pass</button>
        <button class="flex-no-shrink p-1 w-14 mx-1 border-2 rounded cursor-default text-white border-red-400 bg-red-400 text-sm">Fail</button>
        @endif
    @endif
</div>
