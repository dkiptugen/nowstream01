import Tagify from '@yaireo/tagify';
import '@yaireo/tagify/dist/tagify.css';

const input = document.querySelector('.tags-input');

const restrictedTags = [
    'streamer',
    'sex',
    'porn'
];

if (input) {

    const tagify = new Tagify(input, {
        duplicates: false,
        dropdown: {
            enabled: 0
        },

        transformTag(tagData) {
            tagData.value = tagData.value.toLowerCase().trim();
        },

        validate(tagData) {

            const tag = tagData.value.toLowerCase().trim();

            if (restrictedTags.includes(tag)) {
                return `The tag "${tag}" is not allowed`;
            }

            if (/\bstar\b/i.test(tag)) {
                return `The tag "${tag}" contains a restricted word`;
            }

            return true;
        }
    });

    // Optional: show toast-like error instead of alert
    tagify.on('invalid', e => {
        console.warn(e.detail.message);
    });

}
