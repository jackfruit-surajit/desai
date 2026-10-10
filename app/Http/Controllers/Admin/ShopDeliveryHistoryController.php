<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use DB;
use URL;

class ShopDeliveryHistoryController extends Controller
{
    public function index()
    {
        return view('admin.delivery_history.list');
    }

    public function getDatatable(Request $request)
    {
        $query = DB::table('shop_delivery_history as t1')
            ->leftJoin('customers as t2', 't1.shop_id', '=', 't2.id')
            ->leftJoin('admins as t3', 't1.driver_id', '=', 't3.id')
            ->select(
                't1.*',
                't2.shop_name',
                't2.name as customer_name',
                't3.name as driver_name'
            );

        if ($request->has('filter_date') && !empty($request->filter_date)) {
            $query->whereDate('t1.delivery_date', $request->filter_date);
        }

        $data = $query->orderBy('t1.id', 'desc')->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('selfie', function ($model) {
                if (isset($model->selfie) && $model->selfie != '') {
                    $path = URL::asset('public/uploads/driver/' . $model->selfie);
                    return '<img height="50" width="50" src="' . $path . '"/>';
                } else {
                    return 'N/A';
                }
            })
            ->editColumn('delivery_date', function ($model) {
                return $model->delivery_date ? date('d-m-Y', strtotime($model->delivery_date)) : 'N/A';
            })
            ->editColumn('delivery_status', function ($model) {
                if ($model->delivery_status == 1) {
                    return '<span class="badge bg-success">Delivered</span>';
                } elseif ($model->delivery_status == 2) {
                    return '<span class="badge bg-danger">Failed</span>';
                } else {
                    return '<span class="badge bg-warning">Pending</span>';
                }
            })
            ->rawColumns(['selfie', 'delivery_status'])
            ->make(true);
    }
}
