document.addEventListener("DOMContentLoaded", function () {

    const menu = document.getElementById("adminMenu");
    const openBtn = document.getElementById("a-openMenu");
    const closeBtn = document.getElementById("a-closeMenu");

    // open menu
    openBtn.addEventListener("click", () => {
        menu.classList.add("open");
    });

    // close menu
    closeBtn.addEventListener("click", () => {
        menu.classList.remove("open");
    });

    // submenu toggle
    document.querySelectorAll(".a-menu-title").forEach(title => {
        title.addEventListener("click", () => {
            title.nextElementSibling.classList.toggle("open");
        });
    });

    // active menu highlight
    const links = document.querySelectorAll(".a-submenu a");
    const path = window.location.pathname;

    links.forEach(link => {
        if (link.getAttribute("href") === path) {
            link.classList.add("active");
            link.closest(".a-menu").classList.add("open");
        }
    });
});
