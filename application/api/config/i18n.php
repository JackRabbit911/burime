<?php

use Sys\I18n\Enum\DetectionMethod;
use Sys\I18n\Enum\Redirect;

if (MODE === 'api') {
    return [
        'detectionMethod' => DetectionMethod::None,
        'redirect' => Redirect::None,
    ];
}
