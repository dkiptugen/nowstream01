window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');
window.Echo = require("laravel-echo");
window.SocketIO = require("socket.io-client");
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.Popper = require('@popperjs/core').default;
window.$ = window.jQuery = require('jquery');
require('bootstrap');
require('./multiselect.min');
require('./custom');
require('./publication');
window.Wordcloud = require('wordcloud');
window.Sortable = require('sortablejs');

import Echo from "laravel-echo";
import io from "socket.io-client";

/*
 // Ensure io is available globally
 window.io = io;
 
 // Initialize Echo
 window.Echo = new Echo({
 broadcaster: "socket.io",
 host: "https://echo.radioafrica.digital", // Change to your Socket.IO server
 enabledTransports: ['ws', 'wss'],
 });
 */

window.Pusher = require('pusher-js');

window.Echo = new Echo({
	                       broadcaster: 'pusher',
	                       key        : process.env.MIX_PUSHER_APP_KEY,
	                       cluster    : process.env.MIX_PUSHER_APP_CLUSTER,
	                       forceTLS   : true
                       });


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

