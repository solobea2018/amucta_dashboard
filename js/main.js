function goto(url) {
    if (window.location.pathname !== url) {
        window.location.href = url
    }
}
function chatbot() {
    // Only show once per session
    if (sessionStorage.getItem('chatPopupShown')) return;

    // Delay popup 5–15 seconds
    const delay = Math.floor(Math.random() * (15000 - 5000 + 1)) + 5000;
    setTimeout(() => {

        // --- CREATE CHAT INVITE POPUP ---
        const chatPopup = document.createElement('div');
        chatPopup.id = 'chat-popup';
        chatPopup.style.position = 'fixed';
        chatPopup.style.bottom = '20px';
        chatPopup.style.right = '20px';
        chatPopup.style.width = '300px';
        chatPopup.style.background = '#10b981';
        chatPopup.style.color = 'white';
        chatPopup.style.borderRadius = '12px';
        chatPopup.style.boxShadow = '0 8px 20px rgba(0,0,0,0.3)';
        chatPopup.style.padding = '15px';
        chatPopup.style.zIndex = '9999';
        chatPopup.style.textAlign = 'center';
        chatPopup.style.fontFamily = 'Arial, sans-serif';

        // Close button
        const closeBtn = document.createElement('button');
        closeBtn.innerHTML = '✖';
        closeBtn.style.position = 'absolute';
        closeBtn.style.top = '5px';
        closeBtn.style.right = '10px';
        closeBtn.style.border = 'none';
        closeBtn.style.background = 'transparent';
        closeBtn.style.fontSize = '1.2rem';
        closeBtn.style.color = 'white';
        closeBtn.style.cursor = 'pointer';
        closeBtn.addEventListener('click', () => chatPopup.remove());
        chatPopup.appendChild(closeBtn);

        // Message
        const msg = document.createElement('p');
        msg.innerText = 'Hi! Want to chat with AMUCTA support?';
        msg.style.margin = '10px 0';
        msg.style.fontWeight = 'bold';
        chatPopup.appendChild(msg);

        // Accept button
        const acceptBtn = document.createElement('button');
        acceptBtn.innerText = 'Start Chat 💬';
        acceptBtn.style.background = 'white';
        acceptBtn.style.color = '#10b981';
        acceptBtn.style.border = 'none';
        acceptBtn.style.padding = '10px 20px';
        acceptBtn.style.borderRadius = '8px';
        acceptBtn.style.cursor = 'pointer';
        acceptBtn.style.fontWeight = 'bold';
        acceptBtn.addEventListener('mouseover', () => acceptBtn.style.background = '#f0f0f0');
        acceptBtn.addEventListener('mouseout', () => acceptBtn.style.background = 'white');
        chatPopup.appendChild(acceptBtn);

        document.body.appendChild(chatPopup);
        sessionStorage.setItem('chatPopupShown', 'true');

        // --- CHAT WINDOW ---
        const chatWindow = document.createElement('div');
        chatWindow.id = 'chat-window';
        chatWindow.style.position = 'fixed';
        chatWindow.style.bottom = '80px';
        chatWindow.style.right = '20px';
        chatWindow.style.width = '350px';
        chatWindow.style.height = '400px';
        chatWindow.style.background = 'white';
        chatWindow.style.borderRadius = '12px';
        chatWindow.style.boxShadow = '0 10px 25px rgba(0,0,0,0.3)';
        chatWindow.style.display = 'none';
        chatWindow.style.flexDirection = 'column';
        chatWindow.style.overflow = 'hidden';
        chatWindow.style.zIndex = '9999';

        // Chat header
        const header = document.createElement('div');
        header.style.background = '#10b981';
        header.style.color = 'white';
        header.style.padding = '10px';
        header.style.fontWeight = 'bold';
        header.style.display = 'flex';
        header.style.justifyContent = 'space-between';
        header.style.alignItems = 'center';
        header.innerHTML = 'AMUCTA Chat <span style="cursor:pointer;" id="close-chat">✖</span>';
        chatWindow.appendChild(header);

        // Chat messages container
        const messages = document.createElement('div');
        messages.style.flex = '1';
        messages.style.padding = '10px';
        messages.style.overflowY = 'auto';
        messages.style.fontSize = '0.95rem';
        messages.style.color = '#374151';
        chatWindow.appendChild(messages);

        // Input area
        const inputContainer = document.createElement('div');
        inputContainer.style.display = 'flex';
        inputContainer.style.borderTop = '1px solid #ddd';

        const input = document.createElement('input');
        input.type = 'text';
        input.placeholder = 'Type your message...';
        input.style.flex = '1';
        input.style.padding = '10px';
        input.style.border = 'none';
        input.style.outline = 'none';

        const sendBtn = document.createElement('button');
        sendBtn.innerText = 'Send';
        sendBtn.style.background = '#10b981';
        sendBtn.style.color = 'white';
        sendBtn.style.border = 'none';
        sendBtn.style.padding = '0 15px';
        sendBtn.style.cursor = 'pointer';

        sendBtn.addEventListener('click', () => {
            if (!input.value.trim()) return;
            const userMsg = document.createElement('div');
            userMsg.innerText = input.value;
            userMsg.style.textAlign = 'right';
            userMsg.style.margin = '5px 0';
            userMsg.style.fontWeight = 'bold';
            messages.appendChild(userMsg);
            input.value = '';
            messages.scrollTop = messages.scrollHeight;

            // Simple bot reply
            setTimeout(() => {
                const botMsg = document.createElement('div');
                botMsg.innerText = 'Thanks for reaching out! We will contact you soon.';
                botMsg.style.textAlign = 'left';
                botMsg.style.margin = '5px 0';
                botMsg.style.color = '#10b981';
                messages.appendChild(botMsg);
                messages.scrollTop = messages.scrollHeight;
            }, 800);
        });

        inputContainer.appendChild(input);
        inputContainer.appendChild(sendBtn);
        chatWindow.appendChild(inputContainer);
        document.body.appendChild(chatWindow);

        // Event listeners
        acceptBtn.addEventListener('click', () => {
            chatPopup.remove();
            chatWindow.style.display = 'flex';
        });

        document.getElementById('close-chat').addEventListener('click', () => {
            chatWindow.style.display = 'none';
            createFloatingIcon();
        });

        // Floating chat icon when minimized
        function createFloatingIcon() {
            if (document.getElementById('chat-icon')) return;
            const icon = document.createElement('div');
            icon.id = 'chat-icon';
            icon.style.position = 'fixed';
            icon.style.bottom = '20px';
            icon.style.right = '20px';
            icon.style.background = '#10b981';
            icon.style.width = '60px';
            icon.style.height = '60px';
            icon.style.borderRadius = '50%';
            icon.style.display = 'flex';
            icon.style.justifyContent = 'center';
            icon.style.alignItems = 'center';
            icon.style.color = 'white';
            icon.style.fontSize = '1.8rem';
            icon.style.cursor = 'pointer';
            icon.style.boxShadow = '0 5px 15px rgba(0,0,0,0.3)';
            icon.innerHTML = '<i class="bi bi-chat"></i>'; // Bootstrap icon
            icon.addEventListener('click', () => {
                chatWindow.style.display = 'flex';
                icon.remove();
            });
            document.body.appendChild(icon);
        }

    }, delay);

}

(function() {
    // Only run if not already shown in this session
    if (sessionStorage.getItem('donationPopupShown')) return;

    // Create the main popup container
    const popup = document.createElement('div');
    popup.id = 'donation-popup';
    popup.style.position = 'fixed';
    popup.style.top = '50%';
    popup.style.left = '50%';
    popup.style.transform = 'translate(-50%, -50%)';
    popup.style.zIndex = '9999';
    popup.style.background = 'white';
    popup.style.borderRadius = '12px';
    popup.style.padding = '20px';
    popup.style.width = '90%';
    popup.style.maxWidth = '600px';
    popup.style.boxShadow = '0 10px 25px rgba(0,0,0,0.3)';
    popup.style.animation = 'fadeIn 0.5s ease';
    popup.style.textAlign = 'center';

    // Close button
    const closeBtn = document.createElement('button');
    closeBtn.innerHTML = '✖';
    closeBtn.style.position = 'absolute';
    closeBtn.style.top = '10px';
    closeBtn.style.right = '10px';
    closeBtn.style.border = 'none';
    closeBtn.style.background = 'transparent';
    closeBtn.style.fontSize = '1.5rem';
    closeBtn.style.cursor = 'pointer';
    closeBtn.addEventListener('click', () => {
        popup.remove();
    });
    popup.appendChild(closeBtn);

    // Title
    const title = document.createElement('h2');
    title.innerText = 'Support AMUCTA Today!';
    title.style.fontSize = '1.8rem';
    title.style.color = '#16a34a';
    title.style.marginBottom = '10px';
    popup.appendChild(title);

    // Text
    const text = document.createElement('p');
    text.innerText = 'Help us provide quality education and support for students with special needs and local entrepreneurship programs.';
    text.style.fontSize = '1rem';
    text.style.color = '#374151';
    text.style.marginBottom = '15px';
    popup.appendChild(text);

    // Image grid
    const grid = document.createElement('div');
    grid.style.display = 'grid';
    grid.style.gridTemplateColumns = '1fr 1fr';
    grid.style.gap = '10px';
    grid.style.marginBottom = '15px';

    const images = [
        { src: 'https://data.tetea.store/images/gallery/gallery_68c6dfbec41c8.webp', caption: 'Supporting Students with Special Needs' },
        { src: 'https://data.tetea.store/images/gallery/gallery_68c6ded4bf322.webp', caption: 'Supporting Local Business and Entrepreneurship' }
    ];

    images.forEach(imgData => {
        const card = document.createElement('div');
        card.style.borderRadius = '8px';
        card.style.overflow = 'hidden';
        card.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';

        const img = document.createElement('img');
        img.src = imgData.src;
        img.alt = imgData.caption;
        img.style.width = '100%';
        img.style.height = '150px';
        img.style.objectFit = 'cover';
        card.appendChild(img);

        const caption = document.createElement('div');
        caption.innerText = imgData.caption;
        caption.style.padding = '5px';
        caption.style.textAlign = 'center';
        caption.style.fontWeight = '600';
        caption.style.color = '#16a34a';
        card.appendChild(caption);

        grid.appendChild(card);
    });

    popup.appendChild(grid);

    // Donate button
    const donateBtn = document.createElement('a');
    donateBtn.href = '/donation/donate';
    donateBtn.innerText = '💖 Donate Now';
    donateBtn.style.display = 'inline-block';
    donateBtn.style.background = '#10b981';
    donateBtn.style.color = 'white';
    donateBtn.style.padding = '12px 25px';
    donateBtn.style.borderRadius = '8px';
    donateBtn.style.fontSize = '1.1rem';
    donateBtn.style.fontWeight = 'bold';
    donateBtn.style.textDecoration = 'none';
    donateBtn.style.transition = 'background 0.3s';
    donateBtn.addEventListener('mouseover', () => donateBtn.style.background = '#059669');
    donateBtn.addEventListener('mouseout', () => donateBtn.style.background = '#10b981');

    popup.appendChild(donateBtn);

    // Add to DOM after a random delay (5-15 seconds)
    const delay = Math.floor(Math.random() * (15000 - 5000 + 1)) + 5000;
    setTimeout(() => {
        document.body.appendChild(popup);
        sessionStorage.setItem('donationPopupShown', 'true');
    }, delay);

    // Fade-in animation
    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes fadeIn {
            from { opacity: 0; transform: translate(-50%, -55%); }
            to { opacity: 1; transform: translate(-50%, -50%); }
        }
    `;
    document.head.appendChild(style);

})();
document.addEventListener("DOMContentLoaded", () => {
    const chatWindow = document.getElementById("chat-window");
    const chatIcon = document.getElementById("chat-icon");
    const closeBtn = document.getElementById("chat-close-btn");
    const input = document.getElementById("chat-input");
    const csrf_token = document.getElementById("csrf_token");
    const sendBtn = document.getElementById("chat-send-btn");
    const chatBody = document.querySelector(".chat-body");

    // Toggle chat window
    chatIcon.addEventListener("click", () => {
        chatWindow.classList.toggle("chat-closed");
    });
    closeBtn.addEventListener("click", () => {
        chatWindow.classList.add("chat-closed");
    });

    sendBtn.addEventListener("click", () => {
        const msg = input.value.trim();
        const csrf = csrf_token.value.trim();
        if (!msg || !csrf) return;

        // Display user message
        const userMsg = document.createElement("div");
        userMsg.className = "chat-message user";
        userMsg.textContent = msg;
        chatBody.appendChild(userMsg);

        input.value = "";
        chatBody.scrollTop = chatBody.scrollHeight;

        // Show "typing..." placeholder
        const typingMsg = document.createElement("div");
        typingMsg.className = "chat-message bot typing";
        typingMsg.textContent = "";
        chatBody.appendChild(typingMsg);
        chatBody.scrollTop = chatBody.scrollHeight;

        // Send to backend
        fetch("/bot", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                message: msg,
                csrf_token: csrf
            })
        })
            .then(res => res.json())
            .then(data => {
                // Keep "typing..." visible for 5s
                setTimeout(() => {
                    typingMsg.remove(); // remove typing indicator
                    const botMsg = document.createElement("div");
                    botMsg.className = "chat-message bot";
                    botMsg.textContent = data.reply || "⚠️ No reply from server";
                    chatBody.appendChild(botMsg);
                    chatBody.scrollTop = chatBody.scrollHeight;
                }, 5000);
            })
            .catch(err => {
                typingMsg.remove();
                const botMsg = document.createElement("div");
                botMsg.className = "chat-message bot error";
                botMsg.textContent = "❌ Error: Unknown error occurred";
                chatBody.appendChild(botMsg);
                chatBody.scrollTop = chatBody.scrollHeight;
            });
    });


});

window.addEventListener('scroll', function() {
    const header = document.getElementById('header');
    if (window.scrollY > 100) { // Trigger after scrolling 100px
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});
document.addEventListener('DOMContentLoaded', function() {
    const mobileBtn = document.getElementById('mobileMenuBtn');
    const nav = document.getElementById('navigation-container');
    const navClose = document.getElementById('menu-close');

    if (mobileBtn && nav) {
        mobileBtn.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent event bubbling
            nav.classList.toggle('active');
            mobileBtn.classList.toggle('active'); // Rotate icon
        });
    }
    if (navClose && nav) {
        navClose.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent event bubbling
            nav.classList.remove('active');
            mobileBtn.classList.remove('active');
        });
    }

    const mobileMainLinks = nav.querySelectorAll('.mobile-main-link');
    mobileMainLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const parentLi = this.parentElement;
            const allDropdowns = nav.querySelectorAll('.dropdown-container');
            allDropdowns.forEach(li => li.classList.remove('active'));
            parentLi.classList.toggle('active');
        });
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (mobileBtn && nav && !e.target.closest('header') && !e.target.closest('.navigation-container')) {
            nav.classList.remove('active');
            mobileBtn.classList.remove('active');
            // Close all submenus
            const allDropdowns = nav.querySelectorAll('.dropdown-container');
            allDropdowns.forEach(li => li.classList.remove('active'));
        }
    });

});



