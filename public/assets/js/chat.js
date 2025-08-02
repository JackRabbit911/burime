document.addEventListener('DOMContentLoaded', () => {
    // const ws = new WebSocket("ws://localhost:8080");

    const form = document.getElementById('message-form');
    const input = document.getElementById('message-input');
    const messages = document.getElementById('messages');

    const path = window.location.pathname
    const myAuthorId = Number(path.split('/').pop())

    const fetchMessage = async (url, message) => {
        const response = await fetch(url, {
            method: "POST",
            body: message,
        })

        json = response.json()

        return json
    }

    const createChatBubble = (message, positionEnd = false) => {
        const position = positionEnd ? 'chat-end' : 'chat-start'
        const background = positionEnd ? 'chat-bubble-accent' : 'chat-bubble-primary'

        const chat = document.createElement('div')
        chat.classList.add('chat', position)
        const bubble = document.createElement('div')
        bubble.classList.add('chat-bubble', background, 'whitespace-pre-line')
        chat.appendChild(bubble)
        bubble.textContent = message

        return chat
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const url = form.getAttribute('action')
        const response = await fetchMessage(url, input.value)

        if (response.from === myAuthorId) {
            const item = createChatBubble(response.message, true)
            messages.appendChild(item)
        }
        
        input.value = '';
    });

    // ws.onmessage = function(e) {
    //     console.log(e)
    // };
});
