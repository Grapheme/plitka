<?php

return array(

    'paginate_limit' => 30,

    ## Disable functionality of changing url "on-the-fly" for generating
    ## seo-friendly url (via URL::route('page', '...')) with right various url-segments for multilingual pages.
    'disable_url_modification' => 0,

    ## Directory for module UPLOADS
    'uploads_dir' => public_path('uploads/files'),

    'dengionline' => array(
        'url' => 'https://www.onlinedengi.ru/wmpaycheck.php',
        'project' => '294',
        'secret' => 'IT\'S_A_PROJECT_SECRET_WORD',
        'amount' => '100',
        'return_url_success' => URL::route('dengionline.return_url_success'),
        'return_url_fail' => URL::route('dengionline.return_url_fail'),
        'notification_url' => URL::route('dengionline.notification_url'),
    ),
);
