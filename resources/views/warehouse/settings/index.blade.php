@extends('warehouse.layouts.main')

@section('title', 'Site Settings')
@section('breadcrumb-item', 'Site Management')

@section('breadcrumb-item-active', 'Settings')

@section('css')

@endsection

@section('content')

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div id="basicwizard" class="form-wizard row justify-content-center">
                
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-3">
                            <ul class="nav nav-pills nav-justified">

                                <?php
                                    $selected_tab = $tab;
                                    foreach (array_keys($modules) as $index => $module_name) {
                                    $class = ($selected_tab == $index) ? 'active' : '';
                                ?>

                                    <li class="nav-item">
                                        <a href="#tab_s_<?php echo $index; ?>" data-bs-toggle="tab" data-toggle="tab" class="nav-link <?php echo $class; ?>">
                                            <i class="fa fa-cog"></i>
                                            <span class="d-none d-sm-inline"><?php echo $module_name ?></span>
                                        </a>
                                    </li>

                                <?php } ?>
                                
                                <!-- end nav item -->
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="tab-content">

                                
                                <?php
                                    $count = 0;
                                    foreach ($modules as $module_name => $module) {

                                        $class = ($selected_tab == $count) ? 'show active' : '';
                                ?>

                                    <div class="tab-pane <?php echo $class; ?> pt-3" id="tab_s_<?php echo $count; ?>">

                                        <!-- Profile Edit Form -->

                                        <form id="save_<?php echo $module_name ?>" action="{{Route('settings')}}" method="post" enctype="multipart/form-data">
                                            
                                            {{csrf_field()}}

                                            <?php
                                                $html = '';

                                                foreach ($module as $settings) {

                                                    $html .= "<div class=\"row mb-3\" id=\"$settings->slug\">";
                                                    $html .= '<label class="col-md-6 col-lg-6 col-form-label" for="' . $settings->slug . '">' . $settings->title . '</label>';

                                                    switch ($settings->type) {
                                                        case 'text':
                                                            $html .= "<div class=\"col-md-12 col-lg-12 type-{$settings->type}\">";
                                                            $html .= "<input type=\"text\" class=\"form-control\" id=\"{$settings->slug}\" name=\"settings[{$settings->slug}]\" value=\"{$settings->value}\">";
                                                            $html .= "</div>";
                                                            break;

                                                        case 'textarea':
                                                            $html .= "<div class=\"col-md-12 col-lg-12 type-{$settings->type}\">";
                                                            $html .= "<textarea rows = \"3\" class='form-control'  name = \"settings[{$settings->slug}]\" id = \"{$settings->slug}\">{$settings->value}</textarea>";
                                                            $html .= "</div>";
                                                            break;

                                                        case 'password':
                                                            $html .= "<div class=\"col-md-12 col-lg-12 type-{$settings->type}\">";
                                                            $html .= "<input type=\"password\" class=\"form-control\" id=\"{$settings->slug}\" name=\"settings[{$settings->slug}]\" value=\"{$settings->value}\">";
                                                            $html .= "</div>";
                                                            break;

                                                        case 'select':
                                                            $html .= "<div class=\"col-md-12 col-lg-12 type-{$settings->type}\">";
                                                            // $html .= form_dropdown($settings->slug, $settings->options, $settings->value, "id=\"{$settings->slug}\" class=\"sf chzn-select\"");
                                                            $html .= "</div>";
                                                            break;

                                                        case 'select-multiple':
                                                            $html .= "<div class=\"col-md-12 col-lg-12 type-{$settings->type}\">";
                                                            // $html .= form_dropdown($settings->slug, $settings->options, $settings->value, "id=\"{$settings->slug}\" class=\"sf chzn-select\" multiple=\"multiple\"");
                                                            $html .= "</div>";
                                                            break;

                                                        case 'radio':
                                                            $options = explode('|', $settings->options);
                                                            $html .= "<div class=\"col-md-12 col-lg-12 type-{$settings->type}\">";
                                                            foreach ($options as $row) {
                                                                $row_data = explode('=', $row);
                                                                $k2 = $row_data[0];
                                                                $v2 = $row_data[1];
                                                                $checked = $k2 == $settings->value ? "checked=checked\"" : "";
                                                                $html .= "<label class=\"inline\">";
                                                                $html .= "<input type=\"radio\" name=\"settings[$settings->slug]\" value=\"{$k2}\" {$checked}>&nbsp;{$v2}";
                                                                $html .= "</label>";
                                                            }
                                                            $html .= "</div>";
                                                            break;

                                                        case 'checkbox':
                                                            $html .= "<div class=\"col-md-12 col-lg-12 type-{$settings->type}\">";
                                                            $data_arr = explode('|', $settings->options);
                                                            foreach ($data_arr as $row) {
                                                                $option = explode('=', $row);
                                                                $k2 = $option[0];
                                                                $v2 = $option[1];
                                                                $n3 = $option[2];
                                                                $checked = ($v2 == $settings->value) ? "checked=\checked\"" : "";
                                                                $html .= "<label class=\"inline\">";

                                                                $html .= "<input type=\"checkbox\" name=\"settings[$settings->slug][]\" value=\"{$v2}\" {$checked}>&nbsp;{$n3}";
                                                                $html .= "</label>";
                                                            }
                                                            $html .= "</div>";
                                                            break;

                                                        case 'file':
                                                            $photo = $settings->value ? URL::asset('public/uploads/setting/' . $settings->value) : '#';
                                                            $html .= "<div class=\"col-md-12 col-lg-12 type-{$settings->type}\">";
                                                            $html .= "<input type=\"file\" class=\"form-control\" id=\"{$settings->slug}\" name=\"settings[{$settings->slug}]\" >";

                                                            $html .= "<br>";
                                                            $html .= "<div class='view-image'><div class='col-md-10'>";
                                                            $html .= "<a href=\"{$photo}\"  target='_blank'><button type='button' class='btn btn-primary'><i class='fa fa-eye'></i> View</button></a> ";
                                                            $html .= "</div></div>";
                                                            $html .= "</div>";
                                                            break;

                                                        default : $html .= "<div class=\"col-md-12 col-lg-12 type-{$settings->type}\">";
                                                            $html .= "<label class=\"inline\">";
                                                            $html .= "$settings->value";
                                                            $html .= "</label>";
                                                            $html .= "</div>";

                                                    }

                                                    $html .= '</div>';
                                                }

                                                echo $html;

                                            ?>

                                            <input type="hidden" value="<?php echo $module_name ?>" name="save_module_settings"/>
                                            <input type="hidden" value="<?php echo $count; ?>" name="tab"/>

                                            <?php
                                                if ($module_name !== 'System') {
                                            ?>

                                                    <div class="form-actions">
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                                                <a href="{{Route('admin-dashboard')}}" class="btn btn-danger">Cancel</a>
                                                            </div>
                                                        </div>
                                                    </div>

                                            <?php } ?>

                                        </form>

                                        <!-- End Profile Edit Form -->

                                    </div>

                                <?php
                                        $count++;
                                    }
                                ?> 


                            </div>
                        </div>
                    </div>
                    <!-- end tab content-->
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->

@endsection
