<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

if(!function_exists('getDB')){
    function getDB(Request $request)
    {
        if ($request['pays'])
            $idPays = $request['pays'];
        else
            $idPays = 201;

        $db = DB::table('pays')->where('idPays', $idPays)->get('bdPays');
        foreach ($db as $d) {
            $db = $d->bdPays;
        }
        return $db;
    }
}
