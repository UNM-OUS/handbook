<?php
namespace Digraph\Modules\handbook_policies;

class PolicyComment extends \Digraph\DSO\Noun
{
    const FILESTORE = true;
    
    public function body()
    {
        $s = $this->cms()->helper('strings');
        $body = '<div class="digraph-card">Comment period ';
        if ($this['startdate']) {
            $body .= $s->dateHTML($this['startdate']).' through '.$s->dateHTML($this['enddate']);
        } else {
            $body .= 'ends '.$s->dateHTML($this['enddate']);
        }
        $body .= '</div>';
        $body .= parent::body();
        $fs = $this->cms()->helper('filestore');
        if ($files = $fs->list($this)) {
            foreach ($files as $f) {
                $body .= $f->metaCard([]);
            }
        }
        return $body;
    }

    public function formMap(string $action) : array
    {
        $s = $this->factory->cms()->helper('strings');
        $map = parent::formMap($action);
        $map['001_digraph_title'] = false;
        $map['101_startdate'] = [
            'label' => 'Start date (optional)',
            'field' => 'startdate',
            'class' => 'datetime',
            'required' => false
        ];
        $map['102_enddate'] = [
            'label' => 'End date',
            'field' => 'enddate',
            'class' => 'datetime',
            'required' => true
        ];
        $map['105_files'] = [
            'label' => 'Attached files',
            'class' => 'Digraph\\Forms\\Fields\\FileStoreFieldMulti',
            'extraConstructArgs' => ['default']
        ];
        return $map;
    }
}
