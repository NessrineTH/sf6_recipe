<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ThemeAppstackMenuService
{

    public function __construct(private UrlGeneratorInterface $router)
    {
        
    }

    public function generer(array $menu, string $currentRoute): array
    {
        if (false !== strpos($currentRoute, '?')) {
            $currentRoute = strstr($currentRoute, '?', true);
        }
        $currentRouteParts = explode('/', trim($currentRoute));
        $n1ActiveFound = false;
        if (count($currentRouteParts) > 1) {
            for ($i = count($currentRouteParts); $i > 2; --$i) {
                if (!$n1ActiveFound) {
                    $tmpRt = implode('/', $currentRouteParts);
                    foreach ($menu as $k => $v) {
                        if (!$n1ActiveFound) {
                            if (array_key_exists('child', $v)) {
                                $childs = $v['child'];
                                foreach ($childs as $kc => $vc) {
                                    if (array_key_exists('url', $vc)) {
                                        if (0 === strpos($vc['url'], $tmpRt)) {
                                            $menu[$k]['active'] = true;
                                            $menu[$k]['child'][$kc]['active'] = true;
                                            $n1ActiveFound = true;
                                            break;
                                        }
                                    }
                                }
                            }
                            if (array_key_exists('url', $v)) {
                                if (0 === strpos($v['url'], $tmpRt)) {
                                    $menu[$k]['active'] = true;
                                    $n1ActiveFound = true;
                                }
                            }
                        }
                    }
                    array_pop($currentRouteParts);
                }
            }
        }

        return $menu;
    }

    public function menuItemFromRoute($currentRoute, $label, $route, $parameters = [], $linkParameters = [])
    {
        $rt = $route ? $this->router->generate($route, $parameters) : '#';

        $active = false;

        $res = [
            'type' => 'item',
            'url' => $rt,
            'label' => $label,
            'active' => $active,
            'iclass' => '',
            'child' => [],
            'hasSub' => false,
            'css' => '',
            'icon' => '',
        ];

        $allowedLinkParameters = ['badge', 'onclick', 'iclass', 'id', 'icon'];

        foreach ($allowedLinkParameters as $p) {
            $res[$p] = array_key_exists($p, $linkParameters) ? $linkParameters[$p] : '';
        }

        return $res;
    }    

    
    public function menuSubItemFromRoute($menu, $key, $currentRoute, $label, $route, $parameters = [], $linkParameters = [])
    {
        $sub = $this->menuItemFromRoute($currentRoute, $label, $route, $parameters, $linkParameters);
        if ($sub['active']) {
            $menu['active'] = true;
        }
        $menu['child'][$key] = $sub;
        $menu['hasSub'] = true;

        return $menu;
    }

    

    public function menuItemParent($label, $iClass = '', $onclick = '', $css = '', $route = '', $parameters = [])
    {
        $rt = $route ? $this->router->generate($route, $parameters) : '#';
        $active = false;

        return [
            'type' => 'item',
            'url' => $rt,
            'active' => $active,
            'label' => $label,
            'child' => [],
            'iclass' => $iClass,
            'onclick' => $onclick,
            'css' => $css,
        ];
    }

    public function menuItemHeader($label)
    {
        $res = [
            'type' => 'header',
            'label' => $label,
        ];
        return $res;
    }

}