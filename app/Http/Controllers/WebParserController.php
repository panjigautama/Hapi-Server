<?php namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Commodity;
use App\Models\DataSource;
use App\Models\GoogleGeodecode;
use App\Models\Location;
use App\Models\Report;

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
                        $tgl[] = ($th[9]->plaintext >= 1 && $th[9]->plaintext<= 7 && $val >7 )? date("Y-m", strtotime(' -1 month ')).$val :  date("Y-m-").$val;
                }
                
                $td = $tr->find('td');
                if(isset($td[9])){
                    $komoditi = explode(' ', $td[1]->plaintext);
                    if(in_array('Daging' ,$komoditi) && in_array('sapi' ,$komoditi) && in_array('Has' ,$komoditi)){
                        print($td[1]->plaintext . '<br>');

                        print( $td[2]->plaintext . '<br>');
                        
                        $i = 3;
                        foreach($tgl As $tanggal){
                            $harga = str_replace(',','', explode('.',$td[$i++]->plaintext)[0] );
                            echo 'tgl: '.$tanggal . '. '. $harga . '<br>';
                        }
                    }
                }
           }
        }
    }
    
}