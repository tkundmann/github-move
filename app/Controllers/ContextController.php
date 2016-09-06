<?php

namespace App\Controllers;

use App\Data\Models\Site;
use App\Helpers\ContextHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ContextController extends Controller
{
    protected $context;
    
    protected $site;
    
    public function __construct(Request $request)
    {
        parent::__construct($request);
        
        $route = Route::current();
        $this->context = ContextHelper::getContextByRoute($route);
        
        view()->share('context', $this->context);
        
        if ($this->context && ContextHelper::isSiteContext($this->context)) {
            $this->site = Site::getSiteByContext($this->context);
        }
        
        view()->share('site', $this->site);
        
        $this->middleware('context.check:' . $this->context);
    }
    
    public function getSiteId($string = null)
    {
        if ($this->site) {
            return $this->site->id;
        } else {
            if ($string) {
                return 'NULL';
            } else {
                return null;
            }
        }
    }
}
