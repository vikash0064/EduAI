<x-app-layout>
    <x-slot name="pageTitle">EduChat Messenger</x-slot>

    <div x-data="{ showNewChat: false, showGroupModal: false, showAddMemberModal: false }" class="h-[calc(100vh-140px)] bg-white rounded-[32px] shadow-soft border border-outline-variant/10 flex overflow-hidden">
        
        <!-- Sidebar: Chat List -->
        <div class="w-80 border-r border-outline-variant/10 flex flex-col">
            <div class="p-6 border-b border-outline-variant/10">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-poppins font-bold text-xl text-on-surface">Chats</h2>
                    <div class="flex gap-2">
                        @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                        <button @click="showGroupModal = true" class="w-10 h-10 rounded-xl bg-surface-container-low text-on-surface-variant flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                            <span class="material-symbols-outlined text-[20px]">group_add</span>
                        </button>
                        @endif
                        <button @click="showNewChat = true" class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/20 hover:-translate-y-0.5 transition-all">
                            <span class="material-symbols-outlined text-[20px]">edit_square</span>
                        </button>
                    </div>
                </div>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                    <input type="text" placeholder="Search people..." class="w-full pl-12 pr-4 py-3 bg-surface-container-low border-none rounded-2xl text-xs font-semibold focus:ring-2 focus:ring-primary/20">
                </div>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar">
                @foreach($conversations as $conv)
                <a href="{{ route('messages.index', ['conversation_id' => $conv->id]) }}" 
                   class="flex items-center gap-4 p-5 hover:bg-surface-container-low transition-all border-b border-outline-variant/5 {{ (isset($activeConversation) && $activeConversation->id === $conv->id) ? 'bg-primary/5 border-l-4 border-l-primary' : '' }}">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center text-primary font-bold text-lg">
                        {{ substr($conv->name ?? $conv->users->where('id', '!=', auth()->id())->first()->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <h4 class="text-sm font-bold text-on-surface truncate">
                                {{ $conv->name ?? $conv->users->where('id', '!=', auth()->id())->first()->name ?? 'Unknown' }}
                            </h4>
                            <span class="text-[9px] font-bold text-on-surface-variant/60">{{ $conv->latestMessage ? $conv->latestMessage->created_at->format('h:i A') : '' }}</span>
                        </div>
                        <p class="text-[11px] font-medium text-on-surface-variant truncate">
                            {{ $conv->latestMessage ? $conv->latestMessage->body : 'Start a conversation...' }}
                        </p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Main Content: Message Window -->
        <div class="flex-1 flex flex-col bg-surface-container-low/20">
            @if($activeConversation)
            <!-- Chat Header -->
            <div class="p-5 bg-white border-b border-outline-variant/10 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-bold">
                        {{ substr($activeConversation->name ?? $activeConversation->users->where('id', '!=', auth()->id())->first()->name ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-on-surface">
                            {{ $activeConversation->name ?? $activeConversation->users->where('id', '!=', auth()->id())->first()->name ?? 'Unknown' }}
                        </h3>
                        <p class="text-[10px] font-bold text-teal-500 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                            Online
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($activeConversation->type === 'group' && (auth()->user()->isTeacher() || auth()->user()->isAdmin()))
                    <button @click="showAddMemberModal = true" class="w-10 h-10 rounded-xl text-primary bg-primary/10 hover:bg-primary hover:text-white transition-all" title="Add Member">
                        <span class="material-symbols-outlined text-[20px]">person_add</span>
                    </button>
                    @endif
                    <button class="w-10 h-10 rounded-xl text-on-surface-variant hover:bg-surface-container-low transition-all">
                        <span class="material-symbols-outlined text-[20px]">more_vert</span>
                    </button>
                </div>
            </div>

            <!-- Messages Area -->
            <div class="flex-1 overflow-y-auto p-8 space-y-6 custom-scrollbar" id="chat-messages">
                @foreach($activeConversation->messages as $msg)
                <div class="flex {{ $msg->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[70%] space-y-1">
                        <div class="flex items-center gap-2 {{ $msg->user_id === auth()->id() ? 'flex-row-reverse' : '' }} mb-1">
                            <span class="text-[9px] font-bold text-on-surface-variant/60 uppercase">{{ $msg->user->name }}</span>
                        </div>
                        
                        <div class="space-y-2">
                            @if($msg->body)
                            <div class="px-5 py-3 rounded-2xl shadow-sm text-sm {{ $msg->user_id === auth()->id() ? 'bg-primary text-white rounded-tr-none' : 'bg-white text-on-surface rounded-tl-none' }}">
                                {{ $msg->body }}
                            </div>
                            @endif

                            @if($msg->file_path)
                                @php $ext = pathinfo($msg->file_path, PATHINFO_EXTENSION); @endphp
                                @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                    <div class="rounded-2xl overflow-hidden border border-outline-variant/10 shadow-sm max-w-xs">
                                        <img src="{{ asset('storage/' . $msg->file_path) }}" class="w-full h-auto">
                                    </div>
                                @else
                                    <div class="flex items-center gap-3 p-4 bg-white rounded-2xl border border-outline-variant/10 shadow-sm">
                                        <div class="w-10 h-10 rounded-xl bg-surface-container-low flex items-center justify-center text-primary">
                                            <span class="material-symbols-outlined">description</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-on-surface truncate">{{ basename($msg->file_path) }}</p>
                                            <p class="text-[9px] font-bold text-on-surface-variant uppercase">{{ strtoupper($ext) }} File</p>
                                        </div>
                                        <a href="{{ asset('storage/' . $msg->file_path) }}" download class="text-primary hover:scale-110 transition-transform">
                                            <span class="material-symbols-outlined">download</span>
                                        </a>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <div class="flex items-center gap-2 {{ $msg->user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                            <span class="text-[9px] font-bold text-on-surface-variant/40">{{ $msg->created_at->format('h:i A') }}</span>
                            @if($msg->user_id === auth()->id())
                                <span class="material-symbols-outlined text-[12px] text-teal-500">done_all</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Input Area -->
            <form action="{{ route('messages.send') }}" method="POST" enctype="multipart/form-data" class="p-6 bg-white border-t border-outline-variant/10">
                @csrf
                <input type="hidden" name="conversation_id" value="{{ $activeConversation->id }}">
                <div class="flex items-center gap-4 bg-surface-container-low p-2 rounded-[24px]">
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" type="button" class="w-10 h-10 rounded-full text-on-surface-variant hover:bg-white hover:shadow-sm transition-all">
                            <span class="material-symbols-outlined">sentiment_satisfied</span>
                        </button>
                        <!-- Quick Emoji Picker -->
                        <div x-show="open" @click.away="open = false" class="absolute bottom-14 left-0 bg-white p-3 rounded-2xl shadow-xl border border-outline-variant/10 grid grid-cols-4 gap-2 z-50">
                            @foreach(['😀','😂','😍','👍','🔥','🙌','👏','📚'] as $emoji)
                                <button type="button" @click="$refs.msgInput.value += '{{ $emoji }}'; open = false" class="text-xl hover:scale-125 transition-transform">{{ $emoji }}</button>
                            @endforeach
                        </div>
                    </div>
                    
                    <label class="cursor-pointer w-10 h-10 rounded-full text-on-surface-variant hover:bg-white hover:shadow-sm transition-all flex items-center justify-center">
                        <span class="material-symbols-outlined">attach_file</span>
                        <input type="file" name="attachment" class="hidden" @change="$refs.fileIndicator.innerText = $el.files[0].name; $refs.fileIndicator.classList.remove('hidden')">
                    </label>

                    <div class="flex-1 relative">
                        <input x-ref="msgInput" type="text" name="body" placeholder="Type a message..." class="w-full bg-transparent border-none text-sm font-semibold focus:ring-0">
                        <span x-ref="fileIndicator" class="hidden absolute -top-8 left-0 text-[9px] font-bold bg-primary text-white px-2 py-0.5 rounded-full"></span>
                    </div>

                    <button type="submit" class="w-12 h-12 rounded-full bg-primary text-white shadow-lg shadow-primary/20 flex items-center justify-center hover:scale-105 transition-all">
                        <span class="material-symbols-outlined text-[20px]">send</span>
                    </button>
                </div>
            </form>

            @else
            <div class="flex-1 flex flex-col items-center justify-center text-center opacity-40">
                <div class="w-24 h-24 bg-surface-container-low rounded-full flex items-center justify-center text-on-surface-variant mb-6">
                    <span class="material-symbols-outlined text-5xl">forum</span>
                </div>
                <h3 class="text-xl font-poppins font-bold text-on-surface">Select a chat to start messaging</h3>
                <p class="text-sm font-semibold max-w-xs mt-2">Connect with your teachers, students, or staff members instantly.</p>
            </div>
            @endif
        </div>

        <!-- Modals: New Chat & Create Group -->
        <div x-show="showNewChat" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div @click="showNewChat = false" class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-[32px] shadow-2xl w-full max-w-md overflow-hidden">
                <div class="p-6 border-b border-outline-variant/10 flex items-center justify-between">
                    <h3 class="text-lg font-poppins font-bold text-on-surface">Start New Chat</h3>
                    <button @click="showNewChat = false" class="text-on-surface-variant"><span class="material-symbols-outlined">close</span></button>
                </div>
                <div class="p-4 max-h-80 overflow-y-auto">
                    @foreach($contacts as $contact)
                    <form action="{{ route('messages.start') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $contact->id }}">
                        <button type="submit" class="w-full flex items-center gap-4 p-4 hover:bg-surface-container-low rounded-2xl transition-all">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-bold">
                                {{ substr($contact->name, 0, 1) }}
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-bold text-on-surface">{{ $contact->name }}</p>
                                <p class="text-[10px] font-bold text-on-surface-variant uppercase">{{ $contact->role }}</p>
                            </div>
                        </button>
                    </form>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Group Modal -->
        <div x-show="showGroupModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div @click="showGroupModal = false" class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-[32px] shadow-2xl w-full max-w-md overflow-hidden">
                <div class="p-6 border-b border-outline-variant/10 flex items-center justify-between">
                    <h3 class="text-lg font-poppins font-bold text-on-surface">Create Group</h3>
                    <button @click="showGroupModal = false" class="text-on-surface-variant"><span class="material-symbols-outlined">close</span></button>
                </div>
                <form action="{{ route('messages.group') }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Group Name</label>
                        <input type="text" name="name" required class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Select Members</label>
                        <div class="space-y-2 max-h-48 overflow-y-auto p-2 border border-outline-variant/10 rounded-2xl">
                            @foreach($contacts as $contact)
                            <label class="flex items-center gap-3 p-2 hover:bg-surface-container-low rounded-xl cursor-pointer">
                                <input type="checkbox" name="user_ids[]" value="{{ $contact->id }}" class="rounded text-primary focus:ring-primary/20">
                                <span class="text-xs font-semibold text-on-surface">{{ $contact->name }} ({{ ucfirst($contact->role) }})</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="w-full py-4 bg-primary text-on-primary rounded-2xl text-sm font-bold shadow-lg shadow-primary/20 hover:-translate-y-0.5 transition-all">Create Group</button>
                </form>
            </div>
        </div>

        <!-- Add Member Modal -->
        @if($activeConversation)
        <div x-show="showAddMemberModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div @click="showAddMemberModal = false" class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-[32px] shadow-2xl w-full max-w-md overflow-hidden">
                <div class="p-6 border-b border-outline-variant/10 flex items-center justify-between">
                    <h3 class="text-lg font-poppins font-bold text-on-surface">Add Student to Group</h3>
                    <button @click="showAddMemberModal = false" class="text-on-surface-variant"><span class="material-symbols-outlined">close</span></button>
                </div>
                <div class="p-6">
                    <p class="text-xs font-bold text-on-surface-variant uppercase mb-4">Available Students</p>
                    <div class="space-y-2 max-h-60 overflow-y-auto custom-scrollbar">
                        @foreach($contacts->where('role', 'student') as $student)
                            @if(!$activeConversation->users->contains($student->id))
                            <form action="{{ route('messages.add-member') }}" method="POST">
                                @csrf
                                <input type="hidden" name="conversation_id" value="{{ $activeConversation->id }}">
                                <input type="hidden" name="user_id" value="{{ $student->id }}">
                                <button type="submit" class="w-full flex items-center justify-between p-3 hover:bg-surface-container-low rounded-xl transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-primary text-xs font-bold">{{ substr($student->name, 0, 1) }}</div>
                                        <span class="text-xs font-bold text-on-surface">{{ $student->name }}</span>
                                    </div>
                                    <span class="material-symbols-outlined text-primary text-[20px]">add_circle</span>
                                </button>
                            </form>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
