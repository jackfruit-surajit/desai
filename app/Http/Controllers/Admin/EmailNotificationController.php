<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;

use DB;

class EmailNotificationController extends Controller {

    public function index(Request $request) {
        return view('admin.emailNotification.index');
    }

    public function get_list() {
        $data = DB::table('email_content')->where('status','<>','3')->get();
        
        return Datatables::of($data)
                ->addColumn('action', function ($model) {
                            return '<a href="' . Route("emailNotification-edit", ["id" => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-primary"><i class="fa fa-pen-to-square"></i> Edit</span></a>';
                        })
                ->make(true);
    }

    public function get_edit($id = "") {
        $id = base64_decode($id);
        if ($id == "") {
            return redirect()->route('emailNotification');
        }
        $model = DB::table('email_content')->where('id',$id)->first();

        if (empty($model)) {
            return redirect()->route('emailNotification')->with('error_msg', 'Data Not found.');
        }
        return view('admin.emailNotification.edit', ['model' => $model]);
    }

    public function post_edit(Request $request, $id = "") {
        if ($id == "") {
            return redirect()->route('emailNotification');
        }

        $model = DB::table('email_content')->where('id',$id)->first();
        // print_r($model);exit;
        if (empty($model)) {
            return redirect()->route('emailNotification')->with('error_msg', 'Data Not found.');
        }
        $validator = Validator::make($request->all(), [
                    'about' => 'required',
                    'subject' => 'required',
                    'body' => 'required'
        ]);
        if ($validator->passes()) {
            $input = $request->all();
            unset($input['_token']);
            
            DB::table('email_content')->where('id',$id)->update($input);

            return redirect()->route('emailNotification-edit', ['id' => base64_encode($model->id)])->with('success_msg', 'Email Notification updated successfully.');
        }
        return redirect()->route('emailNotification-edit', ['id' => base64_encode($model->id)])->withErrors($validator)->withInput();
    }

}