import "./bootstrap";

// import Swiper from "swiper";
// import "swiper/css";
// import "swiper/css/navigation";
// import "swiper/css/pagination";

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
                            <a href="/kbli/${result.kbli_id}" class="flex flex-col">
                                <strong>${result.kode} - ${result.nama}</strong>
                                <span class="text-sm text-gray-600 dark:text-gray-400">${result.ruang_lingkup}</span>
                            </a>
                        `;
                        li.addEventListener("click", () => {
                            window.location.href = `/kbli/${result.kbli_id}`;
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

// LIVE SEARCH PBUMKU
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input');
    const suggestionsList = document.getElementById('search-suggestions');
    const dinasIdInput = document.getElementById('dinas_id');

    // Only run if search input exists (to avoid errors on other pages)
    if (searchInput && suggestionsList) {
        const liveSearchUrl = searchInput.dataset.liveSearchUrl;
        const dinasId = dinasIdInput ? dinasIdInput.value : '';

        searchInput.addEventListener('input', async function () {
            const query = this.value.trim();

            if (query.length < 2) {
                suggestionsList.innerHTML = '';
                suggestionsList.classList.add('hidden');
                return;
            }

            try {
                const url = new URL(liveSearchUrl, window.location.origin);
                if (query) url.searchParams.append('query', query);
                if (dinasId) url.searchParams.append('dinas_id', dinasId);

                const response = await fetch(url);
                const results = await response.json();

                suggestionsList.innerHTML = '';
                if (results.length > 0) {
                    results.forEach(result => {
                        const li = document.createElement('li');
                        li.innerHTML = `<a href="${result.search_url}" class="text-base-content hover:bg-blue-600 hover:text-white">${result.nama}</a>`;
                        suggestionsList.appendChild(li);
                    });
                    suggestionsList.classList.remove('hidden');
                } else {
                    suggestionsList.classList.add('hidden');
                }
            } catch (error) {
                console.error('Error fetching suggestions:', error);
                suggestionsList.innerHTML = '';
                suggestionsList.classList.add('hidden');
            }
        });

        document.addEventListener('click', function (event) {
            if (!searchInput.contains(event.target) && !suggestionsList.contains(event.target)) {
                suggestionsList.classList.add('hidden');
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", () => {
    console.log("DOM loaded, initializing Swiper");
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
        console.log("Swiper initialized");
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
