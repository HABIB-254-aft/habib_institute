/* =======================
   MOBILE MENU TOGGLE
========================== */

const menuToggle = document.getElementById("menu-toggle");
const mobileMenu = document.getElementById("mobile-menu");

if (menuToggle) {
    menuToggle.addEventListener("click", () => {
        mobileMenu.classList.toggle("open");
    });
}

/* =======================
   DROPDOWN MENU (MOBILE)
========================== */

const dropdowns = document.querySelectorAll(".dropdown-toggle");

dropdowns.forEach(toggle => {
    toggle.addEventListener("click", (e) => {
        e.preventDefault();
        const parent = toggle.parentElement;
        parent.classList.toggle("open");
    });
});

/* =======================
   SCROLL FADE-IN ANIMATION
========================== */

const fadeElements = document.querySelectorAll(".fade-in");

function showOnScroll() {
    const triggerBottom = window.innerHeight * 0.85;

    fadeElements.forEach(el => {
        const box = el.getBoundingClientRect().top;
        if (box < triggerBottom) {
            el.classList.add("visible");
        }
    });
}

window.addEventListener("scroll", showOnScroll);
window.addEventListener("load", showOnScroll);

/* =======================
   ANIMATED STICKY HEADER
========================== */

const header = document.querySelector(".header");
let lastScrollY = window.scrollY;

window.addEventListener("scroll", () => {
    if (window.scrollY > lastScrollY) {
        header.style.transform = "translateY(-70px)";   // hide on scroll down
    } else {
        header.style.transform = "translateY(0)";       // show on scroll up
    }
    lastScrollY = window.scrollY;
});
/* 
   FAQs
 */
document.addEventListener("DOMContentLoaded", function () {
    const faqItems = document.querySelectorAll(".faq-item");

    faqItems.forEach((item) => {
        item.addEventListener("click", () => {
            let ans = item.querySelector(".faq-answer");
            if (ans.style.display == "none") {
                ans.style.display = "block";
            } else {
                ans.style.display = "none";
            }

        });
    })
    // faqItems.forEach(item => {

    //     item.querySelector(".faq-question").addEventListener("click", () => {

    //         item.classList.toggle("active");
    //     });
    // });
});

/* =======================
   SMOOTH SCROLL FOR ANCHOR LINKS
========================== */

document.querySelectorAll('a[href^="#"]').forEach(link => {
    link.addEventListener("click", function (e) {
        const target = document.querySelector(this.getAttribute("href"));
        if (target) {
            e.preventDefault();
            target.scrollIntoView({
                behavior: "smooth"
            });
        }
    });
});
