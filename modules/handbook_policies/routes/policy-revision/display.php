<?php
$noun = $package->noun();
$parent = $noun->parent();
if (!$parent) {
    $cms->helper('notifications')->error(
        $cms->helper('strings')->string('version.orphaned')
    );
} else {
    $s = $cms->helper('strings');
    echo $cms->helper('templates')->render(
        'policies/revision-info.twig',
        [
            'noun' => $noun,
            'showDate' => true,
            'showLink' => false,
            'showType' => true,
            'showCurrent' => true,
            'showSummary' => true
        ]
    );
}
echo $noun->body();
