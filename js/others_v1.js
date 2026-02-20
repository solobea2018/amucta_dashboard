function viewEmployee(id) {
    fetch(`/employee/get_employee/${id}`)
        .then((res) => res.json())
        .then((res) => {
                if (!res) {
                    alert("Employee not found!");
                    return;
                }
                console.log(res)
                if (res.status!=="success"){

                    Swal.fire("Error","Unknown server error","error");
                    return;
                }
                var emp=res.data;
                var roles=res.roles;
                const overlay = document.createElement("div");
                overlay.id = "overlay";
                overlay.className = "popup-overlay";

                overlay.innerHTML = `
        <div class="popup-editor">
          <div class="popup-header">
            <span>Employee Details</span>
            <button onclick="this.closest('.popup-overlay').remove()">✖</button>
          </div>

          <div class="popup-body">
            <div class="bg-white shadow-md rounded-xl p-6 text-sm text-gray-800 space-y-3">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><span class="font-semibold text-gray-600">Name:</span> ${emp.name}</div>
                <div><span class="font-semibold text-gray-600">Title:</span> ${emp.title || "-"}</div>
                
                <div><span class="font-semibold text-gray-600">Email:</span> 
                  <a href="mailto:${emp.email}" class="text-blue-600 hover:underline">${emp.email || "-"}</a>
                </div>
                <div><span class="font-semibold text-gray-600">Phone:</span> 
                  <a href="tel:${emp.phone}" class="text-blue-600 hover:underline">${emp.phone || "-"}</a>
                </div>
                
                <div><span class="font-semibold text-gray-600">Qualification:</span> ${emp.qualification || "-"}</div>
                <div><span class="font-semibold text-gray-600">Entry Year:</span> ${emp.entry_year || "-"}</div>
                
                <div><span class="font-semibold text-gray-600">Department ID:</span> ${emp.department_id || "-"}</div>
                <div><span class="font-semibold text-gray-600">Branch:</span> ${emp.branch || "-"}</div>
                
                <div class="mt-3">
                  <span class="font-semibold text-gray-600">Roles:</span>
                  <div class="ml-4 space-y-1">
                    ${roles.map(r => `
                      <div class="flex items-center gap-3">
                        <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-md text-sm font-medium">
                          ${r.role_name}
                        </span>
                        <span class="text-gray-500 text-sm">(${r.start_date || "-"})</span>
                        <span onclick="updateEmpRole(${r.id},${r.active})" class="text-sm cursor-pointer ${r.active === 1 ? "text-green-600 font-semibold" : "text-red-500"}">
                          ${r.active === 1 ? "Active" : "Inactive"}
                        </span>
                      </div>
                    `).join("")}
                  </div>
                </div>

                
              <div><span class="font-semibold text-gray-600">CV:</span> 
                  ${emp.cv_url
                                ? `<a href="${emp.cv_url}" target="_blank" class="text-green-600 font-medium hover:underline">📄 Download CV</a>`
                                : "N/A"}
                </div>
            </div>

            <div class="pt-4 border-t">
            <span class="font-semibold text-gray-600 block mb-2">Profile:</span>
            ${emp.profile
                            ? `<img src="${emp.profile}" width="50px" height="50px" alt="Profile" class="rounded-lg shadow">`
                            : `<span class="text-gray-500">No image</span>`}
            </div>
</div>
          </div>

          <div class="popup-footer">
            <button onclick="this.closest('.popup-overlay').remove()" class="cursor-pointer">Close</button>
          </div>
        </div>
      `;

                document.body.appendChild(overlay);
            })

        .catch(err=>{
            console.error("Error loading employee:", err);
            alert("Could not load employee details.");
    });
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
    Promise.all([
        fetch("/level/get_simple").then(r => r.json()),
        fetch("/faculty/get_simple").then(r => r.json()),
        fetch("/department/get_simple").then(r => r.json())
    ])
        .then(([data1, data2, data3]) => {
            if (data1.status==="success" && data2.status==="success" && data3.status==="success"){
                const lvs = data1.data;
                const fcts = data2.data;
                const dps = data3.data;
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
      ${fcts.map(f => `<option value="${f.id}">${f.name}</option>`).join("")}
    </select>
  </div>

  <!-- Department -->
  <div class="form-group">
    <label>Department</label>
    <select name="department_id" class="form-control" required>
      <option value="">-- Select Department --</option>
      ${dps.map(f => `<option value="${f.id}">${f.name}</option>`).join("")}
    </select>
  </div>

  <!-- Level -->
  <div class="form-group">
    <label>Level</label>
    <select name="level_id" class="form-control" required>
      <option value="">-- Select Level --</option>
      ${lvs.map(f => `<option value="${f.id}">${f.name}</option>`).join("")}
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
    <label>Entry Requirements</label>
    <textarea name="requirements" rows="5" class="form-control" 
              placeholder="Detailed entry qualifications"></textarea>
  </div>
  <div class="form-group">
    <label>Fee structure</label>
    <textarea name="fees" rows="5" class="form-control" 
              placeholder="Detailed Fee structure"></textarea>
  </div>

  <div class="form-group">
    <button type="submit" class="btn btn-primary">💾 Save</button>
  </div>
</form>
`;
                popHtml("Add Program",form);
            }else{
                Swal.fire("Error!", "Something went wrong", "error");
            }
        })
        .catch(err => {
            Swal.fire("Error!", "Something went wrong", "error");
        });


}
function addHomeContent(){
    var form=  `
    <form onsubmit="sendFormSweet(this,event)" action="/home-content/add">
  <!-- Program Name -->
  <div class="form-group">
    <label>Name</label>
    <input type="text" name="name" class="form-control" required>
  </div>
  <div class="form-group">
    <label>Deadline</label>
    <input type="date" name="deadline" class="form-control" required>
  </div>
  <!-- Description -->
  <div class="form-group">
    <label>Content</label>
    <textarea name="content" rows="5" class="form-control" required 
              placeholder="<div>.....</div>"></textarea>
  </div>

  <!-- Content -->
  <div class="form-group">
    <label>Style</label>
    <textarea name="style" rows="5" class="form-control" 
              placeholder="<style> ...</style>"></textarea>
  </div>
  <div class="form-group">
    <button type="submit" class="btn btn-primary">💾 Save</button>
  </div>
</form>
`;
    popHtml("Add Program",form);
}
function addDepartment() {
    fetch('/faculty/get_simple')
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                var fcts=data.data;

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
                class="form-control">
          <option value="">-- Select Faculty --</option>
          ${fcts.map(f => `<option value="${f.id}">${f.name}</option>`).join("")}
          <!-- dynamically load faculties here -->
        </select>
      </div>
      <div class="form-group">
        <label for="category">Category</label>
        <select id="category" 
                name="category" 
                class="form-control" 
                required>
          <option value="">-- Select Category --</option>
          <option value="department">Department</option>
          <option value="unit">Unit</option>
        </select>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary">💾 Save</button>
      </div>
    </form>
    `;
                popHtml("Add Department/Unit", dept_form);
            } else {
                Swal.fire("Error!", data.message || "Something went wrong", "error");
            }
        })
        .catch(error => {
            Swal.fire("Error!", "Something went wrong", "error");
        });
}
function addImage() {
    var image_form = `
    <form class="form-container" enctype="multipart/form-data" onsubmit="sendFormSweet(this,event)" action="/gallery/add">

      <input type="hidden" name="id" value="">

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
       <label for="description">Description (Optional)</label>
       <textarea class="form-control" name="description" id="description"></textarea>
</div>

      <div class="form-group">
        <label for="image">Image file</label>
        <input type="file" 
               id="image" 
               name="image" 
               class="form-control"
               required>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary">💾 Save</button>
      </div>
    </form>
    `;
    popHtml("Add Image", image_form);
}
function addNews() {
    var news_form = `
    <form class="form-container" enctype="multipart/form-data" onsubmit="sendFormSweet(this,event)" action="/news/add">

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
        <label for="attachment">Attachment (if any)</label>
        <input type="file" 
               accept="application/pdf"
               id="attachment" 
               name="attachment" 
               class="form-control">
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
    <form class="form-container" enctype="multipart/form-data" onsubmit="sendFormSweet(this,event)" action="/attachment/add">

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
function addRole() {
    var role_form = `
    <form class="form-container" onsubmit="sendFormSweet(this,event)" action="/employee-role/add">

      <input type="hidden" name="id" value="">
      <input type="hidden" name="user_id" value="">

      <div class="form-group">
        <label for="name">Role Name</label>
        <input type="text" 
               id="name" 
               name="name" 
               class="form-control" 
               placeholder="Enter attachment name" 
               required>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary">💾 Save</button>
      </div>

    </form>
    `;
    popHtml("Add Role", role_form);
}
function addEvent() {
    var event_form = `
    <form class="form-container" enctype="multipart/form-data" onsubmit="sendFormSweet(this,event)" action="/events/add">

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
    <form class="form-container" enctype="multipart/form-data" onsubmit="sendFormSweet(this,event)" action="/users/add">

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
          <option value="admin" title="admin">Admin</option>
          <option value="hro" title="human resource management officer">HRMO</option>
          <option value="pro" title="public relation officer">PRO</option>
          <option value="hod" title="Head of department">HOD</option>
          <option value="user" selected title="normal user">User</option>
        </select>
      </div>

      <div class="form-group">
        <label for="profile_url">Profile Image</label>
        <input type="file" accept="image/*" id="profile_url" name="profile_url" class="form-control">
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary">💾 Save</button>
      </div>

    </form>
    `;
    popHtml("Add User", user_form);
}
function addAlumni() {
    var alumni_form = `
    <form class="form-container" enctype="multipart/form-data" onsubmit="sendFormSweet(this,event)" action="/alumni/save">

      <input type="hidden" name="id" value="">

      <div class="form-group">
        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name" class="form-control" placeholder="Enter full name" required>
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Enter email" required>
      </div>

      <div class="form-group">
        <label for="phone">Phone Number</label>
        <input type="text" id="phone" name="phone" class="form-control" placeholder="Enter phone number">
      </div>

      <div class="form-group">
        <label for="graduation_year">Graduation Year</label>
        <input type="number" id="graduation_year" name="graduation_year" class="form-control" placeholder="e.g. 2022">
      </div>

      <div class="form-group">
        <label for="course">Course Studied</label>
        <input type="text" id="course" name="course" class="form-control" placeholder="Enter your course/program">
      </div>

      <div class="form-group">
        <label for="employment_status">Employment Status</label>
        <select id="employment_status" name="employment_status" class="form-control">
          <option value="">-- Select Status --</option>
          <option value="employed">Employed</option>
          <option value="self-employed">Self-Employed</option>
          <option value="student">Student</option>
          <option value="unemployed">Unemployed</option>
        </select>
      </div>

      <div class="form-group">
        <label for="message">Message / Current Status</label>
        <textarea id="message" name="message" class="form-control" placeholder="Share your current status or achievements..."></textarea>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-success">🎓 Register</button>
      </div>

    </form>
    `;
    popHtml("Alumni Registration", alumni_form);
}
function filterAlumni() {
    let input = document.getElementById("alumniSearch").value.toLowerCase();
    let cards = document.getElementsByClassName("alumni-card");

    Array.from(cards).forEach(card => {
        let name = card.querySelector(".alumni-name").innerText.toLowerCase();
        let course = card.innerHTML.toLowerCase(); // includes course text
        card.style.display = (name.includes(input) || course.includes(input)) ? "block" : "none";
    });
}
function toggleActive(table, id) {
    fetch('/api.php?active=1', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            table: table,
            id: id,
        })
    })
        .then(response => response.json())
        .then(dataResponse => {
            if (dataResponse.status === "success") {
                Swal.fire("Updated!", dataResponse.message, "success").then(() => {
                    // Refresh the page
                    location.reload();
                });
            } else {
                Swal.fire("Error!", dataResponse.message || "Failed to update resource", "error");
            }
        })
        .catch(error => {
            console.error("Update error:", error);
            Swal.fire("Error!", "Something went wrong", "error");
        });
}
function editAlumni(id) {
    fetch(`/alumni/get/${id}`)
        .then(r => r.json())
        .then(data => {
            let alumni=data.data;
            let form = `
            <form class="form-container" onsubmit="sendFormSweet(this,event)" action="/alumni/update">
                <input type="hidden" name="id" value="${alumni.id}">
                
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" name="full_name" value="${alumni.full_name}" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" value="${alumni.email}" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" value="${alumni.phone}" class="form-control">
                </div>

                <div class="form-group">
                    <label for="course">Course</label>
                    <input type="text" name="course" value="${alumni.course}" class="form-control">
                </div>

                <div class="form-group">
                    <label for="graduation_year">Graduation Year</label>
                    <input type="number" name="graduation_year" value="${alumni.graduation_year}" class="form-control">
                </div>

                <div class="form-group">
                    <label for="employment_status">Employment Status</label>
                    <input type="text" name="employment_status" value="${alumni.employment_status}" class="form-control">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">💾 Update</button>
                </div>
            </form>`;

            popHtml("Edit Alumni", form);
        });
}
function editImage(id) {
    fetch(`/gallery/get/${id}`)
        .then(r => r.json())
        .then(data => {
            let alumni=data.data;
            let form = `
            <form class="form-container" onsubmit="sendFormSweet(this,event)" action="/gallery/add">
                <input type="hidden" name="id" value="${alumni.id}">
                
                <div class="form-group">
                    <label for="full_name">Name</label>
                    <input type="text" name="name" value="${alumni.name}" class="form-control" required>
                </div>
               
                <div class="form-group">
                    <label for="graduation_year">Description</label>
                    <textarea name="description" class="form-control" required>${alumni.description}</textarea>                    
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">💾 Update</button>
                </div>
            </form>`;

            popHtml("Edit Image", form);
        });
}
function createThumbNail(id) {
    fetch('/gallery/thumbnail', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: id
        })
    })
        /*  .then(rs=>{
            return rs.text()
        }).then(
        txt=>{
            console.log(txt)
        }
    )*/

        .then(response => response.json())
        .then(dataResponse => {
            if (dataResponse.status === "success") {
                Swal.fire("Updated!", dataResponse.message, "success");
            } else {
                Swal.fire("Error!", dataResponse.message || "Failed to update resource", "error");
            }
        })
        .catch(error => {
            console.error("Update error:", error);
            Swal.fire("Error!", "Something went wrong", "error");
        });
}
function reduceQuality(id){
    fetch('/gallery/quality', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: id
        })
    })
       /* .then(rs=>{
            return rs.text()
        }).then(
        txt=>{
            console.log(txt)
        }
    )*/
        .then(response => response.json())
        .then(dataResponse => {
            if (dataResponse.status === "success") {
                Swal.fire("Updated!", dataResponse.message, "success");
            } else {
                Swal.fire("Error!", dataResponse.message || "Failed to update resource", "error");
            }
        })
        .catch(error => {
            console.error("Update error:", error);
            Swal.fire("Error!","Something went wrong", "error");
        });
}
function deleteResource(table, id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        var popup = document.getElementById("overlay");
        if (!(popup===null)) {
            popup.remove();
        }
        if (result.isConfirmed) {
            fetch('/api/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    table: table,
                    id: id
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        Swal.fire("Deleted!", data.message, "success").then(() => {
                            location.reload(); // Refresh page after deletion
                        });
                    } else {
                        Swal.fire("Error!", data.message || "Failed to delete resource", "error");
                    }
                })
                .catch(error => {
                    console.error("Delete error:", error);
                    Swal.fire("Error!", "Something went wrong", "error");
                });
        }
    });
}
function updateEmpRole(id,active){
    var popup = document.getElementById("overlay");
    if (!(popup===null)) {
        popup.remove();
    }
    var status=active===1?0:1;
    updateResource('employee_role',id,{active:status})
}
function updateResource(table, id, data) {
    fetch('/api/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            table: table,
            id: id,
            data: data
        })
    })
        .then(response => response.json())
        .then(dataResponse => {
            if (dataResponse.status === "success") {
                Swal.fire("Updated!", dataResponse.message, "success").then(() => {
                    // Refresh the page
                    location.reload();
                });
            } else {
                Swal.fire("Error!", dataResponse.message || "Failed to update resource", "error");
            }
        })
        .catch(error => {
            console.error("Update error:", error);
            Swal.fire("Error!", "Something went wrong", "error");
        });
}
function addEmployeeRole(empId) {
    Promise.all([
        fetch("/employee-role/roles").then(r => r.json()),
        fetch("/department/departments").then(r => r.json()),
        fetch("/department/units").then(r => r.json())
    ])
        .then(([rolesData, deptsData, unitsData]) => {
            if (rolesData.status === "success" && deptsData.status === "success" && unitsData.status === "success") {
                const roles = rolesData.data;
                const departments = deptsData.data;
                const units = unitsData.data;

                const form = `
<form onsubmit="sendFormSweet(this,event)" action="/employee/add_role">

  <input type="hidden" name="employee_id" value="${empId}">
  <input type="hidden" name="id" value="">

  <!-- Role Title -->
    <div class="form-group">
        <label for="role_name">Role Name</label>
        <input type="text" id="role_name" name="role_name" class="form-control" placeholder="Enter name" required>
      </div>
    <div class="form-group">
    <label>Role Group</label>
    <select name="role_title" class="form-control" required>
      <option value="">-- Select group --</option>
      ${roles.map(r => `<option value="${r.id}">${r.name}</option>`).join("")}
    </select>
  </div>

  <!-- Unit -->
  <div class="form-group">
    <label>Unit</label>
    <select name="unit" class="form-control">
      <option value="">-- Select Unit --</option>
      ${units.map(u => `<option value="${u.id}">${u.name}</option>`).join("")}
    </select>
  </div>

  <!-- Department -->
  <div class="form-group">
    <label>Department</label>
    <select name="department_id" class="form-control">
      <option value="">-- Select Department --</option>
      ${departments.map(d => `<option value="${d.id}">${d.name}</option>`).join("")}
    </select>
  </div>

  <!-- Start Date -->
  <div class="form-group">
    <label>Start Date</label>
    <input type="date" name="start_date" class="form-control" required>
  </div>

  <!-- End Date -->
  <div class="form-group">
    <label>End Date</label>
    <input type="date" name="end_date" class="form-control">
  </div>

  <!-- Active -->
  <div class="form-group">
    <label>Active</label>
    <select name="active" class="form-control" required>
      <option value="1" selected>Yes</option>
      <option value="0">No</option>
    </select>
  </div>

  <div class="form-group mt-3">
    <button type="submit" class="btn btn-primary">💾 Save</button>
  </div>
</form>
`;
                popHtml("Add Employee Role", form);
            } else {
                Swal.fire("Error!", "Failed to load roles, departments, or units", "error");
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error!", "Something went wrong", "error");
        });
}
function viewResearch(id) {
    fetch(`/employee-research/get_research/${id}`)
        .then(res => res.json())
        .then(res => {
            if (!res || res.status !== "success") {
                Swal.fire("Error", res?.message || "Unknown server error", "error");
                return;
            }

            var r = res.data;
            const overlay = document.createElement("div");
            overlay.className = "popup-overlay";

            overlay.innerHTML = `
        <div class="popup-editor">
          <div class="popup-header">
            <span>Research Details</span>
            <button onclick="this.closest('.popup-overlay').remove()">✖</button>
          </div>

          <div class="popup-body">
            <div class="bg-white shadow-md rounded-xl p-6 text-sm text-gray-800 space-y-3">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><span class="font-semibold text-gray-600">Authors:</span> ${r.authors || "-"}</div>
                <div><span class="font-semibold text-gray-600">Title:</span> ${r.title || "-"}</div>
                <div><span class="font-semibold text-gray-600">Publication Type:</span> ${r.publication_type || "-"}</div>
                <div><span class="font-semibold text-gray-600">Publisher:</span> ${r.publisher || "-"}</div>
                <div><span class="font-semibold text-gray-600">Year:</span> ${r.year || "-"}</div>
                <div><span class="font-semibold text-gray-600">Link:</span> ${r.link ? `<a href="${r.link}" target="_blank" class="text-blue-600 hover:underline">View</a>` : "-"}</div>
                <div><span class="font-semibold text-gray-600">Abstract:</span> ${r.abstract_text || "-"}</div>                
              </div>
            </div>
          </div>

          <div class="popup-footer">
            <button onclick="this.closest('.popup-overlay').remove()" class="cursor-pointer">Close</button>
          </div>
        </div>
      `;
            document.body.appendChild(overlay);
        })
        .catch(err => {
            console.error("Error loading research:", err);
            alert("Could not load research details.");
        });
}
function addResearch() {
    var research_form = `
    <form class="form-container"
      onsubmit="sendFormSweet(this,event)"
      action="/employee-research/add"
      method="post"
      enctype="multipart/form-data">

    <div class="form-group">
        <label for="authors">Author(s)</label>
        <input type="text"
               id="authors"
               name="authors"
               class="form-control"
               placeholder="e.g. John Doe, Jane Smith"
               required>
    </div>

    <div class="form-group">
        <label for="title">Title</label>
        <input type="text"
               id="title"
               name="title"
               class="form-control"
               placeholder="Enter publication title"
               required>
    </div>

    <div class="form-group">
        <label for="publication_type">Publication Type</label>
        <select id="publication_type"
                name="publication_type"
                class="form-control"
                required>
            <option value="Journal Article">Journal Article</option>
            <option value="Book Chapter">Book Chapter</option>
            <option value="Book">Book</option>
            <option value="Conference Paper">Conference Paper</option>
            <option value="Manuscript">Manuscript</option>
        </select>
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
        <label for="abstract_text">Abstract / Description</label>
        <textarea id="abstract_text"
                  name="abstract_text"
                  class="form-control"
                  rows="4"
                  placeholder="Enter abstract or brief description"></textarea>
    </div>

    <div class="form-group">
        <label for="publisher">Publisher / Journal</label>
        <input type="text"
               id="publisher"
               name="publisher"
               class="form-control"
               placeholder="Journal, publisher, or institution">
    </div>

    <div class="form-group">
        <label for="year">Year</label>
        <input type="text"
               id="year"
               name="year"
               class="form-control"
               placeholder="e.g. 2025">
    </div>

    <div class="form-group">
        <label for="link">External Link</label>
        <input type="url"
               id="link"
               name="link"
               class="form-control"
               placeholder="https://example.com (optional)">
    </div>

    <div class="form-group">
        <label for="file">Upload File (Optional)</label>
        <input type="file"
               id="file"
               name="file"
               class="form-control"
               accept=".pdf,.doc,.docx">
    </div>

    <div class="form-group">
        <label for="status">Status</label>
        <select id="status"
                name="status"
                class="form-control">
            <option value="complete">Complete</option>
            <option value="manuscript">Manuscript</option>
            <option value="onprogress">In Progress</option>
        </select>
    </div>

    <div class="form-group mt-3">
        <button type="submit" class="btn btn-primary">💾 Save</button>
    </div>

</form>

    `;
    popHtml("Add Research / Publication / Project", research_form);

    // Load employee options dynamically
    fetch('/employee/get')
        .then(res => res.json())
        .then(res => {
            if (res && Array.isArray(res)) {
                const select = document.querySelector("#employee_id");
                res.forEach(emp => {
                    const option = document.createElement("option");
                    option.value = emp.id;
                    option.textContent = emp.name;
                    select.appendChild(option);
                });
            }
        })
        .catch(err => console.error("Error loading employees:", err));
}
function addOutreach() {
    var research_form = `
    <form class="form-container"
      onsubmit="sendFormSweet(this,event)"
      action="/amucta-outreach/add"
      method="post"
      enctype="multipart/form-data">

    <div class="form-group">
        <label for="authors">Author(s)</label>
        <input type="text"
               id="authors"
               name="authors"
               class="form-control"
               placeholder="e.g. John Doe, Jane Smith"
               required>
    </div>

    <div class="form-group">
        <label for="title">Title</label>
        <input type="text"
               id="title"
               name="title"
               class="form-control"
               placeholder="Enter publication title"
               required>
    </div>
  
    <div class="form-group">
        <label for="abstract_text">Description</label>
        <textarea id="abstract_text"
                  name="abstract_text"
                  class="form-control"
                  rows="4"
                  placeholder="Enter brief description"></textarea>
    </div>

    <div class="form-group">
        <label for="year">Year</label>
        <input type="text"
               id="year"
               name="year"
               class="form-control"
               placeholder="e.g. 2025">
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
        <label for="link">External Link</label>
        <input type="url"
               id="link"
               name="link"
               class="form-control"
               placeholder="https://example.com (optional)">
    </div>

    <div class="form-group">
        <label for="file">Upload File (Optional)</label>
        <input type="file"
               id="file"
               name="file"
               class="form-control"
               accept=".pdf,.doc,.docx">
    </div>

    <div class="form-group">
        <label for="status">Status</label>
        <select id="status"
                name="status"
                class="form-control">
            <option value="complete">Complete</option>
            <option value="manuscript">Manuscript</option>
            <option value="onprogress">In Progress</option>
        </select>
    </div>

    <div class="form-group mt-3">
        <button type="submit" class="btn btn-primary">💾 Save</button>
    </div>

</form>

    `;
    popHtml("Add Research / Publication / Project / Outreach", research_form);
}
function editDepartment(id) {
    fetch(`/department/department/${id}`)
        .then(r => r.json())
        .then(data => {
            let alumni=data.data;
            let form = `
            <form class="form-container" onsubmit="sendFormSweet(this,event)" action="/department/add">
                <input type="hidden" name="id" value="${alumni.id}">
                <input type="hidden" name="faculty_id" value="${alumni.faculty_id}">
                
                <div class="form-group">
                    <label for="full_name">Name</label>
                    <input type="text" id="full_name" name="name" value="${alumni.name}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <input type="text" id="category" name="category" value="${alumni.category}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea type="text" name="description" id="description" class="form-control">${alumni.description}</textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">💾 Update</button>
                </div>
            </form>`;

            popHtml("Edit Alumni", form);
        });
}
function editHome(id) {
    fetch(`/home-content/get/${id}`)
        .then(r => r.json())
        .then(data => {
            let alumni=data.data;
            let form = `
            <form class="form-container" onsubmit="sendFormSweet(this,event)" action="/home-content/add">
                <input type="hidden" name="id" value="${alumni.id}">                
                
                <div class="form-group">
                    <label for="full_name">Name</label>
                    <input type="text" id="full_name" name="name" value="${alumni.name}" class="form-control" required>
                </div>   
                <div class="form-group">
                    <label for="deadline">Deadline</label>
                    <input type="date" id="deadline" name="deadline" class="form-control" required>
                </div>         
                <div class="form-group">
                    <label for="description">Content</label>
                    <textarea type="text" name="content" id="description" class="form-control">${alumni.content}</textarea>
                </div>
                <div class="form-group">
                    <label for="style">Style</label>
                    <textarea type="text" name="style" id="style" class="form-control">${alumni.style}</textarea>
                </div>
              

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">💾 Update</button>
                </div>
            </form>`;

            popHtml("Edit Alumni", form);
        });
}
function editFaculty(id) {
    fetch(`/faculty/faculty/${id}`)
        .then(r => r.json())
        .then(data => {
            let alumni=data.data;
            let form = `
            <form class="form-container" onsubmit="sendFormSweet(this,event)" action="/faculty/add">
                <input type="hidden" name="id" value="${alumni.id}">                
                
                <div class="form-group">
                    <label for="full_name">Name</label>
                    <input type="text" id="full_name" name="name" value="${alumni.name}" class="form-control" required>
                </div>               

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea type="text" name="description" id="description" class="form-control">${alumni.description}</textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">💾 Update</button>
                </div>
            </form>`;

            popHtml("Edit Alumni", form);
        });
}
function editLevel(id) {
    let form = `
            <form class="form-container" onsubmit="sendFormSweet(this,event)" action="/level/add">
                <input type="hidden" name="id" value="${id}">                
                
                <div class="form-group">
                    <label for="full_name">Name</label>
                    <input type="text" id="full_name" name="name" class="form-control" required>
                </div>               

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea type="text" name="description" id="description" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">💾 Update</button>
                </div>
            </form>`;

    popHtml("Edit Level", form);
}
function editRole(id){
    let form = `
            <form class="form-container" onsubmit="sendFormSweet(this,event)" action="/employee-role/add">
                <input type="hidden" name="id" value="${id}">                
                
                <div class="form-group">
                    <label for="full_name">Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>               

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">💾 Update</button>
                </div>
            </form>`;

    popHtml("Edit Role", form);
}
function resetPass(id){
    fetch("/users/reset",{
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: id
        })
    })
        .then(response => response.json())
        .then(dataResponse => {
            if (dataResponse.status === "success") {
                Swal.fire("Password reset to phone number", dataResponse.message, "success");
            } else {
                Swal.fire("Error!", dataResponse.message || "Failed to update user", "error");
            }
        })
        .catch(error => {
            console.error("Update error:", error);
            Swal.fire("Error!", "Something went wrong", "error");
        });
}
function editEmployee(id) {
    fetch(`/employee/data/${id}`)
        .then(r => r.json())
        .then(data => {
            let employee=data.data;
            let departments=data.departments;
            let faculties=data.faculties;
            let levels=data.levels;
            let form = `
            <form enctype="multipart/form-data" class="form-container" onsubmit="sendFormSweet(this,event)" action="/employee/update">
                <input type="hidden" name="id" value="${employee.id}">
                
                <div class="form-group">
                    <label for="full_name">Name</label>
                    <input type="text" id="full_name" name="name" value="${employee.name}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" value="${employee.title}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" value="${employee.email}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" value="${employee.phone}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="branch">Branch</label>
                    <input type="text" id="branch" name="branch" value="${employee.branch}" class="form-control">
                </div>

                <div class="form-group">
                    <label for="qualification">Qualification</label>
                    <textarea type="text" name="qualification" id="qualification" class="form-control">${employee.qualification}</textarea>
                </div>
                <div class="form-group">
                    <label for="cv">CV</label>
                    <input type="file" id="cv" name="cv" class="form-control">
                </div>
                <div class="form-group">
                    <label for="department_id">Department</label>
                    <select name="department_id" id="department_id" class="form-control" required>
                        <option value="${employee.department_id}">${employee.department_name}</option>
                        ${departments.map(d => `
                            <option value="${d.id}">${d.name}</option>
                        `).join("")}
                    </select>

                </div>
                <div class="form-group">
                    <label for="profile">Profile</label>
                    <input type="file" id="profile" name="profile" class="form-control">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">💾 Update</button>
                </div>
            </form>`;

            popHtml("Edit Alumni", form);
        });
}
function editProgram(id) {
    fetch(`/program/data/${id}`)
        .then(r => r.json())
        .then(data => {
            let program=data.data;
            let departments=data.departments;
            let levels=data.levels;
            let faculties=data.faculties;
            let form =`
            <form class="form-container" onsubmit="sendFormSweet(this, event)" action="/program/update" method="post">
    <input type="hidden" name="id" value="${program.id}">

    <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" value="${program.name}" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Short Name</label>
        <input type="text" name="short_name" value="${program.short_name}" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Intakes</label>
        <input type="number" name="intakes" value="${program.intakes}" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Duration</label>
        <input type="text" name="duration" value="${program.duration}" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Capacity</label>
        <input type="number" name="capacity" value="${program.capacity}" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Accreditation Year</label>
        <input type="text" name="accreditation_year" value="${program.accreditation_year}" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Faculty</label>
        <select name="faculty_id" class="form-control" required>
            <option>--Select--</option>
            ${faculties.map(f => `<option value="${f.id}">${f.name}</option>`).join("")}
        </select>
    </div>

    <div class="form-group">
        <label>Department</label>
        <select name="department_id" class="form-control" required>
            <option>--Select--</option>
            ${departments.map(d => `<option value="${d.id}">${d.name}</option>`).join("")}
        </select>
    </div>

    <div class="form-group">
        <label>Level</label>
        <select name="level_id" class="form-control" required>
            <option >--Select--</option>
            ${levels.map(l => `<option value="${l.id}">${l.name}</option>`).join("")}
        </select>
    </div>

    <div class="form-group">
        <label>Description (text)</label>
        <textarea name="description" class="form-control">${program.description}</textarea>
    </div>

    <div class="form-group">
        <label>Content (HTML)</label>
        <textarea name="content" class="form-control">${program.content}</textarea>
    </div>

    <div class="form-group">
        <label>Fees (HTML)</label>
        <textarea name="fees" class="form-control">${program.fees}</textarea>
    </div>

    <div class="form-group">
        <label>Requirements (HTML)</label>
        <textarea name="requirements" class="form-control">${program.entry_requirements}</textarea>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">💾 Update Program</button>
    </div>
</form>

            `;
            popHtml("Edit Alumni", form);
        });
}
function zoomImage() {

    let overlay = document.getElementById('imgZoomOverlay');

    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'imgZoomOverlay';
        overlay.style.cssText = `
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.85);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            cursor: zoom-out;
        `;

        overlay.innerHTML = `
            <img id="imgZoomTarget" style="
                max-width: 90%;
                max-height: 90%;
                border-radius: 12px;
                box-shadow: 0 0 25px rgba(255,255,255,.35);
            ">
        `;

        overlay.onclick = () => overlay.remove();
        document.body.appendChild(overlay);
    }

    const zoomed = overlay.querySelector('#imgZoomTarget');

    // get clicked image automatically
    zoomed.src = event.target.src;
}




