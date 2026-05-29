<x-layout>
    <x-slot name="title">Team Chat</x-slot>

    <style>
        .msg-bubble {
            position: relative;
        }
        .msg-bubble .delete-msg-btn {
            position: absolute;
            top: 4px;
            right: 4px;
            background: none;
            border: none;
            color: #e74c3c;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.2s;
            padding: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .msg-bubble:hover .delete-msg-btn {
            opacity: 1;
        }
    </style>

    <div style="display: flex; gap: 1.5rem; height: 75vh;">
        <!-- Sidebar -->
        <div class="ui-card" style="width: 250px; flex-shrink: 0; display: flex; flex-direction: column; padding: 1rem;">
            <h3 style="font-family: 'DM Sans', sans-serif; font-size: 0.95rem; margin-bottom: 1rem; color: var(--navy); padding-bottom: 0.5rem; border-bottom: 1px solid var(--lm);">Conversations</h3>
            
            <a href="/chat?type=group" style="padding: 0.75rem; border-radius: 8px; text-decoration: none; color: var(--navy); display: block; margin-bottom: 0.5rem; font-weight: 600; {{ $type === 'group' ? 'background: var(--nav-hover-bg);' : '' }}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 0.3rem; vertical-align: middle;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Group Chat
            </a>

            <h4 style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--muted-text); margin: 1rem 0 0.5rem 0.5rem;">Direct Messages</h4>
            <div id="dmMembersList" style="flex: 1; overflow-y: auto;">
                <!-- Populated via JS -->
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="ui-card" style="flex: 1; display: flex; flex-direction: column; min-width: 0;">
            <div style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--lm); display: flex; align-items: center; justify-content: space-between;">
                <h2 style="margin: 0; font-size: 1.2rem; font-family: 'DM Sans', sans-serif;">
                    {{ $type === 'group' ? 'Team Group Chat' : 'Direct Message' }}
                </h2>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    @if($type === 'group')
                    <div id="onlineStatus" style="font-size: 0.8rem; color: #27ae60; display: flex; align-items: center; gap: 0.4rem;">
                        <div style="width: 8px; height: 8px; background: #27ae60; border-radius: 50%;"></div>
                        <span id="onlineCount">Connecting...</span>
                    </div>
                    @endif

                    @if(($type === 'group' && auth()->user()->isManager()) || $type === 'dm')
                    <button id="clearHistoryBtn" class="ui-button ui-button-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; border-radius: 6px; cursor: pointer;">Clear History</button>
                    @endif
                </div>
            </div>

            <div id="chatMessages" style="flex: 1; overflow-y: auto; padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem;">
                @foreach($messages as $msg)
                    @if($msg->sender_id === auth()->id())
                        <div class="msg-bubble" style="max-width: 75%; padding: 0.6rem 1.8rem 0.6rem 1rem; border-radius: 12px; align-self: flex-end; background: var(--lav); color: var(--navy); border-bottom-right-radius: 2px;">
                            <button class="delete-msg-btn" data-id="{{ $msg->id }}" title="Delete Message">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                            </button>
                            <div style="line-height: 1.4; word-break: break-word;">{!! nl2br(e($msg->body)) !!}</div>
                            <div style="font-size: 0.65rem; opacity: 0.6; text-align: right; margin-top: 0.3rem;">{{ $msg->created_at->format('H:i') }}</div>
                        </div>
                    @else
                        <div style="max-width: 75%; padding: 0.6rem 1rem; border-radius: 12px; align-self: flex-start; background: var(--surface-field); border: 1px solid var(--lm); border-bottom-left-radius: 2px;">
                            <strong style="display: block; font-size: 0.75rem; margin-bottom: 0.2rem; color: var(--navy);">{{ $msg->sender->name }}</strong>
                            <div style="line-height: 1.4; word-break: break-word;">{!! nl2br(e($msg->body)) !!}</div>
                            <div style="font-size: 0.65rem; opacity: 0.6; text-align: right; margin-top: 0.3rem;">{{ $msg->created_at->format('H:i') }}</div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--lm); background: var(--card); border-radius: 0 0 14px 14px;">
                <form id="chatForm" style="display: flex; gap: 0.5rem;">
                    <input type="text" id="chatInput" placeholder="Type a message..." style="flex: 1; padding: 0.75rem; border-radius: 8px; border: 1px solid var(--lm); background: var(--surface-field); color: var(--navy);" required autocomplete="off">
                    <button type="submit" class="ui-button ui-button-primary">Send</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Load Reverb/Echo via CDN -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
    <script>
        window.Pusher = Pusher;
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: '{{ env("REVERB_APP_KEY") }}',
            wsHost: window.location.hostname,
            wsPort: {{ env("REVERB_PORT", 8080) }},
            wssPort: {{ env("REVERB_PORT", 8080) }},
            forceTLS: ({{ env("REVERB_SCHEME", "http") === 'https' ? 'true' : 'false' }}),
            enabledTransports: ['ws', 'wss'],
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const currentUserId = {{ auth()->id() }};
            const currentTeamId = {{ auth()->user()->team_id ?? 'null' }};
            const chatType = '{{ $type }}';
            const withUserId = '{{ $withUserId }}';

            const messagesContainer = document.getElementById('chatMessages');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            // Fetch team members for DM list
            try {
                const res = await fetch('/chat/members');
                const members = await res.json();
                const dmList = document.getElementById('dmMembersList');
                
                members.forEach(m => {
                    const isActive = chatType === 'dm' && withUserId == m.id;
                    dmList.innerHTML += `
                        <a href="/chat?type=dm&with=${m.id}" style="padding: 0.6rem 0.75rem; border-radius: 8px; text-decoration: none; color: var(--navy); display: block; margin-bottom: 0.3rem; font-size: 0.85rem; ${isActive ? 'background: var(--nav-hover-bg); font-weight: 600;' : ''}">
                            <div style="width: 24px; height: 24px; background: var(--lav); color: #fff; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.65rem; margin-right: 0.5rem; vertical-align: middle;">${m.name.charAt(0)}</div>
                            ${m.name}
                        </a>
                    `;
                });
            } catch (e) {}

            // Setup Echo listening
            if (window.Echo) {
                if (chatType === 'group' && currentTeamId) {
                    let onlineCount = 0;
                    window.Echo.join('team.' + currentTeamId)
                        .here((users) => {
                            onlineCount = users.length;
                            document.getElementById('onlineCount').innerText = onlineCount + ' online';
                        })
                        .joining((user) => {
                            onlineCount++;
                            document.getElementById('onlineCount').innerText = onlineCount + ' online';
                        })
                        .leaving((user) => {
                            onlineCount--;
                            document.getElementById('onlineCount').innerText = onlineCount + ' online';
                        })
                        .listen('.MessageSent', (e) => {
                            appendMessage(e.message);
                        });
                } else if (chatType === 'dm' && withUserId) {
                    const ids = [currentUserId, parseInt(withUserId)].sort((a,b) => a - b);
                    window.Echo.private('dm.' + ids[0] + '.' + ids[1])
                        .listen('.MessageSent', (e) => {
                            if (e.message.sender_id != currentUserId) {
                                appendMessage(e.message);
                            }
                        });
                }
            }

            function appendMessage(msg) {
                const isMine = msg.sender_id == currentUserId;
                const div = document.createElement('div');
                if (isMine) {
                    div.className = 'msg-bubble';
                    div.style = `max-width: 75%; padding: 0.6rem 1.8rem 0.6rem 1rem; border-radius: 12px; align-self: flex-end; background: var(--lav); color: var(--navy); border-bottom-right-radius: 2px;`;
                } else {
                    div.style = `max-width: 75%; padding: 0.6rem 1rem; border-radius: 12px; align-self: flex-start; background: var(--surface-field); border: 1px solid var(--lm); border-bottom-left-radius: 2px;`;
                }
                
                let html = '';
                if (isMine) {
                    html += `
                        <button class="delete-msg-btn" data-id="${msg.id}" title="Delete Message">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        </button>
                    `;
                } else {
                    html += `<strong style="display: block; font-size: 0.75rem; margin-bottom: 0.2rem; color: var(--navy);">${msg.sender ? msg.sender.name : 'User'}</strong>`;
                }
                
                // parse created_at to time
                let timeStr = '';
                try {
                    let dateStr = msg.created_at;
                    if (!dateStr.includes('Z') && !dateStr.includes('+')) {
                        dateStr = dateStr + 'Z';
                    }
                    const date = new Date(dateStr);
                    timeStr = date.getHours().toString().padStart(2, '0') + ':' + date.getMinutes().toString().padStart(2, '0');
                } catch(e) {}

                html += `<div style="line-height: 1.4; word-break: break-word;">${msg.body.replace(/\n/g, '<br>')}</div>
                         <div style="font-size: 0.65rem; opacity: 0.6; text-align: right; margin-top: 0.3rem;">${timeStr}</div>`;
                
                div.innerHTML = html;
                messagesContainer.appendChild(div);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            // Message deletion event delegation
            messagesContainer.addEventListener('click', async function(e) {
                const btn = e.target.closest('.delete-msg-btn');
                if (!btn) return;

                const msgId = btn.getAttribute('data-id');
                const bubble = btn.closest('.msg-bubble');

                if (confirm('Are you sure you want to delete this message?')) {
                    try {
                        const res = await fetch(`/chat/messages/${msgId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        });

                        if (res.ok) {
                            bubble.remove();
                        }
                    } catch (err) {
                        console.error('Failed to delete message', err);
                    }
                }
            });

            // Clear history event handler
            const clearHistoryBtn = document.getElementById('clearHistoryBtn');
            if (clearHistoryBtn) {
                clearHistoryBtn.addEventListener('click', async function() {
                    if (confirm('Are you sure you want to clear the conversation history? This cannot be undone.')) {
                        try {
                            const res = await fetch('/chat/clear', {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    type: chatType,
                                    with: withUserId
                                })
                            });

                            if (res.ok) {
                                messagesContainer.innerHTML = '';
                            }
                        } catch (err) {
                            console.error('Failed to clear history', err);
                        }
                    }
                });
            }

            document.getElementById('chatForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const input = document.getElementById('chatInput');
                const body = input.value.trim();
                if (!body) return;

                input.value = '';

                try {
                    const payload = {
                        body: body,
                        type: chatType
                    };
                    if (chatType === 'dm') {
                        payload.receiver_id = withUserId;
                    }

                    const res = await fetch('/chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });
                    
                    if (res.ok) {
                        const msg = await res.json();
                        appendMessage(msg);
                    }
                } catch (err) {
                    console.error('Failed to send message', err);
                }
            });
        });
    </script>
</x-layout>
