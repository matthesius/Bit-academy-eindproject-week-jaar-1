const opennavmodal = document.getElementById("responsivemenu");
const closenavmodal = document.getElementById("closenavmodal");
const navmodal = document.getElementById("navmodal");

if (navmodal) {
    navmodal.style.display = "none";
}

opennavmodal.addEventListener("click", () => {
    navmodal.style.display = "flex";
});

closenavmodal.addEventListener("click", () => {
    navmodal.style.display = "none";
});