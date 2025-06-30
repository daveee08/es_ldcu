<?php

namespace App\Http\Controllers\BookkeeperControllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class CostCenterController extends Controller
{
    public function costcenter_create(Request $request)
    {
        $description = $request->get('description');
        $code = $request->get('code');
        $id = $request->get('id');

        if($id == 0)
        {
            $check = db::table('acc_costcenters')
                ->where('deleted', 0)
                ->where('acc_code', $code)
                ->first();

            if($check)
            {
                return 'exist';
            }

            db::table('acc_costcenters')
                ->insert([
                    'acc_code' => $code,
                    'description' => $description,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => Carbon::now('Asia/Manila'),
                ]);

            return 'done';
        }
    }

    public function costcenter_read(Request $request)
    {
        return db::table('acc_costcenters')
            ->where('deleted', 0)
            ->orderBy('acc_code')
            ->get();
    }
}