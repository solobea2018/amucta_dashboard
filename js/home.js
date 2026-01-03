document.addEventListener("DOMContentLoaded",function(){
    new Swiper(".mainSwiper",{
        loop:true,
        autoplay:{
            delay:5000,
            disableOnInteraction:false
        },
        pagination:{
            el:".swiper-pagination",
            clickable:true
        },
        navigation:{
            nextEl:".swiper-button-next",
            prevEl:".swiper-button-prev"
        }
    });
    const slider = document.getElementById("background-slider");
    if (!(slider==null)){
        var images = [];
        var currentIndex = 0;
        var preloaded = [];

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
            }, 20000); // change every 15s
        }
    }
});
