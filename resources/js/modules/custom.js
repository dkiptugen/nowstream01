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
                    headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
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
import {Notyf} from 'notyf';
import 'notyf/notyf.min.css';

const notify = new Notyf({
    duration: 1000,
    position: {x: 'right', y: 'top'}
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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        });

        const contentType = res.headers.get('content-type') || '';
        const raw = await res.text();

        let Mess = {};
        if (contentType.includes('application/json')) {
            Mess = JSON.parse(raw);
        } else {
            throw new Error(raw.startsWith('<')
                ? 'Server returned HTML instead of JSON. Check login/session or backend error.'
                : raw || 'Unexpected server response.');
        }

        if (!res.ok) {
            throw new Error(
                Mess.msg ||
                Mess.message ||
                (Mess.errors ? Object.values(Mess.errors).flat().join(' ') : '') ||
                `Request failed with status ${res.status}`
            );
        }

        if (Mess.status === true) {
            notify.success(Mess.msg || 'Success');
            setTimeout(() => {
                if (Mess.url) window.location.href = Mess.url;
            }, 1000);
        } else {
            throw new Error(Mess.msg || 'Operation failed');
        }
    } catch (error) {
        console.error(error);
        notify.error(error.message || 'Something went wrong. Please try again.');
    }
});

flatpickr('input[name="datetimes"], .datetimes', {
    enableTime: true,
    mode: "range",
    dateFormat: "Y/m/d h:i K", // equivalent to Y/M/DD hh:mm A
    defaultDate: [
        new Date().setMinutes(0, 0, 0), // start of hour
        new Date(Date.now() + 32 * 60 * 60 * 1000) // +32 hours
    ]
});
flatpickr('input[name="datesingle"], .datesingle', {
    enableTime: true,
    dateFormat: "Y-m-d H:i:S",
    defaultDate: new Date(),
    allowInput: true
});

flatpickr('input[name="datesingle"], .datesingle', {
    enableTime: true,
    dateFormat: "Y-m-d H:i:S",
    defaultDate: new Date(),
    allowInput: true
});
flatpickr('#schedule_date', {
    enableTime: false,
    dateFormat: "Y-m-d",
    allowInput: true
});
flatpickr('.starttime', {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    minuteIncrement: 15
});

flatpickr('.endtime', {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    minuteIncrement: 15
});
const reportInput = document.getElementById('reportRangeInput');
const reportSpan = document.querySelector('#reportrange span');

const today = new Date();
const last30 = new Date();
last30.setDate(today.getDate() - 29);

const fp = flatpickr(reportInput, {
    mode: "range",
    dateFormat: "F j, Y",
    defaultDate: [last30, today],
    onChange: function(selectedDates) {
        if (selectedDates.length === 2) {
            const [start, end] = selectedDates;
            reportSpan.textContent =
                `${fp.formatDate(start, "F j, Y")} - ${fp.formatDate(end, "F j, Y")}`;
        }
    }
});

// initialize display
reportSpan.textContent =
    `${fp.formatDate(last30, "F j, Y")} - ${fp.formatDate(today, "F j, Y")}`;
