const chatWindow = document.getElementById("chat-window");
function goto(url) {
    if (window.location.pathname !== url) {
        window.location.href = url
    }
}
(function chatbot() {
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
        // Event listeners
        acceptBtn.addEventListener('click', () => {
            chatPopup.remove();
            chatWindow.classList.toggle("chat-closed");
        });


    }, delay);

})();
document.addEventListener("DOMContentLoaded", () => {
    const slider = document.getElementById("background-slider");
    let images = [];
    let currentIndex = 0;
    let preloaded = [];

    // Use IntersectionObserver to only start when visible (optional optimization)
    const observer = new IntersectionObserver(entries => {
        if (entries[0].isIntersecting) {
            startSlider();
            observer.disconnect();
        }
    });
    observer.observe(slider);

    function startSlider() {
        fetch("/gallery/background?save_visitor=0")
            .then(res => res.json())
            .then(data => {
                images = data.images || [];
                if (images.length === 0) return;

                // Preload first few images only
                preloadImages(images.slice(0, 3));

                setBackground(images[0]);

                // Use setTimeout (not setInterval) to avoid overlap
                scheduleNext();
            })
            .catch(err => console.error("Failed to load images:", err));
    }

    function preloadImages(list) {
        list.forEach(url => {
            const img = new Image();
            img.src = url;
            preloaded.push(img);
        });
    }

    function setBackground(url) {
        if (slider) {
            slider.style.transition = "background-image 1s ease-in-out";
            slider.style.backgroundSize = "cover";
            slider.style.backgroundPosition = "center";
            slider.style.backgroundImage = `url('${url}')`;
        }
    }

    function scheduleNext() {
        setTimeout(() => {
            currentIndex = (currentIndex + 1) % images.length;
            const nextIndex = (currentIndex + 1) % images.length;

            // Preload next image (if not already)
            if (!preloaded[nextIndex]) {
                const img = new Image();
                img.src = images[nextIndex];
                preloaded[nextIndex] = img;
            }

            setBackground(images[currentIndex]);
            scheduleNext();
        }, 15000); // change every 15s
    }
});

/*document.addEventListener("DOMContentLoaded", () => {
    const slider = document.getElementById("background-slider");
    let images = [];
    let currentIndex = 0;

    // Fetch image list from server (JSON format)
    fetch("/gallery/background?save_visitor=0") // example endpoint
        .then(res => res.json())
        .then(data => {
            images = data.images;
            if (images.length > 0) {
                setBackground(images[0]); // first image
                setInterval(() => {
                    currentIndex = (currentIndex + 1) % images.length;
                    setBackground(images[currentIndex]);
                }, 15000); // change every 5s
            }
        })

        .catch(err => console.error("Failed to load images:", err));

    function setBackground(url) {
        if (slider){
            slider.style.backgroundImage = `url('${url}')`;
        }
    }
});*/

document.addEventListener("DOMContentLoaded", () => {
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
        fetch("/bot?save_visitor=0", {
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
                    botMsg.textContent = data.reply || "Habari! Wahudumu wetu watakujibu hivi punde. Unaweza kutuandikia swali lako kupitia https://amucta.ac.tz/contact";
                    chatBody.appendChild(botMsg);
                    chatBody.scrollTop = chatBody.scrollHeight;
                }, 5000);
            })
            .catch(err => {
                typingMsg.remove();
                const botMsg = document.createElement("div");
                botMsg.className = "chat-message bot error";
                botMsg.textContent = "Habari! Wahudumu wetu watakujibu hivi punde. Unaweza kutuandikia swali lako kupitia https://amucta.ac.tz/contact";
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



