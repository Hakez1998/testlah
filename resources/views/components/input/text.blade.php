<div class="mb-4 relative">
    <input type="text" id="{{$textID}}" x-model="{{$textID}}" class="input border border-gray-400 appearance-none rounded w-full px-3 py-3 pt-5 pb-2 focus focus:border-indigo-600 focus:outline-none active:outline-none active:border-indigo-600" >
    <label class="label absolute text-sm w-auto left-0 ml-3">{{ $placeholder }}</label>
</div>

<style>
    .input {
        transition: border 0.2s ease-in-out;
        min-width: 280px
    }

    .input:focus+.label,
    .input:active+.label,
    .input.filled+.label {

        color: #667eea;
    }

    .label {
        transition: all 0.2s ease-out;
        top: 0.4rem;
      	left: 0;
    }
</style>

