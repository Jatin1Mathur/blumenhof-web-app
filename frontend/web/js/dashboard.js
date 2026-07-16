(() => {
    'use strict';

    document.addEventListener('DOMContentLoaded', () => {
        const page = document.querySelector('[data-dashboard]');

        if (!page) {
            return;
        }

        const colours = {
            green: '#527f27',
            lightGreen: '#8ebc55',
            paleGreen: 'rgba(82, 127, 39, 0.13)',
            blue: '#5488a8',
            amber: '#daa53d',
            yellow: '#ead26d',
            red: '#bd5b4b',
            grey: '#aab4ac',
            grid: 'rgba(55, 77, 57, 0.09)',
        };

        const setText = (id, value) => {
            const element = document.getElementById(id);

            if (element) {
                element.textContent = value;
            }
        };

        const fetchJson = async (url) => {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) {
                throw new Error('Dashboard request failed');
            }

            return response.json();
        };

        const showEmptyChart = (canvasId, message = 'No data available yet.') => {
            const canvas = document.getElementById(canvasId);

            if (!canvas?.parentElement) {
                return;
            }

            canvas.hidden = true;

            const empty = document.createElement('div');
            empty.className = 'dashboard-chart-empty';
            empty.textContent = message;

            canvas.parentElement.appendChild(empty);
        };

        const createChart = (canvasId, configuration, values = []) => {
            const canvas = document.getElementById(canvasId);

            if (!canvas || typeof Chart === 'undefined') {
                showEmptyChart(canvasId, 'Chart library could not be loaded.');
                return;
            }

            const hasData = values.some((value) => Number(value) !== 0);

            if (!hasData) {
                showEmptyChart(canvasId);
                return;
            }

            new Chart(canvas.getContext('2d'), configuration);
        };

        if (typeof Chart !== 'undefined') {
            Chart.defaults.color = '#657168';
            Chart.defaults.font.family =
                'Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif';

            Chart.defaults.plugins.tooltip.backgroundColor =
                'rgba(29, 41, 32, 0.94)';

            Chart.defaults.plugins.tooltip.padding = 12;
            Chart.defaults.plugins.tooltip.cornerRadius = 9;
            Chart.defaults.plugins.tooltip.displayColors = false;
        }

        const stockRequest = fetchJson(page.dataset.stockUrl);

        Promise.allSettled([
            fetchJson(page.dataset.ordersUrl),
            fetchJson(page.dataset.productsUrl),
            stockRequest,
            fetchJson(page.dataset.customersUrl),
            fetchJson(page.dataset.lostUrl),
            page.dataset.financeUrl
                ? fetchJson(page.dataset.financeUrl)
                : Promise.resolve(null),
        ]).then(() => {
            const updated = page.querySelector('[data-dashboard-updated]');

            if (updated) {
                updated.textContent =
                    'Updated at '
                    + new Date().toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit',
                    });
            }
        });

        fetchJson(page.dataset.ordersUrl)
            .then((data) => {
                const values = (data.data || []).map(Number);

                setText('dashboard-week-orders', values[0] || 0);
                setText('dashboard-month-orders', values[1] || 0);

                createChart(
                    'ordersSummaryChart',
                    {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                data: values,
                                backgroundColor: [
                                    colours.lightGreen,
                                    colours.green,
                                ],
                                borderRadius: 12,
                                borderSkipped: false,
                                maxBarThickness: 72,
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false,
                                },
                                tooltip: {
                                    callbacks: {
                                        label: (context) =>
                                            `${context.parsed.y} orders`,
                                    },
                                },
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false,
                                    },
                                    border: {
                                        display: false,
                                    },
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0,
                                    },
                                    grid: {
                                        color: colours.grid,
                                    },
                                    border: {
                                        display: false,
                                    },
                                },
                            },
                        },
                    },
                    values,
                );
            })
            .catch(() => {
                setText('dashboard-week-orders', '—');
                setText('dashboard-month-orders', '—');
                showEmptyChart('ordersSummaryChart', 'Order data could not be loaded.');
            });

        fetchJson(page.dataset.productsUrl)
            .then((data) => {
                const values = (data.data || []).map(Number);

                createChart(
                    'topProductsChart',
                    {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                data: values,
                                backgroundColor: colours.lightGreen,
                                borderRadius: 9,
                                borderSkipped: false,
                            }],
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false,
                                },
                            },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0,
                                    },
                                    grid: {
                                        color: colours.grid,
                                    },
                                    border: {
                                        display: false,
                                    },
                                },
                                y: {
                                    grid: {
                                        display: false,
                                    },
                                    border: {
                                        display: false,
                                    },
                                },
                            },
                        },
                    },
                    values,
                );
            })
            .catch(() => {
                showEmptyChart('topProductsChart', 'Product data could not be loaded.');
            });

        stockRequest
            .then((data) => {
                const stockValues = (data.stock.data || []).map(Number);
                const productionValues = (data.production.data || []).map(Number);

                setText('dashboard-low-stock', stockValues[0] || 0);
                setText('alert-low-stock', stockValues[0] || 0);
                setText('alert-expiring', stockValues[1] || 0);

                const activeProduction =
                    (productionValues[0] || 0)
                    + (productionValues[1] || 0);

                setText('alert-production', activeProduction);

                createChart(
                    'stockChart',
                    {
                        type: 'doughnut',
                        data: {
                            labels: data.stock.labels,
                            datasets: [{
                                data: stockValues,
                                backgroundColor: [
                                    colours.amber,
                                    colours.yellow,
                                    colours.red,
                                ],
                                borderColor: '#ffffff',
                                borderWidth: 5,
                                hoverOffset: 5,
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '68%',
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        usePointStyle: true,
                                        padding: 18,
                                        boxWidth: 8,
                                    },
                                },
                            },
                        },
                    },
                    stockValues,
                );

                createChart(
                    'productionChart',
                    {
                        type: 'bar',
                        data: {
                            labels: data.production.labels,
                            datasets: [{
                                data: productionValues,
                                backgroundColor: [
                                    colours.grey,
                                    colours.blue,
                                    colours.green,
                                ],
                                borderRadius: 9,
                                borderSkipped: false,
                            }],
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false,
                                },
                            },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0,
                                    },
                                    grid: {
                                        color: colours.grid,
                                    },
                                    border: {
                                        display: false,
                                    },
                                },
                                y: {
                                    grid: {
                                        display: false,
                                    },
                                    border: {
                                        display: false,
                                    },
                                },
                            },
                        },
                    },
                    productionValues,
                );
            })
            .catch(() => {
                setText('dashboard-low-stock', '—');
                setText('alert-low-stock', '—');
                setText('alert-expiring', '—');
                setText('alert-production', '—');

                showEmptyChart('stockChart', 'Stock data could not be loaded.');
                showEmptyChart('productionChart', 'Production data could not be loaded.');
            });

        fetchJson(page.dataset.customersUrl)
            .then((data) => {
                const values = (data.data || []).map(Number);

                createChart(
                    'customersChart',
                    {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                data: values,
                                backgroundColor: colours.blue,
                                borderRadius: 9,
                                borderSkipped: false,
                            }],
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false,
                                },
                            },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0,
                                    },
                                    grid: {
                                        color: colours.grid,
                                    },
                                    border: {
                                        display: false,
                                    },
                                },
                                y: {
                                    grid: {
                                        display: false,
                                    },
                                    border: {
                                        display: false,
                                    },
                                },
                            },
                        },
                    },
                    values,
                );
            })
            .catch(() => {
                showEmptyChart('customersChart', 'Customer data could not be loaded.');
            });

        fetchJson(page.dataset.lostUrl)
            .then((clients) => {
                const tbody = document.getElementById('lostClientsBody');

                if (!tbody) {
                    return;
                }

                tbody.innerHTML = '';

                if (!Array.isArray(clients) || clients.length === 0) {
                    tbody.innerHTML =
                        '<tr><td colspan="2" class="dashboard-table-empty">'
                        + 'No customers currently require attention.'
                        + '</td></tr>';

                    return;
                }

                clients.slice(0, 8).forEach((client) => {
                    const row = document.createElement('tr');
                    const company = document.createElement('td');
                    const date = document.createElement('td');

                    company.textContent = client.name || 'Unknown company';
                    date.textContent = client.lastOrder || 'Never';

                    row.append(company, date);
                    tbody.appendChild(row);
                });
            })
            .catch(() => {
                const tbody = document.getElementById('lostClientsBody');

                if (tbody) {
                    tbody.innerHTML =
                        '<tr><td colspan="2" class="dashboard-table-empty">'
                        + 'Customer data could not be loaded.'
                        + '</td></tr>';
                }
            });

        if (page.dataset.financeUrl) {
            fetchJson(page.dataset.financeUrl)
                .then((data) => {
                    const values = (data.data || []).map(Number);
                    const currentRevenue = values.at(-1) || 0;

                    setText(
                        'dashboard-revenue',
                        new Intl.NumberFormat(
                            undefined,
                            {
                                style: 'currency',
                                currency: 'EUR',
                                maximumFractionDigits: 0,
                            },
                        ).format(currentRevenue),
                    );

                    createChart(
                        'revenueChart',
                        {
                            type: 'line',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    data: values,
                                    borderColor: colours.green,
                                    backgroundColor: colours.paleGreen,
                                    fill: true,
                                    tension: 0.38,
                                    pointRadius: 4,
                                    pointHoverRadius: 6,
                                    pointBackgroundColor: colours.green,
                                    pointBorderColor: '#ffffff',
                                    pointBorderWidth: 2,
                                }],
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false,
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: (context) =>
                                                `€${Number(context.parsed.y).toFixed(2)}`,
                                        },
                                    },
                                },
                                scales: {
                                    x: {
                                        grid: {
                                            display: false,
                                        },
                                        border: {
                                            display: false,
                                        },
                                    },
                                    y: {
                                        beginAtZero: true,
                                        grid: {
                                            color: colours.grid,
                                        },
                                        border: {
                                            display: false,
                                        },
                                    },
                                },
                            },
                        },
                        values,
                    );
                })
                .catch(() => {
                    setText('dashboard-revenue', '—');
                    showEmptyChart('revenueChart', 'Revenue data could not be loaded.');
                });
        }
    });
})();
