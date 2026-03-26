<script>
    (function() {
        const container = document.getElementById(@json($containerId));
        const loader = document.getElementById(@json($loaderId));
        const status = document.getElementById(@json($statusId));

        if (!container || !loader) {
            return;
        }

        let nextUrl = container.dataset.nextPageUrl || '';
        let loading = false;
        let failed = false;

        const updateLoader = () => {
            const hasMore = Boolean(nextUrl);
            loader.hidden = !hasMore && !loading && !failed;

            if (!status) {
                return;
            }

            if (loading) {
                status.textContent = container.dataset.loadingLabel || 'Loading more...';
                return;
            }

            if (hasMore) {
                status.textContent = container.dataset.idleLabel || 'Scroll for more';
                return;
            }

            if (failed) {
                status.textContent = container.dataset.errorLabel || 'Unable to load more right now';
                return;
            }

            status.textContent = container.dataset.completeLabel || 'You are all caught up';
        };

        const loadMore = async () => {
            if (loading || !nextUrl) {
                return;
            }

            loading = true;
            failed = false;
            loader.classList.add('is-loading');
            updateLoader();

            try {
                const response = await fetch(nextUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    throw new Error('Infinite scroll request failed');
                }

                const data = await response.json();

                if (data.html) {
                    container.insertAdjacentHTML('beforeend', data.html);
                }

                nextUrl = data.nextPageUrl || '';
                container.dataset.nextPageUrl = nextUrl;
            } catch (error) {
                nextUrl = '';
                failed = true;
            } finally {
                loading = false;
                loader.classList.remove('is-loading');
                updateLoader();
            }
        };

        const sentinel = document.createElement('div');
        sentinel.className = 'infinite-scroll-sentinel';
        loader.appendChild(sentinel);

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    loadMore();
                }
            });
        }, {
            rootMargin: '900px 0px 900px 0px'
        });

        observer.observe(sentinel);
        updateLoader();
    })();
</script>
