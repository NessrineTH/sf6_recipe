<?php

namespace App\Service\DataGrid;

use APY\DataGridBundle\Grid\Column\Column;
use APY\DataGridBundle\Grid\Column\DateColumn;
use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Grid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Router;

class DataGridBase
{
    protected EntityManagerInterface $em;
    protected Grid $grid;

    public function __construct(EntityManagerInterface $em, Grid $grid)
    {
        $this->em = $em;
        $this->grid = $grid;
    }

    protected function getGridBase(): Grid
    {
        return $this->grid;
    }

    protected function addLink(Router $router, string $lib, string $route, array $routeParams = [], ?string $cssClass = null): string
    {
        $cssClass = (null !== $cssClass) ? $cssClass : 'btn btn-sm btn-outline-secondary';

        return ' <a class="' . $cssClass . '" href="' . $router->generate($route, $routeParams) . '" >' . $lib . '</a>';
    }

    protected function addJsLink(string $lib, string $onclick, ?string $cssClass = null): string
    {
        $cssClass = (null !== $cssClass) ? $cssClass : 'btn btn-outline-secondary btn-sm';

        return ' <button class="' . $cssClass . '" onclick="' . $onclick . '" >' . $lib . '</button>';
    }

    protected function createColumnTextNonMapped(string $title, string $id, int $size, $align = 'center'): TextColumn
    {
        return new TextColumn([
            'title' => $title,
            'id' => $id,
            'size' => $size,
            'sortable' => false,
            'filterable' => false,
            'source' => false,
            'align' => $align,
        ]);
    }

    protected function addColumnTextMapped(Grid $grid, string $lib, string $id, string $field, string $operator = Column::OPERATOR_LIKE, int $ordre = 0, int $size = 150): TextColumn
    {
        // EPSLATER : activer "filterable" -> attention casse la dg suivi admin sur tri état (paginator)
        $opt = [
            'title' => $lib,
            'id' => $id,
            'sortable' => true,
            'filterable' => false,
            'source' => true,
            'field' => $field,
            'isManualField' => true,
            'operatorsVisible' => false,
            'size' => $size,
            'align' => 'center',
        ];
        if ($operator) {
            $opt['filterable'] = true;
            $opt['defaultOperator'] = $operator;
        }
        $MyColumn20 = new TextColumn($opt);
        $MyColumn20->setSafe(false);
        $grid->addColumn($MyColumn20, $ordre);

        return $MyColumn20;
    }

    protected function addColumnDateMapped(Grid $grid, string $lib, string $id, string $field, string $operator = Column::OPERATOR_LIKE, int $ordre = 0, int $size = 150): DateColumn
    {
        // EPSLATER : activer "filterable" -> attention casse la dg suivi admin sur tri état (paginator)
        $opt = [
            'title' => $lib,
            'id' => $id,
            'sortable' => true,
            'filterable' => false,
            'source' => true,
            'field' => $field,
            'isManualField' => true,
            'operatorsVisible' => false,
            'format' => 'd/m/Y',
            'size' => $size,
            'align' => 'center',
        ];
        if ($operator) {
            $opt['filterable'] = true;
            $opt['defaultOperator'] = $operator;
        }
        $MyColumn20 = new DateColumn($opt);
        $MyColumn20->setSafe(false);
        $grid->addColumn($MyColumn20, $ordre);

        return $MyColumn20;
    }
}
