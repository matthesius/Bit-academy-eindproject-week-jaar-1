const opennavmodal = document.getElementById("responsivemenu");
const closenavmodal = document.getElementById("closenavmodal");
const navmodal = document.getElementById("navmodal");
const modallinks = document.querySelectorAll(".modal > a");

navmodal.style.display = "none";

opennavmodal.addEventListener("click", () => {
    navmodal.style.display = "flex";
});

closenavmodal.addEventListener("click", () => {
    navmodal.style.display = "none";
});

modallinks.addEventListener("click", () => {
    navmodal.style.display = "none";
});