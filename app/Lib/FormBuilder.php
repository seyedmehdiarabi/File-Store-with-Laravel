<?php


namespace App\Lib;

use Form;
use Config;
use Request;
use Session;

class FormBuilder
{
    protected $routeName = null;

    protected $otherField = [];

    public static $date_input = [];

    protected $defined_vars;

    protected $data = null;

    protected $formId;

    public function __construct($arg, $type = 'create', $data = [], $defined_vars = [])
    {
        $this->defined_vars = $defined_vars;
        $this->formId = 'form_' . rand(1, 100);
        $method = array_key_exists('method', $arg) ? $arg['method'] : 'post';
        $cssClass = array_key_exists('class', $arg) ? $arg['class'] : '';
        $this->data = $data;
        if ($type == 'create') {
            if (array_key_exists('query_string', $arg)) {
                $arg['url'] = $arg['url'] . $arg['query_string'];
                unset($arg['query_string']);
            }
        } else {
            $arg['url'] = $arg['url'] . '/' . $data->id;
            if (array_key_exists('query_string', $arg)) {
                $arg['url'] = $arg['url'] . $arg['query_string'];
                unset($arg['query_string']);
            }

        }
        $request_url = url($arg['url']);
        echo "<form-component
                form_id='$this->formId'
                method='$method'
                request_url='$request_url'
                css_class='$cssClass'
                type='$type'
           >";
        $this->routeName = Request::route() ? Request::route()->getName() : null;
        $this->getOtherField();
    }

    public function textInput($name, $label, $args, $value = null)
    {
        $this->checkHasField('before_' . $name);
        if ($value == null) {
            $value = ($this->data != null) ? $this->data[$name] : '';
        }
        if (array_key_exists('type', $args) && $args['type'] == 'password') {
            $value = '';
        }
        $args = json_encode($args);
        echo "<text-input name='$name' label='$label' value='$value' :args='$args'></text-input>";
        $this->checkHasField('after_' . $name);
    }

    public function fileInput($name, $label, $args = [],$validation='')
    {
        $this->checkHasField('before_' . $name);
        $args = json_encode($args);
        echo "<file-input label='$label' name='$name' :args='$args' validation='$validation'></file-input>";
        $this->checkHasField('after_' . $name);
    }

    public function editor($name, $args, $value = null)
    {
        $this->checkHasField('before_' . $name);
        if ($value == null) {
            $value = ($this->data != null) ? $this->data[$name] : '';
        }
        $args = json_encode($args);
        echo "<text-editor name='$name' value='$value' :args='$args'></text-editor>";
        $this->checkHasField('after_' . $name);
    }

    public function select($list, $name, $label, $args, $value = null)
    {
        $this->checkHasField('before_' . $name);
        $args = json_encode($args);
        $list = json_encode($list);
        if ($value == null) {
            $value = ($this->data != null) ? $this->data[$name] : '';
        }
        echo "<como-box label='$label' name='$name' :args='$args' :list='$list' value='$value'></como-box>";
        $this->checkHasField('after_' . $name);
    }

    public function checkbox($name, $label, $value = null, $args = [])
    {
        $this->checkHasField('before_' . $name);
        if ($value == null) {
            $value = ($this->data != null) ? $this->data[$name] : '';
        }
        $args = json_encode($args);
        echo "<check-box label='$label' name='$name' value='$value' :args='$args'></check-box>";
        $this->checkHasField('after_' . $name);
    }

    public function numberInput($name, $label = null, $args = [], $value = null)
    {
        $this->checkHasField('before_' . $name);
        $args = json_encode($args);
        if ($value === null) {
            $value = ($this->data != null) ? $this->data[$name] : 0;
        }
        echo "<text-input name='$name' label='$label' value='$value' :args='$args' input_type='number'></text-input>";
        $this->checkHasField('after_' . $name);
    }

    public function close()
    {
        echo '</form-component>';
    }

    public function btn($label, $type = 'create')
    {
        $form = $this->formId;
        echo "<form-btn text='$label' type='$type' form_id='$form'></form-btn>";
    }

    public function textarea($name, $label, $args, $value = null)
    {
        $this->checkHasField('before_' . $name);
        $args = json_encode($args);
        if ($value == null) {
            $value = ($this->data != null) ? $this->data[$name] : '';
        }
        echo "<textarea-field name='$name' label='$label' value='$value' :args='$args'></textarea-field>";
        $this->checkHasField('after_' . $name);
    }

    protected function getOtherField()
    {
        if ($this->routeName) {
            $routeName = str_replace('.', '_', $this->routeName);
            $action = $routeName . '_form';
            $result = run_action($action, [], true);
            if (is_array($result)) {
                $this->otherField = $this->otherField + $result;
            }
        }
    }

    protected function checkHasField($event)
    {
        $views = [];
        foreach ($this->otherField as $fields) {
            if (is_array($fields)) {
                foreach ($fields as $key => $value) {
                    if ($key == $event) {
                        if (is_array($value)) {
                            if (array_key_exists('index', $value)) {
                                if (array_key_exists($value['index'], $views)) {
                                    $views[] = $views[$value['index']];
                                }
                                $views[$value['index']] = view($value['path'], ['form' => $this] + $value['args'] + $this->defined_vars);
                            } else {
                                $views[] = view($value['path'], ['form' => $this] + $value['args'] + $this->defined_vars);
                            }
                        } else {
                            $views[] = view($value, ['form' => $this] + $this->defined_vars);
                        }
                    }
                }
            }
        }
        if (sizeof($views) > 0) {
            ksort($views);
            foreach ($views as $view) {
                echo $view;
            }
        }
    }

    public function fieldLocation($action)
    {
        $result = run_action($action, [], true);
        if (is_array($result)) {
            foreach ($result as $path) {
                echo view($path, ['form' => $this]);
            }
        }
    }

    public function colorInput($name, $label, $args, $value = null)
    {
        $this->checkHasField('before_' . $name);
        if ($value == null) {
            $value = ($this->data != null) ? $this->data[$name] : '';
        }
        $args = json_encode($args);
        echo "<color-input label='$label' name='$name' value='$value' :args='$args'></color-input>";
        $this->checkHasField('after_' . $name);
    }

    public function imageInput($name, $label, $args = [], $default = '')
    {
        $this->checkHasField('before_' . $name);
        $args = json_encode($args);
        echo "<image-select-box label='$label' name='$name' default='$default' :args='$args'></image-select-box>";
        $this->checkHasField('after_' . $name);
    }

    public function dateInput($name, $label, $args = [], $value = null)
    {
        $this->checkHasField('before_' . $name);
        $args = json_encode($args);
        if ($value == null) {
            $value = ($this->data != null) ? $this->data[$name] : '';
        }
       // echo "<date-input label='$label' name='$name' value='$value' :arg='$args'></date-input>";
        echo '<date-picker label="'.$label.'" name="'.$name.'" value="'.$value.'" class="persian_number"></date-picker>';
        $this->checkHasField('after_' . $name);
    }

    public function tagBox($name, $label, $args = [], $value = null)
    {
        $this->checkHasField('before_' . $name);
        $args = json_encode($args);
        if ($value == null) {
            $value = ($this->data != null) ? $this->data[$name] : '';
        }
        echo "<tag-box label='$label' name='$name' value='$value' :args='$args'></tag-box>";
        $this->checkHasField('after_' . $name);
    }
}
