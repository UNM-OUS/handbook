<style>
.revision-comparison-info table {
    width: 100%;
}
.revision-comparison-info td {
    background: transparent !important;
    padding: 0;
    width: 50%;
}
</style>
<?php
//ensure both exist
$a = $cms->read($package['url.args.a']);
$b = $cms->read($package['url.args.b']);
if (!$a || !$b) {
    $package->error(404, 'A specified version wasn\'t found');
    return;
}

//make sure versions are in the right order
if ($a->effectiveDate() > $b->effectiveDate()) {
    $package->error(404, 'Versions specified in the wrong order');
    return;
}

//make sure parents match
$ap = $a->parent();
$bp = $b->parent();
if ($ap['dso.id'] != $bp['dso.id'] || $ap['dso.id'] != $package['noun.dso.id']) {
    $package->error(404, 'Invalid or mismatched parents');
    return;
}

//get helpers
$s = $cms->helper('strings');
$n = $cms->helper('notifications');

//display information
echo "<div class='digraph-card revision-comparison-info incidental'>";
echo "<h2>Revision comparison</h2>";
echo "<p>This page displays the text differences between two different revisions of ".$ap->url()->html().".</p>";
echo "<p>All formatting is stripped before comparison. Content that is more complex than plain text, such as images or tables, cannot be compared by this tool. Some dynamically-generated changes may not be reflected, such as the titles of linked policies and pages.<p>";
echo "<h3>Versions being compared</h3>";
echo "<table><tr><td valign='top'>";
echo $cms->helper('templates')->render(
    'policies/revision-info.twig',
    [
        'noun' => $a,
        'showDate' => true,
        'showLink' => false,
        'showType' => true,
        'showCurrent' => false,
        'showSummary' => true
    ]
);
echo "</td><td valign='top'>";
echo $cms->helper('templates')->render(
    'policies/revision-info.twig',
    [
        'noun' => $b,
        'showDate' => true,
        'showLink' => false,
        'showType' => true,
        'showCurrent' => false,
        'showSummary' => true
    ]
);
echo "</td></tr></table>";
echo "</div>";

//display output
$granularity = new cogpowered\FineDiff\Granularity\Word;
$diff = new cogpowered\FineDiff\Diff($granularity);
echo "<div class='diff'>";
$text = $diff->render(
    $a->content_text(),
    $b->content_text()
);
$text = preg_replace("/([\s]+)<\/del><ins>(.+?)\g{1}<\/ins>/msu", "</del><ins>$2</ins>$1", $text);
$text = str_replace("\t", '<span style="display:inline-block;width:2em;"> </span>', $text);
echo "<div style='white-space:pre-wrap;'>$text</div>";

echo "</div>";
