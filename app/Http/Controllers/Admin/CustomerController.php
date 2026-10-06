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
        $data = DB::table('customers')->orderBy('customers.id','desc')->get(['customers.*']);

        return Datatables::of($data)
            ->addIndexColumn()

            ->editColumn('image', function ($model) {
                if (isset($model->photo) && $model->photo != '') {
                    $path = URL::asset(asset_path('uploads/customer/' . $model->photo));
                } else {
                    $path =URL::asset(asset_path('common/image/no-img.png'));
                }

                return '<img height="50" width="50" src="' . $path . '"/>';
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

                $edit = '<a href="' . Route("customer-edit", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-primary" title="Edit"><i class="fa fa-edit"></i></span></a>';    
                $delete = '<a href="' . Route("customer-delete", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-danger"><i class="fa fa-trash" title="Delete"></i></span></a>';

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
            'name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|numeric|digits:10|unique:customers,mobile_no',
            'status' => 'required',
            'gst_no'   => 'nullable|string',
            'pan_no'   => 'nullable|string',
            'shop_name' => 'required|string',
            'shop_photo' => 'required|mimes:png,jpeg,jpg,JPEG',
            'area_id' => 'required|integer',
            'road_id' => 'required|integer',
            
            // 'address'   => 'required|string',
            'latitude'  => 'required|string',
            'longitude' => 'required|string',
            
            'state'   => 'required|string',
            'city'  => 'required|string',
            'pin_code' => 'required|string',
            'landmark'   => 'required|string',
            
        ]);

        $validator->after(function ($validator) use ($request) {

        });

        if ($validator->passes()) {

            $input = [];
            $data = [];
            $input = $request->all();

            unset($input['_token']);
            
            $road = Road::where('id',$input['road_id'])->first(['road_name']);
            $area = Area::where('id',$input['area_id'])->first(['area_name']);

            $data['name'] = $input['name'];
            $data['email'] = $input['email'];
            $data['mobile_no'] = $input['phone'];
            $data['status'] = $input['status'];
           
            if ($request->hasFile('shop_photo')) {
                $sample_image = $request->file('shop_photo');
                $imagename = $this->rand_string(14) . '.' . $sample_image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/customer');
                $sample_image->move($destinationPath, $imagename);
                $data['shop_photo'] = $imagename;
            }

            $data['address'] = $road->road_name.', '.$area->area_name.', '.$input['landmark'].', '.$input['state'].', '.$input['city'].', '.$input['pin_code'];
            $data['gst_no'] = $input['gst_no'];
            $data['pan_no'] = $input['pan_no'];
            $data['latitude'] = $input['latitude'];
            $data['longitude'] = $input['longitude'];
            
            $data['shop_name'] = $input['shop_name'];
            $data['area_id'] = $input['area_id'];
            $data['road_id'] = $input['road_id'];

            $data['state'] = $input['state'];
            $data['city'] = $input['city'];
            $data['pin_code'] = $input['pin_code'];
            $data['landmark'] = $input['landmark'];

            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');

            DB::table('customers')->insertGetId($data);

            return redirect()->route('customer-list')->with('success_msg', 'Customer created successfully.');
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
            'name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|unique:customers,email,'.$request->customer_id,
            'phone' => 'required|numeric|digits:10|unique:customers,mobile_no,'.$request->customer_id,
            'status' => 'required',
            'gst_no'   => 'nullable|string',
            'shop_name' => 'required|string',
            'shop_photo' => 'nullable|mimes:png,jpeg,jpg,JPEG',
            'area_id' => 'required|integer',
            'road_id' => 'required|integer',
            
            // 'address'   => 'required|string',
            'latitude'  => 'required|string',
            'longitude' => 'required|string',
            
            'state'   => 'required|string',
            'city'  => 'required|string',
            'pin_code' => 'required|string',
            'landmark'   => 'required|string',
            
        ]);

        $validator->after(function ($validator) use ($request) {

        });

        if ($validator->passes()) {
            
            $road = Road::where('id',$request->road_id)->first(['road_name']);
            $area = Area::where('id',$request->area_id)->first(['area_name']);

            $data = array(
                'name' => $request->name,
                'email' => $request->email,
                'mobile_no' => $request->phone,
                'status' => $request->status,
                'shop_name' => $request->shop_name,
                'area_id' => $request->area_id,
                'road_id' => $request->road_id,
                'gst_no' => $request->gst_no,
                'pan_no' => $request->pan_no,
                'address' =>$road->road_name.', '.$area->area_name.', '.$request->landmark.', '.$request->state.', '.$request->city.', '.$request->pin_code,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                
                'state' => $request->state,
                'city' => $request->city,
                'pin_code' => $request->pin_code,
                'landmark' => $request->landmark,
            );

            if ($request->hasFile('shop_photo')) {
                $sample_image = $request->file('shop_photo');
                $imagename = $this->rand_string(14) . '.' . $sample_image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/customer');
                $sample_image->move($destinationPath, $imagename);
                $data['shop_photo'] = $imagename;
            }
            
            Customer::where('id', $request->customer_id)->update($data);
            return redirect()->route('customer-list')->with('success_msg', 'Customer updated successfully.');
        } else {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error_msg', 'Something went wrong please check your input.');
        }
    }

}
