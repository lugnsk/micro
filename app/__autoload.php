<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/linpax/microphp-framework/src/base/Autoload.php';

spl_autoload_register(['\Micro\Base\Autoload', 'loader'], true, false);

\Micro\Base\Autoload::setAlias('Micro', __DIR__ . '/../vendor/linpax/microphp-framework/src');
\Micro\Base\Autoload::setAlias('App', __DIR__);
