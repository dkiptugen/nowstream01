import Tagify from '@yaireo/tagify';

const input = document.querySelector('.tags-input');

const restrictedTags = [
    'streamer',
    'sex',
    'porn'
];

if (input) {

    const tagify = new Tagify(input, {
        duplicates: false,
        transformTag: (tagData) => {
            tagData.value = tagData.value.toLowerCase().trim();
        }
    });

    tagify.on('beforeAddTag', (e) => {
        const tag = e.detail.data.value;

        if (restrictedTags.includes(tag) || tag.includes('star')) {

            e.detail.data.class = 'tagify--invalid';

            alert(`The tag "${tag}" is not allowed.`);

            e.preventDefault(); // cancel adding the tag
        }
    });

}
