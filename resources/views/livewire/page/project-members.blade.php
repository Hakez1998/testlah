<div x-data="members" class="relative mt-3 lg:px-6 px-3">
    @if($this->getMyRole())
    <button wire:click="requestBecomeAdmin" class="absolute -top-40 right-10 bg-blue-500 rounded-md h-11 w-44 text-white flex items-center justify-center"> 
        <x-icon.project size="16" color="#ffffff"/>
        <p class="ml-2">Become an Admin</p>
    </button>
    @endif
    <div class="flex justify-end mb-5">
        <div class="relative ">
            <input type="email" wire:model="email" placeholder="Email address" class="rounded-md w-96 pl-5 pr-28 h-11">
            <button wire:click="invite" class="absolute right-0 bg-blue-500 rounded-md rounded-l-none text-white md:w-24 w-20 h-11">Invite +</button>
        </div>
        @error('email') <span class="text-red-500 italic">{{ $message }}</span> @enderror        
    </div>
    
    <!-- Delete/Remove Card -->
    @if($memberID)
    <x-card.delete-alert alpineData="deleteAlert" function="removeMember({{$memberID}})" title="member"/>
    @endif

    <div class="w-full flex items-center justify-start text-base bg-white border-b border-gray-100 py-3 px-4 font-bold text-gray-900">
        <x-icon.member size="16" color="#000000"/>
        <p class="pl-2">Members</p> 
    </div>
    <div class="flex items-center justify-between bg-white px-4 py-3 rounded-t-md border-b border-gray-100">
        <div class="w-64">
            <div class="text-gray-900 font-bold text-base w-full">Name</div>
        </div>
        
        <div class="hidden md:flex text-gray-900 font-bold text-base w-64">Email</div>
        <div class="text-gray-900 font-bold text-base w-28 flex justify-start">Role</div>
        <div class="text-gray-900 font-bold text-base w-28 flex justify-start">Status</div>
        @if(!$this->getMyRole())
        <div class="hidden sm:flex w-48"></div>
        @endif
                                        
    </div>
    @foreach($members as $member)
    <div class="flex items-center justify-between bg-white px-4 py-3 rounded-t-md sm:border-b sm:border-gray-100">
        <div class="w-64">
            <div class="text-gray-600 font-semibold text-base w-full">{{ $member->name }}</div>
        </div>

        <div class="hidden md:flex text-gray-600 font-semibold text-base justify-start w-64 pr-2">{{ $member->email }}</div>
        <div class="text-gray-600 text-sm w-28 flex justify-start">{{ $this->getUserRole($member->id) }}</div>
        <div class="text-gray-600 text-sm w-28 flex justify-start">{{ $this->getStatus($member->id) }}</div>
        @if(!$this->getMyRole())
        <div class="hidden sm:flex w-48 justify-end"> 
            @if($this->getStatus($member->id) == 'Pending')
            <button @click="binded = '{{ $member->email }}'" wire:click="resendInvitation('{{ $member->email }}')" x-bind:disabled="binded == '{{ $member->email }}'" class="w-24 p-1 bg-green-500 disabled:bg-green-300 disabled:cursor-not-allowed rounded-md border border-green-500 text-white text-sm mr-4">Resend</button>
            <button @click="deleteAlert = true" x-bind:disabled="checkRole('{{ $this->getUserRole($member->id) }}')" wire:click="mountTeamID({{ $member->id }})" class="w-24 p-1 disabled:bg-red-300 disabled:cursor-not-allowed bg-red-500 rounded-md border border-red-500 text-white text-sm">Remove</button>
            @elseif(Auth::user()->email != $member->email)           
            <button wire:click="promoteOrDemoteUser({{ $member->id }}, '{{ $this->getUserRole($member->id) }}')" x-text="fetchRole('{{ $this->getUserRole($member->id) }}')" class="w-24 p-1 bg-green-500 rounded-md border border-green-500 text-white text-sm mr-4"></button>
            <button @click="deleteAlert = true" x-bind:disabled="checkRole('{{ $this->getUserRole($member->id) }}')" wire:click="mountTeamID({{ $member->id }})" class="w-24 p-1 disabled:bg-red-300 disabled:cursor-not-allowed bg-red-500 rounded-md border border-red-500 text-white text-sm">Remove</button>
            @endif
        </div>  
        @endif                              
    </div>
    @if(!$this->getMyRole())
    <div class="flex sm:hidden w-full justify-start px-4 pb-3 bg-white border-b border-gray-100">
        @if($this->getStatus($member->id) == 'Pending')
        <button @click="binded = '{{ $member->email }}'" wire:click="resendInvitation('{{ $member->email }}')" x-bind:disabled="binded == '{{ $member->email }}'"  class="w-24 p-1 bg-green-500 disabled:bg-green-300 disabled:cursor-not-allowed rounded-md border border-green-500 text-white text-sm mr-4">Resend</button>
        <button @click="deleteAlert = true" x-bind:disabled="checkRole('{{ $this->getUserRole($member->id) }}')" wire:click="mountTeamID({{ $member->id }})" class="w-24 p-1 disabled:bg-red-300 disabled:cursor-not-allowed bg-red-500 rounded-md border border-red-500 text-white text-sm">Remove</button>
        @elseif(Auth::user()->email != $member->email)
        <button wire:click="promoteOrDemoteUser({{ $member->id }}, '{{ $this->getUserRole($member->id) }}')" x-text="fetchRole('{{ $this->getUserRole($member->id) }}')" class="w-24 p-1 bg-green-500 rounded-md border border-green-500 text-white text-sm mr-4"></button>
        <button @click="deleteAlert = true" x-bind:disabled="checkRole('{{ $this->getUserRole($member->id) }}')" wire:click="mountTeamID({{ $member->id }})" class="w-24 p-1 disabled:bg-red-300 disabled:cursor-not-allowed bg-red-500 rounded-md border border-red-500 text-white text-sm">Remove</button>
        @endif
    </div>  
    @endif
    @endforeach
    <div class="p-4">{{ $members->links() }}</div>  

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('members', () => ({
                deleteAlert: false,
                binded: null,

                checkRole(role)
                {
                    if(role == 'Admin')
                        return true
                },

                fetchRole(userRole)
                {
                    if(userRole == 'Member')
                    {
                        return 'Promote'
                    }
                    else if(userRole == 'Admin')
                    {
                        return 'Demote'
                    }
                },

                init()
                {
                    console.log(this.binded)
                }
            }))
        })
    </script>      
</div>
 