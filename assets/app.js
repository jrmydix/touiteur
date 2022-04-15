import "./styles/app.scss";

/**
 * Gestion de la barre de latérale mobile
 */
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

/**
 * Gestion du thème de couleurs
 */
if (localStorage.theme === "dark" || (!("theme" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches)) {
  document.documentElement.classList.add("dark");
} else {
  document.documentElement.classList.remove("dark");
}

const btnTheme = document.querySelector(".mobile__settings--links-theme");

btnTheme.addEventListener("click", function (e) {
  if (localStorage.theme == "dark") {
    localStorage.theme = "light";
    document.documentElement.classList.remove("dark");
  } else {
    localStorage.theme = "dark";
    document.documentElement.classList.add("dark");
  }
});
