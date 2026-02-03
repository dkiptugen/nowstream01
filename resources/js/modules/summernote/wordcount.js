$.extend($.summernote.plugins, {
    'wordcount': function(context) {
        var self = this;
        var MIN_WORD_COUNT = 300;  // Set minimum word count to 300

        // Add a check to see if the word count feature is enabled
        if (!context.options.wordcount) {
            return;  // If wordcount is set to false, don't initialize
        }

        var $note = context.layoutInfo.note;
        var $statusbar = $('<div class="note-status-output" style="text-align: right; margin-top: 10px;"></div>');
        var $wordCountWarning = $('<div class="word-count-warning" style="color: red; text-align: right; margin-top: 5px;"></div>');

        // Initialization
        this.initialize = function() {
            // Append word count and warning message to editor
            $statusbar.appendTo($note.siblings('.note-editor').find('.note-statusbar'));
            $wordCountWarning.appendTo($note.siblings('.note-editor').find('.note-statusbar'));

            // Bind keyup, paste, and change events
            this.updateWordCount();
            context.layoutInfo.editable.on('keyup paste', function() {
                self.updateWordCount();
            });
        };

        // Word count calculation function
        this.updateWordCount = function() {
            // Get the HTML content and convert to plain text (including list items)
            var text = $(context.layoutInfo.editable.html()).text();
            var wordCount = self.countWords(text);

            // Update the display with the word count
            $statusbar.text('Word count: ' + wordCount);

            // Check if word count is less than the minimum
            if (wordCount < MIN_WORD_COUNT) {
                $wordCountWarning.text('Minimum word count is ' + MIN_WORD_COUNT + '. You have ' + wordCount + ' words.');
            } else {
                $wordCountWarning.text('');  // Clear warning if word count is sufficient
            }
        };

        // Utility function to count words
        this.countWords = function(text) {
            if (text === '') {
                return 0;
            }

            // Remove extra spaces and split by spaces to count words
            var words = text.trim().replace(/\s+/g, ' ').split(' ');
            return words.length;
        };

        // Cleanup function when the plugin is destroyed
        this.destroy = function() {
            $statusbar.remove();
            $wordCountWarning.remove();
            context.layoutInfo.editable.off('keyup paste');
        };
    }
});