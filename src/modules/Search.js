import axios from "axios";

class Search {
  constructor() {
    this.addSearchHTML();
    this.resultsDiv = document.querySelector("#search-overlay__results");
    this.openButton = document.querySelectorAll(".js-search-trigger");
    this.closeButton = document.querySelector(".search-overlay__close");
    this.searchOverlay = document.querySelector(".search-overlay");
    this.searchField = document.querySelector("#search-term");
    this.isOverlayOpen = false;
    this.isSpinnerVisible = false;
    this.previousValue = "";
    this.typingTimer = null;
    this.events();
  }

  // Events
  events() {
    this.openButton.forEach((el) => {
      el.addEventListener("click", (e) => {
        e.preventDefault();
        this.openOverlay();
      });
    });

    this.closeButton.addEventListener("click", () => this.closeOverlay());
    document.addEventListener("keydown", (e) => this.keyPressDispatcher(e));
    this.searchField.addEventListener("keyup", () => this.typingLogic());

    // Handle clicks on "Did you mean" suggestion buttons (event delegation,
    // since these buttons are injected dynamically into resultsDiv)
    this.resultsDiv?.addEventListener("click", (e) => {
      const btn = e.target.closest(".search-suggestion");
      if (!btn) return;
      this.searchField.value = btn.dataset.term;
      this.previousValue = "";
      this.getResults();
    });
  }

  // Typing logic
  typingLogic() {
    if (this.searchField.value !== this.previousValue) {
      clearTimeout(this.typingTimer);

      if (this.searchField.value) {
        if (!this.isSpinnerVisible) {
          this.resultsDiv.innerHTML = '<div class="spinner-loader"></div>';
          this.isSpinnerVisible = true;
        }

        this.typingTimer = setTimeout(this.getResults.bind(this), 750);
      } else {
        this.resultsDiv.innerHTML = "";
        this.isSpinnerVisible = false;
      }
    }

    this.previousValue = this.searchField.value;
  }

  // Render a single results section. Returns "" (renders nothing) when
  // there are no items, so empty categories no longer show a
  // "No X match that search" line.
  renderSection(title, items) {
    if (!items.length) return "";

    return `
      <h2 class="search-overlay__section-title">${title}</h2>
      <ul class="link-list min-list">
        ${items
          .map(
            (item) => `
              <li>
                <a href="${item.permalink}">${item.title}</a>
              </li>
            `,
          )
          .join("")}
      </ul>
    `;
  }

  // "Did you mean" suggestions, shown only when there are zero results
  // across every category. Pulls from pastryData.searchSynonyms, which
  // needs to be localized from PHP (see note below).
  renderSuggestions(term) {
    const q = term.toLowerCase().trim();
    const synonyms = (window.pastryData && pastryData.searchSynonyms) || {};

    const close = Object.keys(synonyms).filter(
      (key) => key.includes(q) || q.includes(key),
    );

    if (!close.length) return "";

    // De-dupe in case multiple keys map to the same canonical term
    const canonicalTerms = [...new Set(close.map((key) => synonyms[key]))];

    return `
      <p class="search-overlay__suggestions">
        Did you mean:
        ${canonicalTerms
          .slice(0, 3)
          .map(
            (term) =>
              `<button type="button" class="search-suggestion" data-term="${term}">${term}</button>`,
          )
          .join(", ")}?
      </p>
    `;
  }

  // Fetch Results
  async getResults() {
    try {
      const response = await axios.get(
        `${pastryData.root_url}/wp-json/pastry/v1/search?term=${this.searchField.value}`,
      );

      const results = response.data;

      const hasAnyResults =
        results.generalInfo.length ||
        results.pastry_case.length ||
        results.professors.length ||
        results.locale.length ||
        results.journal.length;

      if (!hasAnyResults) {
        this.resultsDiv.innerHTML = `
          <div class="search-overlay__empty">
            <p>No results for "${this.searchField.value}".</p>
            ${this.renderSuggestions(this.searchField.value)}
          </div>
        `;
        this.isSpinnerVisible = false;
        return;
      }

      this.resultsDiv.innerHTML = `
        <div class="row">

          <div class="one-third">
            ${this.renderSection("General Information", results.generalInfo)}
            ${this.renderSection("Artisans", results.professors)}
          </div>

          <div class="one-third">
            ${this.renderSection("Pastry Case", results.pastry_case)}
          </div>

          <div class="one-third">
            ${this.renderSection("Locales", results.locale)}
            ${this.renderSection("Journal", results.journal)}
          </div>

        </div>
      `;

      this.isSpinnerVisible = false;
    } catch (e) {
      console.error("Search error:", e);
      this.resultsDiv.innerHTML = "<p>Unexpected error. Please try again.</p>";
    }
  }

  // Key interactions
  keyPressDispatcher(e) {
    if (
      e.keyCode === 83 && // "s"
      !this.isOverlayOpen &&
      document.activeElement.tagName !== "INPUT" &&
      document.activeElement.tagName !== "TEXTAREA"
    ) {
      this.openOverlay();
    }

    if (e.keyCode === 27 && this.isOverlayOpen) {
      this.closeOverlay();
    }
  }

  // Open / Close Overlay
  openOverlay() {
    this.searchOverlay.classList.add("search-overlay--active");
    document.body.classList.add("body-no-scroll");
    this.searchField.value = "";
    setTimeout(() => this.searchField.focus(), 301);
    this.isOverlayOpen = true;
  }

  closeOverlay() {
    this.searchOverlay.classList.remove("search-overlay--active");
    document.body.classList.remove("body-no-scroll");
    this.isOverlayOpen = false;
  }

  // Insert Search Overlay HTML
  addSearchHTML() {
    document.body.insertAdjacentHTML(
      "beforeend",
      `
      <div class="search-overlay">
        <div class="search-overlay__top">
          <div class="container">
            <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
            <label for="search-term" class="sr-only">Search</label>
            <input
              type="text"
              class="search-term"
              placeholder="Search desserts, locales, journal…"
              id="search-term"
            >
            <button class="fa fa-window-close search-overlay__close" aria-hidden="true"></button>
          </div>
        </div>

        <div class="container">
          <div id="search-overlay__results"></div>
          <div class="search-overlay__backup">
            <a href="${pastryData.root_url}/search">
              View full search page →
            </a>
          </div>
        </div>
      </div>
    `,
    );
  }
}

export default Search;
