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

function sendFormSweet(form, event) {
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
                Swal.fire({
                    icon: "success",
                    allowOutsideClick: false,
                    html: xhr.responseText
                });
                form.reset();
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    html: xhr.responseText
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
function editContent(id) {
    // Fetch HTML (your function getHtml)
    var content = getHtml("/notes/detail?notes_id=" + id);

    // Create popup elements
    const overlay = document.createElement("div");
    overlay.className = "popup-overlay";

    overlay.innerHTML = `
    <div class="popup-editor">
      <div class="popup-header">
        <span>Edit Note</span>
        <button onclick="this.closest('.popup-overlay').remove()">✖</button>
      </div>
      <form class="flex flex-col h-full overflow-y-auto" 
            style="flex:1;" 
            onsubmit="sendFormSweet(this,event)" 
            action="/api/update_notes">

        <input type="hidden" name="id" value="${id}">
        
        <div class="popup-body">
          <label class="block text-sm font-medium text-gray-700 mb-2">Feature Image</label>
          <input type="text" 
                 name="topic_image"                
                 placeholder="https://revcad1.com/images/image_name.png"
                 style="margin-bottom:10px; padding:8px; border:1px solid #ddd; border-radius:6px; width:100%">
          
          <textarea name="content">${content}</textarea>
        </div>
        
        <div class="popup-footer">
          <button type="submit" class="cursor-pointer">💾 Save</button>
        </div>
      </form>
    </div>
  `;

    document.body.appendChild(overlay);
}