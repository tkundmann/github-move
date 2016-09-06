<?php

namespace App\Extensions\Presenter;

use Illuminate\Pagination\UrlWindow;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Pagination\BootstrapThreePresenter as BaseBootstrapThreePresenter;

class BootstrapThreePresenter extends BaseBootstrapThreePresenter
{

    /**
     * Create a new Bootstrap presenter instance.
     *
     * @param  \Illuminate\Contracts\Pagination\Paginator  $paginator
     * @param  \Illuminate\Pagination\UrlWindow|null  $window
     * @return void
     */
    public function __construct(PaginatorContract $paginator, UrlWindow $window = null)
    {
        $this->paginator = $paginator;
        $this->window = is_null($window) ? UrlWindow::make($paginator, 2) : $window->get();
    }

    /**
     * Convert the URL window into Bootstrap HTML.
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function render()
    {
        if ($this->hasPages()) {
            return new HtmlString(sprintf(
                '<ul class="pagination">%s %s %s %s %s</ul>',
                $this->getFirstButton('&lt;&lt;'),
                $this->getPreviousButton('&lt;'),
                $this->getLinks(),
                $this->getNextButton('&gt;'),
                $this->getLastButton('&gt;&gt;')
            ));
        }

        return '';
    }

    /**
     * Get the first page pagination element.
     *
     * @param  string  $text
     * @return string
     */
    public function getFirstButton($text = '&laquo;')
    {
        if ($this->paginator->currentPage() <= 1) {
            return $this->getDisabledTextWrapper($text);
        }

        $url = $this->paginator->url(1);

        return $this->getPageLinkWrapper($url, $text, 'first');
    }

    /**
     * Get the last page pagination element.
     *
     * @param  string  $text
     * @return string
     */
    public function getLastButton($text = '&raquo;')
    {
        if ($this->paginator->currentPage() == $this->paginator->lastPage()) {
            return $this->getDisabledTextWrapper($text);
        }

        $url = $this->paginator->url($this->paginator->lastPage());

        return $this->getPageLinkWrapper($url, $text, 'last');
    }
}
