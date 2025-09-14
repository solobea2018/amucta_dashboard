function goto(url) {
    if (window.location.pathname !== url) {
        window.location.href = url
    }
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



