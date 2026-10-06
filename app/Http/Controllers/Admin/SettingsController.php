<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use DB;

class SettingsController extends Controller {

    public function index() {

        $data = array();
        $tab = 0;

        if (Session::has('tab')) {
            $data['tab'] = Session::get('tab');
            Session::forget('tab');
        } else {
            $data['tab'] = 0;
        }


        $modules = [];

        foreach (DB::table('settings')->orderBy('row_order','asc')->get() as $mod) {
            $modules[$mod->module][] = (object) array(
                        'slug' => $mod->slug,
                        'title' => $mod->title,
                        'description' => $mod->description,
                        'type' => $mod->type,
                        'default' => $mod->default,
                        'value' => $mod->value,
                        'options' => $mod->options,
                        'is_required' => $mod->is_required,
                        'is_gui' => $mod->is_gui,
                        'module' => $mod->module,
                        'order' => $mod->row_order,
            );
        }
        
        $data['modules'] = $modules;
        return view('admin.settings.index', $data);
    }

    public function store(Request $request) {
        $data = $request->all();
        $update_value = array();

        if(!empty($data['settings'])){
        
            foreach ($data['settings'] as $key => $val) {

                if (is_array($val)) {
                    $val = implode("|", $val);
                }

                
                $update_value[] = array('slug' => $key, 'value' => $val);

                $checkKeyIsFile = DB::table('settings')->select('type')->where('slug','=',$key)->first();
                
                if ($request->hasFile('settings') && $checkKeyIsFile->type == 'file') {
                    $sample_image = $request->file('settings');
                    
                    $sample_image = $sample_image[$key];
                    $imagename = $sample_image->getClientOriginalName();
                    
                    $destinationPath = public_path('uploads/setting');
                    
                    $sample_image->move($destinationPath, $imagename);

                    DB::table('settings')->where('slug', $key)
                            ->where('module', $data['save_module_settings'])
                            ->update(['value' => $imagename]);
                }else{

                    DB::table('settings')->where('slug', $key)
                    ->where('module', $data['save_module_settings'])
                    ->update(['value' => $val]);

                }

                
            }
        
            Session::put('tab', $data['tab']);
            return redirect()->route('settings')->with('success_msg', 'Settings updated successfully.');
        }else{
            return redirect()->back()->with('error_msg', 'Check Your Input !');
        }

    }

}