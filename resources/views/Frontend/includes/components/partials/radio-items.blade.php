@foreach($radios as $item)
<div class="col-xl-2 col-lg-3 col-sm-6 grid-item">
    @include('Frontend.includes.components.cards.slider-card')
</div>
@endforeach
<script>
document.addEventListener('DOMContentLoaded', function () {

    let page = {{ $radios->currentPage() }};
    let lastPage = {{ $radios->lastPage() }};
    let loading = false;

    function loadMore() {
        if (loading) return;
        if (page >= lastPage) return;

        loading = true;
        page++;

        document.getElementById('loading').style.display = 'block';

        fetch(`?page=${page}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('radio-container')
                .insertAdjacentHTML('beforeend', html);

            loading = false;
            document.getElementById('loading').style.display = 'none';
        })
        .catch(() => {
            loading = false;
            document.getElementById('loading').style.display = 'none';
        });
    }

    // Scroll trigger
    window.addEventListener('scroll', function () {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 300) {
            loadMore();
        }
    });

});
</script>
