import "./bootstrap";

// import Swiper from "swiper";
// import "swiper/css";
// import "swiper/css/navigation";
// import "swiper/css/pagination";

// // Theme Toggle Function
// const toggleTheme = () => {
//     const themeController = document.querySelector(".theme-controller");
//     const currentTheme = document.documentElement.getAttribute("data-theme");
//     const newTheme = currentTheme === "synthwave" ? "light" : "synthwave";

//     document.documentElement.setAttribute("data-theme", newTheme);
//     themeController.checked = newTheme === "synthwave";
//     localStorage.setItem("theme", newTheme);
//     console.log("Theme switched to:", newTheme); // Debug
// };

// // Initialize theme from localStorage
// const savedTheme = localStorage.getItem("theme") || "light";
// document.documentElement.setAttribute("data-theme", savedTheme);
// document.querySelector(".theme-controller").checked =
//     savedTheme === "synthwave";

// // Attach toggleTheme to buttons
// document.querySelectorAll('[onclick="toggleTheme()"]').forEach((button) => {
//     button.addEventListener("click", toggleTheme);
// });

//LIVE SEARCH KBLI
document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("search-input");
    const suggestionsContainer = document.getElementById("suggestions");
    const suggestionsList = document.getElementById("suggestions-list");

    if (searchInput && suggestionsContainer && suggestionsList) {
        searchInput.addEventListener("input", async function () {
            const query = this.value.trim();

            if (query.length < 2) {
                suggestionsContainer.classList.add("hidden");
                suggestionsList.innerHTML = "";
                return;
            }

            try {
                const response = await fetch(
                    `/kbli/live-search?query=${encodeURIComponent(query)}`
                );
                const results = await response.json();

                suggestionsList.innerHTML = "";
                if (results.length > 0) {
                    results.forEach((result) => {
                        const li = document.createElement("li");
                        li.className =
                            "px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer";
                        li.innerHTML = `
                            <a href="/kbli/${result.slug}" class="flex flex-col">
                                <strong>${result.kode} - ${result.name}</strong>
                                <span class="text-sm text-gray-600 dark:text-gray-400">${result.scope}</span>
                            </a>
                        `;
                        li.addEventListener("click", () => {
                            window.location.href = `/kbli/${result.slug}`;
                        });
                        suggestionsList.appendChild(li);
                    });
                    suggestionsContainer.classList.remove("hidden");
                } else {
                    suggestionsContainer.classList.add("hidden");
                }
            } catch (error) {
                console.error("Error fetching suggestions:", error);
                suggestionsContainer.classList.add("hidden");
            }
        });

        // Sembunyikan dropdown saat klik di luar
        document.addEventListener("click", function (e) {
            if (
                !suggestionsContainer.contains(e.target) &&
                e.target !== searchInput
            ) {
                suggestionsContainer.classList.add("hidden");
            }
        });
    }
});

// Debounce function untuk menunda fetch request
function debounce(func, wait) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

// Live Search untuk PBUMKU
document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("pbumku-search-input");
    const suggestionsList = document.getElementById("pbumku-suggestions-list");
    const suggestionsContainer = document.getElementById("pbumku-suggestions");

    if (searchInput && suggestionsList && suggestionsContainer) {
        const debouncedSearch = debounce(async () => {
            const query = searchInput.value.trim();
            const liveSearchUrl = searchInput.getAttribute(
                "data-live-search-url"
            );
            const dinasId = document.getElementById("dinas_id")?.value || "";


            if (query.length < 2) {
                suggestionsContainer.classList.add("hidden");
                suggestionsList.innerHTML = "";
                return;
            }

            try {
                const response = await fetch(
                    `${liveSearchUrl}?query=${encodeURIComponent(
                        query
                    )}&dinas_id=${encodeURIComponent(dinasId)}`
                );
                const results = await response.json();

                suggestionsList.innerHTML = "";
                if (results.length > 0) {
                    results.forEach((result) => {
                        const li = document.createElement("li");
                        li.className =
                            "px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer";
                        li.innerHTML = `
                            <a href="/pbumku/${result.slug}" class="flex flex-col">
                                <strong>${result.name}</strong>
                                <span class="text-sm text-gray-600 dark:text-gray-400">${result.dinas}</span>
                            </a>
                        `;
                        li.addEventListener("click", () => {
                            window.location.href = `/pbumku/${result.slug}`;
                        });
                        suggestionsList.appendChild(li);
                    });
                    suggestionsContainer.classList.remove("hidden");
                } else {
                    suggestionsContainer.classList.add("hidden");
                }
            } catch (error) {
                console.error("Error fetching PBUMKU suggestions:", error);
                suggestionsContainer.classList.add("hidden");
            }
        }, 300); // Jeda 300ms

        searchInput.addEventListener("input", debouncedSearch);

        // Sembunyikan dropdown saat klik di luar
        document.addEventListener("click", (event) => {
            if (
                !suggestionsContainer.contains(event.target) &&
                event.target !== searchInput
            ) {
                suggestionsContainer.classList.add("hidden");
            }
        });
    }
});

// PAGINATION BERANDA ATAS
document.addEventListener("DOMContentLoaded", () => {
    try {
        new Swiper(".carouselUtama", {
            centeredSlides: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                0: {
                    // spaceBetween: 0,
                    navigation: { enabled: false },
                    pagination: { enabled: true },
                },
                640: {
                    // spaceBetween: 10,
                    navigation: { enabled: true },
                    pagination: { enabled: true },
                },
                1024: {
                    // spaceBetween: 0,
                    navigation: { enabled: true },
                    pagination: { enabled: true },
                },
            },
        });
    } catch (error) {
        console.error("Swiper initialization failed:", error);
    }

    // try {
    //     new Swiper(".cardSwiper", {
    //         slidesPerView: 3,
    //         spaceBetween: 30,
    //         freeMode: true,
    //         pagination: {
    //             el: ".card-swiper-pagination",
    //             clickable: true,
    //         },
    //         breakpoints: {
    //             0: {
    //                 slidesPerView: 1,
    //                 spaceBetween: 10,
    //             },
    //             640: {
    //                 slidesPerView: 2,
    //                 spaceBetween: 20,
    //             },
    //             1024: {
    //                 slidesPerView: 3,
    //                 spaceBetween: 30,
    //             },
    //         },
    //     });
    //     console.log("Card Swiper initialized");
    // } catch (error) {
    //     console.error("Card Swiper initialization failed:", error);
    // }

    var swiper1 = new Swiper(".swiper1", {
        pagination: ".swiper-pagination1",
        paginationClickable: true,
    });
    var swiper2 = new Swiper(".swiper2", {
        freeMode: true,
        // slidesPerView: 2,
        spaceBetween: 30,
        pagination: {
            el: ".swiper-pagination2",
            clickable: true,
        },
        paginationClickable: false,
        breakpoints: {
            0: {
                slidesPerView: 1,
                spaceBetween: 10,
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            1024: {
                slidesPerView: 2,
                spaceBetween: 30,
            },
        },
    });
});

// Quick search functionality for KBLI page
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.quick-search-btn').forEach(button => {
        button.addEventListener('click', function() {
            const searchTerm = this.getAttribute('data-search');
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.value = searchTerm;
                const form = document.querySelector('form[action*="kbli.search"]');
                if (form) form.submit();
            }
        });
    });
});

// PBUMKU page functionality
document.addEventListener('DOMContentLoaded', function() {
    // Quick search functionality for PBUMKU page
    document.querySelectorAll('.quick-search-btn').forEach(button => {
        button.addEventListener('click', function() {
            const searchTerm = this.getAttribute('data-search');
            const searchInput = document.getElementById('pbumku-search-input');
            if (searchInput) {
                searchInput.value = searchTerm;
                const form = document.querySelector('form[action*="pbumku.search"]');
                if (form) form.submit();
            }
        });
    });

    // Enhanced dropdown functionality (excluding language dropdowns)
    const dropdowns = document.querySelectorAll('.dropdown:not(.language-dropdown):not(.language-dropdown-mobile)');

    dropdowns.forEach(dropdown => {
        const button = dropdown.querySelector('[tabindex="0"]');
        const menu = dropdown.querySelector('.dropdown-content');

        if (button && menu) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const isOpen = !menu.classList.contains('hidden');

                // Close all other dropdowns (excluding language dropdowns)
                document.querySelectorAll('.dropdown-content').forEach(otherMenu => {
                    if (otherMenu !== menu && !otherMenu.closest('.language-dropdown') && !otherMenu.closest('.language-dropdown-mobile')) {
                        otherMenu.classList.add('hidden');
                    }
                });

                // Toggle current dropdown
                menu.classList.toggle('hidden');
            });
        }
    });

    // Close dropdowns when clicking outside (excluding language dropdowns)
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-content').forEach(menu => {
                if (!menu.closest('.language-dropdown') && !menu.closest('.language-dropdown-mobile')) {
                    menu.classList.add('hidden');
                }
            });
        }
    });

});
