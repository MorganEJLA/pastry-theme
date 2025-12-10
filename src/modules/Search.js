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

  // Fetch Results
  async getResults() {
    try {
      const response = await axios.get(
        `${pastryData.root_url}/wp-json/pastry/v1/search?term=${this.searchField.value}`
      );

      const results = response.data;

      this.resultsDiv.innerHTML = `
        <div class="row">

          <!-- GENERAL INFO -->
          <div class="one-third">
            <h2 class="search-overlay__section-title">General Information</h2>
            ${
              results.generalInfo.length
                ? '<ul class="link-list min-list">'
                : "<p>No general information matches that search.</p>"
            }
              ${results.generalInfo
                .map(
                  (item) => `
                    <li>
                      <a href="${item.permalink}">${item.title}</a>
                    </li>
                  `
                )
                .join("")}
            ${results.generalInfo.length ? "</ul>" : ""}
          </div>

          <!-- FEATURED DESSERTS -->
          <div class="one-third">
            <h2 class="search-overlay__section-title">Featured Desserts</h2>
            ${
              results.event.length
                ? '<ul class="link-list min-list">'
                : "<p>No featured desserts match that search.</p>"
            }
              ${results.event
                .map(
                  (item) => `
                    <li>
                      <a href="${item.permalink}">${item.title}</a>
                    </li>
                  `
                )
                .join("")}
            ${results.event.length ? "</ul>" : ""}

            <h2 class="search-overlay__section-title">Pastry Case</h2>
            ${
              results.pastry_case.length
                ? '<ul class="link-list min-list">'
                : "<p>No pastry case items match that search.</p>"
            }
              ${results.pastry_case
                .map(
                  (item) => `
                    <li>
                      <a href="${item.permalink}">${item.title}</a>
                    </li>
                  `
                )
                .join("")}
            ${results.pastry_case.length ? "</ul>" : ""}
          </div>

          <!-- LOCALES + JOURNAL -->
          <div class="one-third">
            <h2 class="search-overlay__section-title">Locales</h2>
            ${
              results.locale.length
                ? '<ul class="link-list min-list">'
                : "<p>No locales match that search.</p>"
            }
              ${results.locale
                .map(
                  (item) => `
                    <li>
                      <a href="${item.permalink}">${item.title}</a>
                    </li>
                  `
                )
                .join("")}
            ${results.locale.length ? "</ul>" : ""}

            <h2 class="search-overlay__section-title">Journal</h2>
            ${
              results.journal.length
                ? '<ul class="link-list min-list">'
                : "<p>No journal entries match that search.</p>"
            }
              ${results.journal
                .map(
                  (item) => `
                    <li>
                      <a href="${item.permalink}">${item.title}</a>
                    </li>
                  `
                )
                .join("")}
            ${results.journal.length ? "</ul>" : ""}
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
            <input
              type="text"
              class="search-term"
              placeholder="Search desserts, locales, journal…"
              id="search-term"
            >
            <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
          </div>
        </div>

        <div class="container">
          <div id="search-overlay__results"></div>
        </div>
      </div>
    `
    );
  }
}

export default Search;
