(function () {
    'use strict';

    var HINT_TEXT = 'Pilih wilayah untuk melihat ringkasan.';
    var LOADING_TEXT = 'Memuat ringkasan wilayah...';
    var ERROR_TEXT = 'Ringkasan wilayah gagal dimuat.';
    var EMPTY_TEXT = 'Belum ada laporan di wilayah ini.';

    function renderHint(container) {
        if (container.children.length > 0) {
            return;
        }

        showMessage(container, HINT_TEXT, 'area-info__hint text-muted');
    }

    function clearContainer(container) {
        while (container.firstChild) {
            container.removeChild(container.firstChild);
        }
    }

    function showMessage(container, text, className) {
        clearContainer(container);

        var line = document.createElement('p');
        line.className = className;
        line.textContent = text;
        container.appendChild(line);
    }

    function renderInfo(container, data) {
        clearContainer(container);

        var area = data && data.area ? data.area : {};
        var reports = data && Array.isArray(data.recent_reports) ? data.recent_reports : [];
        var activeCount = typeof data.active_count === 'number' ? data.active_count : 0;

        var heading = document.createElement('p');
        heading.className = 'area-info__title';
        heading.textContent = String(area.name == null ? '' : area.name);
        container.appendChild(heading);

        var count = document.createElement('p');
        count.className = 'area-info__count';
        count.textContent = 'Laporan aktif: ' + activeCount;
        container.appendChild(count);

        if (reports.length === 0) {
            var empty = document.createElement('p');
            empty.className = 'area-info__empty';
            empty.textContent = EMPTY_TEXT;
            container.appendChild(empty);

            return;
        }

        var label = document.createElement('p');
        label.className = 'area-info__label';
        label.textContent = 'Laporan terbaru';
        container.appendChild(label);

        var list = document.createElement('ul');
        list.className = 'area-info__list';

        reports.forEach(function (report) {
            var item = document.createElement('li');

            var title = document.createElement('span');
            title.className = 'area-info__report-title';
            title.textContent = String(report.title == null ? '' : report.title);

            var meta = document.createElement('span');
            meta.className = 'area-info__report-meta';
            meta.textContent = String(report.status == null ? '' : report.status)
                + ' - ' + String(report.created_at == null ? '' : report.created_at);

            item.appendChild(title);
            item.appendChild(meta);
            list.appendChild(item);
        });

        container.appendChild(list);
    }

    function loadInfo(select, container, urlTemplate, state) {
        // Token mencegah respons lama menimpa pilihan wilayah yang lebih baru.
        state.token += 1;

        var token = state.token;
        var value = select.value;

        if (value === '') {
            showMessage(container, HINT_TEXT, 'area-info__hint text-muted');

            return;
        }

        showMessage(container, LOADING_TEXT, 'area-info__loading');

        var url = urlTemplate.replace('__ID__', encodeURIComponent(value));

        fetch(url, {
            method: 'GET',
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin'
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Respons tidak valid');
                }

                return response.json();
            })
            .then(function (data) {
                if (token !== state.token) {
                    return;
                }

                renderInfo(container, data);
            })
            .catch(function () {
                if (token !== state.token) {
                    return;
                }

                showMessage(container, ERROR_TEXT, 'area-info__error');
            });
    }

    function init() {
        var selects = document.querySelectorAll('select[name="area_id"][data-area-info]');

        Array.prototype.forEach.call(selects, function (select) {
            var container = document.getElementById(select.getAttribute('data-area-info'));
            var urlTemplate = select.getAttribute('data-area-info-url-template');

            if (container === null || !urlTemplate) {
                return;
            }

            var state = { token: 0 };

            select.addEventListener('change', function () {
                loadInfo(select, container, urlTemplate, state);
            });

            renderHint(container);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
