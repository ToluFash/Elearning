/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/custom.scss';
import 'bootstrap/dist/css/bootstrap.css';
import 'jquery/src/jquery';

// start the Stimulus application
import * as bootstrap from 'bootstrap';
import videojs from 'video.js';
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');
require('!style-loader!css-loader!video.js/dist/video-js.css')