<?php
namespace Digraph\Modules\handbook_policies;

class Policy extends \Digraph\Modules\CoreTypes\Versioned
{
    const VERSION_TYPE = 'policy-revision';

    public function actions($links)
    {
        $groups = $this->factory->cms()->helper('users')->groups();
        $admin = in_array('editor', $groups);
        if ($admin) {
            $links['versions'] = '!noun/versions';
        }
        return $links;
    }

    public function body()
    {
        $body = parent::body();
        if ($this['public_revisions']) {
            $t = $this->factory->cms()->helper('templates');
            $body .= $t->render('policies/public-revisions-footer.twig', [
                'policy' => $this,
            ]);
        }
        return $body;
    }

    public function addMenuFilter(string $type)
    {
        return $type != static::VERSION_TYPE;
    }

    public function formMap(string $action): array
    {
        $map = parent::formMap($action);
        $map['public_revisions'] = [
            'label' => 'Enable public revision interface',
            'tips' => [
                'When checked a revision history link will be appended to this policy page.',
            ],
            'field' => 'public_revisions',
            'class' => 'checkbox',
            'weight' => 500,
        ];
        $map['digraph_slug'] = [
            'default' => '[policynum]',
        ];
        return $map;
    }

    public function slugVars()
    {
        return [
            'policynum' => preg_replace('/^([a-z][0-9]+(\.[0-9]+)*).+$/i', '$1', $this->name()),
        ];
    }
}
