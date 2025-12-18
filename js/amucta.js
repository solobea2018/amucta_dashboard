function previewImage(url, description) {
    document.addEventListener("keydown", handleKeyDown);

    const overlay = document.createElement("div");
    overlay.id = "overlay";
    overlay.style.position = "fixed";
    overlay.style.top = "0";
    overlay.style.left = "0";
    overlay.style.width = "100%";
    overlay.style.height = "100%";
    overlay.style.background = "rgba(0,0,0,0.6)";
    overlay.style.display = "flex";
    overlay.style.alignItems = "center";
    overlay.style.justifyContent = "center";
    overlay.style.zIndex = "9999";

    const popup = document.createElement("div");
    popup.style.width = "80%";
    popup.style.height = "80%";
    popup.style.background = "#fff";
    popup.style.borderRadius = "8px";
    popup.style.display = "flex";
    popup.style.flexDirection = "column";
    popup.style.overflow = "hidden";
    popup.style.boxShadow = "0 5px 20px rgba(0,0,0,0.3)";

    const header = document.createElement("div");
    header.style.background = "#f5f5f5";
    header.style.padding = "10px";
    header.style.display = "flex";
    header.style.justifyContent = "space-between";
    header.style.alignItems = "center";
    header.style.borderBottom = "1px solid #ddd";

    const title = document.createElement("span");
    title.textContent = "Image Preview";
    title.style.fontWeight = "bold";

    const closeBtn = document.createElement("button");
    closeBtn.innerHTML = "✖";
    closeBtn.style.border = "none";
    closeBtn.style.background = "transparent";
    closeBtn.style.fontSize = "18px";
    closeBtn.style.cursor = "pointer";
    closeBtn.onclick = () => overlay.remove();

    header.appendChild(title);
    header.appendChild(closeBtn);

    const body = document.createElement("div");
    body.style.flex = "1";
    body.style.display = "flex";
    body.style.flexDirection = "column";
    body.style.alignItems = "center";
    body.style.justifyContent = "center";
    body.style.padding = "15px";
    body.style.overflow = "auto";

    const img = document.createElement("img");
    img.src = url;
    img.style.maxWidth = "100%";
    img.style.maxHeight = "100%";
    img.style.borderRadius = "6px";
    img.style.objectFit = "contain";

    const desc = document.createElement("p");
    desc.textContent = description || "";
    desc.style.marginTop = "10px";
    desc.style.fontSize = "14px";
    desc.style.color = "#333";
    desc.style.textAlign = "center";

    body.appendChild(img);
    body.appendChild(desc);

    popup.appendChild(header);
    popup.appendChild(body);
    overlay.appendChild(popup);
    document.body.appendChild(overlay);
}
function sendFormSweet(form, event) {
    var popup = document.getElementById("overlay");
    if (!(popup===null)) {
        popup.remove();
    }
    event.preventDefault();

    // Show loading alert
    Swal.fire({
        title: "Processing...",
        text: "Please wait",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    var action = form.action;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", action);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            Swal.close(); // Close loading alert

            if (xhr.status === 200) {
                let resText = xhr.responseText.trim();
                let isJson = false;
                let data = null;

                try {
                    data = JSON.parse(resText);
                    isJson = true;
                } catch (e) {
                    isJson = false;
                }

                if (isJson && data.status === "success" && data.message) {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: data.message,
                        allowOutsideClick: false
                    });
                    form.reset();
                } else if (isJson && data.status === "error" && data.message) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: data.message,
                    });
                } else {
                    // Fallback if response isn't in expected JSON structure
                    Swal.fire({
                        icon: "success",
                        allowOutsideClick: false,
                        html: resText
                    });
                    form.reset();
                }
            }
            else if (xhr.status === 300 || xhr.status === 301) {
                window.location.href = xhr.responseText;
            }
            else {
                let msg = xhr.responseText;
                try {
                    let err = JSON.parse(msg);
                    msg = err.message || msg;
                } catch (e) {}
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    html: msg
                });
            }
        }
    };

    xhr.onerror = function () {
        Swal.close();
        Swal.fire({
            icon: "error",
            title: "Network Error",
            text: "Something went wrong. Please try again."
        });
    };

    xhr.send(new FormData(form));
}
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert("Copied!");
    });
}
function createNotification(type, clickDismiss, duration, message) {
    // Create the notification element
    var notification = document.createElement('div');
    notification.style.position = 'fixed';
    notification.style.top = '80px';
    notification.style.right = '20px';
    notification.style.padding = '15px';
    notification.style.borderRadius = '5px';
    notification.style.color = 'white';
    notification.style.zIndex = '10000'; // Ensure it's on top of other elements
    notification.style.transition = 'opacity 0.5s ease'; // Smooth transition for fading out
    // Set the background color based on notification type
    if (type === 'success') {
        notification.style.backgroundColor = 'green'; // Success color
    } else if (type === 'failed') {
        notification.style.backgroundColor = 'red'; // Failure color
    }
    // Set the notification text
    notification.innerText = message;
    // Append the notification to the body
    document.body.appendChild(notification);
    // Function to dismiss the notification
    function dismissNotification() {
        notification.style.opacity = '0'; // Fade out
        setTimeout(function () {
            notification.remove(); // Remove from DOM after fade out
        }, 500); // Wait for transition duration before removing
    }
    // Dismiss notification on click if clickDismiss is true
    if (clickDismiss) {
        notification.addEventListener('click', dismissNotification);
    } else {
        // If not on click, set a timeout to dismiss automatically
        setTimeout(dismissNotification, duration);
    }
}

function handleKeyDown(event){
    if (event.key === 'Escape') {
        var popup = document.getElementById("overlay");
        if (!(popup===null)) {
            popup.remove();
        }
        document.removeEventListener('keydown', handleKeyDown);
    }
}
function popHtml(title, html){
    document.addEventListener("keydown",handleKeyDown);
    const overlay = document.createElement("div");
    overlay.id="overlay";
    overlay.className = "popup-overlay";

    overlay.innerHTML = `
    <div class="popup-editor">
      <div class="popup-header">
        <span>${title}</span>
        <button onclick="this.closest('.popup-overlay').remove()">✖</button>
      </div>
       
       <div class="popup-body">
            ${html}
        </div> 
        <div class="popup-footer">
          
        </div>
    </div>
  `;

    document.body.appendChild(overlay);
}
function previewHtml(content) {
    // Create overlay
    document.addEventListener("keydown",handleKeyDown)
    const overlay = document.createElement("div");
    overlay.className = "popup-overlay";
    overlay.id="overlay";
    overlay.style.position = "fixed";
    overlay.style.top = "0";
    overlay.style.left = "0";
    overlay.style.width = "100%";
    overlay.style.height = "100%";
    overlay.style.background = "rgba(0,0,0,0.6)";
    overlay.style.display = "flex";
    overlay.style.alignItems = "center";
    overlay.style.justifyContent = "center";
    overlay.style.zIndex = "9999";

    // Create popup container
    const popup = document.createElement("div");
    popup.className = "popup-preview";
    popup.style.width = "80%";
    popup.style.height = "80%";
    popup.style.background = "#fff";
    popup.style.borderRadius = "8px";
    popup.style.overflow = "hidden";
    popup.style.display = "flex";
    popup.style.flexDirection = "column";
    popup.style.boxShadow = "0 5px 20px rgba(0,0,0,0.3)";

    // Header with close button
    const header = document.createElement("div");
    header.style.background = "#f5f5f5";
    header.style.padding = "10px";
    header.style.display = "flex";
    header.style.justifyContent = "space-between";
    header.style.alignItems = "center";
    header.style.borderBottom = "1px solid #ddd";
    header.innerHTML = `<span style="font-weight:bold">Preview</span>`;

    const closeBtn = document.createElement("button");
    closeBtn.innerHTML = "✖";
    closeBtn.style.border = "none";
    closeBtn.style.background = "transparent";
    closeBtn.style.fontSize = "18px";
    closeBtn.style.cursor = "pointer";
    closeBtn.onclick = () => overlay.remove();

    header.appendChild(closeBtn);

    // Iframe to render content
    const iframe = document.createElement("iframe");
    iframe.style.flex = "1";
    iframe.style.width = "100%";
    iframe.style.border = "none";

    // Insert content inside iframe
    iframe.onload = () => {
        iframe.contentDocument.open();
        iframe.contentDocument.write(content);
        iframe.contentDocument.close();
    };

    popup.appendChild(header);
    popup.appendChild(iframe);
    overlay.appendChild(popup);
    document.body.appendChild(overlay);
}