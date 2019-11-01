<?php


namespace SimplePaginator\SimplePaginator;


/**
* Simple plug-and-play pagination for rapid development
*
* PHP version > 7.1.27
* @category PHP
* @author Michael Cook <mcook0775@gmail.com>
* @copyright 2019 Michael Cook
* @license https://en.wikipedia.org/wiki/MIT_License MIT License
*/


class SimplePaginator
{

    private $view;
    private $paginated;
    private $totalItems;
    private $itemsPerPage;
    private $numberOfPages;
    private $currentPage;
    private $html;
    private $pages;
    private $data;
    private $uri;

    /**
    * @param $view must be an instance of the twig view object
    * In Slim 3, use $this->view when instantiating inside a controller or route
    * By default, @param twig is true, but if you are not using twig, use false
    */
    public function __construct($view=NULL, Array $data, $itemsPerPage)
    {
        $this->view = $view;
        $this->data = $data;
        $this->setBaseUri();
        $this->setCurrentPage();
        $this->setTotalItems($this->data);
        $this->setItemsPerPage($itemsPerPage);
        $this->setPaginated();
        $this->setPages();
        $this->setNumberOfPages();
        if (!is_null($view)) {
            $this->addGlobals();
        }
        $this->setHtml();
    }

    /**
    * @return array current page of data from paginated $this->data
    * loop through in view to render data
    */
    public function getPage()
    {
        return $this->pages[$this->currentPage - 1];
    }

    /**
    * @return string containing raw html for pagination nav
    * To be used when not using SimplePaginator with Twig
    */
    public function getNavHtml()
    {
        return $this->html;
    }

    /**
    * @return request uri without query string
    * $this->uri property used to automatically set pagination nav links
    */
    private function setBaseUri()
    {
        $this->uri = strtok($_SERVER['REQUEST_URI'], '?');
    }

    /**
    * Get current page from query string, type cast to int, set $this->currentPage property
    */
    private function setCurrentPage()
    {
        if (isset($_GET['page'])) {
            $this->currentPage = (int) $_GET['page'];
        }
    }

    private function setTotalItems($arr)
    {
        $this->totalItems = count($arr);
    }

    private function setItemsPerPage($numberOfItems)
    {
        $this->itemsPerPage = $numberOfItems;
    }

    private function setPaginated()
    {
        if ($this->totalItems > $this->itemsPerPage) {
            $this->paginated = true;
        } else {
            $this->paginated = false;
        }
    }

    private function setPages()
    {
        if ($this->paginated) {
            $this->pages = array_chunk($this->data, $this->itemsPerPage, true);
        } else {
            $this->pages = $this->data;
        }
    }

    private function setNumberOfPages()
    {
        $this->numberOfPages = count($this->pages);
    }

    /**
    * Adds required variables for pagination to Twig environment
    * Allows pagination nav to work out of the box without setup
    */
    private function addGlobals()
    {
        $this->view->getEnvironment()->addGlobal('paginated', $this->paginated);
        $this->view->getEnvironment()->addGlobal('pages', $this->numberOfPages);
        $this->view->getEnvironment()->addGlobal('page', $this->currentPage);
        $this->view->getEnvironment()->addGlobal('uri', $this->uri);
    }

    /**
    * Generate html for pagination nav if not using Twig
    * Style and appearance can be customized by changing Bootstrap classes or adding custom classes
    */
    private function setHtml()
    {
        $html = '<nav class="mt-5"><ul class="pagination pagination-sm">';

        if ($this->currentPage > 1) {
            $html .= '<li class="page-item"><a class="page-link text-info" href="'
                  . $this->uri . '?page=' . ($this->currentPage - 1) . '">Previous</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><a class="page-link text-secondary">Previous</a></li>';
        }

        for ($i = 1; $i <= $this->numberOfPages; $i++) {
            if ($this->currentPage == $i) {
                $html .= '<li class="page-item active"><a class="page-link" href="'
                . $this->uri . '?page=' . $i . '">' . $i . '</a></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link" href="'
                . $this->uri . '?page=' . $i . '">' . $i . '</a></li>';
            }
        }

        if ($this->currentPage == $this->numberOfPages) {
            $html .= '<li class="page-item disabled"><a class="page-link text-secondary">Next</a></li>';
        } else {
            $html .= '<li class="page-item"><a class="page-link text-info" href="'
                  . $this->uri . '?page=' . ($this->currentPage + 1) . '">Next</a></li>';
        }

        $html .= '</ul></nav>';
        $this->html = $html;
    }
}
