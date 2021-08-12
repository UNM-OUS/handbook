<p>
    This list displays all recorded online updates to policies, including maintenance/web updates.
    Revisions are sorted by the date they were added to the website.
    Revision history is not complete for all policies prior to 2019.
</p>
<?php
$p = $cms->helper('paginator');
$t = $cms->helper('templates');

$search = $cms->factory()->search();
$search->where('${dso.type} = "policy-revision"');

echo $p->paginate(
    $search->count(),
    $package,
    'page',
    10,
    function($start,$end) use($search,$t) {
        $search->offset($start-1);
        $search->limit($end-$start);
        $search->order('${dso.created.date} desc');
        foreach ($search->execute() as $noun) {
            echo $t->render(
                'policies/revision-info.twig',
                [
                    'noun' => $noun,
                    'showDate' => true,
                    'showLink' => true,
                    'showParent' => true,
                    'showType' => true,
                    'showCurrent' => false,
                    'showSummary' => false
                ]
            );
        }
    }
);