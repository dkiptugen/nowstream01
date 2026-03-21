document.addEventListener('DOMContentLoaded', () => {

    // ========== ENHANCED SELECTS ==========
    if (window.Choices) {
        document.querySelectorAll('.js-choice,  .chosen-select').forEach(select => {
            if (select.dataset.choicesInitialized === 'true') {
                return;
            }

            const isMultiple = select.hasAttribute('multiple');
            const placeholder = select.dataset.placeholder || select.getAttribute('placeholder') || '';

            new window.Choices(select, {
                allowHTML: false,
                itemSelectText: '',
                removeItemButton: isMultiple,
                searchEnabled: select.options.length > 8,
                shouldSort: false,
                placeholder: Boolean(placeholder),
                placeholderValue: placeholder,
            });

            select.dataset.choicesInitialized = 'true';
        });
    }

    // ========== IMAGE SEARCH FORM ==========
    const imageSearch = document.getElementById('image-search');
    if (imageSearch) {
        imageSearch.addEventListener('submit', e => {
            e.preventDefault();
            document.getElementById('images_display').innerHTML = '';
        });
    }


    // ========== MODAL RESET ==========
    const myModal = document.getElementById('myModal');
    if (myModal) {
        myModal.addEventListener('hidden.bs.modal', e => {
            const form = e.target.querySelector('form');
            if (form) form.reset();
            const body = e.target.querySelector('.modal-body');
            if (body) body.innerHTML = '';
        });
    }

    // ========== SELECT IMAGE ==========
    document.querySelectorAll('.selectImage').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            const id = btn.dataset.id;
            const src = btn.getAttribute('src');
            document.getElementById('mainImage').value = id;
            document.getElementById('thumbnail').src = src;
            const preview = document.getElementById('content-preview');
            preview.classList.add('d-none');
            preview.classList.remove('d-flex');
            document.getElementById('image-modal').classList.remove('show');
        });
    });

    // ========== FILE INPUT UPLOAD ==========
    document.querySelectorAll('.file-input').forEach(input => {
        input.addEventListener('change', async e => {
            const files = Array.from(input.files);
            const formName = input.name;
            const data = new FormData();
            files.forEach(f => data.append(formName, f));

            const progressBar = document.getElementById('uploadProgressBar');
            const progressContainer = document.getElementById('progressBarContainer');
            progressContainer.style.display = 'block';
            progressBar.style.width = '0%';
            progressBar.setAttribute('aria-valuenow', 0);

            try {
                const res = await fetch(input.dataset.url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: data
                });
                const result = await res.json();

                if (result === 'invalid') {
                    const errEl = document.getElementById('err');
                    errEl.innerHTML = 'Invalid File!';
                    errEl.style.display = 'block';
                } else {
                    document.querySelector('.upload').classList.add('d-none');
                    document.getElementById('image').src = result.imageloc;
                    document.getElementById('imgname').value = result.imgname;
                    document.getElementById('size').value = result.size;
                    document.getElementById('mime').value = result.mime;

                    const preview = document.getElementById('content-preview');
                    preview.classList.remove('d-none');
                    preview.classList.add('d-flex');
                }
            } catch (err) {
                console.error(err);
                const errEl = document.getElementById('err');
                errEl.innerHTML = 'Upload failed.';
                errEl.style.display = 'block';
            }
        });
    });


    // ========== TAGS INPUT ==========
    const tagsInput = document.querySelector('.tags-input');
    const restrictedTags = ['the star', 'star news', 'the star online', 'thestaronline', 'the star kenya', 'thestar', 'thestardigital', 'the star digital', 'star', 'the star newspaper', 'star news kenya', 'the star news', 'the star', 'mpasho'];

    if (tagsInput) {
        tagsInput.addEventListener('beforeItemAdd', e => {
            const tag = e.item.toLowerCase().trim();
            if (restrictedTags.includes(tag) || tag.includes('star')) {
                e.cancel = true;
                alert(`The tag "${tag}" is not allowed.`);
            }
        });
    }

    // ========== THEME TOGGLE ==========
    const toggleBtn = document.getElementById('theme-toggle');
    const applyTheme = theme => {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        document.body.classList.toggle('dark-mode', theme === 'dark');
    };

    let savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    applyTheme(savedTheme);

    toggleBtn?.addEventListener('click', e => {
        e.preventDefault();
        const newTheme = document.body.classList.contains('dark-mode') ? 'light' : 'dark';
        applyTheme(newTheme);
    });

});
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';

const notify = new Notyf({
    duration: 1000,
    position: { x: 'right', y: 'top' }
});

// Event delegation (replaces $(document).on)
document.addEventListener('submit', async function (e) {
    if (!e.target.classList.contains('create-form')) return;

    e.preventDefault();

    const frm = e.target;
    const formData = new FormData(frm);

    try {
        const res = await fetch(frm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });

        const Mess = await res.json();

        if (Mess.status === true) {
            notify.success(Mess.msg || 'Success');

            // redirect after short delay (replaces onHidden)
            setTimeout(() => {
                if (Mess.url) {
                    window.location.href = Mess.url;
                }
            }, 1000);

        } else {
            notify.error(Mess.msg || 'Operation failed');
        }

    } catch (error) {
        console.error(error);

        notify.error('Something went wrong. Please try again.');
    }
});
