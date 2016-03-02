<?php

require __DIR__ . '/../micro/base/Autoload.php';

spl_autoload_register(['\Micro\Base\Autoload', 'loader'], true, false);

\Micro\Base\Autoload::setAlias('Micro', __DIR__ . '/../micro');
\Micro\Base\Autoload::setAlias('App', __DIR__);
\Micro\Base\Autoload::setAlias('Web', getenv('DOCUMENT_ROOT'));
