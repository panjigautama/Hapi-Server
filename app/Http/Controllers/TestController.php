<?php namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class TestController extends BaseController
{
    public function index(){
        include ('simple_html_dom.php');
        $html = file_get_html('http://pasarjaya.co.id/komoditas');
        foreach($html->find('table[id=detail]') as $tb) 
        {    
           foreach($tb->find('tr') as $tr) 
           {

                $td = $tr->find('td');
                if(isset($td[1])){
                    $komoditi = explode(' ', $td[1]->plaintext);
                    if(in_array('Daging' ,$komoditi) && in_array('sapi' ,$komoditi) && in_array('Has' ,$komoditi)){
                        print_r($td[1]->plaintext . '<br>');

                        if(isset($td[2]))
                        print_r($td[2]->plaintext . '<br>');

                        if(isset($td[3]))
                        print_r($td[3]->plaintext . '<br>');

                        if(isset($td[4]))
                        print_r($td[4]->plaintext . '<br>');

                        if(isset($td[5]))
                        print_r($td[5]->plaintext . '<br>');

                        if(isset($td[6]))
                        print_r($td[6]->plaintext . '<br>');

                        if(isset($td[7]))
                        print_r($td[7]->plaintext . '<br>');

                        if(isset($td[8]))
                        print_r($td[8]->plaintext . '<br>');

                        if(isset($td[9]))
                        print_r($td[9]->plaintext . '<br>');
                    }
                }
               /*foreach($tr->find('td') as $td) 
               {
                    echo $td->plaintext .'<br>';
               }*/
           }
        }
    }
}