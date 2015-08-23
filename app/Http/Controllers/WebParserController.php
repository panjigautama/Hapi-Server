<?php namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Commodity as Commodity;
use App\Models\DataSource;
use App\Models\GoogleGeodecode;
use App\Models\Location;
use App\Models\Report as Report;

class WebParserController extends BaseController
{
    public function parsePasarjaya(){
        include ('simple_html_dom.php');
        $html = file_get_html('http://pasarjaya.co.id/komoditas');
        foreach($html->find('table[id=detail]') as $tb) 
        {    
           foreach($tb->find('tr') as $tr) 
           {

                $th = $tr->find('th');
                
                if(isset($th[9])){
                    $tgl = [];
                        foreach($th as $index => $val)
                        if ($index >= 3)
                        $tgl[] = ($th[9]->plaintext >= 1 && $th[9]->plaintext<= 7 && $val >7 )? date("Y-m", strtotime(' -1 month ')).$val->plaintext :  date("Y-m-").$val->plaintext;
                }
                
                $td = $tr->find('td');
                if(isset($td[9])){
                    $komoditi = explode(' ', $td[1]->plaintext);
                    if(in_array('Daging' ,$komoditi) && in_array('sapi' ,$komoditi) && in_array('Has' ,$komoditi)){
                        print($td[1]->plaintext . '<br>');

                        print( $td[2]->plaintext . '<br>');
                        
                        $i = 3;
                        foreach($tgl As $tanggal){
                            $cek = Report::where('created_at', 'like', $tanggal.'%')->first();
                            if(!$cek){
                            $tanggal =$tanggal.' 00:00:00';
                                $harga = str_replace(',','', explode('.',$td[$i++]->plaintext)[0] );
                                $report = new Report;
                                $report->price = $harga;
                                $report->created_at = $tanggal;
                                $report->commodities_id = 1;
                                $report->location_id = 1;
                                $report->data_sources_id = 1;
                                $report->sms_id = 1;
                                $report->save();
                                
                                //Demo data start
                                $report = new Report;
                                $report->price = $harga;
                                $report->created_at = $tanggal;
                                $report->commodities_id = 1;
                                $report->location_id = 2;
                                $report->data_sources_id = 1;
                                $report->sms_id = 1;
                                $report->save();
                                $report = new Report;
                                $report->price = $harga;
                                $report->created_at = $tanggal;
                                $report->commodities_id = 1;
                                $report->location_id = 3;
                                $report->data_sources_id = 1;
                                $report->sms_id = 1;
                                $report->save();
                                $report = new Report;
                                $report->price = $harga;
                                $report->created_at = $tanggal;
                                $report->commodities_id = 1;
                                $report->location_id = 4;
                                $report->data_sources_id = 1;
                                $report->sms_id = 1;
                                $report->save();
                                $report = new Report;
                                $report->price = $harga;
                                $report->created_at = $tanggal;
                                $report->commodities_id = 1;
                                $report->location_id = 5;
                                $report->data_sources_id = 1;
                                $report->sms_id = 1;
                                $report->save();
                                //Demo data end
                            }
                            echo 'tgl: '.$tanggal . '. '. $harga . '<br>';
                        }
                    }
                }
           }
        }
    }
    
}