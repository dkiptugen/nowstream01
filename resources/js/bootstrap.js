// resources/js/bootstrap.js

// Lodash
import _ from 'lodash';
window._ = _;

// Axios
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Popper.js and jQuery
import { createPopper } from '@popperjs/core';

import $ from 'jquery';
window.Popper = { createPopper };
window.$ = window.jQuery = $;

// Bootstrap
import 'bootstrap';

// Your custom scripts

import './custom';

// SCSS (if you want to import global styles here)
// import '../scss/app.scss';

// Global Modules
import './modules/moment';
import './modules/bootstrap';
import './modules/feather';
import './modules/font-awesome';
//import './modules/sidebar';
import './modules/toastr';
import './modules/user-agent';

// Forms & UI
import './modules/select2';
import './modules/daterangepicker';
import './modules/datetimepicker';
import './modules/mask';
import './modules/validation';
import './modules/wizard';

// Maps & Editors
import './modules/vector-maps';
import './modules/summernote';
import './modules/tagsinput';

// Tables & Files
import './modules/datatables';
import './modules/dropzone';

// Echo (real-time)
import './echo';
