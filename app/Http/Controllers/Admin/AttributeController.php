<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function getValues(Request $request)
    {
        $data = $request->validate([
            'name' => 'required'
        ]);

        $attr = Attribute::whereName($data['name'])->first();
        if(is_null($attr))
            return response([ 'data' => []]);

        return response([ 'data' => $attr->values->pluck('value') ]);

    }
}
