document.getElementById("toggleFilter").addEventListener("click", function () {
    const box = document.getElementById("inlineFilterBox");
    box.style.display =
        box.style.display === "none" || box.style.display === ""
            ? "block"
            : "none";
});

document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("search-input");
    const suggestions = document.getElementById("search-suggestions");
    let debounceTimer;

    // Helper to update ARIA expanded state
    function setAriaExpanded(expanded) {
        input.setAttribute("aria-expanded", expanded ? "true" : "false");
    }

    input.addEventListener("input", function () {
        clearTimeout(debounceTimer);
        const query = this.value.trim();

        if (!query) {
            suggestions.style.display = "none";
            suggestions.innerHTML = "";
            setAriaExpanded(false);
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(
                `{{ route('customers.index') }}?q=${encodeURIComponent(
                    query
                )}&ajax=1`
            )
                .then((res) => res.json())
                .then((data) => {
                    if (Object.keys(data).length === 0) {
                        suggestions.innerHTML = `<li class="list-group-item text-muted" role="option" tabindex="-1">No results found</li>`;
                        suggestions.style.display = "block";
                        setAriaExpanded(true);
                        return;
                    }

                    let html = "";

                    for (const [type, values] of Object.entries(data)) {
                        html += `<li class="list-group-item bg-light fw-bold text-primary" role="presentation">${type}</li>`;
                        values.forEach((value) => {
                            html += `
                                    <li class="list-group-item list-group-item-action suggestion-item" role="option" tabindex="0" data-value="${value}">
                                        ${value}
                                    </li>
                                `;
                        });
                    }

                    suggestions.innerHTML = html;
                    suggestions.style.display = "block";
                    setAriaExpanded(true);
                })
                .catch((err) => {
                    suggestions.innerHTML = `<li class="list-group-item text-danger" role="option" tabindex="-1">Error loading</li>`;
                    suggestions.style.display = "block";
                    setAriaExpanded(true);
                });
        }, 300);
    });

    // Click and keyboard handler for suggestions
    suggestions.addEventListener("click", (e) => {
        const li = e.target.closest("li.suggestion-item");
        if (li) {
            input.value = li.dataset.value;
            suggestions.style.display = "none";
            setAriaExpanded(false);
            document.getElementById("top-search-bar").submit();
        }
    });

    suggestions.addEventListener("keydown", (e) => {
        const active = document.activeElement;
        if (!active.classList.contains("suggestion-item")) return;

        if (e.key === "Enter" || e.key === " ") {
            e.preventDefault();
            active.click();
        } else if (e.key === "ArrowDown") {
            e.preventDefault();
            const next = active.nextElementSibling;
            if (next && next.classList.contains("suggestion-item"))
                next.focus();
        } else if (e.key === "ArrowUp") {
            e.preventDefault();
            const prev = active.previousElementSibling;
            if (prev && prev.classList.contains("suggestion-item"))
                prev.focus();
            else input.focus();
        }
    });

    // Hide suggestions if clicked or focused outside
    document.addEventListener("click", (e) => {
        if (!input.contains(e.target) && !suggestions.contains(e.target)) {
            suggestions.style.display = "none";
            setAriaExpanded(false);
        }
    });
});
