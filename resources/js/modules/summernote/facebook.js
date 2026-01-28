var InsertFacebookButton = function (context) {
    var ui = $.summernote.ui;

    var button = ui.button({
        contents: '<i class="fab fa-facebook-square"></i>',
        tooltip: 'Insert Facebook Post',
        click: function () {
            // Create a modal for the Facebook embed
            var $modal = $('<div class="modal" tabindex="-1" role="dialog">' +
                '<div class="modal-dialog" role="document">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<h5 class="modal-title">Insert Facebook Post</h5>' +
                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                '</button>' +
                '</div>' +
                '<div class="modal-body">' +
                '<div class="form-group">' +
                '<label for="facebookPostUrl">Facebook Post URL:</label>' +
                '<input type="text" id="facebookPostUrl" class="form-control" placeholder="Enter Facebook Post URL"/>' +
                '</div>' +
                '</div>' +
                '<div class="modal-footer">' +
                '<button type="button" class="btn btn-primary" id="insertFacebookBtn">Insert</button>' +
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>');

            // Append the modal to the body and show it
            $modal.modal('show');

            // Attach a click event to the "Insert" button in the modal
            $modal.find('#insertFacebookBtn').on('click', function () {
                // Get the Facebook post URL
                var facebookPostUrl = $('#facebookPostUrl').val();

                // Insert the Facebook post using the official Embedded Posts Plugin
                var embedCode = '<div class="fb-post" data-href="' + facebookPostUrl + '" data-show-text="true"></div>';
                context.invoke('editor.insertHTML', embedCode);

                // Reload the Facebook SDK
                if (typeof FB !== 'undefined' && FB.XFBML) {
                    FB.XFBML.parse();
                } else {
                    // Re-load Facebook SDK if it hasn't been loaded
                    (function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) return;
                        js = d.createElement(s); js.id = id;
                        js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v12.0';
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));
                }

                $modal.modal('hide');
            });
        }
    });

    return button.render();
};

$.extend($.summernote.plugins, {
    'facebook': function (context) {
        context.memo('button.insertFacebook', function () {
            return InsertFacebookButton(context);
        });

        // Load the Facebook SDK asynchronously
        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v12.0';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    }
});