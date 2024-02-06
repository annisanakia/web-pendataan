<?php

namespace Lib\core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use View;

class RESTful extends Controller
{

    protected $model;
    protected $controller_name;
    protected $lib_activity;
    protected $max_row = 50;
    protected $priv;
    protected $title = '';
    protected $actions = array();
    protected $middleware_except = '';
    protected $table_name = '';
    protected $filter_string = '';
    protected $enable_xls = false;
    protected $enable_pdf = false;
    protected $enable_xls_button = false;
    protected $enable_pdf_button = false;
    protected $enable_import = false;
    protected $add_param_to_custom_filters = [];
    protected $disabled_add = false;

    public function __construct($model, $controller_name)
    {
        $this->middleware('auth', ['except' => $this->middleware_except]);

        $this->model = $model;
        $this->controller_name = $controller_name;
        $this->priv['edit_priv'] = true;
        $this->priv['add_priv'] = true;
        $this->priv['delete_priv'] = true;
        $this->enable_xls = true;
        $this->enable_pdf = true;
        $this->lib_activity = new \Lib\activity();

        view::share('priv', $this->priv);
        view::share('title', $this->title);
        view::share('controller_name', strtolower($controller_name));
    }

    public function index()
    {
        $request = request();
        $with = $this->getList($request);

        if (request()->ajax()) {
            return view($this->controller_name . '::list', $with);
        }

        return view($this->controller_name . '::index', $with);
    }

    public function getList(Request $request, $start = '', $end = '')
    {
        $sort_field = request()->sort_field;
        $sort_type = request()->sort_type;
        $data = $this->model->select(['*']);

        $this->configSetting();

        $sort_type = request()->sort_type > 2? 0 : request()->sort_type;
        $table = $this->table_name != '' ? $this->table_name : strtolower($this->controller_name);
        $this->filter($data, $request, $table);
        $this->order($data, $request);
        if ($request->has('max_row')) {
            $this->setMaxRow($request->input('max_row'));
        }

        $this->filter_string = http_build_query($request->all());

        if ($this->priv['add_priv'] && !$this->disabled_add)
            $this->actions[] = array('name' => 'Tambah Data', 'url' => strtolower($this->controller_name) . '/create', 'class' => 'btn btn-primary', 'icon' => 'fa-solid fa-plus');
        
        $url_xls = '#';
        if ($this->enable_xls) {
            $url_xls = strtolower($this->controller_name) . '/getListAsXls?' . $this->filter_string;
        }

        $url_pdf = '#';
        if ($this->enable_pdf) {
            $url_pdf = strtolower($this->controller_name) . '/getListAsPdf?' . $this->filter_string;
        }

        if ($this->enable_pdf_button) {
            $this->actions[] = array('name' => '', 'url' => $url_pdf, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-danger', 'icon' => 'fa-solid fa-file-pdf');
        }

        if ($this->enable_xls_button) {
            $this->actions[] = array('name' => '', 'url' => $url_xls, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-success', 'icon' => 'fa-solid fa-file-excel');
        }

        if ($this->enable_import && !$this->disabled_add) {
            $this->actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/import', 'class' => 'btn btn-outline-primary', 'icon' => 'fa-solid fa-upload');
        }

        $this->beforeIndex($data);

        $data = $data->paginate($this->max_row);
        $data->chunk(100);

        if (method_exists($this, 'customParam')) {
            $with = $this->customParam();
        }

        $url_param = $request->all();
        unset($url_param['sort_field'],$url_param['sort_type']);
        
        $with['datas'] = $data;
        $with['param'] = $request->all();
        $with['url_param'] = $url_param;
        $with['actions'] = $this->actions;
        $with['sort_field'] = $sort_field;
        $with['sort_type'] = $sort_type;

        return $with;
    }

    public function configSetting()
    { }

    public function beforeIndex($data)
    { }

    public function filter($data, $request, $table)
    {
        if ($request->isMethod('post') || $request->isMethod('get')) {
            $schema = \DB::getDoctrineSchemaManager();
            $tables = $schema->listTableColumns($table);
            $filters = $this->getFilters($request);
            if ($filters) {
                $newFilters = [];
                foreach ($filters as $key => $value) {
                    if ($value != '') {
                        if ($this->add_param_to_custom_filters) {
                            if (is_array($this->add_param_to_custom_filters)) {
                                if (array_key_exists($key, $this->add_param_to_custom_filters)) {
                                    $newFilters[$key] = $value;
                                }
                            }
                        }
                        if (array_key_exists($key, $tables)) {
                            if ($tables[$key]->getType()->getName() == 'string' || $tables[$key]->getType()->getName() == 'text') {
                                $data->where($table.'.'.$key, 'LIKE', '%' . $value . '%');
                            } elseif ($tables[$key]->getType()->getName() == 'date' || $tables[$key]->getType()->getName() == 'time') {
                                if ($key == 'start' || $key == 'start_date') {
                                    $data->where($key, '>=', $value);
                                }
                                if ($key == 'end' || $key == 'end_date') {
                                    $data->where($table.'.'.$key, '<=', $value);
                                }
                                if ($key == 'date') {
                                    $data->whereDate($table.'.'.$key, $value);
                                }
                            } else {
                                $data->where($table.'.'.$key, '=', $value);
                            }
                        } else {
                            $newFilters[$key] = $value;
                        }
                    }
                }
                /** Jika Module extend dari restfull ingin menambahkan filter pencarian
                 *  maka tidak perlu membuat method dengan nama filter (istilahnya override function) tp buat method dengan nama customFilter di Module
                 *  sehingga filter yang sudah ada di restfull tidak perlu ditulis ulang, contoh penggunaannya bisa dilihat di Module Ng_academic_cost
                 *  sehingga di method index jika membutuhkan filter tetap ditambahkan $this->filter($data, $request, 'nama_tabel') tidak perlu bikin method filter lagi; 
                 */
                if (method_exists($this, 'customFilter')) {
                    $this->customFilter($data, $newFilters);
                }
            }
        }
    }

    public function order($data, $request)
    {
        if ($request->isMethod('post') || $request->isMethod('get')) {
            $sort_type = $request->input('sort_type') > 2? 0 : $request->input('sort_type');
            $order_field = orders()[$sort_type] ?? null;
            if ($request->input('sort_field') != '' && $order_field) {
                $data->orderBy($request->input('sort_field'), $order_field);
            } else {
                $data->orderBy('id', 'desc');
            }
        }
    }

    public function create()
    {   
        if ($this->disabled_add) {
            return Redirect::route(strtolower($this->controller_name) . '.index');
        }

        $action[] = array('name' => 'Batal', 'url' => strtolower($this->controller_name), 'class' => 'btn btn-secondary px-3 ms-md-1');
        $action[] = array('name' => 'Simpan', 'type' => 'submit', 'url' => '#', 'class' => 'btn btn-success px-3 ms-md-1');
        $this->setAction($action);

        $content['actions'] = $this->actions;
        $content['data'] = null;

        return view($this->controller_name . '::create', $content);
    }

    public function store()
    {
        $input = $this->getParams(request()->all());
        $validation = $this->model->validate($input);

        if ($validation->passes()) {
            $data = $this->model->create($input);

            $user_id = \Auth::user()->id ?? null;
            $table_name = $this->model->getTable() ?? null;
            $data_id = $data->id ?? null;
            $activity_after = json_encode($data);
            $this->lib_activity->addActivity($user_id, $table_name, $data_id, 'store', date('Y-m-d H:i:s'), $activity_after);

            return Redirect::route(strtolower($this->controller_name) . '.index');
        }
        return Redirect::route(strtolower($this->controller_name) . '.create')
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
    }

    public function edit($id)
    {
        $data = $this->model->find($id);

        if (is_null($data)) {
            return Redirect::route(strtolower($this->controller_name) . '.index');
        }

        $action[] = array('name' => 'Batal', 'url' => strtolower($this->controller_name), 'class' => 'btn btn-secondary px-3 ms-md-1');
        if ($this->priv['delete_priv'])
            $action[] = array('name' => 'Hapus', 'url' => strtolower($this->controller_name) . '/delete/' . $id, 'class' => 'btn btn-danger px-3 ms-md-1 delete', 'attr' => 'ng-click=confirm($event) data-name='.($data->name ?? $data->code));
        $action[] = array('name' => 'Simpan', 'type' => 'submit', 'url' => '#', 'class' => 'btn btn-success px-3 ms-md-1');

        $this->setAction($action);

        $content['data'] = $data;
        $content['actions'] = $this->actions;

        return View($this->controller_name . '::edit' , $content);
    }

    public function detail($id)
    {
        $data = $this->model->find($id);
        if (is_null($data)) {
            return Redirect::route(strtolower($this->controller_name) . '.index');
        }

        $action[] = array('name' => 'Batal', 'url' => strtolower($this->controller_name), 'class' => 'btn btn-click btn-grey responsive');
        if ($this->priv['delete_priv'])
            $action[] = array('name' => 'Hapus', 'url' => strtolower($this->controller_name) . '/delete/' . $id, 'class' => 'btn btn-click btn-red responsive', 'attr' => 'ng-click=confirm($event)');
        $this->setAction($action);
        
        $content['data'] = $data;
        $content['actions'] = $this->actions;

        return View($this->controller_name . '::detail' , $content);
    }

    public function update($id)
    {
        $input = $this->getParams(request()->all());
        $validation = $this->model->validate($input);

        if ($validation->passes()) {
            $data = $this->model->find($id);
            $activity_before = json_encode($data);

            $data->update($input);

            $user_id = \Auth::user()->id ?? null;
            $table_name = $this->model->getTable() ?? null;
            $data_id = $data->id ?? null;
            $activity_after = json_encode($data);
            $this->lib_activity->addActivity($user_id, $table_name, $data_id, 'update', date('Y-m-d H:i:s'), $activity_after, $activity_before);

            return Redirect::route(strtolower($this->controller_name) . '.index');
        }
        return Redirect::route(strtolower($this->controller_name) . '.edit', $id)
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
    }

    public function delete($id)
    {
        if ($this->priv['delete_priv']) {
            $data = $this->model->find($id);
            if($data){
                $data->delete();
            }

            $user_id = \Auth::user()->id ?? null;
            $table_name = $this->model->getTable() ?? null;
            $data_id = $data->id ?? null;
            $activity_before = json_encode($data);
            $this->lib_activity->addActivity($user_id, $table_name, $id, 'delete', date('Y-m-d H:i:s'), null, $activity_before);
        }
        if (!request()->ajax()) {
            return Redirect::route(strtolower($this->controller_name) . '.index');
        }
    }

    function setMaxRow($max)
    {
        $this->max_row = $max;
    }

    function setTitle($title)
    {
        $this->title = $title;
    }

    function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    function setAction($action = array())
    {
        $this->actions = $action;
    }

    function setExceptMiddleware($except)
    {
        $this->middleware_except = $except;
    }

    function setTableName($table)
    {
        $this->table_name = $table;
    }

    public function getFilters(Request $request)
    {
        $filters = [];
        if ($request->has('filter')) {
            foreach ($request->input('filter') as $key => $value) {
                if (!empty($value)) {
                    $filters[$key] = $value;
                }
            }
        }

        unset($filters['_token']);
        return $filters;
    }

    public function getParams($params)
    {
        /** cara pake method ini bisa dilihat di modul ng_coa */
        if (method_exists($this, 'customInput')) {
            return $this->customInput($params);
        } else {
            return $params;
        }
    }
}