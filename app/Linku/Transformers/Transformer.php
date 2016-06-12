<?php

namespace Linku\Linku\Transformers;

use Carbon\Carbon;
use Illuminate\Http\Request;

abstract class Transformer
{

    public function transformCollection($items)
    {
        $return = [];

        foreach ($items as $item){
            array_push($return, $this->transform($item));
        }

        return $return;
    }

    public function transformGroupedCollection($group)
    {
        $return = [];

        foreach ($group as $key => $items){
            $return[$key] = $this->transformCollection($items);
        }

        return $return;
    }

    public function formatDate($date)
    {
        $date = Carbon::parse($date);
        return $date->toIso8601String();
    }

    public abstract function transform($item);

    public abstract function transformRequest(Request $request);

}