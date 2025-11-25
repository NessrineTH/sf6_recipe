<?php

namespace App\Service\DataGrid;

use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use EPS\ParamBundle\Entity\Param;

class DataGridParam extends DataGridBase
{
    public function getGrid($uneditablePkeys = [])
    {
        $grid = $this->grid;
        $source = new Entity(Param::class);

        $tableAlias = $source->getTableAlias();

        $source->manipulateQuery(function ($query) use ($tableAlias, $uneditablePkeys) {

            if (count($uneditablePkeys)) {
                $query->andWhere($tableAlias . '.pkey NOT IN (:uneditablePkeys)');
                $query->setParameter('uneditablePkeys', $uneditablePkeys);
            }
        });

        $grid->setSource($source);

        $MyColumn1 = new TextColumn(array(
            'title' => 'Valeur',
            'id' => 'val',
            'size' => '500',
            'sortable' => true,
            'filterable' => false,
            'source' => false
        ));
        $MyColumn1->manipulateRenderCell(function ($value, $row, $router) {
            $val = 'voir le détail';
            switch ($row->getField('ptype')) {
                case Param::pType_str :
                    $val = $row->getField('pvalStr');
                    break;
                case Param::pType_int:
                    $val = $row->getField('pvalInt');
                    break;
                case Param::pType_date:
                    $val = ($d = $row->getField('pvalDate')) ? $d->format('d/m/Y') : '';
                    break;

            }

            return '<span id="val_' . $row->getField('id') . '">' . $val . '</span>';

        });

        $MyColumn1->setSafe(false);
        $grid->addColumn($MyColumn1);

        $MyColumn2 = new TextColumn(array(
            'title' => '',
            'id' => 'action',
            'size' => '30',
            'sortable' => false,
            'filterable' => false,
            'source' => false
        ));
        $MyColumn2->manipulateRenderCell(function ($value, $row, $router) {
            $url = $router->generate('coreadminz_param_edit', ['id' => $row->getField('id')]);
            $link = ' <a class="btn btn-outline-secondary eps-common-onclick" href="#"  data-eps-action="formModalRefreshGrid" data-eps-urlaction="' . $url . '">modifier</a>';
            return '<span id="action_' . $row->getField('id') . '">' . $link . '</span>';

        });

        $MyColumn2->setSafe(false);
        $grid->addColumn($MyColumn2);
        $grid->setLimits(200);
        // $grid->setDefaultOrder('created', 'desc');
        return $grid->getGridResponse();
    }

}