<?php

use function Deployer\run;
use function Deployer\task;

task('opcache:clear', function () {
    run('~/bin/restart_php.sh');
});