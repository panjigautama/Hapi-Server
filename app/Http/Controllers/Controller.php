<?php namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Commodity;
use App\Models\DataSource;
use App\Models\GoogleGeodecode;
use App\Models\Location;
use App\Models\Report As Report;
use Illuminate\Support\Facades\DB As DB;

class Controller extends BaseController
{
    public function index(){
        $data = Report::selectRaw('*,max(price) price')->where('created_at', '>=', $_GET['tgl1'] )->where('created_at', '<=', $_GET['tgl2'])
                ->groupBy(DB::Raw('date(created_at)'))->groupBy('location_id')
                ->orderBy(DB::Raw('date(created_at)'))->orderBy('location_id')
                ->get()->load('location');
        return view('index', ['data'=>$data]);
    }
}
