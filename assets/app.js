import "./styles/app.scss";

/**
 * Preloader
 */
window.addEventListener("load", function () {
  document.querySelector(".preloader").classList.add("loaded");
});

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
if (
  localStorage.theme === "dark" ||
  (!("theme" in localStorage) &&
    window.matchMedia("(prefers-color-scheme: dark)").matches)
) {
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

/**
 * Animation de like
 */
const likeBtn = document.querySelector(".heart");
const likeSvg = document.querySelector(".heart__svg");

likeBtn.addEventListener("click", function () {
  likeBtn.classList.toggle("liked");
  likeSvg.classList.toggle("animating");
});

likeBtn.addEventListener("animationend", function () {
  likeBtn.classList.toggle("liked");
  likeSvg.classList.toggle("animating");
  likeSvg.innerHTML =
    "<g><path d='M12 21.638h-.014C9.403 21.59 1.95 14.856 1.95 8.478c0-3.064 2.525-5.754 5.403-5.754 2.29 0 3.83 1.58 4.646 2.73.814-1.148 2.354-2.73 4.645-2.73 2.88 0 5.404 2.69 5.404 5.755 0 6.376-7.454 13.11-10.037 13.157H12z'></path></g>";
  likeSvg.classList.toggle("liked");
});
