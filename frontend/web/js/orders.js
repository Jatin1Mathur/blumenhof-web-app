(() => {
    'use strict';

    document.addEventListener('DOMContentLoaded', () => {
        const page = document.querySelector('[data-orders-page]');

        if (!page) {
            return;
        }

        const searchInput = page.querySelector('[data-order-search]');
        const statusSelect = page.querySelector('[data-order-status]');
        const deliverySelect = page.querySelector('[data-order-delivery]');
        const resetButton = page.querySelector('[data-order-reset]');
        const overdueButton = page.querySelector('[data-show-overdue]');
        const emptyState = page.querySelector('[data-order-empty]');
        const rows = [...page.querySelectorAll('[data-order-item]')];

        const normalise = (value) => (
            String(value || '').trim().toLowerCase()
        );

        const applyFilters = () => {
            const searchValue = normalise(searchInput?.value);
            const statusValue = normalise(statusSelect?.value);
            const deliveryValue = normalise(deliverySelect?.value);

            let visibleCount = 0;

            rows.forEach((row) => {
                const rowSearch = normalise(row.dataset.orderSearch);
                const rowStatus = normalise(row.dataset.orderStatus);
                const rowDelivery = normalise(row.dataset.orderDelivery);

                const searchMatches =
                    searchValue === ''
                    || rowSearch.includes(searchValue);

                const statusMatches =
                    statusValue === ''
                    || rowStatus === statusValue;

                const deliveryMatches =
                    deliveryValue === ''
                    || rowDelivery === deliveryValue;

                const visible =
                    searchMatches
                    && statusMatches
                    && deliveryMatches;

                row.hidden = !visible;

                if (visible) {
                    visibleCount += 1;
                }
            });

            if (emptyState) {
                emptyState.hidden = visibleCount > 0;
            }
        };

        searchInput?.addEventListener('input', applyFilters);
        searchInput?.addEventListener('search', applyFilters);
        statusSelect?.addEventListener('change', applyFilters);
        deliverySelect?.addEventListener('change', applyFilters);

        resetButton?.addEventListener('click', () => {
            if (searchInput) {
                searchInput.value = '';
            }

            if (statusSelect) {
                statusSelect.value = '';
            }

            if (deliverySelect) {
                deliverySelect.value = '';
            }

            applyFilters();
        });

        overdueButton?.addEventListener('click', () => {
            if (deliverySelect) {
                deliverySelect.value = 'overdue';
            }

            applyFilters();
        });

        page.querySelectorAll('[data-status-shortcut]').forEach((button) => {
            button.addEventListener('click', () => {
                if (statusSelect) {
                    statusSelect.value =
                        button.dataset.statusShortcut || '';
                }

                applyFilters();
            });
        });
    });
})();
