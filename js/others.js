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
                Swal.fire({
                    icon: "success",
                    allowOutsideClick: false,
                    html: xhr.responseText
                });
                form.reset();
            }else if (xhr.status===300 || xhr.status===301){
                window.location.href=xhr.responseText;
            }
            else {
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

function addEmployee() {
    var employee_form = `
    <form class="form-container" onsubmit="sendFormSweet(this,event)" action="/employee/add">

      <input type="hidden" name="id" value="">

      <div class="form-group">
        <label for="name">Name</label>
        <input type="text"
               id="name"
               name="name"
               class="form-control"
               placeholder="Enter full name"
               required>
      </div>

      <div class="form-group">
        <label for="title">Title</label>
        <input type="text"
               id="title"
               name="title"
               class="form-control"
               placeholder="Enter job title">
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email"
               id="email"
               name="email"
               class="form-control"
               placeholder="Enter email address">
      </div>

      <div class="form-group">
        <label for="phone">Phone</label>
        <input type="text"
               id="phone"
               name="phone"
               class="form-control"
               placeholder="Enter phone number">
      </div>

      <div class="form-group">
        <label for="profile">Profile</label>
        <input type="file"
               accept="image/*"
               id="profile"
               name="image"
               class="form-control">
      </div>

      <div class="form-group">
        <label for="qualification">Qualification</label>
        <textarea id="qualification"
                  name="qualification"
                  class="form-control"
                  rows="3"
                  placeholder="Enter qualifications"></textarea>
      </div>

      <div class="form-group">
        <label for="entry_year">Entry Year</label>
        <input type="text"
               id="entry_year"
               name="entry_year"
               class="form-control"
               placeholder="e.g., 2024">
      </div>

      <div class="form-group">
        <label for="active">Active</label>
        <select id="active"
                name="active"
                class="form-control">
          <option value="1">Yes</option>
          <option value="0">No</option>
        </select>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary">💾 Save</button>
      </div>
    </form>
    `;
    popHtml("Add Employee", employee_form);
}
function addFaculty() {
    var faculty_form = `
    <form class="form-container" onsubmit="sendFormSweet(this,event)" action="/faculty/add">

      <input type="hidden" name="id" value="">
      <input type="hidden" name="user_id" value="">

      <div class="form-group">
        <label for="name">Faculty Name</label>
        <input type="text" 
               id="name" 
               name="name" 
               class="form-control" 
               placeholder="Enter faculty name" 
               required>
      </div>

      <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" 
                  name="description" 
                  class="form-control" 
                  rows="4" 
                  placeholder="Enter description"></textarea>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary">💾 Save</button>
      </div>
    </form>
    `;
    popHtml("Add Faculty", faculty_form);
}
function addLevel(){
    var level_form=`
    <form class="form-container" onsubmit="sendFormSweet(this,event)" action="/level/add">

  <input type="hidden" name="id" value="">

  <div class="form-group">
    <label for="name">Name</label>
    <input type="text" 
           id="name" 
           name="name" 
           class="form-control" 
           placeholder="Enter name" 
           required>
  </div>

  <div class="form-group">
    <label for="description">Description</label>
    <textarea id="description" 
              name="description" 
              class="form-control" 
              rows="4" 
              placeholder="Enter description"></textarea>
  </div>

  <div class="form-group">
  <button type="submit" class="btn btn-primary">💾 Save</button>
</div>
</form>

    `;
    popHtml("Add Level",level_form);
}
function addProgram(){
    var form=  `
    <form onsubmit="sendFormSweet(this,event)" action="/program/add">

  <input type="hidden" name="id" value="">
  <input type="hidden" name="user_id" value="{{auth_user_id}}">

  <!-- Program Name -->
  <div class="form-group">
    <label>Program Name</label>
    <input type="text" name="name" class="form-control" required 
           placeholder="Bachelor of Computer Engineering">
  </div>

  <!-- Short Name -->
  <div class="form-group">
    <label>Short Name</label>
    <input type="text" name="short_name" class="form-control" required 
           placeholder="BCE">
  </div>

  <!-- Intakes -->
  <div class="form-group">
    <label>Intakes (per year)</label>
    <input type="number" name="intakes" min="1" class="form-control" required 
           placeholder="2">
  </div>

  <!-- Duration -->
  <div class="form-group">
    <label>Duration</label>
    <input type="text" name="duration" class="form-control" required 
           placeholder="3 Years">
  </div>

  <!-- Capacity -->
  <div class="form-group">
    <label>Capacity</label>
    <input type="number" step="0.01" name="capacity" class="form-control" required 
           placeholder="120">
  </div>

  <!-- Accreditation Year -->
  <div class="form-group">
    <label>Accreditation Year</label>
    <input type="text" name="accreditation_year" class="form-control" required 
           placeholder="2024">
  </div>

  <!-- Faculty -->
  <div class="form-group">
    <label>Faculty</label>
    <select name="faculty_id" class="form-control" required>
      <option value="">-- Select Faculty --</option>
      <!-- Fill dynamically -->
    </select>
  </div>

  <!-- Department -->
  <div class="form-group">
    <label>Department</label>
    <select name="department_id" class="form-control" required>
      <option value="">-- Select Department --</option>
      <!-- Fill dynamically -->
    </select>
  </div>

  <!-- Level -->
  <div class="form-group">
    <label>Level</label>
    <select name="level_id" class="form-control" required>
      <option value="">-- Select Level --</option>
      <!-- e.g. Certificate, Diploma, Bachelor -->
    </select>
  </div>

  <!-- Description -->
  <div class="form-group">
    <label>Description</label>
    <textarea name="description" rows="3" class="form-control" required 
              placeholder="Brief overview of the program"></textarea>
  </div>

  <!-- Content -->
  <div class="form-group">
    <label>Content</label>
    <textarea name="content" rows="5" class="form-control" required 
              placeholder="Detailed program curriculum and info"></textarea>
  </div>

  <div class="form-group">
    <button type="submit" class="btn btn-primary">💾 Save</button>
  </div>
</form>


`;
    popHtml("Add Program",form);
}
function addDepartment() {
    var dept_form = `
    <form class="form-container" onsubmit="sendFormSweet(this,event)" action="/department/add">

      <input type="hidden" name="id" value="">
      <input type="hidden" name="user_id" value="">

      <div class="form-group">
        <label for="name">Department Name</label>
        <input type="text" 
               id="name" 
               name="name" 
               class="form-control" 
               placeholder="Enter department name" 
               required>
      </div>

      <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" 
                  name="description" 
                  class="form-control" 
                  rows="4" 
                  placeholder="Enter description"></textarea>
      </div>

      <div class="form-group">
        <label for="faculty_id">Faculty</label>
        <select id="faculty_id" 
                name="faculty_id" 
                class="form-control" 
                required>
          <option value="">-- Select Faculty --</option>
          <!-- dynamically load faculties here -->
        </select>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary">💾 Save</button>
      </div>
    </form>
    `;
    popHtml("Add Department", dept_form);
}
function addImage() {
    var image_form = `
    <form class="form-container" onsubmit="sendFormSweet(this,event)" action="/gallery/add">

      <input type="hidden" name="id" value="">
      <input type="hidden" name="user_id" value="">

      <div class="form-group">
        <label for="name">Image Name</label>
        <input type="text" 
               id="name" 
               name="name" 
               class="form-control" 
               placeholder="Enter image name" 
               required>
      </div>

      <div class="form-group">
        <label for="category">Category</label>
        <select id="category" 
                name="category" 
                class="form-control" 
                required>
          <option value="">-- Select Category --</option>
          <option value="gallery">Gallery</option>
          <option value="slides">Slides</option>
          <option value="news">News</option>
          <option value="logo">Logo</option>
          <option value="icon">Icon</option>
          <option value="profile">Profile</option>
        </select>
      </div>

      <div class="form-group">
        <label for="url">Image URL</label>
        <input type="text" 
               id="url" 
               name="url" 
               class="form-control" 
               placeholder="Enter image URL" 
               required>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary">💾 Save</button>
      </div>
    </form>
    `;
    popHtml("Add Image", image_form);
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
function addNews() {
    var news_form = `
    <form class="form-container" onsubmit="sendFormSweet(this,event)" action="/news/add">

      <input type="hidden" name="id" value="">
      <input type="hidden" name="user_id" value="">

      <div class="form-group">
        <label for="name">News Title</label>
        <input type="text" 
               id="name" 
               name="name" 
               class="form-control" 
               placeholder="Enter news title" 
               required>
      </div>

      <div class="form-group">
        <label for="feature_image">Feature Image</label>
        <input type="file" 
               accept="image/*"
               id="feature_image" 
               name="feature_image" 
               class="form-control">
      </div>

      <div class="form-group">
        <label for="category">Category</label>
        <input type="text" 
               id="category" 
               name="category" 
               class="form-control" 
               placeholder="Enter news category">
      </div>
      <div class="form-group">
        <label for="expire">Expire Date</label>
        <input type="date" 
               id="expire" 
               name="expire" 
               class="form-control" 
               placeholder="Enter Date">
      </div>

      <div class="form-group">
        <label for="content">Content</label>
        <textarea id="content" 
                  name="content" 
                  class="form-control" 
                  rows="5" 
                  placeholder="Enter news content" 
                  required></textarea>
      </div>

      <div class="form-group">
        <label for="attachment">Attachment URL (if any)</label>
        <input type="file" 
               accept="application/pdf"
               id="attachment" 
               name="attachment" 
               class="form-control" 
               placeholder="Enter attachment URL">
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary">💾 Save</button>
      </div>
    </form>
    `;
    popHtml("Add News", news_form);
}
function addAttachment() {
    var attachment_form = `
    <form class="form-container" onsubmit="sendFormSweet(this,event)" action="/attachments/add">

      <input type="hidden" name="id" value="">
      <input type="hidden" name="user_id" value="">

      <div class="form-group">
        <label for="name">Attachment Name</label>
        <input type="text" 
               id="name" 
               name="name" 
               class="form-control" 
               placeholder="Enter attachment name" 
               required>
      </div>

      <div class="form-group">
        <label for="file_url">File</label>
        <input type="file" 
               id="file_url" 
               name="file_url" 
               class="form-control"
               accept="application/pdf"
               required>
      </div>

      <div class="form-group">
        <label for="type">Attachment Type</label>
        <input type="text" 
               id="type" 
               name="type" 
               class="form-control" 
               placeholder="Enter category">
      </div>

      <div class="form-group">
        <label for="related_to">Related To</label>
        <input type="text" 
               id="related_to" 
               name="related_to" 
               class="form-control" 
               placeholder="Enter related module e.g., news, program">
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary">💾 Save</button>
      </div>

    </form>
    `;
    popHtml("Add Attachment", attachment_form);
}
function addEvent() {
    var event_form = `
    <form class="form-container" onsubmit="sendFormSweet(this,event)" action="/events/add">

      <input type="hidden" name="id" value="">
      <input type="hidden" name="user_id" value="">

      <div class="form-group">
        <label for="name">Event Name</label>
        <input type="text" id="name" name="name" class="form-control" placeholder="Enter event name" required>
      </div>

      <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" class="form-control" rows="4" placeholder="Enter event description"></textarea>
      </div>

      <div class="form-group">
        <label for="start_date">Start Date</label>
        <input type="date" id="start_date" name="start_date" class="form-control" required>
      </div>

      <div class="form-group">
        <label for="end_date">End Date</label>
        <input type="date" id="end_date" name="end_date" class="form-control">
      </div>

      <div class="form-group">
        <label for="location">Location</label>
        <input type="text" id="location" name="location" class="form-control" placeholder="Enter event location">
      </div>

      <div class="form-group">
        <label for="feature_image">Feature Image</label>
        <input type="file" accept="image/*" id="feature_image" name="feature_image" class="form-control">
      </div>

      <div class="form-group">
        <label for="attachment">Attachment</label>
        <input type="file" accept="application/pdf" id="attachment" name="attachment" class="form-control" placeholder="Enter attachment">
      </div>

      <div class="form-group">
        <label for="category">Category</label>
        <input type="text" id="category" name="category" class="form-control" placeholder="Enter event category">
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary">💾 Save</button>
      </div>

    </form>
    `;
    popHtml("Add Event", event_form);
}
function addUser() {
    var user_form = `
    <form class="form-container" onsubmit="sendFormSweet(this,event)" action="/users/add">

      <input type="hidden" name="id" value="">

      <div class="form-group">
        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name" class="form-control" placeholder="Enter full name" required>
      </div>

      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" class="form-control" placeholder="Enter username" required>
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Enter email" required>
      </div>

      <div class="form-group">
        <label for="phone_number">Phone Number</label>
        <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="Enter phone number">
      </div>

      <div class="form-group">
        <label for="role">Role</label>
        <select id="role" name="role" class="form-control" required>
          <option value="">-- Select Role --</option>
          <option value="manager">Manager</option>
          <option value="admin">Admin</option>
          <option value="user" selected>User</option>
        </select>
      </div>

      <div class="form-group">
        <label for="profile_url">Profile Image</label>
        <input type="file" accept="image/*" id="profile_url" name="image" class="form-control">
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary">💾 Save</button>
      </div>

    </form>
    `;
    popHtml("Add User", user_form);
}

