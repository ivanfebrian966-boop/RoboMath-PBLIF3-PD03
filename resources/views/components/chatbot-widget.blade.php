<!-- Floating Chatbot Widget -->
<div x-data="chatbotWidget()" class="fixed bottom-6 right-6 z-50">

    <!-- Floating Chat Toggle Button with Mascot Image & Logo Gradients -->
    <button @click="toggleChat()" class="bg-gradient-to-r from-orange-500 via-amber-500 to-indigo-600 hover:from-orange-600 hover:to-indigo-700 text-white font-extrabold p-3 sm:p-4 rounded-full shadow-2xl flex items-center gap-3 transition-all transform hover:scale-105 border-4 border-amber-200 group">
        <div class="w-10 h-10 rounded-full bg-white p-0.5 overflow-hidden shadow-md flex-shrink-0">
            <img src="{{ asset('images/maskot.png') }}" alt="RoboBot" class="w-full h-full object-cover rounded-full">
        </div>
        <span class="hidden sm:inline font-bold pr-2">Tanya RoboBot AI!</span>
    </button>

    <!-- Chat Modal Window -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="fixed bottom-24 right-6 w-96 max-w-[calc(100vw-3rem)] bg-[#FAF6EF] rounded-3xl shadow-2xl border-4 border-amber-300 flex flex-col overflow-hidden h-[500px]"
         style="display: none;">

        <!-- Header -->
        <div class="bg-gradient-to-r from-orange-500 via-amber-500 to-indigo-600 p-4 text-white flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-full bg-white p-0.5 overflow-hidden flex-shrink-0 shadow-lg ring-2 ring-white/40">
                    <img src="{{ asset('images/maskot.png') }}" alt="RoboBot AI" class="w-full h-full object-contain rounded-full bg-indigo-50">
                </div>
                <div>
                    <img src="{{ asset('images/logo.png') }}" alt="RoboMath" class="h-5 w-auto bg-white/90 p-0.5 rounded mb-0.5">
                    <p class="text-xs text-amber-100 font-extrabold">RoboBot AI • Matematika SD 🤖</p>
                </div>
            </div>
            <button @click="isOpen = false" class="text-white/80 hover:text-white font-bold text-xl px-2 leading-none hover:bg-white/10 rounded-lg p-1 transition-colors">
                ✕
            </button>
        </div>

        <!-- Messages Area -->
        <div class="flex-1 p-4 overflow-y-auto space-y-4 bg-[#FFFDF9]" id="chat-messages-container">
            <div class="flex items-start gap-2">
                <div class="w-8 h-8 rounded-full bg-amber-100 p-0.5 overflow-hidden flex-shrink-0 shadow-sm border border-amber-300">
                    <img src="{{ asset('images/maskot.png') }}" alt="RoboBot" class="w-full h-full object-cover rounded-full">
                </div>
                <div class="bg-white p-3 rounded-2xl border-2 border-amber-200/80 text-sm shadow-sm max-w-[80%] text-slate-800 font-medium">
                    Halo {{ auth()->user()->name }}! 👋 Aku RoboBot, teman belajarmu di RoboMath. Mau tanya soal penjumlahan, perkalian, pecahan, atau logika matematika? 😊
                </div>
            </div>

            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.sender === 'user' ? 'flex items-end justify-end gap-2' : 'flex items-start gap-2'">
                    <template x-if="msg.sender === 'bot'">
                        <div class="w-8 h-8 rounded-full bg-amber-100 p-0.5 overflow-hidden flex-shrink-0 shadow-sm border border-amber-300">
                            <img src="{{ asset('images/maskot.png') }}" alt="RoboBot" class="w-full h-full object-cover rounded-full">
                        </div>
                    </template>
                    <div :class="msg.sender === 'user' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-3 rounded-2xl rounded-br-none text-sm max-w-[80%] shadow-sm font-semibold' : 'bg-white p-3 rounded-2xl border-2 border-amber-200/80 text-sm shadow-sm max-w-[80%] text-slate-800 font-medium whitespace-pre-line'" x-text="msg.text"></div>
                </div>
            </template>

            <div x-show="isLoading" class="flex items-center gap-2 text-amber-800 text-xs font-bold italic">
                <div class="w-6 h-6 rounded-full overflow-hidden animate-pulse">
                    <img src="{{ asset('images/maskot.png') }}" class="w-full h-full object-cover">
                </div>
                <span>RoboBot sedang berpikir... 🤖</span>
            </div>
        </div>

        <!-- Quick Questions Chips -->
        <div class="p-2 bg-amber-50/80 border-t border-amber-200 flex gap-2 overflow-x-auto text-xs font-bold text-amber-950">
            <button @click="sendQuick('Apa itu RoboMath?')" class="bg-white px-3 py-1.5 rounded-full border border-amber-300 whitespace-nowrap hover:bg-orange-50 hover:border-orange-400 shadow-xs">🤖 Apa itu RoboMath?</button>
            <button @click="sendQuick('Bagaimana cara mudah belajar perkalian?')" class="bg-white px-3 py-1.5 rounded-full border border-amber-300 whitespace-nowrap hover:bg-orange-50 hover:border-orange-400 shadow-xs">✖️ Cara Perkalian Cepat</button>
            <button @click="sendQuick('Apa itu algoritma matematika?')" class="bg-white px-3 py-1.5 rounded-full border border-amber-300 whitespace-nowrap hover:bg-orange-50 hover:border-orange-400 shadow-xs">🔀 Algoritma Matematika</button>
        </div>

        <!-- Input Area -->
        <form @submit.prevent="sendMessage()" class="p-3 bg-white border-t-2 border-amber-200 flex items-center gap-2">
            <input type="text" 
                   x-model="inputMessage" 
                   placeholder="Ketik pertanyaan matematikamu..." 
                   class="flex-1 px-4 py-2.5 rounded-2xl border-2 border-amber-200 focus:border-orange-500 focus:outline-none text-sm font-semibold text-slate-800 bg-[#FFFDF9]"
                   :disabled="isLoading">
            <button type="submit" 
                    class="bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-extrabold px-4 py-2.5 rounded-2xl transition-all disabled:opacity-50 shadow-md"
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
                    this.messages.push({ sender: 'bot', text: 'Maaf, RoboBot sedang beristirahat sebentar. Coba lagi nanti ya! 🙏' });
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
