<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Stolz\Assets\Laravel\Facade as Assets;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $breadcrumbs = [];
    private $styles = [];
    private $scripts = [];
    protected $canonical;
    protected $description;
    protected $keywords;
    protected $title;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * init
     */
    private function init()
    {
        $this->styles = [
            'fontawesome.min.css',
            'bootstrap.min.css',
            'style.css',
            'iziModal.min.css'
        ];
        $this->scripts = [
            'jquery.min.js',
            'sticky.min.js',
            'bootstrap.min.js',
            'core.js',
            'iziModal.min.js'
        ];
        $this->setAssets();

        $this->canonical = Str::lower(request()->url());
        $this->setViewGlobal('canonical', $this->canonical);
    }

    /**
     * set default assets
     */
    private function setAssets()
    {
        Assets::addJs($this->scripts);
        Assets::addCss($this->styles);
    }

    /**
     * Set page title (head)
     * @param $value
     */
    protected function setTitle($value)
    {
        $page = request('page', null);
        if ($page) {
            $value .= " {$page} страница";
        }
        $this->title = $value;
        $this->setViewGlobal('title', $value);
    }

    /**
     * Set global variable
     * @param $name
     * @param $value
     */
    private function setViewGlobal($name, $value)
    {
        View::share($name, $value);
    }

    /** Set page description (head)
     * @param $value
     */
    protected function setDescription($value)
    {
        $this->description = $value;
        $this->setViewGlobal('description', $value);
    }

    /** Set page keywords (head)
     * @param array $keywords
     * @internal param $value
     */
    protected function setKeywords(array $keywords)
    {
        if ($keywords) {
            $this->keywords = implode($keywords, ', ');
            $this->setViewGlobal('keywords', $this->keywords);
        }
    }

    /**
     * @param $css
     */
    protected function addCss($css)
    {
        $this->styles[] = $css;
        $this->setAssets();
    }

    /**
     * @param $js
     */
    protected function addJs($js)
    {
        $this->scripts[] = $js;
        $this->setAssets();
    }

    /**
     * Add breadcrumb
     * @param string $url
     * @param string $name
     * @param string $title
     */
    protected function addBread($url = '', $name = '', $title = '')
    {
        $this->breadcrumbs[] = ['url' => $url, 'name' => $name, 'title' => $title];
        $this->setBreadcrumbs();
    }

    /**
     * Set breadcrumbs to views
     */
    protected function setBreadcrumbs()
    {
        View::share('breadcrumbs', $this->breadcrumbs);
    }

    protected function getImg($firms, $city, $size = '450,450', $zoom = 10)
    {
        $points = [];
        $center = "{$city->lon},{$city->lat}";
        foreach ($firms as $n => $firm) {
            $points[] = "{$firm->lon},{$firm->lat},vkbkm";
        }
        return "https://static-maps.yandex.ru/1.x/?ll={$center}&size={$size}&l=map&z={$zoom}&l=map&pt=" . implode('~', $points);
    }
}
