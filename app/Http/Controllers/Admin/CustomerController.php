<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Customer;
use App\Models\Area;
use App\Models\Road;
use DB;
use URL;
use Auth;
use Validator;
use Hash;

class CustomerController extends Controller
{
    
    public function index(Request $request){
        return view('admin.customer.list');
    }

    public function getStudentDatatable(Request $request) {
        $data = DB::table('customers')
                ->leftJoin('road', 'customers.road_id', '=', 'road.id')
                ->leftJoin('area', 'road.area_id', '=', 'area.id')
                ->orderBy('customers.id', 'desc')
                ->get(['customers.*', 'area.area_name', 'road.road_name']);

        return Datatables::of($data)
            ->addIndexColumn()

            ->editColumn('image', function ($model) {
                $noImg = URL::asset(asset_path('common/image/no-img.png'));
                if (isset($model->shop_photo) && $model->shop_photo != '' && file_exists(public_path('uploads/customer/' . $model->shop_photo))) {
                    $path = URL::asset(asset_path('uploads/customer/' . $model->shop_photo));
                    return '<a href="' . $path . '" target="_blank"><img height="50" width="50" src="' . $path . '" class="img-thumbnail" onerror="this.src=\'' . $noImg . '\'"/></a>';
                } else {
                    $path = $noImg;
                    return '<img height="50" width="50" src="' . $path . '" class="img-thumbnail" onerror="this.src=\'' . $noImg . '\'"/>';
                }
            })

            ->editColumn('address', function ($model) {
                return wordwrap($model->address,50,"<br>\n");
            })

            ->editColumn('created_at', function ($model) {
                return !empty($model->created_at) ? date('d-m-Y', strtotime($model->created_at)) : '';
            })

            ->editColumn('status', function ($model) {                
                if ($model->status == '0') {
                    $status = '';
                } else if ($model->status == '1') {
                    $status = 'checked';
                } else if ($model->status == '2') {
                    $status = '';
                }
                $status = '<div class="container">
                                <div class="row text-center">
                                    <div class="col-md-12 offset-md-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                            type="checkbox" 
                                            role="switch" 
                                            id="flexSwitchCheckChecked_'.$model->id.'" '.$status.' 
                                            data-href="' . Route("customer-status-change", ['id' => base64_encode($model->id)]) . '"
                                            onchange="changeStatusStaff(this)">
                                        </div>
                                    </div>
                                </div>
                            </div>';
                    
                return $status;
            })

            ->addColumn('action', function ($model) {

                $edit = $delete ='';

                $edit = '<a href="' . Route("customer-edit", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-primary" title="Edit"><i class="fa fa-edit"></i> Edit</span></a>';    
                $delete = '<a href="' . Route("customer-delete", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-danger"><i class="fa fa-trash" title="Delete"></i> Delete</span></a>';

                return
                    '<div class="action-btns">'.
                       $edit . $delete.
                    '</div>';
            })
            ->rawColumns(['address','status', 'action','image'])
            ->make(true);
    }

    public function create(Request $request){
        $areas = Area::where('status','1')->get();
        $roads = Road::where('status','1')->get();
        return view('admin.customer.add',compact(['areas','roads']));
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|numeric|digits:10|unique:customers,mobile_no',
            'status' => 'required',
            'gst_no'   => 'nullable|string|max:100',
            'pan_no'   => 'nullable|string|max:100',
            'shop_name' => 'required|string|max:255',
            'shop_photo' => 'required|mimes:png,jpeg,jpg,JPEG',
            'area_id' => 'nullable|integer',
            'road_id' => 'required|integer',
            'latitude'  => 'required|string|max:100',
            'longitude' => 'required|string|max:100',
            'state'   => 'required|string|max:255',
            'city'  => 'required|string|max:255',
            'pin_code' => 'required|string|max:50',
            'landmark'   => 'required|string|max:255',
        ]);

        $validator->after(function ($validator) use ($request) {

        });

        if ($validator->passes()) {

            $input = $request->all();
            unset($input['_token']);
            
            $road = Road::where('id', $input['road_id'])->first(['road_name', 'area_id']);
            $area_id = !empty($input['area_id']) ? $input['area_id'] : ($road ? $road->area_id : null);
            $area = $area_id ? Area::where('id', $area_id)->first(['area_name']) : null;

            $road_name = $road ? $road->road_name : '';
            $area_name = $area ? $area->area_name : '';
            $name = mb_strtoupper($input['name'], 'UTF-8');
            $shop_name = mb_strtoupper($input['shop_name'], 'UTF-8');
            $gst_no = !empty($input['gst_no']) ? mb_strtoupper($input['gst_no'], 'UTF-8') : null;
            $pan_no = !empty($input['pan_no']) ? mb_strtoupper($input['pan_no'], 'UTF-8') : null;
            $state = mb_strtoupper($input['state'], 'UTF-8');
            $city = mb_strtoupper($input['city'], 'UTF-8');
            $pin_code = mb_strtoupper($input['pin_code'], 'UTF-8');
            $landmark = mb_strtoupper($input['landmark'], 'UTF-8');
            $address = mb_strtoupper(trim("{$road_name}, {$area_name}, {$landmark}, {$state}, {$city}, {$pin_code}", ', '), 'UTF-8');

            $data = [];
            $data['name'] = $name;
            $data['email'] = strtolower($input['email']);
            $data['mobile_no'] = $input['phone'];
            $data['status'] = $input['status'];
           
            if ($request->hasFile('shop_photo')) {
                $sample_image = $request->file('shop_photo');
                $imagename = $this->rand_string(14) . '.' . $sample_image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/customer');
                $sample_image->move($destinationPath, $imagename);
                $data['shop_photo'] = $imagename;
            }

            $data['address'] = $address;
            $data['gst_no'] = $gst_no;
            $data['pan_no'] = $pan_no;
            $data['latitude'] = $input['latitude'];
            $data['longitude'] = $input['longitude'];
            
            $data['shop_name'] = $shop_name;
            $data['area_id'] = $area_id;
            $data['road_id'] = $input['road_id'];

            $data['state'] = $state;
            $data['city'] = $city;
            $data['pin_code'] = $pin_code;
            $data['landmark'] = $landmark;

            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');

            DB::table('customers')->insertGetId($data);

            return redirect()->route('customer-list')->with('success', 'Customer created successfully.')->with('success_msg', 'Customer created successfully.');
        } else {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error_msg', 'Something went wrong please check your input.');
        }
    }
    
    public function statusChange(Request $request,$id){
        $datareturn = [];
        $data = [];
        $input = [];
        $id = base64_decode($id);
        $input = $request->all();

        $data['status'] = $input['status'];
        $data['updated_at'] = date('Y-m-d H:i:s');

        $model = DB::table('customers')->where('id',$id)->update($data);
        $datareturn['status'] = 200;
        $datareturn['msg'] = 'Customers status updated successfully.';
        return response()->json($datareturn);
    }

    public function delete($id) {
        $id = base64_decode($id);
        $model = DB::table('customers')->where('id', $id)->first();
        
        if (!empty($model)) {

            if(DB::table('customers')->where('id',$id)->delete()){
                return redirect()->route('customer-list')->withSuccess('Customer deleted successfully.');
            }
            else{
                return redirect()->back()->withErrors('Error!! while deleting customer!!!'); 
            }
        } else {
            return redirect()->back()->withErrors('Sorry ! No customer details found.'); 
        }
    }
      
    public function edit($id) {
        $id = base64_decode($id);
        $model = Customer::where('id', $id)->first();
        if (!$model) {
            return redirect()->back()->with('error_msg', 'Invalid Link!');
        }
        $data['id'] = $id;
        $data['model'] = $model;
        $data['areas'] = Area::where('status','1')->get();
        $data['roads'] = Road::where('status','1')->get();
        return view('admin.customer.edit', $data);
    }

    public function update(Request $request) {

        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,'.$request->customer_id.',id',
            'phone' => 'required|numeric|digits:10|unique:customers,mobile_no,'.$request->customer_id.',id',
            'status' => 'required',
            'gst_no'   => 'nullable|string|max:100',
            'pan_no'   => 'nullable|string|max:100',
            'shop_name' => 'required|string|max:255',
            'shop_photo' => 'nullable|mimes:png,jpeg,jpg,JPEG',
            'area_id' => 'nullable|integer',
            'road_id' => 'required|integer',
            'latitude'  => 'required|string|max:100',
            'longitude' => 'required|string|max:100',
            'state'   => 'required|string|max:255',
            'city'  => 'required|string|max:255',
            'pin_code' => 'required|string|max:50',
            'landmark'   => 'required|string|max:255',
        ]);

        $validator->after(function ($validator) use ($request) {

        });

        if ($validator->passes()) {
            
            $road = Road::where('id', $request->road_id)->first(['road_name', 'area_id']);
            $area_id = !empty($request->area_id) ? $request->area_id : ($road ? $road->area_id : null);
            $area = $area_id ? Area::where('id', $area_id)->first(['area_name']) : null;

            $road_name = $road ? $road->road_name : '';
            $area_name = $area ? $area->area_name : '';
            $name = mb_strtoupper($request->name, 'UTF-8');
            $shop_name = mb_strtoupper($request->shop_name, 'UTF-8');
            $gst_no = !empty($request->gst_no) ? mb_strtoupper($request->gst_no, 'UTF-8') : null;
            $pan_no = !empty($request->pan_no) ? mb_strtoupper($request->pan_no, 'UTF-8') : null;
            $state = mb_strtoupper($request->state, 'UTF-8');
            $city = mb_strtoupper($request->city, 'UTF-8');
            $pin_code = mb_strtoupper($request->pin_code, 'UTF-8');
            $landmark = mb_strtoupper($request->landmark, 'UTF-8');
            $address = mb_strtoupper(trim("{$road_name}, {$area_name}, {$landmark}, {$state}, {$city}, {$pin_code}", ', '), 'UTF-8');

            $data = array(
                'name' => $name,
                'email' => strtolower($request->email),
                'mobile_no' => $request->phone,
                'status' => $request->status,
                'shop_name' => $shop_name,
                'area_id' => $area_id,
                'road_id' => $request->road_id,
                'gst_no' => $gst_no,
                'pan_no' => $pan_no,
                'address' => $address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'state' => $state,
                'city' => $city,
                'pin_code' => $pin_code,
                'landmark' => $landmark,
                'updated_at' => date('Y-m-d H:i:s'),
            );

            if ($request->hasFile('shop_photo')) {
                $sample_image = $request->file('shop_photo');
                $imagename = $this->rand_string(14) . '.' . $sample_image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/customer');
                $sample_image->move($destinationPath, $imagename);
                $data['shop_photo'] = $imagename;
            }
            
            Customer::where('id', $request->customer_id)->update($data);
            return redirect()->route('customer-list')->with('success', 'Customer updated successfully.')->with('success_msg', 'Customer updated successfully.');
        } else {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error_msg', 'Something went wrong please check your input.');
        }
    }

}
