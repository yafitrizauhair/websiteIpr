document.addEventListener("DOMContentLoaded", function () {
  const carouselItems = document.querySelectorAll(".carousel-item");
  let currentIndex = 0;
  const totalItems = carouselItems.length;

  function showSlide(index) {
    carouselItems.forEach((item, i) => {
      if (i === index) {
        item.classList.add("active");
      } else {
        item.classList.remove("active");
      }
    });
  }

  // Mengganti slide secara otomatis setiap 3 detik
  setInterval(() => {
    currentIndex = (currentIndex + 1) % totalItems;
    showSlide(currentIndex);
  }, 4000);
});

const hamburgerMenu = document.querySelector("#hamburger-menu");
const navMenu = document.querySelector("#nav-menu");
const navbar = document.querySelector("#navbar");

hamburgerMenu.addEventListener("click", function () {
  navMenu.classList.toggle("nav-menu-active");
});

document.addEventListener("click", function (e) {
  // Jika klik terjadi di luar navbar, maka sembunyikan navbar
  if (!navbar.contains(e.target)) {
    navMenu.style.display = "none";
  }
});

// const slides = ["slides1", "slides2"];
// const logos = ["logos1", "logos2"];

// var copy = document.querySelector(".logos-slide").cloneNode(true);
// document.querySelector(".logos").appendChild(copy);

// var copy2 = document.getElementById("slides2").cloneNode(true);
// document.getElementById("logos2").appendChild(copy2);

// const copy3 = document.getElementById("slides3").cloneNode(true);
// document.getElementById("logos3").appendChild(copy3);

// const copy4 = document.getElementById("slides4").cloneNode(true);
// document.getElementById("logos4").appendChild(copy4);
// const copy5 = document.getElementById("slides5").cloneNode(true);
// document.getElementById("logos5").appendChild(copy5);

// const copy6 = document.getElementById("slides6").cloneNode(true);
// document.getElementById("logos6").appendChild(copy6);

function cloneSliderWithDirection(id, reverse = false) {
  const original = document.getElementById(id);
  if (!original) return;

  const clone = original.cloneNode(true);
  if (reverse) {
    clone.classList.add("reverse");
  }
  original.parentElement.appendChild(clone);
}

// Terapkan bergantian reverse
cloneSliderWithDirection("slides1", false); // kanan
cloneSliderWithDirection("slides2", true); // kiri
cloneSliderWithDirection("slides3", false); // kanan
cloneSliderWithDirection("slides4", true); // kiri
cloneSliderWithDirection("slides5", false); // kanan
cloneSliderWithDirection("slides6", true); // kiri
