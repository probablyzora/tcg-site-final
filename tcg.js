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
    formData.append("types", data.types.join(","));
    formData.append("height", data.height);
    formData.append("weight", data.weight);

    await fetch("savecard.php", {
      method: "POST",
      body: formData,
    });
    // element html des cartes
    cardCollection.insertAdjacentHTML("beforeend", buildCardHTML(data));
    refreshCards() // ajoute la div + image a la suite des autres cartes
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
function buildCardHTML(data) {
  const primaryType = data.types[0];
  const typeBadges = data.types
    .map((t) => `<span class="type-badge type-${t}">${t}</span>`)
    .join("");
  const cardHTML = `
  <div class="poke-card type-${primaryType}"
  data-id="${data.id}"
  data-name="${data.name}"
  data-sprite="${data.sprite}"
  data-types="${data.types.join(",")}"
  data-height="${data.height}"
  data-weight="${data.weight}">
   <span class="star">☆</span>
  <img src="${data.sprite}" alt="${data.name}" style="width:100px; image-rendering:pixelated;">
  <p>#${data.id} ${data.name}</p>
  ${typeBadges}
  </div>`;
  return cardHTML;
}
async function openPack() {
  console.log("clicked pack button");
  const check = await fetch("checkCard.php");
  const status = await check.json();
  const lastOpened = new Date(status.lastOpened);
  const nextOpen = new Date(lastOpened.getTime() + 24 * 60 * 60 * 1000);
  const diff = nextOpen - new Date();
  const hours = Math.floor(diff / 1000 / 60 / 60);
  const minutes = Math.floor((diff / 1000 / 60) % 60);
  if (!status.canOpen) {
    console.log(`can't open pack. next open in ${hours}h,${minutes}m`);
    let notif = document.getElementById("notif");
    notif.classList.add("visible");
    notif.classList.remove("valid");
    notif.classList.add("invalid");
    notif.innerHTML = `Can't open the pack yet!. Wait ${hours}h:${minutes}m.`;
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
      formData.append("types", data.types.join(","));
      formData.append("height", data.height);
      formData.append("weight", data.weight);

      await fetch("savecard.php", {
        method: "POST",
        body: formData,
      });
      cardCollection.insertAdjacentHTML("beforeend", buildCardHTML(data)); // ajoute la div + image a la suite des autres cartes
      refreshCards()
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
// FAVOURITES
function getFavs() {
  return JSON.parse(localStorage.getItem("favourites") || "[]");
}
function saveFavs(favs) {
  localStorage.setItem("favourites", JSON.stringify(favs));
}

// REFRESH — applies favourites, filters, and rebuilds filter bar
function refreshCards() {
  const favs = getFavs();
  const collection = document.getElementById("cardCollection");
  if (!collection) return;

  const cards = Array.from(collection.querySelectorAll(".poke-card"));

  // apply favourite style and star
  cards.forEach((card) => {
    const isFav = favs.includes(card.dataset.id);
    card.classList.toggle("favourite", isFav);
    card.querySelector(".star").textContent = isFav ? "★" : "☆";
  });

  // move favourites to top
  const favCards = cards.filter((c) => favs.includes(c.dataset.id));
  const rest = cards.filter((c) => !favs.includes(c.dataset.id));
  [...favCards, ...rest].forEach((c) => collection.appendChild(c));

  // apply type filter
  const activeFilters = Array.from(
    document.querySelectorAll("#filterBar button.active"),
  ).map((b) => b.dataset.type);
  cards.forEach((card) => {
    if (activeFilters.length === 0) {
      card.style.display = "";
    } else {
      const cardTypes = card.dataset.types.split(",");
      card.style.display = cardTypes.some((t) => activeFilters.includes(t))
        ? ""
        : "none";
    }
  });

  // rebuild filter bar
  const allTypes = new Set();
  cards.forEach((card) =>
    card.dataset.types.split(",").forEach((t) => allTypes.add(t)),
  );
  const bar = document.getElementById("filterBar");
  if (!bar) return;
  const currentActive = new Set(activeFilters);
  bar.innerHTML = "";
  allTypes.forEach((type) => {
    const btn = document.createElement("button");
    btn.textContent = type;
    btn.dataset.type = type;
    btn.className = `type-${type}`;
    if (currentActive.has(type)) btn.classList.add("active");
    btn.addEventListener("click", () => {
      btn.classList.toggle("active");
      refreshCards();
    });
    bar.appendChild(btn);
  });
}

// STAR CLICK
if (cardCollection) {
  cardCollection.addEventListener("click", function (e) {
    if (!e.target.classList.contains("star")) return;
    const card = e.target.closest(".poke-card");
    const favs = getFavs();
    const id = card.dataset.id;
    if (favs.includes(id)) {
      saveFavs(favs.filter((f) => f !== id));
    } else {
      saveFavs([...favs, id]);
    }
    refreshCards();
  });
}

// MODAL
if (cardCollection) {
  cardCollection.addEventListener("click", function (e) {
    if (e.target.classList.contains("star")) return;
    const card = e.target.closest(".poke-card");
    if (!card) return;
    document.getElementById("modalSprite").src = card.dataset.sprite;
    document.getElementById("modalName").textContent = card.dataset.name;
    document.getElementById("modalId").textContent = "#" + card.dataset.id;
    document.getElementById("modalHeight").textContent =
      "Height: " + card.dataset.height / 10 + "m";
    document.getElementById("modalWeight").textContent =
      "Weight: " + card.dataset.weight / 10 + "kg";
    document.getElementById("modalTypes").innerHTML = card.dataset.types
      .split(",")
      .map((t) => `<span class="type-badge type-${t}">${t}</span>`)
      .join("");
    const modal = document.getElementById("cardModal");
    modal.style.display = "flex";
  });
}

document.getElementById("closeModal")?.addEventListener("click", () => {
  document.getElementById("cardModal").style.display = "none";
});
document.getElementById("cardModal")?.addEventListener("click", function (e) {
  if (e.target === this) this.style.display = "none";
});

// run on load
refreshCards();
