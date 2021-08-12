<?php
namespace Digraph\Modules\handbook_policies;

class Revision extends \Digraph\Modules\CoreTypes\Version
{
    public function policySummary()
    {
        $text = $this['policy.summary'];
        $f = $this->cms()->helper('filters');
        return $f->filterPreset($text, 'text-safe');
    }

    public function content_text($wordCount = null)
    {
        if ($wordCount) {
            $wordCount = $wordCount - count(preg_split('/[ \r\n]+/', $this->title()));
            if ($wordCount < 0) {
                $wordCount = 0;
            }
        }
        return $this->title() . PHP_EOL . PHP_EOL . parent::content_text($wordCount = null);
    }

    public function formMap(string $action): array
    {
        $map = parent::formMap($action);
        $map['version_effectivedate'] = false;
        $map['digraph_name']['label'] = 'Revision title';
        $map['digraph_name']['weight'] = 10;
        $map['digraph_name']['tips'][] = 'Title of revision for revision history lists.';
        $map['digraph_title']['class'] = '\\Formward\\Fields\\Hidden';
        $map['summary'] = [
            'label' => 'Revision summary',
            'field' => 'policy.summary',
            'class' => '\\Formward\\Fields\\Textarea',
            'tips' => [
                'Use this field to provide a brief description of the change, if necessary.',
                'Anything entered here will be publicly visible on the policy updates page, and on public revision history pages.',
            ],
            'weight' => 15,
        ];
        $map['faculty_notification'] = [
            'label' => 'Attached files',
            'class' => 'Digraph\\Forms\\Fields\\FileStoreFieldMulti',
            'extraConstructArgs' => ['memo'],
            'tips' => [
                'This field is to attach supporting/explanatory information, such as faculty notification memos.',
            ],
            'weight' => 16,
        ];
        $map['minor_revision'] = [
            'label' => 'Minor/maintenance revision',
            'field' => 'policy.minor_revision',
            'class' => 'checkbox',
            'cssClasses' => ['revisionForm_minorRevisionCheckbox'],
            'tips' => [
                'Minor revisions will not appear on the main policy updates page, and are for small non-substantive changes such as formatting, typos, or updating links.',
            ],
            'weight' => 19,
        ];
        $map['date'] = [
            'label' => 'Revision approval date',
            'field' => 'policy.approval.date',
            'class' => 'date',
            'cssClasses' => ['revisionForm_hiddenForMinor'],
            'weight' => 20,
        ];
        $map['approver'] = [
            'label' => 'Revision approved by',
            'field' => 'policy.approval.by',
            'class' => '\\Formward\\Fields\\Input',
            'cssClasses' => ['revisionForm_hiddenForMinor'],
            'weight' => 20,
        ];
        return $map;
    }

    public function searchIndexed()
    {
        return false;
    }

    public function effectiveDate()
    {
        if ($this['policy.minor_revision']) {
            return parent::effectiveDate();
        } else {
            return intval($this['policy.approval.date'] ?? parent::effectiveDate());
        }
    }
}
