import { Dropzone } from "dropzone";
if($('#drop').length > 0)
{
    const link  =   $('#drop').data('link');
    const dropzone = new Dropzone("div#drop", { url: link});
}

