@extends("theme.$theme.layout")

@section('titulo')
    Asistente Virtual
@endsection

@section('styles')
    <style>
        .chat-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .chat-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .chat-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
        }
        .chat-card-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .chat-card-header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .chat-card-body {
            padding: 20px;
        }
    </style>
@endsection

@section('contenido')
<div id="app">
    <div class="chat-wrapper">
        <div class="chat-card">
            <div class="chat-card-header">
                <h2><i class="fas fa-robot"></i> Asistente Virtual con IA</h2>
                <p>Chatea con nuestro asistente inteligente para obtener ayuda con pacientes</p>
            </div>
            <div class="chat-card-body">
                <chat-component></chat-component>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scriptsPlugins')
    <!-- Vue.js desde CDN -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2.7.16/dist/vue.js"></script>
    <!-- Axios desde CDN -->
    <script src="https://cdn.jsdelivr.net/npm/axios@0.27.2/dist/axios.min.js"></script>

    <!-- Componente Chat -->
    <script>
        Vue.component('chat-component', {
            template: `
                <div class="chat-container">
                    <!-- Lista de Chats -->
                    <div class="chat-list" v-if="!selectedChat">
                        <div class="chat-header">
                            <h3>Mis Chats</h3>
                            <button class="btn btn-primary btn-sm" @click="showNewChatModal = true">
                                <i class="fa fa-plus"></i> Nuevo Chat
                            </button>
                        </div>
                        <div class="chat-list-items">
                            <div
                                v-for="chat in chats"
                                :key="chat.id"
                                class="chat-item"
                                @click="selectChat(chat)"
                            >
                                <div class="chat-item-info">
                                    <h4>@{{ chat.name || 'Chat sin nombre' }}</h4>
                                    <p class="text-muted">
                                        @{{ chat.last_message ? chat.last_message.message.substring(0, 50) + '...' : 'Sin mensajes' }}
                                    </p>
                                    <small class="text-muted">
                                        @{{ chat.type === 'individual' ? 'Individual' : 'Grupal' }}
                                    </small>
                                </div>
                            </div>
                            <div v-if="chats.length === 0" class="text-center text-muted">
                                <p>No tienes chats aún</p>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Activo -->
                    <div class="chat-active" v-else>
                        <div class="chat-header">
                            <button class="btn btn-link" @click="selectedChat = null">
                                <i class="fa fa-arrow-left"></i>
                            </button>
                            <h3>@{{ selectedChat.name }}</h3>
                            <span class="badge" :class="selectedChat.type === 'group' ? 'badge-info' : 'badge-success'">
                                @{{ selectedChat.type === 'group' ? 'Grupal' : 'Individual' }}
                            </span>
                        </div>

                        <!-- Área de Mensajes -->
                        <div class="chat-messages" ref="messagesContainer">
                            <div
                                v-for="message in messages"
                                :key="message.id"
                                class="message"
                                :class="{ 'message-ai': message.is_ai_message, 'message-user': !message.is_ai_message }"
                            >
                                <div class="message-sender">
                                    <strong>@{{ getMessageSender(message) }}</strong>
                                    <small class="text-muted">@{{ formatDate(message.created_at) }}</small>
                                </div>
                                <div class="message-content">
                                    @{{ message.message }}
                                </div>
                            </div>
                            <div v-if="isLoading" class="message message-ai">
                                <div class="message-content">
                                    <i class="fa fa-spinner fa-spin"></i> El asistente está escribiendo...
                                </div>
                            </div>
                        </div>

                        <!-- Formulario de Envío -->
                        <div class="chat-input">
                            <form @submit.prevent="sendMessage">
                                <div class="input-group">
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="newMessage"
                                        placeholder="Escribe un mensaje..."
                                        :disabled="isLoading"
                                    />
                                    <span class="input-group-btn">
                                        <button
                                            type="submit"
                                            class="btn btn-primary"
                                            :disabled="!newMessage.trim() || isLoading"
                                        >
                                            <i class="fa fa-paper-plane"></i> Enviar
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal Nuevo Chat -->
                    <div class="modal" :class="{ show: showNewChatModal }" v-if="showNewChatModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" @click="showNewChatModal = false">
                                        <span>&times;</span>
                                    </button>
                                    <h4 class="modal-title">Nuevo Chat con Paciente</h4>
                                </div>
                                <div class="modal-body">
                                    <form @submit.prevent="createPatientChat">
                                        <div class="form-group">
                                            <label>ID del Paciente</label>
                                            <input
                                                type="number"
                                                class="form-control"
                                                v-model="newChatPatientId"
                                                placeholder="Ingrese el ID del paciente"
                                                required
                                            />
                                            <small class="help-block">
                                                Ingrese el ID del paciente para iniciar un chat
                                            </small>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" @click="showNewChatModal = false">
                                        Cancelar
                                    </button>
                                    <button type="button" class="btn btn-primary" @click="createPatientChat">
                                        Crear Chat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `,
            data() {
                return {
                    chats: [],
                    selectedChat: null,
                    messages: [],
                    newMessage: '',
                    isLoading: false,
                    pollingInterval: null,
                    lastMessageDate: null,
                    showNewChatModal: false,
                    newChatPatientId: '',
                };
            },
            mounted() {
                this.loadChats();
            },
            beforeDestroy() {
                this.stopPolling();
            },
            methods: {
                async loadChats() {
                    try {
                        const response = await axios.get('/api/chat');
                        if (response.data.success) {
                            this.chats = response.data.chats;
                        }
                    } catch (error) {
                        console.error('Error loading chats:', error);
                        this.showError('Error al cargar los chats');
                    }
                },
                async selectChat(chat) {
                    this.selectedChat = chat;
                    await this.loadMessages();
                    this.startPolling();
                },
                async loadMessages() {
                    try {
                        const response = await axios.get('/api/chat/' + this.selectedChat.id + '/messages');
                        if (response.data.success) {
                            this.messages = response.data.messages;
                            this.lastMessageDate = this.getLastMessageDate();
                            this.$nextTick(() => {
                                this.scrollToBottom();
                            });
                        }
                    } catch (error) {
                        console.error('Error loading messages:', error);
                        this.showError('Error al cargar los mensajes');
                    }
                },
                async sendMessage() {
                    if (!this.newMessage.trim() || this.isLoading) return;

                    this.isLoading = true;
                    const messageText = this.newMessage;
                    this.newMessage = '';

                    try {
                        const response = await axios.post('/api/chat/' + this.selectedChat.id + '/messages', {
                            message: messageText
                        });

                        if (response.data.success) {
                            this.messages.push(response.data.data);
                            if (response.data.data.child_messages && response.data.data.child_messages.length > 0) {
                                this.messages.push(...response.data.data.child_messages);
                            }
                            this.lastMessageDate = this.getLastMessageDate();
                            this.$nextTick(() => {
                                this.scrollToBottom();
                            });
                        }
                    } catch (error) {
                        console.error('Error sending message:', error);
                        this.showError('Error al enviar el mensaje');
                        this.newMessage = messageText;
                    } finally {
                        this.isLoading = false;
                    }
                },
                startPolling() {
                    this.stopPolling();
                    this.pollingInterval = setInterval(() => {
                        this.pollNewMessages();
                    }, 3000);
                },
                stopPolling() {
                    if (this.pollingInterval) {
                        clearInterval(this.pollingInterval);
                        this.pollingInterval = null;
                    }
                },
                async pollNewMessages() {
                    if (!this.selectedChat || !this.lastMessageDate) return;

                    try {
                        const response = await axios.get('/api/chat/' + this.selectedChat.id + '/messages/poll', {
                            params: {
                                since: this.lastMessageDate
                            }
                        });

                        if (response.data.success && response.data.count > 0) {
                            this.messages.push(...response.data.messages);
                            this.lastMessageDate = this.getLastMessageDate();
                            this.$nextTick(() => {
                                this.scrollToBottom();
                            });
                        }
                    } catch (error) {
                        console.error('Error polling messages:', error);
                    }
                },
                async createPatientChat() {
                    if (!this.newChatPatientId) return;

                    try {
                        const response = await axios.post('/api/chat/find-or-create-patient-chat', {
                            patient_id: this.newChatPatientId
                        });

                        if (response.data.success) {
                            this.showNewChatModal = false;
                            this.newChatPatientId = '';
                            await this.loadChats();
                            this.selectChat(response.data.chat);
                        }
                    } catch (error) {
                        console.error('Error creating chat:', error);
                        this.showError('Error al crear el chat');
                    }
                },
                getLastMessageDate() {
                    if (this.messages.length === 0) return new Date().toISOString();
                    return this.messages[this.messages.length - 1].created_at;
                },
                getMessageSender(message) {
                    if (message.is_ai_message) {
                        return 'Asistente IA';
                    }
                    if (message.sender_user) {
                        return message.sender_user.name || message.sender_user.email;
                    }
                    if (message.sender_patient) {
                        const p = message.sender_patient;
                        return ((p.pnombre || '') + ' ' + (p.snombre || '') + ' ' + (p.papellido || '') + ' ' + (p.sapellido || '')).trim();
                    }
                    return 'Usuario';
                },
                                formatDate(dateString) {
                    const date = new Date(dateString);
                    const now = new Date();
                    const diff = now - date;
                    const seconds = Math.floor(diff / 1000);
                    const minutes = Math.floor(seconds / 60);
                    const hours = Math.floor(minutes / 60);
                    const days = Math.floor(hours / 24);

                    if (days > 0) return 'Hace ' + days + ' día' + (days > 1 ? 's' : '');
                    if (hours > 0) return 'Hace ' + hours + ' hora' + (hours > 1 ? 's' : '');
                    if (minutes > 0) return 'Hace ' + minutes + ' minuto' + (minutes > 1 ? 's' : '');
                    return 'Justo ahora';
                },
                scrollToBottom() {
                    if (this.$refs.messagesContainer) {
                        this.$refs.messagesContainer.scrollTop = this.$refs.messagesContainer.scrollHeight;
                    }
                },
                showError(message) {
                    alert(message);
                }
            },
            watch: {
                selectedChat(newVal) {
                    if (!newVal) {
                        this.stopPolling();
                        this.messages = [];
                    }
                }
            }
        });

        // Inicializar Vue
        new Vue({
            el: '#app'
        });
    </script>

    <style>
        .chat-container {
            display: flex;
            flex-direction: column;
            height: 600px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #fff;
        }

        .chat-list {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .chat-header {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            background: #f5f5f5;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-header h3 {
            margin: 0;
            font-size: 18px;
        }

        .chat-list-items {
            flex: 1;
            overflow-y: auto;
        }

        .chat-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background 0.2s;
        }

        .chat-item:hover {
            background: #f9f9f9;
        }

        .chat-item-info h4 {
            margin: 0 0 5px 0;
            font-size: 16px;
        }

        .chat-item-info p {
            margin: 0 0 5px 0;
            font-size: 14px;
        }

        .chat-active {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
            background: #f9f9f9;
        }

        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            max-width: 80%;
        }

        .message-user {
            background: #e3f2fd;
            margin-left: auto;
        }

        .message-ai {
            background: #fff3cd;
        }

        .message-sender {
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
        }

        .message-content {
            word-wrap: break-word;
        }

        .chat-input {
            padding: 15px;
            border-top: 1px solid #ddd;
            background: #fff;
        }

        .modal.show {
            display: block;
            background: rgba(0, 0, 0, 0.5);
        }
    </style>
@endsection
