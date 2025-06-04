document.addEventListener('DOMContentLoaded', () => {
    const chatButton = document.getElementById('chat-button');
    const chatWindow = document.getElementById('chat-window');
    const closeChatButton = document.getElementById('close-chat');
    const chatInput = document.getElementById('chat-input');
    const sendButton = document.getElementById('send-button');
    const chatBody = document.getElementById('chat-body');

    chatButton.addEventListener('click', () => {
        chatWindow.classList.toggle('open');
        // Add initial welcome message when chat opens
        if (chatWindow.classList.contains('open') && chatBody.children.length === 0) {
            appendMessage('¡Hola! Soy el asistente personal de Lidia Fernández. Estoy aquí para responder cualquier pregunta que tengas sobre su trayectoria profesional y personalidad. ¡No dudes en preguntar!', 'ai');
        }
    });

    closeChatButton.addEventListener('click', () => {
        chatWindow.classList.remove('open');
    });

    sendButton.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    function sendMessage() {
        const message = chatInput.value.trim();
        if (message) {
            appendMessage(message, 'user');
            chatInput.value = '';
            sendToGemini(message);
        }
    }

    function appendMessage(message, sender) {
        const messageElement = document.createElement('div');
        messageElement.classList.add('message', sender);

        if (sender === 'ai') {
            const imgElement = document.createElement('img');
            imgElement.src = 'ChatGPT Image 4 jun 2025, 17_07_30.png'; // Ruta de la imagen de la caricatura
            imgElement.alt = 'AI Avatar';
            imgElement.classList.add('ai-avatar'); // Clase para estilizar la imagen
            messageElement.appendChild(imgElement);
        }

        const textElement = document.createElement('p');
        textElement.textContent = message;
        messageElement.appendChild(textElement);

        chatBody.appendChild(messageElement);
        chatBody.scrollTop = chatBody.scrollHeight; // Scroll to bottom
    }

    async function sendToGemini(userMessage) {
        try {
            const response = await fetch('gemini_proxy.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message: userMessage })
            });
            const data = await response.json();
            if (data.error) {
                appendMessage(`Error: ${data.error}`, 'ai');
            } else {
                appendMessage(data.text, 'ai');
            }
        } catch (error) {
            console.error('Error communicating with Gemini proxy:', error);
            appendMessage('Lo siento, no pude conectar con la IA en este momento.', 'ai');
        }
    }
});