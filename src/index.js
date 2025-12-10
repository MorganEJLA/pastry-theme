import "../css/style.scss";

// Our modules / classes
import MobileMenu from "./modules/MobileMenu";
import HeroSlider from "./modules/HeroSlider";
import Search from "./modules/Search";

// Instantiate a new object using our modules/classes
const mobileMenu = new MobileMenu();
const heroSlider = new HeroSlider();
const search = new Search();

// ================================================
// Inline Locale Search + A–Z Filtering
// (Runs ONLY on the Locale Archive page)
// ================================================
(function () {
  // Script is loaded in the footer, so DOM is ready.
  const browseSection = document.getElementById("browse-locales");
  if (!browseSection) return; // not on locale archive

  const searchInput = browseSection.querySelector("#locale-inline-search");
  const suggestionsBox = browseSection.querySelector(
    "#locale-inline-suggestions"
  );
  const filterButtons = browseSection.querySelectorAll(".az-filter__btn");
  const cards = browseSection.querySelectorAll(".locale-card");

  if (!searchInput || !cards.length) return;

  // Build a simple map of locale elements
  const locales = Array.from(cards).map((card) => {
    const titleEl = card.querySelector(".locale-card__title");
    const label = titleEl
      ? titleEl.textContent.trim()
      : card.dataset.title || "";
    const normalized = label.toLowerCase();

    return {
      label,
      title: normalized,
      url: card.href,
    };
  });

  // ---------------------------
  // Smooth scroll: hero <-> browse
  // ---------------------------
  const backToHeroLink = browseSection.querySelector(".locales-back-to-hero");
  const hero = document.getElementById("locale-hero-top");

  if (backToHeroLink && hero) {
    backToHeroLink.addEventListener("click", (e) => {
      e.preventDefault();
      const top = hero.getBoundingClientRect().top + window.pageYOffset;
      window.scrollTo({ top, behavior: "smooth" });
    });
  }

  // Also smooth-scroll from hero overlay down to browse
  document.querySelectorAll(".locales-browse-link").forEach((link) => {
    link.addEventListener("click", (e) => {
      const target = document.getElementById("browse-locales");
      if (!target) return;
      e.preventDefault();
      const top = target.getBoundingClientRect().top + window.pageYOffset;
      window.scrollTo({ top, behavior: "smooth" });
    });
  });

  // ---------------------------
  // Floating scroll-to-hero arrow
  // ---------------------------
  const scrollArrow = document.getElementById("scroll-to-hero");

  if (scrollArrow && hero) {
    // show/hide arrow once user scrolls a bit
    window.addEventListener("scroll", () => {
      const triggerPoint = window.innerHeight * 0.7; // ~70% viewport height
      if (window.scrollY > triggerPoint) {
        scrollArrow.classList.add("is-visible");
      } else {
        scrollArrow.classList.remove("is-visible");
      }
    });

    // smooth scroll back to hero on click
    scrollArrow.addEventListener("click", (e) => {
      e.preventDefault();
      const top = hero.getBoundingClientRect().top + window.pageYOffset;
      window.scrollTo({ top, behavior: "smooth" });
    });
  }

  // ---------------------------
  // A–Z Grid Filtering
  // ---------------------------
  function filterByLetter(letter) {
    const l = letter.toLowerCase();
    cards.forEach((card) => {
      const title = (card.dataset.title || "").toLowerCase();
      const matches = l === "all" || title.startsWith(l);
      card.style.display = matches ? "" : "none";
    });
  }

  filterButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      filterButtons.forEach((b) => b.classList.remove("is-active"));
      btn.classList.add("is-active");
      filterByLetter(btn.dataset.letter);
    });
  });

  // initial state
  filterByLetter("all");

  // ---------------------------
  // Inline Search Suggestions
  // ---------------------------
  function clearSuggestions() {
    if (!suggestionsBox) return;
    suggestionsBox.innerHTML = "";
    suggestionsBox.style.display = "none";
  }

  function renderSuggestions(list) {
    if (!suggestionsBox) return;

    if (!list.length) {
      clearSuggestions();
      return;
    }

    suggestionsBox.innerHTML = list
      .map(
        (item) => `
          <button type="button"
                  class="locale-search__suggestion"
                  data-url="${item.url}">
            ${item.label}
          </button>
        `
      )
      .join("");

    suggestionsBox.style.display = "block";

    suggestionsBox
      .querySelectorAll(".locale-search__suggestion")
      .forEach((btn) => {
        btn.addEventListener("click", () => {
          window.location.href = btn.dataset.url;
        });
      });
  }

  searchInput.addEventListener("input", () => {
    const q = searchInput.value.trim().toLowerCase();
    if (!q) {
      clearSuggestions();
      // also reset grid when input is cleared
      filterByLetter("all");
      filterButtons.forEach((b) => {
        b.classList.toggle("is-active", b.dataset.letter === "all");
      });
      return;
    }

    const matches = locales
      .filter((locale) => locale.title.includes(q))
      .slice(0, 8); // cap suggestions

    renderSuggestions(matches);
  });

  // Enter selects first suggestion
  searchInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      const first = suggestionsBox
        ? suggestionsBox.querySelector(".locale-search__suggestion")
        : null;
      if (first) {
        e.preventDefault();
        window.location.href = first.dataset.url;
      }
    }
  });

  // Click outside closes suggestions
  document.addEventListener("click", (e) => {
    if (!browseSection.contains(e.target)) {
      clearSuggestions();
    }
  });
})();
