<?php
    require_once "generateRandomString.php";
    require_once "randinit.php";
    function generateCaptcha($code) {
    return '<svg class="unselectable" height="60" width="225"><defs>
            <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" style="stop-color:#' . generateRandomString(6) . ';stop-opacity:1" />
            <stop offset="100%" style="stop-color:#' . generateRandomString(6) . ';);stop-opacity:1" />
            </linearGradient>
            <linearGradient id="grad2" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" style="stop-color:#' . generateRandomString(6) . ';stop-opacity:1" />
            <stop offset="100%" style="stop-color:#' . generateRandomString(6) . ';);stop-opacity:1" />
            </linearGradient>
            <linearGradient id="grad3" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" style="stop-color:#' . generateRandomString(6) . ';stop-opacity:1" />
            <stop offset="100%" style="stop-color:#' . generateRandomString(6) . ';);stop-opacity:1" />
            </linearGradient>
            <linearGradient id="grad4" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" style="stop-color:#' . generateRandomString(6) . ';stop-opacity:1" />
            <stop offset="100%" style="stop-color:#' . generateRandomString(6) . ';);stop-opacity:1" />
            </linearGradient>
            <linearGradient id="grad5" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" style="stop-color:#' . generateRandomString(6) . ';stop-opacity:1" />
            <stop offset="100%" style="stop-color:#' . generateRandomString(6) . ';);stop-opacity:1" />
            </linearGradient>
            <linearGradient id="grad6" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" style="stop-color:#' . generateRandomString(6) . ';stop-opacity:1" />
            <stop offset="100%" style="stop-color:#' . generateRandomString(6) . ';);stop-opacity:1" />
            </linearGradient>
            <linearGradient id="grad7" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" style="stop-color:#' . generateRandomString(6) . ';stop-opacity:1" />
            <stop offset="100%" style="stop-color:#' . generateRandomString(6) . ';);stop-opacity:1" />
            </linearGradient>
            <linearGradient id="grad8" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" style="stop-color:#' . generateRandomString(6) . ';stop-opacity:1" />
            <stop offset="100%" style="stop-color:#' . generateRandomString(6) . ';);stop-opacity:1" />
            </linearGradient>
            <linearGradient id="grad9" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" style="stop-color:#' . generateRandomString(6) . ';stop-opacity:1" />
            <stop offset="100%" style="stop-color:#' . generateRandomString(6) . ';);stop-opacity:1" />
            </linearGradient>
            <linearGradient id="grad10" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" style="stop-color:#' . generateRandomString(6) . ';stop-opacity:1" />
            <stop offset="100%" style="stop-color:#' . generateRandomString(6) . ';);stop-opacity:1" />
            </linearGradient>
            <linearGradient id="grad11" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" style="stop-color:#' . generateRandomString(6) . ';stop-opacity:1" />
            <stop offset="100%" style="stop-color:#' . generateRandomString(6) . ';);stop-opacity:1" />
            </linearGradient>
            </defs>
            <line x1="' . randint(255) . '" y1="' . randint(60) . '" x2="' . randint(255) . '" y2="' . randint(60) . '" stroke="url(#grad1)" stroke-width="2" stroke-linecap="round" stroke-dasharray="1, 3"/>
            <circle cx="' . randint(255) . '" cy="' . randint(60) . '" r="' . randint(60) . '" stroke="url(#grad2)" stroke-width="3" fill="none" />
            <line x1="' . randint(255) . '" y1="' . randint(60) . '" x2="' . randint(255) . '" y2="' . randint(60) . '" stroke="url(#grad3)" stroke-width="2" stroke-linecap="round" stroke-dasharray="1, 3"/>
            <circle cx="' . randint(255) . '" cy="' . randint(60) . '" r="' . randint(60) . '" stroke="url(#grad4)" stroke-width="3" fill="none" />
            <line x1="' . randint(255) . '" y1="' . randint(60) . '" x2="' . randint(255) . '" y2="' . randint(60) . '" stroke="url(#grad5)" stroke-width="2" stroke-linecap="round" stroke-dasharray="1, 3"/>
            <text fill="url(#grad6)" font-size="45" x="0" y="50" style="transform:rotateY('. randint(90) .'deg); transform: rotateX('. randint(90) .'deg); transform: skewX(' . randint(255) . '); transform: skewY(' . randint(255) . '); transform: skewZ(' . randint(255) . ');">' . $code . '</text>
            <line x1="' . randint(255) . '" y1="' . randint(60) . '" x2="' . randint(255) . '" y2="' . randint(60) . '" stroke="url(#grad7)" stroke-width="2" stroke-linecap="round" stroke-dasharray="1, 3"/>
            <circle cx="' . randint(255) . '" cy="' . randint(60) . '" r="' . randint(60) . '" stroke="url(#grad8)" stroke-width="3" fill="none" />
            <line x1="' . randint(255) . '" y1="' . randint(60) . '" x2="' . randint(255) . '" y2="' . randint(60) . '" stroke="url(#grad9)" stroke-width="2" stroke-linecap="round" stroke-dasharray="1, 3"/>
            <circle cx="' . randint(255) . '" cy="' . randint(60) . '" r="' . randint(60) . '" stroke="url(#grad10)" stroke-width="3" fill="none" />
            <line x1="' . randint(255) . '" y1="' . randint(60) . '" x2="' . randint(255) . '" y2="' . randint(60) . '" stroke="url(#grad11)" stroke-width="2" stroke-linecap="round" stroke-dasharray="1, 3"/>
            </svg><br>';
    }
    ?>