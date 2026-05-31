const swiper = new Swiper(".swiper", {
  // Optional parameters
  direction: "horizontal",
  loop: true,

  // If we need pagination
  pagination: {
    el: ".swiper-pagination",
  },

  // Navigation arrows
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },

  // And if we need scrollbar
  scrollbar: {
    el: ".swiper-scrollbar",
  },
});

//////////////////////////////////////////////////////////////////
// my code hehe
const root = document.documentElement;
let body = document.querySelectorAll("body");
const themeButton = document.getElementById("themeButton");
const submitButton = document.getElementById("submitButton");
console.log(themeButton);
let currentTheme = localStorage.getItem("theme");
console.log("submitButton");
// THEME STUFF
//
function applyTheme(requestedTheme) {
  switch (requestedTheme) {
    case "light":
      //## When user wants it to be light mode
      oppositeTheme = "dark";
      break;
    case "dark":
      // and when user wants dark mode like they should
      oppositeTheme = "light";
      break;
    default:
      // when things get bad
      console.log(
        "something's wrong with applyingtheme, setting to light mode",
      );
      oppositeTheme = "dark";
  }
  body.forEach((body) => body.classList.add(requestedTheme));
  body.forEach((body) => body.classList.remove(oppositeTheme));
  if (oppositeTheme == "dark") {
    themeButton.innerHTML = "🌙";
  } else {
    themeButton.innerHTML = "☀️";
  }
  currentTheme = requestedTheme;
  localStorage.setItem("theme", requestedTheme);
}

// initial theme run check //////

switch (currentTheme) {
  case "light":
    console.log(currentTheme);
    console.log("light mode in localStorage");
    break;
  case "dark":
    console.log(currentTheme);
    console.log("dark mode in storage");
    break;
  default:
    console.log(currentTheme);
    console.log("no theme in storage or error, setting default to light mode");
    localStorage.setItem("theme", "light");
}
applyTheme(currentTheme);
///

themeButton.addEventListener("click", function () {
  console.log("theme button click");
  switch (currentTheme) {
    case "light":
      applyTheme("dark");
      break;
    case "dark":
      applyTheme("light");
      break;
    default:
      console.log("current theme is wonky");
      applyTheme("light");
  }
});