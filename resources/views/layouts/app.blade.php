<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>NexaTrack - Stay Ahead. Stay Connected</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/icon.JPG') }}" type="image/png" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/master.css') }}">
    @stack('styles')
</head>

<body class="d-flex flex-column min-vh-100 bg-light text-dark">
    <!-- Fixed Top Navbar -->
    <header class="navbar-glass shadow-sm" role="banner">
        <div class="container py-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <a href="{{ route('home') }}" aria-label="Go to NexaTrack home page">
                <img src="{{ asset('images/logo2.png') }}" class="logo-img" alt="NexaTrack Logo" />
            </a>

            <nav class="d-flex gap-3 align-items-center" role="navigation" aria-label="Primary navigation">
                <a href="{{ route('home') }}" class="parallelogram-hover" tabindex="0">Dashboard</a>
                <a href="{{ route('customers.index') }}" class="parallelogram-hover" tabindex="0">Customer List</a>
            </nav>
        </div>
    </header>

    <!-- Fixed Search Bar -->
    <div class="search-wrapper" role="search" aria-label="Site search">
        <div class="container">
            <form id="top-search-bar" action="{{ route('customers.index') }}" method="GET"
                class="d-flex justify-content-end align-items-center flex-wrap gap-2 position-relative" role="search">

                <!-- Search Input -->
                <div class="position-relative">
                    <label for="search-input" class="visually-hidden">Search contacts</label>
                    <input type="search" name="q" id="search-input" class="form-control" placeholder="Search"
                        value="{{ request('q') }}" autocomplete="off" aria-controls="search-suggestions"
                        aria-expanded="false" aria-haspopup="listbox" aria-label="Search contacts" />

                    <ul id="search-suggestions" class="list-group position-absolute mt-1 shadow-sm bg-white w-100"
                        role="listbox" style="top: 100%; display: none;"></ul>
                </div>

                <!-- Filter Button -->
                <button type="button" class="btn btn-primary" id="toggleFilter" aria-expanded="false">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>

                <!-- Inline Filters -->
                <div id="inlineFilterBox"
                    class="card shadow-lg border-0 position-absolute top-100 mt-2 end-0 w-100 w-md-75"
                    style="display: none; z-index: 1070;">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-primary fw-bold">
                            <i class="fas fa-filter me-2"></i>Advanced Filters
                        </h6>
                        <button type="button" class="btn-close" aria-label="Close"
                            onclick="document.getElementById('inlineFilterBox').style.display='none'"></button>
                    </div>

                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Filter Field -->
                            <div class="col-md-4">
                                <label for="filter_field" class="form-label fw-semibold text-secondary">Filter
                                    By</label>
                                <select name="filter_field" id="filter_field" class="form-select">
                                    <option value="">-- Select Field --</option>
                                    <option value="area" {{ request('filter_field') == 'area' ? 'selected' : '' }}>
                                        Area
                                    </option>
                                    <option value="city" {{ request('filter_field') == 'city' ? 'selected' : '' }}>
                                        City
                                    </option>
                                    <option value="country"
                                        {{ request('filter_field') == 'country' ? 'selected' : '' }}>
                                        Country
                                    </option>
                                    <option value="source" {{ request('filter_field') == 'source' ? 'selected' : '' }}>
                                        Source
                                    </option>
                                    <option value="software"
                                        {{ request('filter_field') == 'software' ? 'selected' : '' }}>
                                        Software
                                    </option>
                                </select>
                            </div>

                            <!-- Filter Value (populated via JS) -->
                            <div class="col-md-4">
                                <label for="filter_value" class="form-label fw-semibold text-secondary">Select</label>
                                <select name="filter_value" id="filter_value" class="form-select">
                                    <option value="">-- Select --</option>
                                </select>
                            </div>

                            <!-- Filter Button -->
                            <div class="col-12 text-end">
                                <button type="button" id="applyFiltersBtn" class="btn btn-success px-4">
                                    <i class="fas fa-check me-1"></i> Apply Filters
                                </button>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content container py-4 flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white text-center text-muted py-3 border-top mt-auto" role="contentinfo">
        &copy; {{ date('Y') }}
        <a href="https://totalofftec.com" target="_blank" class="text-decoration-none fw-semibold text-dark"
            rel="noopener noreferrer">
            TOTALOFFTEC
        </a>. All rights reserved.
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const baseUrl = @json(route('customers.index'));
            const filterRoute = @json(route('customers.filter'));

            const filterBtn = document.getElementById('toggleFilter');
            const filterBox = document.getElementById('inlineFilterBox');
            const input = document.getElementById('search-input');
            const suggestions = document.getElementById('search-suggestions');
            const filterFieldSelect = document.getElementById('filter_field');
            const filterValueSelect = document.getElementById('filter_value');
            const applyFiltersBtn = document.getElementById('applyFiltersBtn');
            const form = document.getElementById('top-search-bar');
            let debounceTimer;

            // ✅ Restore filter state from query params (if any)
            const urlParams = new URLSearchParams(window.location.search);
            const preselectedField = urlParams.get('filter_field');
            const preselectedValue = urlParams.get('filter_value');

            if (preselectedField && filterFieldSelect) {
                filterFieldSelect.value = preselectedField;

                // Load value options based on selected field
                const filterUrl = `${baseUrl}?ajax=1&filter_field=${encodeURIComponent(preselectedField)}`;
                fetch(filterUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    })
                    .then(res => res.ok ? res.json() : Promise.reject())
                    .then(values => {
                        let options = `<option value="">-- Select --</option>`;
                        values.forEach(val => {
                            const selected = val === preselectedValue ? 'selected' : '';
                            options += `<option value="${val}" ${selected}>${val}</option>`;
                        });
                        filterValueSelect.innerHTML = options;
                    })
                    .catch(() => {
                        filterValueSelect.innerHTML = `<option value="">Error loading</option>`;
                    });
            }

            // ✅ Toggle filter box visibility
            filterBtn?.addEventListener('click', () => {
                const isVisible = filterBox.style.display === 'block';
                filterBox.style.display = isVisible ? 'none' : 'block';
                filterBtn.setAttribute('aria-expanded', String(!isVisible));
            });

            // ✅ Close filter box when clicking outside
            document.addEventListener('click', (e) => {
                if (filterBox && !filterBox.contains(e.target) && !filterBtn.contains(e.target)) {
                    filterBox.style.display = 'none';
                    filterBtn.setAttribute('aria-expanded', 'false');
                }
            });

            // ✅ Apply filter (opens new tab only if both selected)
            applyFiltersBtn?.addEventListener('click', () => {
                const field = filterFieldSelect.value.trim();
                const value = filterValueSelect.value.trim();

                const url = (field && value) ?
                    `${filterRoute}?filter_field=${encodeURIComponent(field)}&filter_value=${encodeURIComponent(value)}` :
                    baseUrl;

                window.open(url, '_blank');
            });

            // ✅ Dynamic filter: populate values based on selected field
            filterFieldSelect?.addEventListener('change', () => {
                const selectedField = filterFieldSelect.value;
                filterValueSelect.innerHTML = `<option value="">-- Select --</option>`;
                if (!selectedField) return;

                const filterUrl = `${baseUrl}?ajax=1&filter_field=${encodeURIComponent(selectedField)}`;
                fetch(filterUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    })
                    .then(res => res.ok ? res.json() : Promise.reject())
                    .then(values => {
                        let options = `<option value="">-- Select --</option>`;
                        values.forEach(val => {
                            options += `<option value="${val}">${val}</option>`;
                        });
                        filterValueSelect.innerHTML = options;
                    })
                    .catch(() => {
                        filterValueSelect.innerHTML = `<option value="">Error loading</option>`;
                    });
            });

            // ✅ Live search suggestions
            const setAriaExpanded = (state) => {
                input?.setAttribute('aria-expanded', state ? 'true' : 'false');
            };

            input?.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                const query = input.value.trim();

                if (!query) {
                    suggestions.innerHTML = '';
                    suggestions.style.display = 'none';
                    setAriaExpanded(false);
                    return;
                }

                debounceTimer = setTimeout(() => {
                    const url = `${baseUrl}?ajax=1&q=${encodeURIComponent(query)}`;

                    fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin'
                        })
                        .then(res => res.ok ? res.json() : Promise.reject())
                        .then(data => {
                            if (!data || Object.keys(data).length === 0) {
                                suggestions.innerHTML =
                                    `<li class="list-group-item text-muted">No results found</li>`;
                                suggestions.style.display = 'block';
                                setAriaExpanded(true);
                                return;
                            }

                            let html = '';
                            for (const [label, values] of Object.entries(data)) {
                                html +=
                                    `<li class="list-group-item bg-light fw-bold text-primary">${label}</li>`;
                                values.forEach(val => {
                                    html +=
                                        `<li class="list-group-item list-group-item-action suggestion-item" data-value="${val}" role="option" tabindex="0">${val}</li>`;
                                });
                            }

                            suggestions.innerHTML = html;
                            suggestions.style.display = 'block';
                            setAriaExpanded(true);
                        })
                        .catch(() => {
                            suggestions.innerHTML =
                                `<li class="list-group-item text-danger">Error loading suggestions</li>`;
                            suggestions.style.display = 'block';
                            setAriaExpanded(true);
                        });
                }, 300);
            });

            // ✅ Suggestion click
            suggestions?.addEventListener('click', (e) => {
                const item = e.target.closest('.suggestion-item');
                if (item) {
                    input.value = item.dataset.value;
                    suggestions.style.display = 'none';
                    setAriaExpanded(false);
                    form?.submit();
                }
            });

            // ✅ Click outside hides suggestion box
            document.addEventListener('click', (e) => {
                if (!input?.contains(e.target) && !suggestions?.contains(e.target)) {
                    suggestions.style.display = 'none';
                    setAriaExpanded(false);
                }
            });
        });
    </script>

</body>

</html>
