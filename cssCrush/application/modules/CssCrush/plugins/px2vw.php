<?php
namespace CssCrush;

\csscrush_plugin('px2vw', function ($process) {
    $process->functions->add('px2vw', 'CssCrush\fn__px2vw');
    $process->functions->add('vw', 'CssCrush\fn__px2vw');
});

function fn__px2vw($input) {

    return px2vw($input, 'vw', Crush::$process->settings->get('px2vw-base', 1280));
}

function px2vw($input, $unit, $default_base) {

    list($px, $base) = Functions::parseArgsSimple($input) + array(1280, $default_base);

    return round(($px / $base) * 100) . $unit;
}
