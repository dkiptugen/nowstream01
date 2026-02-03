(function ($) {
    $.extend($.summernote.plugins, {
        'relatedArticles': function (context) {
            var ui = $.summernote.ui;

            var button = ui.button({
                contents: '<i class="fas fa-group"/> Related Articles',
                click: function () {
                    // Create a modal for picking articles
                    var modal = '<div class="modal" tabindex="-1" role="dialog">' +
                        '<div class="modal-dialog" role="document">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<h5 class="modal-title">Pick an Article</h5>' +
                        '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                        '<span aria-hidden="true">&times;</span>' +
                        '</button>' +
                        '</div>' +
                        '<div class="modal-body">' +
                        '<input type="text" id="articleSearch" class="form-control" placeholder="Search articles">' +
                        '<ul id="articleList"></ul>' +
                        '</div>' +
                        '<div class="modal-footer">' +
                        '<button type="button" class="btn btn-primary" id="insertArticleBtn">Insert Article</button>' +
                        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';

                    // Append modal to the body
                    $(document.body).append(modal);

                    // Fetch articles from the server
                    $.ajax({
                        url: 'http://localhost/cms/public/api/get_articles', // Replace with your server-side API endpoint
                        method: 'GET',
                        success: function (data) {
                            // Populate the modal with articles
                            var articleList = $('#articleList');
                            var articleSearch = $('#articleSearch');

                            // Function to filter articles based on search input
                            function filterArticles() {
                                var searchTerm = articleSearch.val().toLowerCase();

                                data.forEach(function (article) {
                                    var listItem = articleList.find('li:contains("' + article.title + '")');

                                    if (article.title.toLowerCase().includes(searchTerm)) {
                                        listItem.show();
                                    } else {
                                        listItem.hide();
                                    }
                                });
                            }

                            // Populate the modal with articles
                            data.forEach(function (article) {
                                var listItem = $('<li>').text(article.title);
                                articleList.append(listItem);
                            });

                            // Show the modal
                            $('.modal').modal('show');

                            // Handle article insertion
                            $('#insertArticleBtn').on('click', function () {
                                var selectedArticle = articleList.find('li.active').text();

                                if (selectedArticle) {
                                    context.invoke('editor.insertText', 'Related Article: ' + selectedArticle);
                                    $('.modal').modal('hide');
                                }
                            });

                            // Enable search functionality
                            articleSearch.on('input', filterArticles);
                        },
                        error: function (err) {
                            console.error('Error fetching articles:', err);
                        }
                    });
                }
            });

            context.memo('button.relatedArticles', function () {
                return button.render();
            });
        }
    });
})(jQuery);

