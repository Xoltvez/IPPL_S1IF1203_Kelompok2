document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('hamburger');
    const menu = document.getElementById('mobile-menu');
    const sections = document.querySelectorAll("section[id]");
    const navLinks = document.querySelectorAll(".nav-link");
    
    // Ambil ketiga garis hamburger
    const line1 = document.getElementById('line-1');
    const line2 = document.getElementById('line-2');
    const line3 = document.getElementById('line-3');

    // --- LOGIKA HAMBURGER ---
    if (btn && menu) {
        btn.addEventListener('click', () => {
            menu.classList.toggle('opacity-0');
            menu.classList.toggle('scale-95');
            menu.classList.toggle('pointer-events-none');
            menu.classList.toggle('opacity-100');
            menu.classList.toggle('scale-100');
            menu.classList.toggle('pointer-events-auto');

            line1.classList.toggle('rotate-45');
            line1.classList.toggle('translate-y-0');
            line1.classList.toggle('-translate-y-1.5');

            line2.classList.toggle('opacity-0');

            line3.classList.toggle('-rotate-45');
            line3.classList.toggle('translate-y-0');
            line3.classList.toggle('translate-y-1.5');
        });
    }

    // --- LOGIKA SCROLLSPY (Sekarang di dalam DOMContentLoaded) ---
    window.addEventListener("scroll", () => {
        let current = "";

        sections.forEach((section) => {
            const sectionTop = section.offsetTop;
            // pageYOffset diganti window.scrollY (lebih modern)
            if (window.scrollY >= sectionTop - 150) {
                current = section.getAttribute("id");
            }
        });

        if (window.scrollY < 300) {
            current = "beranda";
        }

        navLinks.forEach((link) => {
            link.classList.remove("text-blue-500");
            
            const href = link.getAttribute("href");
            if (href.includes(current)) {
                link.classList.add("text-blue-500");
            } else if (current === "beranda" && (href === "#" || href === "")) {
                link.classList.add("text-blue-500");
            }
        });
    });
});