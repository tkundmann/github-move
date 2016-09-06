<?php

namespace App\Controllers\Admin;

use App\Controllers\ContextController;
use App\Data\Models\Asset;
use App\Data\Models\Role;
use App\Data\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class RemoveFromSiteController extends ContextController
{
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
        $this->middleware('role:' . Role::ADMIN . '|' . Role::SUPERADMIN);
    }
    
    public function getRemove()
    {
        return view('admin.removeFromSite');
    }
    
    public function postRemoveByLotNumber()
    {
        $lotNumber = trim(Input::get('lot_number'));
        $method = Input::get('table_type_select');

        $removed = [
            'asset' => 0,
            'shipment' => 0
        ];

        $error = false;

        if (!$method || !$lotNumber) {
            $error = true;
        } else {
            switch ($method) {
                case 'assets':
                    if ($removedCount = $this->remove(Asset::class, $lotNumber)) {
                        $removed['asset'] = $removedCount;
                    }
                    break;
                case 'shipments':
                    if ($removedCount = $this->remove(Shipment::class, $lotNumber)) {
                        $removed['shipment'] = $removedCount;
                    }
                    break;
                case 'both':
                    if ($removedCount = $this->remove(Asset::class, $lotNumber)) {
                        $removed['asset'] = $removedCount;
                    }
    
                    if ($removedCount = $this->remove(Shipment::class, $lotNumber)) {
                        $removed['shipment'] = $removedCount;
                    }
                    break;
                default:
                    $error = true;
                    break;
            }
        }
    
        return redirect()->route('admin.remove')->with(['lot_number' => $lotNumber, 'by_lot_number_removed' => $removed, 'by_lot_number_error' => $error]);
    }

    public function postRemoveAssets()
    {
        $input = htmlspecialchars(Input::get('barcode_numbers'));
        $input = str_replace([',', ';', ' ', "\r\n", "\r", "\n"], ',', $input);
        $barcodeNumbers = explode(',', $input);

        $removed = [
            'successful' => [],
            'unsuccessful' => []
        ];

        foreach ($barcodeNumbers as $barcodeNumber) {
            if (Asset::where('barcodeNumber', '=', $barcodeNumber)->delete()) {
                array_push($removed['successful'], $barcodeNumber);
            } else {
                array_push($removed['unsuccessful'], $barcodeNumber);
            }
        }

        return redirect()->route('admin.remove')->with(['assets_removed' => $removed]);
    }
    
    protected function remove($class, $lotNumber)
    {
        return $class::where('lotNumber', '=', $lotNumber)->delete();
    }
}
