<?php

namespace App\Controllers\Page;

use App\Controllers\ContextController;
use App\Data\Constants;
use App\Data\Models\Feature;
use App\Data\Models\File;
use App\Data\Models\Role;
use App\Helpers\UrlHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageController extends ContextController
{

    const RESULTS_PER_PAGE = 50;
    const STRING_LIMIT = 50;

    /**
     * Create a new controller instance.
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->middleware('auth');
        $this->middleware('context.permissions:' . $this->context);
        $this->middleware('role:'. Role::USER .'|'. Role::SUPERUSER);
    }
    
    public function getPage($context, $page)
    {
        if (($this->site->hasFeature(Feature::HAS_PAGES)) && ($this->site->hasPage($page))) {

            $sitePage = $this->site->getPage($page);

            if ($sitePage->type == 'Standard') {
                $canAccessPageUserRestricted = true;
                $canAccessPageLotNumberRestricted = true;

                if (!Auth::user()->hasRole([ Role::SUPERUSER ])) {
                    if ($sitePage->userRestricted) {
                        $canAccessPageUserRestricted = false;

                        if (Auth::user()->pages->where('id', $sitePage->id)->first()) {
                            $canAccessPageUserRestricted = true;
                        }
                    }

                    if ($sitePage->lotNumberRestricted) {
                        $canAccessPageLotNumberRestricted = false;

                        $userLotNumbers = Auth::user()->lotNumbers->pluck('prefix')->toArray();
                        $fileLotNumbers = [];

                        foreach ($sitePage->files as $file) {
                            $fileLotNumbers = array_merge($fileLotNumbers, $file->lotNumbers->pluck('prefix')->toArray());
                        }

                        $fileLotNumbers = array_unique($fileLotNumbers);

                        $intersection = array_intersect($fileLotNumbers, $userLotNumbers);

                        if (count($intersection) > 0) {
                            $canAccessPageLotNumberRestricted = true;
                        }
                    }
                }

                if (!$canAccessPageUserRestricted || !$canAccessPageLotNumberRestricted) {
                    throw new AccessDeniedHttpException();
                }
            }

            $files = [];

            $query = File::query();

            $query->where('page_id', '=', $sitePage->id);
            $query = $query->sortable(['id' => 'asc']);
            $files = $query->paginate(self::RESULTS_PER_PAGE);

            $fileAccessArray = [];

            if ($sitePage->lotNumberRestricted) {
                $userLotNumbers = Auth::user()->lotNumbers->pluck('prefix')->toArray();

                foreach ($files->items() as $file) {
                    $fileLotNumbers = $file->lotNumbers->pluck('prefix')->toArray();
                    $fileAccessArray[$file->id] = (count(array_intersect($fileLotNumbers, $userLotNumbers)) > 0) || Auth::user()->hasRole([ Role::SUPERUSER ]);
                }

            }

            $fileAvailabilityArray = [];

            if (Constants::CHECK_FILE_AVAILABILITY) {
                foreach ($files->items() as $file) {
                    if (!$sitePage->lotNumberRestricted || ($sitePage->lotNumberRestricted && $fileAccessArray[$file->id])) {
                        $fileAvailabilityArray[$file->id] = UrlHelper::isFileAvailable($file->url);
                    }
                    else {
                        $fileAvailabilityArray[$file->id] = false;
                    }
                }
            }
            else {
                foreach ($files->items() as $file) {
                    $fileAvailabilityArray[$file->id] = true;
                }
            }

            $hasFilesWithDate = false;

            foreach ($files->items() as $file) {
                if ($file->fileDate) {
                    $hasFilesWithDate = true;
                }
            }

            return view('page.page', [
                'page' => $sitePage,
                'files' => $files,
                'fileAccess' => $fileAccessArray,
                'fileAvailability' => $fileAvailabilityArray,
                'hasFilesWithDate' => $hasFilesWithDate,
                'order' => $query->getQuery()->orders,
                'limit' => self::STRING_LIMIT
            ]);
        }
        else {
            throw new NotFoundHttpException();
        }
    }
}
