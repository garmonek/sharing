/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
const $ = require('jquery');
global.$ = global.jQuery = $;

import '../css/app.css';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap/dist/js/bootstrap.bundle.min';

import 'select2/dist/js/select2.full.min';
import 'select2/dist/css/select2.min.css';

import 'javascript-flex-images/flex-images.css';
import flexImages from 'javascript-flex-images';
new flexImages({ selector: '.flex-images', rowHeight: 140 });

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
