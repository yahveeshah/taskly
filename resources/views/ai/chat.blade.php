<x-layout>
    <x-slot name="title">AI Assistant</x-slot>

    <div class="ui-card" style="display: flex; flex-direction: column; height: 75vh;">
        <div style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--lm); display: flex; justify-content: space-between; align-items: center;">
            <h2 style="margin: 0;">AI Assistant</h2>
            <form action="{{ route('ai.chat.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="ui-button ui-button-sm ui-button-danger">Clear History</button>
            </form>
        </div>
        
        <div id="chatMessages" style="flex: 1; overflow-y: auto; padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem;">
            @foreach($messages as $msg)
                <div style="max-width: 80%; padding: 0.8rem 1.2rem; border-radius: 12px; {{ $msg->role === 'user' ? 'align-self: flex-end; background: var(--lav); color: var(--navy); border-bottom-right-radius: 4px;' : 'align-self: flex-start; background: var(--surface-field); border: 1px solid var(--lm); border-bottom-left-radius: 4px;' }}">
                    <strong style="display: block; font-size: 0.75rem; margin-bottom: 0.3rem; opacity: 0.8;">{{ $msg->role === 'user' ? 'You' : 'Taskly AI' }}</strong>
                    <div style="line-height: 1.5;">{!! nl2br(e($msg->message)) !!}</div>
                </div>
            @endforeach
        </div>

        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--lm);">
            <form id="chatForm" style="display: flex; gap: 0.5rem;">
                <input type="text" id="chatInput" placeholder="Ask Taskly AI about your tasks..." style="flex: 1; padding: 0.75rem; border-radius: 8px; border: 1px solid var(--lm); background: var(--surface-field); color: var(--navy);" required autocomplete="off">
                <button type="submit" class="ui-button ui-button-primary">Send</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('chatForm');
            const input = document.getElementById('chatInput');
            const sendButton = form.querySelector('button[type="submit"]');
            const messagesContainer = document.getElementById('chatMessages');

            // Scroll to bottom initially
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const userMsg = input.value.trim();
                if (!userMsg) return;

                // Add user message to UI
                const userDiv = appendChatMessage('You', userMsg, true);
                
                input.value = '';
                input.disabled = true;
                sendButton.disabled = true;
                messagesContainer.scrollTop = messagesContainer.scrollHeight;

                // Add loading indicator
                const loadingDiv = document.createElement('div');
                loadingDiv.id = "aiLoading";
                loadingDiv.style = "align-self: flex-start; opacity: 0.6; font-size: 0.85rem;";
                loadingDiv.innerText = "Taskly AI is thinking...";
                messagesContainer.appendChild(loadingDiv);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
                let blink = null;
                let cursor = null;

                try {
                    const response = await fetch('/ai/chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ message: userMsg })
                    });

                    if (!response.ok) throw new Error('Network response was not ok');
                    if (!response.body) throw new Error('Streaming is not supported');

                    const reader = response.body.getReader();
                    const decoder = new TextDecoder();
                    const aiDiv = appendChatMessage('Taskly AI', '', false);
                    const textNode = aiDiv.querySelector('[data-message-text]');
                    cursor = aiDiv.querySelector('[data-stream-cursor]');
                    blink = setInterval(function () {
                        cursor.style.visibility = cursor.style.visibility === 'hidden' ? 'visible' : 'hidden';
                    }, 450);
                    let started = false;

                    while (true) {
                        const { value, done } = await reader.read();
                        if (done) break;

                        const chunk = decoder.decode(value, { stream: true });
                        if (!chunk) continue;

                        if (!started) {
                            document.getElementById('aiLoading')?.remove();
                            started = true;
                        }

                        textNode.textContent += chunk;
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    }

                    const finalChunk = decoder.decode();
                    if (finalChunk) {
                        if (!started) {
                            document.getElementById('aiLoading')?.remove();
                            started = true;
                        }
                        textNode.textContent += finalChunk;
                    }

                    document.getElementById('aiLoading')?.remove();
                    clearInterval(blink);
                    cursor.remove();
                } catch (error) {
                    document.getElementById('aiLoading')?.remove();
                    if (blink) clearInterval(blink);
                    if (cursor) cursor.remove();
                    const errDiv = document.createElement('div');
                    errDiv.style = "align-self: center; color: #e74c3c; font-size: 0.85rem;";
                    errDiv.innerText = "Failed to get response. Please try again.";
                    messagesContainer.appendChild(errDiv);
                } finally {
                    input.disabled = false;
                    sendButton.disabled = false;
                    input.focus();
                }
                
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            });

            function appendChatMessage(label, message, mine) {
                const wrapper = document.createElement('div');
                wrapper.style = mine
                    ? "max-width: 80%; padding: 0.8rem 1.2rem; border-radius: 12px; align-self: flex-end; background: var(--lav); color: var(--navy); border-bottom-right-radius: 4px;"
                    : "max-width: 80%; padding: 0.8rem 1.2rem; border-radius: 12px; align-self: flex-start; background: var(--surface-field); border: 1px solid var(--lm); border-bottom-left-radius: 4px;";

                const strong = document.createElement('strong');
                strong.style = "display: block; font-size: 0.75rem; margin-bottom: 0.3rem; opacity: 0.8;";
                strong.textContent = label;

                const body = document.createElement('div');
                body.style = "line-height: 1.5; white-space: pre-wrap;";

                const text = document.createElement('span');
                text.dataset.messageText = 'true';
                text.textContent = message;
                body.appendChild(text);

                if (!mine) {
                    const cursor = document.createElement('span');
                    cursor.dataset.streamCursor = 'true';
                    cursor.textContent = '|';
                    body.appendChild(cursor);
                }

                wrapper.appendChild(strong);
                wrapper.appendChild(body);
                messagesContainer.appendChild(wrapper);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;

                return wrapper;
            }
        });
    </script>
</x-layout>
