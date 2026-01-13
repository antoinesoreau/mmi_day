document.addEventListener("DOMContentLoaded", () => {
  const faqItems = document.querySelectorAll(".faq-item");
  const filterSelect = document.getElementById("filterSelect");
  const noResultMsg = document.getElementById("no-result-msg");

  // Gestion de l'accordéon
  faqItems.forEach((item) => {
    const header = item.querySelector(".faq-header");
    header.addEventListener("click", () => {
      item.classList.toggle("active");
    });
  });

  // Gestion du filtre
  if (filterSelect) {
    filterSelect.addEventListener("change", (e) => {
      const filterValue = e.target.value;
      let visibleCount = 0; // On compte combien de questions restent affichées

      faqItems.forEach((item) => {
        const itemCategory = item.getAttribute("data-category");

        // Si "Tout voir" OU correspondance exacte
        // Note: on utilise trim() pour éviter les erreurs d'espaces
        if (
          filterValue === "all" ||
          itemCategory.trim() === filterValue.trim()
        ) {
          item.style.display = "block";
          visibleCount++;
        } else {
          item.style.display = "none";
          item.classList.remove("active");
        }
      });

      // Gestion du message "Aucun résultat"
      if (noResultMsg) {
        if (visibleCount === 0) {
          noResultMsg.style.display = "block";
        } else {
          noResultMsg.style.display = "none";
        }
      }
    });
  }
});
