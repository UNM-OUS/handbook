<?php
namespace Digraph\Modules\handbook_policies;

use Digraph\DSO\Noun;
use Digraph\Helpers\AbstractHelper;

class PolicyHelper extends AbstractHelper
{
    protected $_sortable;

    public function child_sort(Noun $a, Noun $b)
    {
        $a = $this->generateSortableNumber($a);
        $b = $this->generateSortableNumber($b);
        return strcmp($a, $b);
    }

    protected function generateSortableNumber(Noun $noun)
    {
        $order = 5;
        $name = $noun->name();
        if (substr($name, 0, 13) == 'Information: ') {
            $name = substr($name, 13);
            $order = 9;
        }
        $sortable = preg_replace('/^([a-z][0-9]+(\.[0-9]+)*).+$/i', '$1', $name);
        if ($sortable == $name) {
            $sortable = '_0';
        }
        $letter = substr($sortable, 0, 1);
        $sortable = substr($sortable, 1);
        $sortable = explode('.', $sortable);
        while (count($sortable) < 3) {
            $sortable[] = '0';
        }
        $sortable = array_map(
            function ($e) {
                return str_pad($e, 3, '0', STR_PAD_LEFT);
            },
            $sortable
        );
        array_unshift($sortable, $letter);
        array_unshift($sortable, $order);
        return implode('', $sortable);
    }

    public function officialUpdates()
    {
        $search = $this->cms->factory()->search();
        $search->where('${dso.type} = "policy-revision" AND ${policy.approval.date} is not null AND (${policy.minor_revision} <> 1 OR ${policy.minor_revision} is null)');
        $search->order('${policy.approval.date} desc');
        $results = $search->execute();
        return $results;
    }

    public function commentsCurrent()
    {
        $search = $this->cms->factory()->search();
        $search->where('${dso.type} = "policy-comment" AND cast(${enddate} as int) > :now AND (cast(${startdate} as int) is null OR cast(${startdate} as int) < :now)');
        $search->order('cast(${enddate} as int) asc');
        return $search->execute([':now' => time()]);
    }

    public function commentsPast()
    {
        $search = $this->cms->factory()->search();
        $search->where('${dso.type} = "policy-comment" AND cast(${enddate} as int) < :now');
        $search->order('cast(${enddate} as int) desc');
        return $search->execute([':now' => time()]);
    }
}
