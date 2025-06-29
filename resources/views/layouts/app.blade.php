<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог товаров</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
</head>

<body>
    <div class="container py-4">
        @if(Request::routeIs('group') || Request::routeIs('product'))
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    @yield('breadcrumbs')
                </ol>
            </nav>
        @endif

        @yield('content')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Постоянные переменные
            window.groupIds = @json($groupIds ?? []);
            const groupIds = window.groupIds || [];

            const config = {
                selectors: {
                    container: '#products-container',
                    pagination: '#pagination-links',
                    sortSelect: '#sort-select',
                    perPageSelect: '#per-page-select',
                },
                urls: {
                    api: '/api/products'
                }
            };

            // Инициализация
            const init = () => {
                setupEventListeners();
                loadProducts();
            };

            // Настройка обработчиков событий
            const setupEventListeners = () => {
                $(document).on('click', '.page-link', handlePaginationClick);
                $('#sort-select, #per-page-select').on('change', function () {
                    loadProducts();
                });
            };

            // Загрузка товаров
            const loadProducts = (url = null) => {
                showLoader();

                const params = {
                    sort: $(config.selectors.sortSelect).val(),
                    per_page: $(config.selectors.perPageSelect).val(),
                    page: getPageFromUrl(url),
                    groupIds: groupIds.join(',')
                };

                $.ajax({
                    url: url || config.urls.api,
                    data: params,
                    success: handleSuccess,
                    error: handleError
                });
            };

            // Обработка успешного ответа
            const handleSuccess = (response) => {
                renderProducts(response.data);
                renderPagination(response);
            };

            // Обработка ошибок
            const handleError = (xhr) => {
                const errorMessage = xhr.responseJSON?.message || 'Ошибка загрузки товаров';
                showError(errorMessage);
            };

            // Клик по пагинации
            const handlePaginationClick = (e) => {
                e.preventDefault();
                loadProducts($(e.target).attr('href'));
            };

            // Показать загрузчик
            const showLoader = () => {
                $(config.selectors.container).html(`
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary"></div>
                <p class="mt-2">Загрузка...</p>
            </div>
        `);
            };

            // Показать ошибку
            const showError = (message) => {
                $(config.selectors.container).html(`
            <div class="col-12">
                <div class="alert alert-danger">
                    ${message}
                    <button onclick="window.location.reload()" class="btn btn-sm btn-outline-secondary ms-2">
                        Обновить
                    </button>
                </div>
            </div>
        `);
            };

            // Рендер товаров
            const renderProducts = (products) => {
                if (!products || products.length === 0) {
                    $(config.selectors.container).html(`
                <div class="col-12">
                    <div class="alert alert-info">Товары не найдены</div>
                </div>
            `);
                    return;
                }

                let html = '';
                products.forEach(product => {
                    html += `
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title">${escapeHtml(product.name)}</h6>
                            <p class="card-text text-success fw-bold mb-1">
                                ${product.price} ₽
                            </p>
                            <p class="small text-muted mb-2">
                                Категория: ${escapeHtml(product.group_name)}
                            </p>
                            <a href="/product/${product.id}" class="btn btn-sm btn-outline-primary">
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            `;
                });

                $(config.selectors.container).html(html);
            };

            // Рендер пагинации
            const renderPagination = (response) => {
                if (!response.meta || !response.links) {
                    $(config.selectors.pagination).empty();
                    return;
                }

                const { current_page, last_page, path } = response.meta;
                let html = '<ul class="pagination justify-content-center flex-wrap">';

                if (response.links.prev) {
                    html += `
                <li class="page-item">
                    <a class="page-link" href="${response.links.prev}">&laquo;</a>
                </li>
            `;
                }

                const visiblePages = getVisiblePages(current_page, last_page);
                visiblePages.forEach((page, index) => {
                    if (page === '...') {
                        html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    } else {
                        html += `
                    <li class="page-item ${page === current_page ? 'active' : ''}">
                        <a class="page-link" href="${path}?page=${page}">${page}</a>
                    </li>
                `;
                    }
                });

                if (response.links.next) {
                    html += `
                <li class="page-item">
                    <a class="page-link" href="${response.links.next}">&raquo;</a>
                </li>
            `;
                }

                html += '</ul>';
                $(config.selectors.pagination).html(html);
            };

            // Вспомогательные функции
            const getPageFromUrl = (url) => {
                return url ? new URL(url).searchParams.get('page') : 1;
            };

            const getVisiblePages = (current, last) => {
                const range = 2;
                let pages = [];

                for (let i = 1; i <= last; i++) {
                    if (i === 1 || i === last || (i >= current - range && i <= current + range)) {
                        pages.push(i);
                    } else if (pages[pages.length - 1] !== '...') {
                        pages.push('...');
                    }
                }

                return pages;
            };

            const escapeHtml = (unsafe) => {
                return unsafe
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            };

            // Запуск
            init();
        });
    </script>
</body>

</html>