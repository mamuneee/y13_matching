<?php

namespace App\Http\Controllers;

use App\Contracts\OccupationParser;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OccupationsController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $occparser;

    public function __construct(OccupationParser $parser)
    {
        $this->occparser = $parser;
    }

    public function index()
    {
        return $this->occparser->list();
    }

    public function compare(Request $request)
    {
        $this->occparser->setScope('skills');
        $occupation_1 = $this->occparser->get($request->get('occupation_1'));
        $occupation_2 = $this->occparser->get($request->get('occupation_2'));
       
        /** IMPLEMENT COMPARISON **/
        if(!empty(count($occupation_2)) && !empty(count($occupation_2))){
            $scores = [];
            foreach($occupation_1 as $i){
                foreach($occupation_2 as $j){
                    if($i['label'] == $j['label']){
                        array_push($scores, (1-abs($i['value'] - $j['value'])/$i['value']));
                        break;
                    }
                }
                
            }
            $match = ceil((array_sum($scores)/count($scores) - (count($occupation_1)-count($scores))/count($occupation_1))*100);
        } else {
            $match = '0';
        }

        /** IMPLEMENT COMPARISON **/

        return [
            'occupation_1' => $occupation_1,
            'occupation_2' => $occupation_2,
            'match' => $match
        ];
    }
}
