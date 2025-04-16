<?php

use Sys\I18n\Enum\DetectionMethod;
use Sys\I18n\Enum\Redirect;
use Sys\I18n\I18n;

return [
    'langs' => ['ru' => 'Русский', 'en' => 'English', 'de' => 'Deutsch'],
    'detectionMethod' => DetectionMethod::Segment,
    'redirect' => Redirect::Lang2empty,
    'index' => 0, //Position of the segment in uri or subdomain in host
];
