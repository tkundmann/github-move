<?php
namespace App\Extensions\Eloquent;

use Illuminate\Support\Facades\Request;
use Input;

trait Sortable
{
    public static function createSortableLink($parameters)
    {
        $field = (count($parameters) >= 1) ? $parameters[0] : null;
        $label = (count($parameters) >= 2) ? $parameters[1] : null;
        $icon = (count($parameters) >= 3) ? $parameters[2] : null;
        $order = (count($parameters) >= 4) ? $parameters[3] : null;

        if (!$label) {
            if ($field) {
                $label = $field;
            }
            else {
                $label = '[[ sortable link error ]]';
            }
        }

        if ($field) {
            $sortIconOrderSuffix = (Input::get('sort_by') == $field ? (Input::get('order') === 'asc' ? '-asc' : '-desc') : null);

            if (!$sortIconOrderSuffix && $order && !empty($order)) {
                foreach ($order as $key => $value) {
                    if ($field == $value['column']) {
                        $sortIconOrderSuffix = '-' . $value['direction'];
                    }
                }
            }

            if ($sortIconOrderSuffix) {
                $sortIcon = $icon ? $icon : 'fa fa-sort';
            }
            else {
                $sortIcon = 'fa fa-sort';
            }

            $queryParameters = array_merge(Input::get(), array('sort_by' => $field, 'order' => (Input::get('order') === 'asc' ? 'desc' : 'asc')));
            $queryString = http_build_query($queryParameters);

            return '<a href="' . url(Request::path() . '?' . $queryString) . '" >' . htmlentities($label) . '</a> <i class="' . $sortIcon . $sortIconOrderSuffix . '"></i>';
        }
        else {
            return $label;
        }
    }
    
    public function scopeSortable($query, array $default = null)
    {
        if (Input::has('sort_by') && Input::has('order')) {
            return $query->orderBy(Input::get('sort_by'), Input::get('order'));
        }
        elseif (($default !== null) && !empty($default)) {
            foreach ($default as $field => $order) {
                $query->orderBy($field, $order);
            }
            return $query;
        }
        else {
            return $query;
        }
    }
}
