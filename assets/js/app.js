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

import 'bootstrap';

import 'select2'
import 'select2/dist/css/select2.min.css';

import 'lightbox2';
import 'lightbox2/dist/css/lightbox.min.css';

import 'javascript-flex-images/flex-images.css';
import flexImages from 'javascript-flex-images';

document.addEventListener('DOMContentLoaded', (event) => {
    new flexImages({ selector: '.flex-images', rowHeight: 140 });

    $('.js-flash-close').click(function(){
       $(this).parent().slideUp();
    });
});
