<?php

namespace App\Modules\Report_data\Controllers;

use Models\collection_data as collection_dataModel;
use Lib\core\RESTful;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use File;
use DateInterval;
use DateTime;
use DatePeriod;

class Report_data extends RESTful {

    public function __construct() {
        $model = new collection_dataModel;
        $controller_name = 'Report_data';
        
        $this->table_name = 'collection_data';
        parent::__construct($model, $controller_name);
    }

    public function index()
    {
        $with = [];
        return view($this->controller_name . '::index', $with);
    }

    public function getData()
    {
        $model = request()->model ?? null;
        if($model == 1){
            // NIK
            return $this->getListByNIK();
        }elseif($model == 2){
            // Kecamatan
            return $this->getListByDistrict();
        }elseif($model == 3){
            // Kelurahan
            return $this->getListBySubdistrict();
        // }elseif($model == 4){
            // TPS
        }else{
            return $this->getListByCoordinator();
        }
    }

    public function getListByNIK()
    {
        $start_date = request()->start_date;
        $end_date = request()->end_date;

        $datas = $this->model->select(['*']);
        if($start_date != ''){
            $datas->whereDate('created_at','>=',$start_date);
        }
        if($end_date != ''){
            $datas->whereDate('created_at','<=',$end_date);
        }

        $this->filter($datas, request(), 'collection_data');
        $max_row = request()->input('max_row') ?? 50;
        $datas = $datas->paginate($max_row);
        $datas->chunk(100);

        $day = date('w');
        $start_date = new DateTime($start_date ?? date('Y-m-d', strtotime('-'.($day-1).' days')));
        $end_date = new DateTime($end_date ?? date('Y-m-d', strtotime('+'.(7-$day).' days')));

        $collection_datas = \Models\collection_data::whereDate('created_at','>=',$start_date)
            ->whereDate('created_at','<=',$end_date)
            ->select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(*) as total'))
            ->groupBy('date');
        $collection_datas = $collection_datas->get()->pluck('total','date')->all();

        $interval = DateInterval::createFromDateString('1 day');
        $end_week = new DateTime(date('Y-m-d', strtotime('+'.(8-$day).' days')));
        $date_range = new DatePeriod($start_date, $interval, $end_week);

        $dates = [];
        $dataByDates = [];
        foreach ($date_range as $dt) {
            $date = $dt->format('Y-m-d');
            $dates[] = dateToIndo($date);
            $dataByDates[] = $collection_datas[$date] ?? 0;
        }
        
        $with['datas'] = $datas;
        $with['model'] = request()->model;
        $with['start_date'] = request()->start_date;
        $with['end_date'] = request()->end_date;
        $with['param'] = request()->all();
        $with['dates'] = $dates;
        $with['dataByDates'] = $dataByDates;
        return view($this->controller_name . '::list', $with);
    }

    public function getListByDistrict()
    {
        $start_date = request()->start_date;
        $end_date = request()->end_date;

        $datas = \Models\district::select(['*']);

        $this->filter($datas, request(), 'district');
        $max_row = request()->input('max_row') ?? 50;
        $datas = $datas->paginate($max_row);
        $datas->chunk(100);

        $district_ids = $datas->pluck('id')->all();
        $districts = $datas->pluck('name')->all();

        $collection_datas = $this->model->select(['*'])
            ->whereIn('district_id',$district_ids);
        if($start_date != ''){
            $collection_datas->whereDate('created_at','>=',$start_date);
        }
        if($end_date != ''){
            $collection_datas->whereDate('created_at','<=',$end_date);
        }

        $collection_datas = $collection_datas->get();

        $with['model'] = request()->model;
        $with['start_date'] = request()->start_date;
        $with['end_date'] = request()->end_date;
        $with['datas'] = $datas;
        $with['districts'] = $districts;
        $with['param'] = request()->all();
        $with['collection_datas'] = $collection_datas;
        return view($this->controller_name . '::listByDistrict', $with);
    }

    public function getListBySubdistrict()
    {
        $start_date = request()->start_date;
        $end_date = request()->end_date;
        $subdistrict_ids = is_array(request()->subdistrict_ids)? request()->subdistrict_ids : [];

        $datas = \Models\subdistrict::select(['*']);
        if(count($subdistrict_ids) > 0){
            $datas->whereIn('id',$subdistrict_ids);
        }

        $this->filter($datas, request(), 'subdistrict');
        $max_row = request()->input('max_row') ?? 50;
        $datas = $datas->paginate($max_row);
        $datas->chunk(100);

        $subdistrict_ids = $datas->pluck('id')->all();
        $subdistricts = $datas->pluck('name')->all();

        $collection_datas = $this->model->select(['*'])
            ->whereIn('subdistrict_id',$subdistrict_ids);
        if($start_date != ''){
            $collection_datas->whereDate('created_at','>=',$start_date);
        }
        if($end_date != ''){
            $collection_datas->whereDate('created_at','<=',$end_date);
        }

        $collection_datas = $collection_datas->get();

        $with['subdistrict_ids'] = is_array(request()->subdistrict_ids)? request()->subdistrict_ids : [];
        $with['model'] = request()->model;
        $with['start_date'] = request()->start_date;
        $with['end_date'] = request()->end_date;
        $with['datas'] = $datas;
        $with['subdistricts'] = $subdistricts;
        $with['param'] = request()->all();
        $with['collection_datas'] = $collection_datas;
        return view($this->controller_name . '::listBySubdistrict', $with);
    }

    public function getListByCoordinator()
    {
        $start_date = request()->start_date;
        $end_date = request()->end_date;

        $datas = \app\Models\User::select(['*'])
                ->where('groups_id',2);

        $this->filter($datas, request(), 'subdistrict');
        $max_row = request()->input('max_row') ?? 50;
        $datas = $datas->paginate($max_row);
        $datas->chunk(100);

        $coordinator_ids = $datas->pluck('id')->all();
        $coordinators = $datas->pluck('name')->all();

        $collection_datas = $this->model->select(['*'])
            ->whereIn('coordinator_id',$coordinator_ids);
        if($start_date != ''){
            $collection_datas->whereDate('created_at','>=',$start_date);
        }
        if($end_date != ''){
            $collection_datas->whereDate('created_at','<=',$end_date);
        }

        $collection_datas = $collection_datas->get();

        $with['model'] = request()->model;
        $with['start_date'] = request()->start_date;
        $with['end_date'] = request()->end_date;
        $with['datas'] = $datas;
        $with['coordinators'] = $coordinators;
        $with['param'] = request()->all();
        $with['collection_datas'] = $collection_datas;
        return view($this->controller_name . '::listByCoordinator', $with);
    }

    public function customFilter($data, $newFilters)
    {
        foreach ($newFilters as $key => $value) {
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
