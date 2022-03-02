import "./styles/app.scss";

const settings = document.querySelector(".mobile__settings");
const settingsToggler = document.querySelector("#nav-toggler");
const settingsCloser = document.querySelector("#nav-closer");
const main = document.querySelector("main");
const header = document.querySelector(".header");
const nav = document.querySelector("nav");

settingsToggler.addEventListener("click", () => {
  header.classList.add("border-transparent");
  nav.classList.add("border-transparent");
  main.classList.add("bg-dark");
  settings.classList.add("active");
});

settingsCloser.addEventListener("click", () => {
  main.classList.remove("bg-dark");
  settings.classList.remove("active");
  header.classList.remove("border-transparent");
  nav.classList.remove("border-transparent");
});
