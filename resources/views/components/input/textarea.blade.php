<div class="mb-4 relative">
    <textarea id="{{$textID}}" class="textarea text-sm border border-gray-400 appearance-none rounded w-full px-3 py-3 pt-8 pb-2 focus focus:border-indigo-600 focus:outline-none active:outline-none active:border-indigo-600"></textarea>
    <label for="email" class="labelTextarea absolute text-sm w-auto left-0 ml-3 cursor-text">{{ $placeholder }}</label>
</div>
<style>
    .textarea {
        transition: border 0.2s ease-in-out;
        min-width: 280px
    }

    .textarea:focus+.labelTextarea,
    .textarea:active+.labelTextarea
    {
        color: #667eea;
    },
    .textarea.filled+.labelTextarea {
        color: #ffffff;
        
    }

    .labelTextarea {
        transition: all 0.2s ease-out;
        top: 0.4rem;
      	left: 0;
    }
</style>

