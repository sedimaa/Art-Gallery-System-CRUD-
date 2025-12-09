document.addEventListener("DOMContentLoaded", () => {
  console.log("[gallery] searchbar.js loaded");

  const input = document.getElementById("gallery-q");
  const grid  = document.getElementById("gallery-grid");
  const empty = document.querySelector(".gallery-empty");
  const form  = document.getElementById("gallery-search") || input?.closest("form");

  if (!input || !grid) {
    console.warn("[gallery] Missing #gallery-q or #gallery-grid");
    return;
  }

  if (form) form.addEventListener("submit", (e) => e.preventDefault());

  const items = Array.from(grid.querySelectorAll(".art-item"));

  function filterArtworks(q) {
    const term = (q || "").toLowerCase().trim();
    let shown = 0;

    items.forEach((el) => {
      const title  = (el.dataset.title  || "").toLowerCase();
      const artist = (el.dataset.artist || "").toLowerCase();
      const genre  = (el.dataset.genre  || "").toLowerCase();

      const match =
        !term ||
        title.includes(term) ||
        artist.includes(term) ||
        genre.includes(term);

      el.style.display = match ? "" : "none";
      if (match) shown++;
    });

    if (empty) empty.style.display = shown ? "none" : "block";
  }

  let t;
  input.addEventListener("input", () => {
    clearTimeout(t);
    t = setTimeout(() => filterArtworks(input.value), 120);
  });

  grid.addEventListener("click", (e) => {
    const tag = e.target.closest(".genre-tag");
    if (!tag) return;
    input.value = (tag.textContent || "").trim();
    filterArtworks(input.value);
    input.focus();
  });

  filterArtworks("");
});

