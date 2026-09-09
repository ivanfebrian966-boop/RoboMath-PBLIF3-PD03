<!-- Floating Chatbot Widget -->
<div x-data="chatbotWidget()" class="fixed bottom-6 right-6 z-50">

    <!-- Floating Chat Toggle Button -->
    <button @click="toggleChat()" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-extrabold p-4 rounded-full shadow-2xl flex items-center gap-3 transition-all transform hover:scale-105 border-4 border-white">
        <span class="text-3xl animate-bounce">🤖</span>
        <span class="hidden sm:inline font-bold pr-2">Tanya AlgoBot AI!</span>
    </button>

    <!-- Chat Modal Window -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="fixed bottom-24 right-6 w-96 max-w-[calc(100vw-3rem)] bg-white rounded-3xl shadow-2xl border-4 border-indigo-200 flex flex-col overflow-hidden h-[500px]"
         style="display: none;">

        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-4 text-white flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-2xl">
                    🤖
                </div>
                <div>
                    <h3 class="font-extrabold text-base leading-tight">AlgoBot AI</h3>
                    <p class="text-xs text-indigo-100">Teman Belajar Logikamu 🌟</p>
                </div>
            </div>
            <button @click="isOpen = false" class="text-white/80 hover:text-white font-bold text-xl px-2">
                ✕
            </button>
        </div>

        <!-- Messages Area -->
        <div class="flex-1 p-4 overflow-y-auto space-y-4 bg-slate-50" id="chat-messages-container">
            <div class="flex items-start gap-2">
                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-lg flex-shrink-0">🤖</div>
                <div class="bg-white p-3 rounded-2xl border border-slate-200 text-sm shadow-sm max-w-[80%] text-slate-700">
                    Halo {{ auth()->user()->name }}! 👋 Aku AlgoBot, teman belajarmu di AlgoKids. Mau tanya soal urutan, percabangan, atau pengulangan? 😊
                </div>
            </div>

            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.sender === 'user' ? 'flex items-end justify-end gap-2' : 'flex items-start gap-2'">
                    <template x-if="msg.sender === 'bot'">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-lg flex-shrink-0">🤖</div>
                    </template>
                    <div :class="msg.sender === 'user' ? 'bg-indigo-600 text-white p-3 rounded-2xl rounded-br-none text-sm max-w-[80%] shadow-sm font-medium' : 'bg-white p-3 rounded-2xl border border-slate-200 text-sm shadow-sm max-w-[80%] text-slate-700 whitespace-pre-line'" x-text="msg.text"></div>
                </div>
            </template>

            <div x-show="isLoading" class="flex items-center gap-2 text-slate-400 text-xs font-bold italic">
                <span class="animate-spin text-lg">⏳</span> AlgoBot sedang berpikir...
            </div>
        </div>

        <!-- Quick Questions Chips -->
        <div class="p-2 bg-slate-100 border-t border-slate-200 flex gap-2 overflow-x-auto text-xs font-bold text-indigo-700">
            <button @click="sendQuick('Apa itu algoritma?')" class="bg-white px-3 py-1.5 rounded-full border border-indigo-200 whitespace-nowrap hover:bg-indigo-50"> Apa itu Algoritma?</button>
            <button @click="sendQuick('Apa itu percabangan (if-else)?')" class="bg-white px-3 py-1.5 rounded-full border border-indigo-200 whitespace-nowrap hover:bg-indigo-50">🔀 Percabangan?</button>
            <button @click="sendQuick('Apa itu pengulangan (loop)?')" class="bg-white px-3 py-1.5 rounded-full border border-indigo-200 whitespace-nowrap hover:bg-indigo-50">🔄 Pengulangan?</button>
        </div>

        <!-- Input Area -->
        <form @submit.prevent="sendMessage()" class="p-3 bg-white border-t border-slate-200 flex items-center gap-2">
            <input type="text" 
                   x-model="inputMessage" 
                   placeholder="Ketik pertanyaanmu..." 
                   class="flex-1 px-4 py-2.5 rounded-2xl border-2 border-slate-200 focus:border-indigo-500 focus:outline-none text-sm font-medium"
                   :disabled="isLoading">
            <button type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold px-4 py-2.5 rounded-2xl transition-all disabled:opacity-50"
                    :disabled="isLoading || !inputMessage.trim()">
                🚀
            </button>
        </form>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function chatbotWidget() {
        return {
            isOpen: false,
            inputMessage: '',
            isLoading: false,
            messages: [],
            toggleChat() {
                this.isOpen = !this.isOpen;
            },
            sendQuick(text) {
                this.inputMessage = text;
                this.sendMessage();
            },
            async sendMessage() {
                if (!this.inputMessage.trim() || this.isLoading) return;
                
                const userMsg = this.inputMessage.trim();
                this.messages.push({ sender: 'user', text: userMsg });
                this.inputMessage = '';
                this.isLoading = true;
                this.scrollToBottom();

                try {
                    const response = await fetch("{{ route('siswa.chat.send') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ message: userMsg })
                    });
                    const data = await response.json();
                    this.messages.push({ sender: 'bot', text: data.message });
                } catch (e) {
                    this.messages.push({ sender: 'bot', text: 'Maaf, AlgoBot sedang beristirahat sebentar. Coba lagi nanti ya! 🙏' });
                } finally {
                    this.isLoading = false;
                    this.scrollToBottom();
                }
            },
            scrollToBottom() {
                setTimeout(() => {
                    const container = document.getElementById('chat-messages-container');
                    if (container) container.scrollTop = container.scrollHeight;
                }, 100);
            }
        }
    }
</script>
