<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use DB;
use URL;

class SalesVisitHistoryController extends Controller
{
    public function index()
    {
        return view('admin.sales_visit_history.list');
    }

    public function getDatatable(Request $request)
    {
        $query = DB::table('sales_visit_history as t1')
            ->leftJoin('customers as t2', 't1.shop_id', '=', 't2.id')
            ->leftJoin('admins as t3', 't1.executive_id', '=', 't3.id')
            ->select(
                't1.*',
                't2.shop_name',
                't3.name as executive_name'
            );

        if ($request->has('filter_date') && !empty($request->filter_date)) {
            $query->whereDate('t1.created_at', $request->filter_date);
        }

        $data = $query->orderBy('t1.id', 'desc')->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('shop_photo', function ($model) {
                if (isset($model->shop_photo) && $model->shop_photo != '') {
                    $path = URL::asset('public/uploads/sales_executive/' . $model->shop_photo); // Assuming folder path
                    return '<img height="50" width="50" src="' . $path . '" onerror="this.src=\''.URL::asset('public/common/image/no-img.png').'\'"/>';
                } else {
                    return 'N/A';
                }
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at ? date('d-m-Y H:i A', strtotime($model->created_at)) : 'N/A';
            })
            ->editColumn('status', function ($model) {
                if ($model->status == 1) {
                    return '<span class="badge bg-success">Visited</span>';
                } elseif ($model->status == 2) {
                    return '<span class="badge bg-danger">Skipped</span>';
                } else {
                    return '<span class="badge bg-warning">Pending</span>';
                }
            })
            ->rawColumns(['shop_photo', 'status'])
            ->make(true);
    }
}
