<?php

use Sys\I18n\Enum\DetectionMethod;
use Sys\I18n\Enum\Redirect;

return [
    'langs' => ['ru' => 'Русский', 'en' => 'English', 'de' => 'Deutsch'],
    'detectionMethod' => MODE === 'api' ? DetectionMethod::None : DetectionMethod::Segment,
    'redirect' => MODE === 'api' ? Redirect::None : Redirect::Lang2empty,
    'index' => 0, //Position of the segment in uri or subdomain in host
];
