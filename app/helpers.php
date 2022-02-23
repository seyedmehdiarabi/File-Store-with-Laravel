<?php


function upload_file($request, $name, $directory, $pix = '')
{
    if ($request->hasFile($name)) {
        $file_name = $pix . time() . '.' . $request->file($name)->getClientOriginalExtension();
        if ($request->file($name)->move('files/' . $directory, $file_name)) {
            return $file_name;
        } else {
            return null;
        }
    } else {
        return null;
    }
}

function replace_number($number)
{
    $number = str_replace("0", '۰', $number);
    $number = str_replace("1", '۱', $number);
    $number = str_replace("2", '۲', $number);
    $number = str_replace("3", '۳', $number);
    $number = str_replace("4", '۴', $number);
    $number = str_replace("5", '۵', $number);
    $number = str_replace("6", '۶', $number);
    $number = str_replace("7", '۷', $number);
    $number = str_replace("8", '۸', $number);
    $number = str_replace("9", '۹', $number);

    return $number;
}

function replace_number2($number)
{
    $number = str_replace("۰", '0', $number);
    $number = str_replace("۱", '1', $number);
    $number = str_replace("۲", '2', $number);
    $number = str_replace("۳", '3', $number);
    $number = str_replace("۴", '4', $number);
    $number = str_replace("۵", '5', $number);
    $number = str_replace("۶", '6', $number);
    $number = str_replace("۷", '7', $number);
    $number = str_replace("۸", '8', $number);
    $number = str_replace("۹", '9', $number);

    return $number;
}

function inTrashed($req)
{
    if (array_key_exists('trashed', $req) && $req['trashed'] == 'true') {
        return true;
    } else {
        return false;
    }
}

function create_paginate_url($string, $text)
{
    if ($string == '?') {
        $string = $string . $text;
    } else {
        $string = $string . '&' . $text;
    }
    return $string;
}

function create_crud_route($route_param, $controller, $except = ['show'], $config = [])
{
    Route::resource($route_param, $controller, $config)->except($except);
    Route::post($route_param . '/remove_items', $controller . '@remove_items')->name($route_param . '.destroy');
    Route::post($route_param . '/restore_items', $controller . '@restore_items')->name($route_param . '.restore');
    Route::post($route_param . '/{id}', $controller . '@restore')->name($route_param . '.restore');
}


function remove_file($file_name, $directory)
{
    if (!empty($file_name) && file_exists('files/' . $directory . '/' . $file_name)) {
        unlink('files/' . $directory . '/' . $file_name);
    }
}

function getTimestamp($date, $type)
{
    $Jdf = new Jdf();
    $time = 0;
    $e = explode('/', $date);
    if (sizeof($e) == 3) {
        $y = $e[0];
        $m = $e[1];
        $d = $e[2];
        if ($type == 'first') {
            $time = $Jdf->jmktime(0, 0, 0, $m, $d, $y);
        } else {
            $time = $Jdf->jmktime(23, 59, 59, $m, $d, $y);
        }
    }
    return $time;
}

function getCartSellerData($sellers, $seller_id)
{
    foreach ($sellers as $key => $value) {
        if ($value->id == $seller_id) {
            return $value;
        }
    }
}


function CheckAccess($AccessList, $key1, $key2)
{
    $result = false;
    if ($AccessList) {
        $access = json_decode($AccessList->access);
        if (is_object($access)) {
            if (property_exists($access, $key1)) {
                if (is_array($access->$key1)) {
                    foreach ($access->$key1 as $k => $v) {
                        if ($v == $key2) {
                            $result = true;
                        }
                    }
                }
            }
        }
    }
    return $result;
}

function checkParentMenuAccess($accessList, $access)
{
    $result = false;
    if (Auth::user()->role == 'admin') {
        $result = true;
    } else {
        $e = explode('|', $access);
        if (sizeof($e) > 0 && is_object($accessList)) {
            foreach ($e as $key => $value) {
                if (!empty($value)) {
                    if (property_exists($accessList, $value)) {
                        $result = true;
                    }
                }
            }
        }
    }
    return $result;
}

function checkAddChildMuenAccess($accessList, $child)
{
    $result = false;
    if (Auth::user()->role == 'admin') {
        $result = true;
    } else {
        $property = $child['access'];
        if (is_object($accessList)) {
            if (property_exists($accessList, $property)) {
                if (is_array($accessList->$property)) {
                    if (!array_key_exists('accessValue', $child)) {
                        $result = true;
                    } else {
                        foreach ($accessList->$property as $key => $value) {
                            if ($value == $child['accessValue']) {
                                $result = true;
                            }
                        }
                    }
                }
            }
        }
    }
    return $result;
}


function add_panel_menu($array, $position = "default")
{
    if (Request::isMethod('get')) {
        $menu = Config::get('app.panel_menu', []);
        if ($position === "default") {
            $menu[] = $array;
        } else {
            if (!array_key_exists($position, $menu)) {
                $menu[$position] = $array;
            } else {
                $new_menu = [];
                $new_menu[$position] = $array;
                foreach ($menu as $key => $value) {
                    if ($key < $position) {
                        $new_menu[$key] = $value;
                    } else {
                        $new_menu[($key + 1)] = $value;
                    }
                }

                $menu = $new_menu;
            }
        }
        Config::set('app.panel_menu', $menu);
    }
}

function add_panel_child_menu($menu)
{
    if (is_array($menu)) {
        $panel_menu = Config::get('app.panel_menu', []);
        if (array_key_exists('parent_menu', $menu)) {
            foreach ($panel_menu as $key => $value) {
                if (array_key_exists('name', $value)) {
                    if ($value['name'] == $menu['parent_menu']) {
                        if (array_key_exists('child', $panel_menu[$key])) {
                            $panel_menu[$key]['child'][] = $menu;
                        } else {
                            $panel_menu[$key]['child'][0] = $menu;
                        }
                    }
                }
            }
        }
        Config::set('app.panel_menu', $panel_menu);
    }

}

function run_action($actionName, $args, $return = false, $withOutKey = false)
{
    $modulesMainClass = Config::get('app.modulesMainClass', []);
    $i = 0;
    $res = [];
    foreach ($modulesMainClass as $key => $object) {
        if (method_exists($object, $actionName)) {
            $result = call_user_func_array(array($object, $actionName), $args);
            if ($withOutKey && $result) {
                $res = $res + $result;
            } elseif ($result) {
                $res[$i] = $result;
                $i++;
            }
        }
    }
    if ($return) {

        return $res;
    }
}


function getRouteName()
{
    $routeName = Request::route() ? Request::route()->getName() : null;
    $routeName = str_replace('.', '_', $routeName);
    return $routeName;
}


function CView($path, $args = [])
{
    $args = CompleteData('before_show_view', $args);
    if (function_exists('get_widget_data')) {
        $widgetData = get_widget_data($path, $args);
        $args['design_config'] = $widgetData['configs'];
        $args = array_merge($args, $widgetData['args']);
    }
    new \Modules\visitStatistics\Models\RouteStatistics();
    if (defined('layout')) {
        run_action('set_layout_data', [layout]);
    }
    $view = view($path, $args);
    if (request()->has('request-type') && request()->get('request-type') == "axios") {
        $view = $view->renderSections();
        $view['js_tags'] = registerVueFile('', 'vue', config('cms.develop'));
    }
    return $view;
}

function CompleteData($actionName, $data, $array = false)
{
    $modulesMainClass = Config::get('app.modulesMainClass', []);
    foreach ($modulesMainClass as $key => $object) {
        if (method_exists($object, $actionName)) {
            $param_arr = $array === true ? $data : [$data];
            $result = call_user_func_array(array($object, $actionName), $param_arr);
            if ($result) {
                $data = $result;
            }
        }
    }
    return $data;
}


function get_calendar($created_at, $time = false)
{
    $jdf = new \App\Lib\Jdf();
    $e = explode(' ', $created_at);
    $date = explode('-', $e[0]);
    if ($time) {
        $time = $e[1] . ' -- ';
    }
    return e(replace_number($time . $jdf->gregorian_to_jalali($date[0], $date[1], $date[2], '/')));
}

function position_view($position)
{
    $result = run_action($position, [], true);
    $views = [];
    foreach ($result as $array) {
        foreach ($array as $key => $value) {
            if (array_key_exists($value['index'], $views)) {
                $views[] = $value;
            } else {
                $views[$value['index']] = $value;
            }
        }
    }
    ksort($views);
    return $views;
}

function registerVueFile($theme, $render = 'ssr', $type = 'develop')
{
    $files = [];
    $actionName = 'registerComponent';
    $type = config('cms.develop');
    $modulesMainClass = Config::get('app.modulesMainClass', []);
    $index = 0;
    foreach ($modulesMainClass as $key => $object) {
        if (method_exists($object, $actionName)) {
            $result = call_user_func_array(array($object, $actionName), [$theme, $type]);
            if (is_array($result)) {
                $files[$index] = $result;
                $index++;
            }
        }
    }
    if ($render == 'ssr') {
        foreach ($files as $file) {
            ?>
            <script src="<?= asset($file['path']) ?>?" type="text/javascript"></script>
            <?php
        }
        $vue_path = $type ? 'js/vue-develop.js' : 'js/vue.js';
        ?>
        <script src="<?= asset($vue_path) ?>?" type="text/javascript"></script>
        <script src="<?= asset('js/widgets.js?id='.config('cms.widgetId')) ?>?" type="text/javascript"></script>
        <script> loadComponent(<?= json_encode($files) ?>); </script>
        <script>
            setTimeout(function () {
                const app = new Vue({
                    el: '#app',
                    store: store,
                    vuetify: new Vuetify({rtl: true})
                });
                window.vm = app;
            }, 5);
        </script>
        <?php
    } else {
        return $files;
    }
}

function add_css_file($theme = 'all')
{
    $files = [];
    $actionName = 'registerCssFile';
    $modulesMainClass = Config::get('app.modulesMainClass', []);
    foreach ($modulesMainClass as $key => $object) {
        if (method_exists($object, $actionName)) {
            $result = call_user_func_array(array($object, $actionName), [$theme]);
            if (is_array($result)) {
                $files = $files + $result;
            }
        }
    }
    ?>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="<?= asset('css/vuetify.min.css') ?>" rel="stylesheet">
    <?php
    if (sizeof($files)) {
        foreach ($files as $file) {
            echo '<link rel="stylesheet" href="' . $file . '">';
        }
    }
}

function vue_component_detail($module)
{
    $moduleDev = 'modules/' . $module . '/component.js';
    $modulePro = 'modules/' . $module . '/min-component.js';
    $path = config('cms.develop') == false ? $modulePro : $moduleDev;
    $detail = [
        'code1' => $module,
        'path' => $path
    ];
    if (config('cms.develop')) {
        $detail['file'] = './modules/' . $module . '/resources/js/components.js';
    }
    return $detail;
}


