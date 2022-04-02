<div class="relative w-full appearance-none label-floating">
    @if($form)
    <textarea wire:model.lazy="observation" wire:focusout="updateObservation" class="autoexpand text-sm tracking-wide py-2 px-4 mb-3 leading-relaxed appearance-none block w-full bg-gray-200 border border-gray-200 rounded focus:outline-none focus:bg-white focus:border-gray-500"
        type="text" placeholder="Observation">{{$observation}}</textarea>
        <label for="Observation" class="absolute tracking-wide py-2 px-4 mb-4 opacity-0 leading-tight block top-0 left-0 cursor-text">Message...
    </label>
    @else
    <div class="autoexpand text-sm tracking-wide h-16 pt-2 px-4 mb-3 leading-relaxed appearance-none block w-full bg-gray-200 border border-gray-200 rounded focus:outline-none focus:bg-white focus:border-gray-500">{{$observation}}</div>
    @endif
</div>