<?php

namespace App\Modules\Home\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use DateInterval;
use DateTime;
use DatePeriod;

class Home extends Controller {

    protected $controller_name;
    protected $lib;

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['form','store']]);
        $this->controller_name = 'Home';

        view::share('controller_name', strtolower($this->controller_name));
        try {
            parent::getHost();
        } catch (\Exception $e) { }
    }

    public function index()
    {
        $groups_id = \Auth::user()->groups_id ?? null;
        $districts = \Models\district::all();
        $collection_datas = \Models\collection_data::all();

        if($groups_id != 2){
            $withTarget = $this->getDataTargetGraph();
            $withStatus = $this->getDataStatusGraph();
            $with = array_merge($withTarget, $withStatus);
            $withTargetToday = $this->getDataTargetGraph(date('Y-m-d'), date('Y-m-d'));
            $with['dataByToday'] = $withTargetToday['dataByDistrict'];
        }
        // dd($withTargetToday);

        $with['groups_id'] = $groups_id;
        $with['districts'] = $districts;
        $with['collection_datas'] = $collection_datas;
        return view($this->controller_name . '::index', $with);
    }

    public function getDataStatusGraph($district_id = null, $start_date = null, $end_date = null)
    {
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;
        $collection_datas = \Models\collection_data::select(['*']);
        if($district_id != null){
            $collection_datas->where('district_id',$district_id);
        }
        if($start_date != null){
            $collection_datas->whereDate('created_at','>=',$start_date);
        }
        if($end_date != null){
            $collection_datas->whereDate('created_at','<=',$end_date);
        }
        if($groups_id == 2){
            $collection_datas->where('coordinator_id',$user_id);
        }
        $collection_datas = $collection_datas->get();

        $status = [];
        $dataByStatus = [];
        foreach (status() as $key => $status_name) {
            $dataStatus = $collection_datas->where('status',$key)->count();
            $dataByStatus[] = $dataStatus;
            $status[] = $status_name;
        }
        foreach (status_share() as $key => $status_name) {
            $dataStatus = $collection_datas->where('status_share',$key)->count();
            $dataByStatus[] = $dataStatus;
            $status[] = $status_name;
        }

        $with['status'] = $status;
        $with['dataByStatus'] = $dataByStatus;
        return $with;
    }

    public function getDataTargetGraph($start_date = null, $end_date = null)
    {
        $collection_datas = \Models\collection_data::select(['*']);
        if($start_date != null){
            $collection_datas->whereDate('created_at','>=',$start_date);
        }
        if($end_date != null){
            $collection_datas->whereDate('created_at','<=',$end_date);
        }
        $collection_datas = $collection_datas->get();

        $districts = \Models\district::get();
        $district_names = $districts->pluck('name')->all();

        $dataByDistrict = [];
        $dataByTarget = [];
        foreach ($districts as $district) {
            $dataDistrict = $collection_datas->where('district_id',$district->id)->count();
            $dataTarget = $district->subdistrict->sum('target');
            $dataByDistrict[] = $dataDistrict;
            $dataByTarget[] = $dataTarget;
        }

        $with['district_names'] = $district_names;
        $with['dataByDistrict'] = $dataByDistrict;
        $with['dataByTarget'] = $dataByTarget;
        return $with;
    }

    public function getData()
    {
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;
        $district_id = request()->district_id;

        $day = date('N');
        $start_date = new DateTime(date('Y-m-d', strtotime('-'.($day-1).' days')));
        $end_date = new DateTime(date('Y-m-d', strtotime('+'.(7-$day).' days')));

        $dataByDay = $this->getDataGraph($district_id, $groups_id, $start_date, $end_date, $day);
        if($groups_id != 2){
            $with = $this->getDataCoorGraph($district_id, $groups_id, $start_date, $end_date);
        }else{
            $with = $this->getDataStatusGraph($district_id, $start_date, $end_date);
        }

        $collection_datas = \Models\collection_data::where('district_id',request()->district_id)
                ->whereDate('created_at',date('Y-m-d'));
        if($groups_id == 2){
            $collection_datas->where('coordinator_id',$user_id);
        }
        $this->filter($collection_datas, request(), 'collection_data');
        $max_row = request()->input('max_row') ?? 50;
        $collection_datas = $collection_datas->paginate($max_row);
        $collection_datas->chunk(100);

        $with['district_id'] = $district_id;
        $with['dataByDay'] = $dataByDay;
        $with['collection_datas'] = $collection_datas;
        $with['param'] = request()->all();
        return view($this->controller_name . '::getData', $with);
    }

    public function getDataGraph($district_id, $groups_id, $start_date, $end_date, $day)
    {
        $user_id = \Auth::user()->id ?? null;
        $collection_datas = \Models\collection_data::where('district_id',$district_id)
            ->whereDate('created_at','>=',$start_date)
            ->whereDate('created_at','<=',$end_date)
            ->select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(*) as total'))
            ->groupBy('date');
        if($groups_id == 2){
            $collection_datas->where('coordinator_id',$user_id);
        }
        $collection_datas = $collection_datas->get()->pluck('total','date')->all();

        $interval = DateInterval::createFromDateString('1 day');
        $end_week = new DateTime(date('Y-m-d', strtotime('+'.(8-$day).' days')));
        $date_range = new DatePeriod($start_date, $interval, $end_week);

        $dataByDay = [];
        foreach ($date_range as $dt) {
            $date = $dt->format('Y-m-d');
            // $day = $dt->format('w') == 0? '7' : $dt->format('w');
            $dataByDay[] = $collection_datas[$date] ?? 0;
        }
        return $dataByDay;
    }

    public function getDataCoorGraph($district_id, $groups_id, $start_date, $end_date)
    {
        $collection_datas = \Models\collection_data::where('district_id',$district_id)
            ->whereDate('created_at','>=',$start_date)
            ->whereDate('created_at','<=',$end_date)
            ->select('coordinator_id', \DB::raw('count(*) as total'))
            ->groupBy('coordinator_id');
        if($groups_id == 2){
            $collection_datas->where('coordinator_id',$user_id);
        }
        $collection_datas = $collection_datas->get()->pluck('total','coordinator_id')->all();

        $user_coordinators = \app\Models\User::where('groups_id',2)->get();
        $coordinators = $user_coordinators->pluck('id')->all();
        $coordinators[''] = '';

        $dataByCoor = [];
        foreach ($coordinators as $coordinator_id) {
            $dataByCoor[] = $collection_datas[$coordinator_id] ?? 0;
        }
        $coordinators = $user_coordinators->pluck('name')->all();
        $coordinators[] = 'none';

        $with['coordinators'] = $coordinators;
        $with['dataByCoor'] = $dataByCoor;
        return $with;
    }

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
                        if (array_key_exists($key, $tables)) {
                            if ($tables[$key]->getType()->getName() == 'string' || $tables[$key]->getType()->getName() == 'text') {
                                $data->where($key, 'LIKE', '%' . $value . '%');
                            } elseif ($tables[$key]->getType()->getName() == 'date' || $tables[$key]->getType()->getName() == 'time') {
                                if ($key == 'start' || $key == 'start_date') {
                                    $data->where($key, '>=', $value);
                                }
                                if ($key == 'end' || $key == 'end_date') {
                                    $data->where($key, '<=', $value);
                                }
                                if ($key == 'date') {
                                    $data->whereDate($key, $value);
                                }
                            } else {
                                $data->where($key, '=', $value);
                            }
                        } else {
                            if($key == 'subdistrict_name'){
                                $data->whereHas('subdistrict', function($builder) use($value){
                                    $builder->where('name', 'LIKE', '%' . $value . '%');
                                });
                            } elseif ($key == 'coordinator_name') {
                                $data->whereHas('coordinator', function($builder) use($value){
                                    $builder->where('name', 'LIKE', '%' . $value . '%');
                                });
                            }
                        }
                    }
                }
            }
        }
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

    public function form()
    {
        $action[] = array('name' => 'Simpan', 'type' => 'submit', 'url' => '#', 'class' => 'btn btn-success px-3 ms-md-1');

        $with['actions'] = $action;
        return view($this->controller_name . '::form', $with);
    }

    public function store()
    {
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;
        
        $input = request()->all();
        // $input['coordinator_id'] = request()->coordinator_id ?? ($groups_id == 2? $user_id : null);
        $model = new \Models\collection_data();
        $validation = $model->validate($input);

        if ($validation->passes()) {
            unset($input['photo']);
            if (request()->hasFile('photo')) {
                $input['photo'] = $this->store_image();
            }
            $data = $model->create($input);

            $table_name = $model->getTable() ?? null;
            $data_id = $data->id ?? null;
            $activity_after = json_encode($data);
            $libActivity = new \Lib\activity();
            $libActivity->addActivity($user_id, $table_name, $data_id, 'storeForm', date('Y-m-d H:i:s'), $activity_after);

            return Redirect::route('form')
                    ->with('success', 'Data berhasil disimpan!');
        }
        return Redirect::route('form')
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
    }

    public function store_image()
    {
        $url = null;
        if (request()->hasFile('photo')) {
            $image = request()->file('photo');
            $imagename = date('ymd') . time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('assets/file/ktp');

            if (!file_exists($destinationPath)) {
                File::makeDirectory($destinationPath, $mode = 0777, true, true);
            }

            $image->move($destinationPath, $imagename);
            $path = request()->getSchemeAndHttpHost() . '/assets/file/ktp/' . $imagename;
            $url = $path;
        }

        return $url;
    }
}
