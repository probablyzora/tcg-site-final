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
let tickedBox = document.querySelector(".tosCheckbox")?.checked;
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
      requestedTheme = "light";
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
console.log(submitButton);
applyTheme(currentTheme);
console.log("a", submitButton);
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
function verifyEmail(writtenEmail) {
  if (writtenEmail.value == "") {
    console.log("invalid email");
    email.classList.add("invalid");
    return false;
  } else {
    console.log("valid email");
    email.classList.add("valid");
    return true;
  }
}
submitButton.addEventListener("click", function () {
  console.log("a", submitButton);
  let givenEmail = document.getElementById("email").value;
  let notif = document.getElementById("notif");
  tickedBox = document.querySelector(".tosCheckbox").checked;
  console.log("givenEmail=" + givenEmail);
  console.log("submit button clic");
  emailVerified = verifyEmail(givenEmail);
  console.log(tickedBox);
  notif.classList.add("visible");
  console.log("tickedBox=" + tickedBox);
  notif.classList.add("valid");
  if (tickedBox == false) {
    console.log("no tos");
    notif.classList.remove("valid");
    notif.classList.add("invalid");
    notif.innerHTML = "You need to accept the TOS.";
  }
  console.log("emailVerified=" + emailVerified);
  if (emailVerified == false) {
    console.log("bad email");
    notif.classList.remove("valid");
    notif.classList.add("invalid");
    notif.innerHTML = "You need to input a valid email.";
  }
  if (emailVerified && tickedBox) {
    console.log("all good submit");
    notif.classList.remove("invalid");
    notif.classList.add("valid");
    notif.innerHTML = "You are now subscribed to the newsletter.";
  }
  setTimeout(function () {
    notif.classList.remove("visible");
  }, 3000);
});

//tosCheckbox.addEventListener("click", function () {
//  tickedBox = document.querySelector(".tosCheckbox").checked;
//  console.log(tickedBox);
//});
const addButton = document.getElementById("addButton");
const searchName = document.getElementById("searchName");
const cardCollection = document.getElementById("cardCollection");
const boosterPack = document.getElementById("boosterPack");
console.log("a", boosterPack);
//asynchrone = ordre independant des autres fonctions
async function addPokemonCard() {
  const name = searchName.value;
  if (!name) return; // si il y a pas de nom dans la barre de recherche

  try {
    const response = await fetch(`tcgsite.php?name=${name}`); // requete cote php
    const data = await response.json();

    if (data.error) {
      console.log(data.error);
      return;
    }
    const formData = new FormData(); // communication entre js/php et sql
    formData.append("id", data.id);
    formData.append("name", data.name);

    await fetch("savecard.php", {
      method: "POST",
      body: formData,
    });
    // element html des cartes
    const cardHTML = `
            <div class="poke-card pulse">
                <a href="${data.sprite}" class="glightbox" data-gallery="gallery1">
                    <img src="${data.sprite}" alt="${data.name}" style="width: 100px; image-rendering: pixelated;">
                </a>
                <p>#${data.id} ${data.name}</p>
            </div>
        `;
    cardCollection.insertAdjacentHTML("beforeend", cardHTML); // ajoute la div + image a la suite des autres cartes
    GLightbox({ selector: ".glightbox" }); // refraichissement lightbox sinon lightbox ne marche pas sur les nouvelles images

    searchName.value = ""; // effacer apres avoir cherché
  } catch (error) {
    console.error("Error adding card:", error);
  }
}

if (addButton) {
  addButton.addEventListener("click", addPokemonCard);
} // toucher bouton // voir ligne 228
if (searchName) {
  searchName.addEventListener("keypress", (event) => {
    if (event.key === "Enter") addPokemonCard();
  });
} // accepter touche Enter

async function openPack() {
  console.log("clicked pack button");
  const check = await fetch("checkCard.php");
  const status = await check.json();
  if (!status.canOpen) {
    console.log("can't open pack");
    let notif = document.getElementById("notif");
    notif.classList.add("visible");
    notif.classList.remove("valid");
    notif.classList.add("invalid");
    notif.innerHTML = "Can't open the pack yet!.";
    setTimeout(function () {
      notif.classList.remove("visible");
    }, 3000);
    return;
  }

  await fetch("checkCard.php", { method: "POST" });
  const numberOfCards = 5;
  for (let i = 0; i < numberOfCards; i++) {
    randomId = Math.floor(Math.random() * 1025);
    try {
      const response = await fetch(`tcgsite.php?id=${randomId}`);
      const data = await response.json();
      if (data.error) {
        console.log(data.error);
        return;
      }
      const formData = new FormData(); // communication entre js/php et sql
      formData.append("id", data.id);
      formData.append("name", data.name);
      await fetch("savecard.php", {
        method: "POST",
        body: formData,
      });
      const cardHTML = `
            <div class="poke-card pulse">
                <a href="${data.sprite}" class="glightbox" data-gallery="gallery1">
                    <img src="${data.sprite}" alt="${data.name}" style="width: 100px; image-rendering: pixelated;">
                </a>
                <p>#${data.id} ${data.name}</p>
            </div>
        `;
      cardCollection.insertAdjacentHTML("beforeend", cardHTML); // ajoute la div + image a la suite des autres cartes
      GLightbox({ selector: ".glightbox" });
    } catch (error) {
      console.error("Error adding card:", error);
    }
  }
}
if (boosterPack) {
  boosterPack.addEventListener("click", openPack);
} // pour que ca fonctionne meme avec l'ordre de chargement
console.log(boosterPack);
