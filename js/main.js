function goto(url) {
    if (window.location.pathname !== url) {
        window.location.href = url
    }
}
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



