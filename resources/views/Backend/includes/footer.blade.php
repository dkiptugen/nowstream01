<footer class="footer">
    <div class="container-fluid">
        <div class="row text-muted">
            <div class="col-6 text-start">
                <p class="mb-0">
                    &copy; Copyright <a href="https://www.caydeesoft.com">Caydeesoft Solutions Limited</a> 2025. All rights reserved.
                </p>
            </div>

        </div>
    </div>
</footer>
</div>
</div>
<script>
    window.appConfig = Object.assign({}, window.appConfig ?? {}, {
        mediaLibraryIndexUrl: @json(route('backend.media-library.index')),
        mediaLibraryStoreUrl: @json(route('backend.media-library.store')),
        authenticatedUserId: @json(auth()->id()),

    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.alert').forEach(function (alert) {
            if (!alert.hasAttribute('role')) {
                alert.setAttribute('role', 'alert');
            }
        });

        document.querySelectorAll('table').forEach(function (table) {
            if (!table.querySelector('caption')) {
                const card = table.closest('.card');
                const heading = card?.querySelector('.card-title, h1, h2, h3, h4, h5, h6');
                const caption = document.createElement('caption');
                caption.className = 'visually-hidden';
                caption.textContent = heading?.textContent?.trim() || 'Data table';
                table.prepend(caption);
            }

            table.querySelectorAll('thead th').forEach(function (headerCell) {
                headerCell.setAttribute('scope', 'col');
            });
        });

        document.querySelectorAll('input, select, textarea').forEach(function (field) {
            if (field.required) {
                field.setAttribute('aria-required', 'true');
            }

            if (field.classList.contains('is-invalid')) {
                field.setAttribute('aria-invalid', 'true');
                const feedback = field.parentElement?.querySelector('.invalid-feedback');

                if (feedback && !feedback.id) {
                    feedback.id = field.id ? `${field.id}-error` : `field-error-${Math.random().toString(36).slice(2, 8)}`;
                }

                if (feedback?.id) {
                    field.setAttribute('aria-describedby', feedback.id);
                }
            }
        });

        const currentUrl = new URL(window.location.href);
        document.querySelectorAll('#sidebar .sidebar-link').forEach(function (link) {
            try {
                const linkUrl = new URL(link.href, window.location.origin);
                if (linkUrl.pathname === currentUrl.pathname) {
                    link.setAttribute('aria-current', 'page');
                }
            } catch (error) {
                // Ignore malformed links.
            }
        });

        const notificationCount = document.querySelector('[data-top-notification-count]');
        if (notificationCount) {
            const updateNotificationLabel = function () {
                notificationCount.setAttribute('aria-label', `${notificationCount.textContent.trim() || '0'} notifications`);
            };

            updateNotificationLabel();
            const observer = new MutationObserver(updateNotificationLabel);
            observer.observe(notificationCount, { childList: true, subtree: true, characterData: true });
        }
    });
</script>
<script type="module" src="{{ asset('backend_assets/js/app.js?'.time()) }}"></script>

@yield('footer')
</body>

</html>
