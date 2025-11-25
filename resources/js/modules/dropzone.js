// Assign Dropzone globally
window.Dropzone = require('dropzone');

// Optionally import the CSS
require('dropzone/dist/dropzone.css');


if($('#drop').length > 0)
{
    const link  =   $('#drop').data('link');
    const dropzone = new Dropzone("div#drop", { url: link});
}

