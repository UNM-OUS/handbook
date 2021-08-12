<?php
$package['fields.page_name'] = $package['fields.page_title'] = $package['url.text'] = 'Multiple options';
?>

<p>
    The requested URL can be resolved to more than one piece of content.
    To avoid breaking any links or causing external links to point at the wrong policy, this disambiguation page has been generated to explain the situation.
</p>

<p>
    Please choose an option below from the list of current content that has existed at this URL.
</p>

<?php
echo "<ul>";
foreach ($package['response.300'] as $i) {
    if (@$i['object']) {
        $object = $cms->read($i['object']);
        $objectLink = $object->url()->html()->string();
        if ($package['url.verb'] != 'display') {
            echo "<li><em>{$objectLink}</em><br>{$i['link']}</li>";
        } else {
            echo "<li>{$i['link']}</li>";
        }
    } else {
        echo "<li>{$i['link']}</li>";
    }
}
echo "</ul>";
?>

<p>
This may happen to policy links if a policy number is reused after an earlier policy with that number was abolished or renumbered.
</p>

<p>
If you control a website that links to this URL, please update your site to point at one of the options above instead of this page.
</p>