(function () {
    'use strict';

    function loadPaginatedContent(url, container) {
        const tbody = container.querySelector('table tbody');
        const paginationRow = container.querySelector('.table-pagination-row');

        if (!tbody || !paginationRow) {
            return Promise.reject(new Error('Pagination container incomplete'));
        }

        const summaryEl = paginationRow.querySelector('.pagination-summary-text');
        const navGroup = paginationRow.querySelector('.pagination-nav-group');
        const key = container.getAttribute('data-ajax-pagination');

        container.classList.add('ajax-pagination-loading');

        return fetch(url, {
            headers: {
                'Accept': 'text/html',
            },
        })
            .then(function (res) {
                if (!res.ok) throw new Error('Failed to load page');
                return res.text();
            })
            .then(function (html) {
                const doc = new DOMParser().parseFromString(html, 'text/html');
                const newContainer = doc.querySelector('[data-ajax-pagination="' + key + '"]');

                if (!newContainer) throw new Error('Pagination target not found');

                const newTbody = newContainer.querySelector('table tbody');
                const newPaginationRow = newContainer.querySelector('.table-pagination-row');

                if (newTbody) tbody.innerHTML = newTbody.innerHTML;

                if (newPaginationRow) {
                    const newSummary = newPaginationRow.querySelector('.pagination-summary-text');
                    const newNav = newPaginationRow.querySelector('.pagination-nav-group');

                    if (summaryEl && newSummary) {
                        summaryEl.innerHTML = newSummary.innerHTML;
                    }
                    if (navGroup && newNav) {
                        navGroup.innerHTML = newNav.innerHTML;
                    }
                }

                history.pushState({ ajaxPagination: true }, '', url);

                container.scrollIntoView({ behavior: 'smooth', block: 'start' });

                document.dispatchEvent(new CustomEvent('ajaxPagination:updated', {
                    detail: { container, url },
                }));
            })
            .finally(function () {
                container.classList.remove('ajax-pagination-loading');
            });
    }

    document.addEventListener('click', function (e) {
        const link = e.target.closest('[data-ajax-pagination] .pagination-nav-group a.page-link');
        if (!link) return;

        const pageItem = link.closest('.page-item');
        if (pageItem && pageItem.classList.contains('disabled')) return;

        const container = link.closest('[data-ajax-pagination]');
        if (!container || !link.href) return;

        e.preventDefault();

        loadPaginatedContent(link.href, container).catch(function () {
            window.location.href = link.href;
        });
    });

    window.addEventListener('popstate', function () {
        const container = document.querySelector('[data-ajax-pagination]');
        if (!container) return;

        loadPaginatedContent(window.location.href, container).catch(function () {
            window.location.reload();
        });
    });
})();
