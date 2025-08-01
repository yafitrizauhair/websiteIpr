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

// document.getElementById("form").addEventListener("submit", function (e) {
//   e.preventDefault();

//   const data = {
//     nama: this.nama.value,
//     perusahaan: this.perusahaan.value,
//     alamat: this.alamat.value,
//     telepon: this.telepon.value,
//     email: this.email.value,
//     subjek: this.subjek.value,
//     pesan: this.pesan.value,
//   };

//   fetch(
//     "https://script.google.com/macros/s/AKfycbzOrgc9ii6D_WN5SdHrfyI6ZEUkodxc7wC8JaN-6voB-04L8536zxOdF0KG1_RmwGG3/exec",
//     {
//       method: "POST",
//       body: JSON.stringify(data),
//       headers: {
//         "Content-Type": "application/json",
//       },
//     }
//   )
//     .then((response) => response.text())
//     .then((data) => {
//       alert("Pesan berhasil dikirim!");
//     })
//     .catch((error) => {
//       alert("Gagal mengirim.");
//       console.error(error);
//     });
// });
