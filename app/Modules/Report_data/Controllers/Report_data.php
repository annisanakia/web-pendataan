<?php

namespace App\Modules\Report_data\Controllers;

use Models\collection_data as collection_dataModel;
use Lib\core\RESTful;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
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
        $groups_id = \Auth::user()->groups_id;
        $subdistrict_ids = \Models\subdistrict::pluck('id')->all();
        if($groups_id == 2){
            $subdistrict_ids = session()->get('subdistrict_ids');
        }
        $with['subdistrict_ids'] = $subdistrict_ids;
        return view($this->controller_name . '::index', $with);
    }

    public function getData()
    {
        $groups_id = \Auth::user()->groups_id;
        $model = request()->model ?? null;
        if($model == 1){
            // Citizens
            $with = $this->getListByCitizens();
            return view($this->controller_name . '::listByCitizens', $with);
        }elseif($model == 2){
            // Kecamatan
            $with = $this->getListByDistrict();
            return view($this->controller_name . '::listByDistrict', $with);
        }elseif($model == 3){
            // Kelurahan
            $with = $this->getListBySubdistrict();
            return view($this->controller_name . '::listBySubdistrict', $with);
        }elseif($model == 4){
            // Koordinator
            $with = $this->getListByCoordinator();
            return view($this->controller_name . '::listByCoordinator', $with);
        }elseif($model == 5){
            // TPS
            if(request()->subdistrict_id == ''){
                return '
                    <div class="alert alert-info text-center mt-4">
                        Pilih opsi <b>kelurahan</b> terlebih dahulu untuk melihat laporan
                    </div>
                ';
            }
            $with = $this->getListByTPS();
            return view($this->controller_name . '::listByTPS', $with);
        }elseif($model == 6){
            // Jenis Kelamin
            $with = $this->getListByGender();
            return view($this->controller_name . '::listByGender', $with);
        }elseif($model == 7){
            // Pekerjaan
            $with = $this->getListByJob();
            return view($this->controller_name . '::listByJob', $with);
        }elseif($model == 8){
            // Umur
            $with = $this->getListByAge();
            return view($this->controller_name . '::listByAge', $with);
        }elseif($model == 9){
            // Relawan Data
            $with = $this->getListByVolunteer();
            return view($this->controller_name . '::listByVolunteer', $with);
        }else{
            // Laporan Simpatisan
            if(request()->subdistrict_id == ''){
                return '
                    <div class="alert alert-info text-center mt-4">
                        Pilih opsi <b>kelurahan</b> terlebih dahulu untuk melihat laporan
                    </div>
                ';
            }
            if(request()->coordinator_id == '' && $groups_id == 2){
                return '
                    <div class="alert alert-info text-center mt-4">
                        Pilih opsi <b>koordinator</b> terlebih dahulu untuk melihat laporan
                    </div>
                ';
            }
            if(request()->no_tps == ''){
                return '
                    <div class="alert alert-info text-center mt-4">
                        Pilih opsi <b>no TPS</b> terlebih dahulu untuk melihat laporan
                    </div>
                ';
            }
            $with = $this->getListBySimpatisan();
            return view($this->controller_name . '::listBySimpatisan', $with);
        }
    }

    public function getListByCitizens()
    {
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;

        $start_date = request()->start_date;
        $end_date = request()->end_date;
        $status = request()->status;
        $status_share = request()->status_share;
        $is_supervisor = request()->is_supervisor;
        $sort_field = request()->sort_field;
        $sort_type = request()->sort_type;

        $datas = $this->model->select(['collection_data.*','subdistrict.name as subdistrict_name','users.name as coordinator_name','volunteer_data.name as volunteer_name']);
        $datas->leftJoin('subdistrict', function ($join) {
            $join->on('subdistrict.id', '=', 'collection_data.subdistrict_id');
        })->leftJoin('users', function ($join) {
            $join->on('users.id', '=', 'collection_data.coordinator_id');
        })->leftJoin('volunteer_data', function ($join) {
            $join->on('volunteer_data.id', '=', 'collection_data.volunteer_data_id');
        });

        if($start_date != ''){
            $datas->whereDate('collection_data.created_at','>=',$start_date);
        }
        if($end_date != ''){
            $datas->whereDate('collection_data.created_at','<=',$end_date);
        }
        if($status != ''){
            $datas->where('collection_data.status',$status);
        }
        if($status_share != ''){
            $datas->where('collection_data.status_share',$status_share);
        }
        if(is_numeric($is_supervisor)){
            $datas->where('collection_data.is_supervisor',$is_supervisor);
        }
        if($groups_id == 2){
            $datas->where('coordinator_id',$user_id);
        }
        
        $sort_type = request()->sort_type > 2? 0 : request()->sort_type;
        $this->filter($datas, request(), 'collection_data');
        $this->order($datas, request());
        $max_row = request()->input('max_row') ?? 50;

        $datas = $datas->orderBy('id','desc')->paginate($max_row);
        $datas->chunk(100);

        $day = date('w');
        $start_date = new DateTime($start_date ?? date('Y-m-d', strtotime('-'.($day-1).' days')));
        $end_date = new DateTime($end_date ?? date('Y-m-d', strtotime('+'.(7-$day).' days')));

        $collection_datas = \Models\collection_data::select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(*) as total'));
        if($start_date != ''){
            $collection_datas->whereDate('collection_data.created_at','>=',$start_date);
        }
        if($end_date != ''){
            $collection_datas->whereDate('collection_data.created_at','<=',$end_date);
        }
        if($status != ''){
            $collection_datas->where('collection_data.status',$status);
        }
        if($status_share != ''){
            $collection_datas->where('collection_data.status_share',$status_share);
        }
        if(is_numeric($is_supervisor)){
            $collection_datas->where('collection_data.is_supervisor',$is_supervisor);
        }
        if($groups_id == 2){
            $collection_datas->where('coordinator_id',$user_id);
        }
        $collection_datas = $collection_datas->groupBy('date')->get()->pluck('total','date')->all();

        $interval = DateInterval::createFromDateString('1 day');
        // $end_week = new DateTime(date('Y-m-d', strtotime('+'.(8-$day).' days')));
        $end_week = request()->end_date ?? date('Y-m-d', strtotime('+'.(7-$day).' days'));
        $end_week = new DateTime(date('Y-m-d', strtotime($end_week . ' +1 day')));
        $date_range = new DatePeriod($start_date, $interval, $end_week);

        $dates = [];
        $dataByDates = [];
        foreach ($date_range as $dt) {
            $date = $dt->format('Y-m-d');
            $dates[] = dateToIndo($date);
            $dataByDates[] = $collection_datas[$date] ?? 0;
        }

        $this->filter_string = http_build_query(request()->all());
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListAsPdf?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-danger', 'icon' => 'fa-solid fa-file-pdf');
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListAsXls?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-success', 'icon' => 'fa-solid fa-file-excel');
        
        $with['datas'] = $datas;
        $with['model'] = request()->model;
        $with['start_date'] = request()->start_date;
        $with['end_date'] = request()->end_date;
        $with['param'] = request()->all();
        $with['dates'] = $dates;
        $with['dataByDates'] = $dataByDates;
        $with['actions'] = $actions;
        $with['sort_field'] = $sort_field;
        $with['sort_type'] = $sort_type;
        return $with;
    }

    public function getListByDistrict()
    {
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;

        $start_date = request()->start_date;
        $end_date = request()->end_date;
        $sort_field = request()->sort_field;
        $sort_type = request()->sort_type;

        $datas = $this->model->select('district.name as name', 'district.code as code', 'district_id', \DB::raw('count(*) as total'), \DB::raw('sum(case when status = 2 then 1 else 0 end) AS total_verif'))
            ->with('district')
            ->has('district')
            ->leftJoin('district', function ($join) {
                    $join->on('district.id', '=', 'collection_data.district_id');
                });;
        if($start_date != ''){
            $datas->whereDate('created_at','>=',$start_date);
        }
        if($end_date != ''){
            $datas->whereDate('created_at','<=',$end_date);
        }
        if($groups_id == 2){
            $datas->where('coordinator_id',$user_id);
        }
        
        $this->filter($datas, request(), 'district');
        $max_row = request()->input('max_row') ?? 50;
        
        $sort_type = $sort_type > 2? 0 : $sort_type;
        $order_field = orders()[$sort_type] ?? null;
        if(in_array($sort_field,['name','code','total','total_verif']) && $order_field){
            $datas->orderBy($sort_field, $order_field ?? 'desc');
        }
        // if(in_array($sort_field,['verif','share','data']) && $order_field){
        //     $datas->withCount(['collections_tps_'.$sort_field  => function($query) use ($subdistrict_id,$start_date,$end_date,$groups_id,$user_id){
        //         if($subdistrict_id != ''){
        //             $query->where('subdistrict_id',$subdistrict_id);
        //         }
        //         if($start_date != ''){
        //             $query->whereDate('created_at','>=',$start_date);
        //         }
        //         if($end_date != ''){
        //             $query->whereDate('created_at','<=',$end_date);
        //         }
        //         if($groups_id == 2){
        //             $query->where('coordinator_id',$user_id);
        //         }
        //     }])->orderBy('collections_tps_'.$sort_field.'_count', $order_field ?? 'desc');
        // }

        $datas = $datas->groupBy('district_id')
            ->orderBy('district_id','asc')->paginate($max_row);

        // $datas = \Models\district::select(['collection_data.district_id', \DB::raw('count(*) as total'), \DB::raw('sum(case when status = 2 then 1 else 0 end) AS total_verif')])
        //     ->leftJoin('collection_data', function ($join) {
        //         $join->on('collection_data.district_id', '=', 'district.id');
        //     });
        // $this->filter($datas, request(), 'district');
        // $max_row = request()->input('max_row') ?? 50;
        
        // $sort_type = $sort_type > 2? 0 : $sort_type;
        // $order_field = orders()[$sort_type] ?? null;
        // if(in_array($sort_field,['code','name','total','total_verif']) && $order_field){
        //     $datas->orderBy($sort_field, $order_field ?? 'desc');
        // }
        // if(in_array($sort_field,['verif','share','data']) && $order_field){
        //     $datas->withCount(['collections_'.$sort_field  => function($query) use ($start_date,$end_date,$groups_id,$user_id){
        //         if($start_date != ''){
        //             $query->whereDate('created_at','>=',$start_date);
        //         }
        //         if($end_date != ''){
        //             $query->whereDate('created_at','<=',$end_date);
        //         }
        //         if($groups_id == 2){
        //             $query->where('coordinator_id',$user_id);
        //         }
        //     }])->orderBy('collections_'.$sort_field.'_count', $order_field ?? 'desc');
        // }

        // $datas = $datas->groupBy('district_id')->orderBy('district.id','desc')->paginate($max_row);
        // $datas->chunk(100);

        // $district_ids = $datas->pluck('id')->all();
        // $districts = $datas->pluck('name')->all();

        // $collections_verif = \Models\collection_data::select('district_id', \DB::raw("count(id) as total"))
        //         ->whereIn('district_id',$district_ids);
        // $collections_data = \Models\collection_data::select('district_id', \DB::raw("count(id) as total"))
        //         ->whereIn('district_id',$district_ids);
        // if($start_date != ''){
        //     $collections_verif->whereDate('created_at','>=',$start_date);
        //     $collections_data->whereDate('created_at','>=',$start_date);
        // }
        // if($end_date != ''){
        //     $collections_verif->whereDate('created_at','<=',$end_date);
        //     $collections_data->whereDate('created_at','<=',$end_date);
        // }
        // if($groups_id == 2){
        //     $collections_verif->where('coordinator_id',$user_id);
        //     $collections_data->where('coordinator_id',$user_id);
        // }
        // $collections_verif = $collections_verif->where('status',2)
        //         ->groupBy('district_id')->get()
        //         ->pluck('total','district_id')->all();
        // $collections_data = $collections_data->groupBy('district_id')->get()
        //         ->pluck('total','district_id')->all();

        $this->filter_string = http_build_query(request()->all());
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListDistrictTPSAsPdf?' . $this->filter_string, 'attr' => 'target="_blank"', 'name'=>'Rekap TPS tiap Kecamatan','class' => 'btn btn-outline-danger', 'icon' => 'fa-solid fa-file-pdf');
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListDistrictAsPdf?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-danger', 'icon' => 'fa-solid fa-file-pdf');
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListDistrictAsXls?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-success', 'icon' => 'fa-solid fa-file-excel');

        $with['model'] = request()->model;
        $with['start_date'] = request()->start_date;
        $with['end_date'] = request()->end_date;
        $with['datas'] = $datas;
        // $with['districts'] = $districts;
        $with['param'] = request()->all();
        // $with['collections_verif'] = $collections_verif;
        // $with['collections_data'] = $collections_data;
        $with['actions'] = $actions;
        $with['sort_field'] = $sort_field;
        $with['sort_type'] = $sort_type;
        return $with;
    }

    public function getListBySubdistrict()
    {
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;

        $start_date = request()->start_date;
        $end_date = request()->end_date;
        $subdistrict_ids = is_array(request()->subdistrict_ids)? request()->subdistrict_ids : [];
        $sort_field = request()->sort_field;
        $sort_type = request()->sort_type;

        $datas = \Models\subdistrict::with(['collections_data'])
                ->select(['*']);
        if(count($subdistrict_ids) > 0){
            $datas->whereIn('id',$subdistrict_ids);
        }else{
            if($groups_id == 2){
                $subdistrict_ids = session()->get('subdistrict_ids');
                $datas->whereIn('id',$subdistrict_ids);
            }
        }

        $this->filter($datas, request(), 'subdistrict');
        $max_row = request()->input('max_row') ?? 50;
        
        $sort_type = $sort_type > 2? 0 : $sort_type;
        $order_field = orders()[$sort_type] ?? null;
        if(in_array($sort_field,['code','name']) && $order_field){
            $datas->orderBy($sort_field, $order_field ?? 'desc');
        }
        if(in_array($sort_field,['verif','share','data']) && $order_field){
            $datas->withCount(['collections_'.$sort_field  => function($query) use ($start_date,$end_date,$groups_id,$user_id){
                if($start_date != ''){
                    $query->whereDate('created_at','>=',$start_date);
                }
                if($end_date != ''){
                    $query->whereDate('created_at','<=',$end_date);
                }
                if($groups_id == 2){
                    $query->where('coordinator_id',$user_id);
                }
            }])->orderBy('collections_'.$sort_field.'_count', $order_field ?? 'desc');
        }
        
        $datas = $datas->orderBy('id','desc')->paginate($max_row);
        $datas->chunk(100);

        $subdistrict_ids = $datas->pluck('id')->all();
        $subdistricts = $datas->pluck('name')->all();

        $collections_verif = \Models\collection_data::select('subdistrict_id', \DB::raw("count(id) as total"))
                ->whereIn('subdistrict_id',$subdistrict_ids);
        $collections_data = \Models\collection_data::select('subdistrict_id', \DB::raw("count(id) as total"))
                ->whereIn('subdistrict_id',$subdistrict_ids);
        if($start_date != ''){
            $collections_verif->whereDate('created_at','>=',$start_date);
            $collections_data->whereDate('created_at','>=',$start_date);
        }
        if($end_date != ''){
            $collections_verif->whereDate('created_at','<=',$end_date);
            $collections_data->whereDate('created_at','<=',$end_date);
        }
        if($groups_id == 2){
            $collections_verif->where('coordinator_id',$user_id);
            $collections_data->where('coordinator_id',$user_id);
        }
        $collections_verif = $collections_verif->where('status',2)
                ->groupBy('subdistrict_id')->get()
                ->pluck('total','subdistrict_id')->all();
        $collections_data = $collections_data->groupBy('subdistrict_id')->get()
                ->pluck('total','subdistrict_id')->all();

        $this->filter_string = http_build_query(request()->all());
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListSubdistrictAsPdf?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-danger', 'icon' => 'fa-solid fa-file-pdf');
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListSubdistrictAsXls?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-success', 'icon' => 'fa-solid fa-file-excel');

        $with['subdistrict_ids'] = is_array(request()->subdistrict_ids)? request()->subdistrict_ids : [];
        $with['model'] = request()->model;
        $with['start_date'] = request()->start_date;
        $with['end_date'] = request()->end_date;
        $with['datas'] = $datas;
        $with['subdistricts'] = $subdistricts;
        $with['param'] = request()->all();
        $with['collections_verif'] = $collections_verif;
        $with['collections_data'] = $collections_data;
        $with['actions'] = $actions;
        $with['sort_field'] = $sort_field;
        $with['sort_type'] = $sort_type;
        return $with;
    }

    public function getListByCoordinator()
    {
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;

        $start_date = request()->start_date;
        $end_date = request()->end_date;
        $sort_field = request()->sort_field;
        $sort_type = request()->sort_type;

        $datas = \app\Models\User::with(['collections_data'])
                ->select(['*'])
                ->where('groups_id',2);
        if($groups_id == 2){
            $datas->where('id',$user_id);
        }

        $this->filter($datas, request(), 'subdistrict');
        $max_row = request()->input('max_row') ?? 50;

        $sort_type = $sort_type > 2? 0 : $sort_type;
        $order_field = orders()[$sort_type] ?? null;
        if(in_array($sort_field,['name']) && $order_field){
            $datas->orderBy($sort_field, $order_field ?? 'desc');
        }
        if(in_array($sort_field,['verif','share','data']) && $order_field){
            $datas->withCount(['collections_'.$sort_field  => function($query) use ($start_date,$end_date,$groups_id,$user_id){
                if($start_date != ''){
                    $query->whereDate('created_at','>=',$start_date);
                }
                if($end_date != ''){
                    $query->whereDate('created_at','<=',$end_date);
                }
                if($groups_id == 2){
                    $query->where('coordinator_id',$user_id);
                }
            }])->orderBy('collections_'.$sort_field.'_count', $order_field ?? 'desc');
        }

        $datas = $datas->orderBy('id','desc')->paginate($max_row);
        $datas->chunk(100);

        $coordinator_ids = $datas->pluck('id')->all();
        $coordinators = $datas->pluck('name')->all();
        
        $collections_verif = \Models\collection_data::select('coordinator_id', \DB::raw("count(id) as total"));
        $collections_data = \Models\collection_data::select('coordinator_id', \DB::raw("count(id) as total"));
        if($start_date != ''){
            $collections_verif->whereDate('created_at','>=',$start_date);
            $collections_data->whereDate('created_at','>=',$start_date);
        }
        if($end_date != ''){
            $collections_verif->whereDate('created_at','<=',$end_date);
            $collections_data->whereDate('created_at','<=',$end_date);
        }
        if($groups_id == 2){
            $collections_verif->where('coordinator_id',$user_id);
            $collections_data->where('coordinator_id',$user_id);
        }
        $collections_verif = $collections_verif->where('status',2)
                ->groupBy('coordinator_id')->get()
                ->pluck('total','coordinator_id')->all();
        $collections_data = $collections_data->groupBy('coordinator_id')->get()
                ->pluck('total','coordinator_id')->all();

        $this->filter_string = http_build_query(request()->all());
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListCoordinatorAsPdf?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-danger', 'icon' => 'fa-solid fa-file-pdf');
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListCoordinatorAsXls?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-success', 'icon' => 'fa-solid fa-file-excel');

        $with['model'] = request()->model;
        $with['start_date'] = request()->start_date;
        $with['end_date'] = request()->end_date;
        $with['datas'] = $datas;
        $with['coordinators'] = $coordinators;
        $with['param'] = request()->all();
        $with['actions'] = $actions;
        $with['sort_field'] = $sort_field;
        $with['sort_type'] = $sort_type;
        $with['collections_verif'] = $collections_verif;
        $with['collections_data'] = $collections_data;
        return $with;
    }

    public function getListByTPS()
    {
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;

        $start_date = request()->start_date;
        $end_date = request()->end_date;
        $subdistrict_id = request()->subdistrict_id;
        $sort_field = request()->sort_field;
        $sort_type = request()->sort_type;

        $datas = $this->model->select('no_tps', 'rw', 'rt', \DB::raw('count(*) as total'), \DB::raw('sum(case when status = 2 then 1 else 0 end) AS total_verif'));
        if($start_date != ''){
            $datas->whereDate('created_at','>=',$start_date);
        }
        if($end_date != ''){
            $datas->whereDate('created_at','<=',$end_date);
        }
        if($subdistrict_id != ''){
            $datas->where('subdistrict_id',$subdistrict_id);
        }
        if($groups_id == 2){
            $datas->where('coordinator_id',$user_id);
        }
        
        $this->filter($datas, request(), 'collection_data');
        $max_row = request()->input('max_row') ?? 50;
        
        $sort_type = $sort_type > 2? 0 : $sort_type;
        $order_field = orders()[$sort_type] ?? null;
        if(in_array($sort_field,['no_tps','rw','rt','total','total_verif']) && $order_field){
            $datas->orderBy($sort_field, $order_field ?? 'desc');
        }
        // if(in_array($sort_field,['verif','share','data']) && $order_field){
        //     $datas->withCount(['collections_tps_'.$sort_field  => function($query) use ($subdistrict_id,$start_date,$end_date,$groups_id,$user_id){
        //         if($subdistrict_id != ''){
        //             $query->where('subdistrict_id',$subdistrict_id);
        //         }
        //         if($start_date != ''){
        //             $query->whereDate('created_at','>=',$start_date);
        //         }
        //         if($end_date != ''){
        //             $query->whereDate('created_at','<=',$end_date);
        //         }
        //         if($groups_id == 2){
        //             $query->where('coordinator_id',$user_id);
        //         }
        //     }])->orderBy('collections_tps_'.$sort_field.'_count', $order_field ?? 'desc');
        // }

        $datas = $datas->groupBy('no_tps')
            ->groupBy('rw')
            ->groupBy('rt')
            ->orderBy('no_tps','asc')
            ->orderBy('rw','asc')
            ->orderBy('rt','asc')->paginate($max_row);
        $datas->chunk(100);

        $this->filter_string = http_build_query(request()->all());
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListTPSAsPdf?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-danger', 'icon' => 'fa-solid fa-file-pdf');
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListTPSAsXls?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-success', 'icon' => 'fa-solid fa-file-excel');
        
        $subdistrict = \Models\subdistrict::find($subdistrict_id);

        $with['datas'] = $datas;
        $with['model'] = request()->model;
        $with['start_date'] = request()->start_date;
        $with['end_date'] = request()->end_date;
        $with['subdistrict_id'] = request()->subdistrict_id;
        $with['param'] = request()->all();
        $with['actions'] = $actions;
        $with['sort_field'] = $sort_field;
        $with['sort_type'] = $sort_type;
        // $with['collections_verif'] = $collections_verif;
        // $with['collections_data'] = $collections_data;
        $with['subdistrict'] = $subdistrict;
        return $with;
    }

    public function getListByGender()
    {
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;

        $start_date = request()->start_date;
        $end_date = request()->end_date;
        $subdistrict_ids = is_array(request()->subdistrict_ids)? request()->subdistrict_ids : [];
        $sort_field = request()->sort_field;
        $sort_type = request()->sort_type;

        $genders = [
            [
                'name'=>'NA',
                'gender'=>null
            ],
            [
                'name'=>'Laki-laki',
                'gender'=>'L'
            ],
            [
                'name'=>'Perempuan',
                'gender'=>'P'
            ]
        ];

        $collection_datas = \Models\collection_data::select(['*']);
        if($start_date != ''){
            $collection_datas->whereDate('created_at','>=',$start_date);
        }
        if($end_date != ''){
            $collection_datas->whereDate('created_at','<=',$end_date);
        }
        if(count($subdistrict_ids) > 0){
            $collection_datas->whereIn('subdistrict_id',$subdistrict_ids);
        }
        if($groups_id == 2){
            $collection_datas->where('coordinator_id',$user_id);
        }
        $this->filter($collection_datas, request(), 'collection_data');
        $collection_datas = $collection_datas->get();

        foreach($genders as $key => $data){
            $collection_data = $collection_datas->where('gender',$data['gender']);
            $genders[$key]['verif'] = $collection_data->where('status',2)->count();
            $genders[$key]['share'] = $collection_data->where('status_share',2)->count();
            $genders[$key]['data'] = $collection_data->count();
        }

        $sort_type = $sort_type > 2? 0 : $sort_type;
        $order_field = orders()[$sort_type] ?? null;
        if($sort_field != '' && $order_field){
            $keys = array_column($genders, $sort_field);
            array_multisort($keys, ($order_field == 'asc'? SORT_ASC : SORT_DESC), $genders);
        }

        $this->filter_string = http_build_query(request()->all());
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListGenderAsPdf?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-danger', 'icon' => 'fa-solid fa-file-pdf');
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListGenderAsXls?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-success', 'icon' => 'fa-solid fa-file-excel');
        
        $with['subdistrict_ids'] = is_array(request()->subdistrict_ids)? request()->subdistrict_ids : [];
        $with['datas'] = collect($genders);
        $with['model'] = request()->model;
        $with['start_date'] = request()->start_date;
        $with['end_date'] = request()->end_date;
        $with['param'] = request()->all();
        $with['collection_datas'] = $collection_datas;
        $with['actions'] = $actions;
        $with['sort_field'] = $sort_field;
        $with['sort_type'] = $sort_type;
        return $with;
    }

    public function getListByJob()
    {
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;

        $start_date = request()->start_date;
        $end_date = request()->end_date;
        $subdistrict_ids = is_array(request()->subdistrict_ids)? request()->subdistrict_ids : [];
        $sort_field = request()->sort_field;
        $sort_type = request()->sort_type;

        $collection_datas = \Models\collection_data::select(['*']);
        if($start_date != ''){
            $collection_datas->whereDate('created_at','>=',$start_date);
        }
        if($end_date != ''){
            $collection_datas->whereDate('created_at','<=',$end_date);
        }
        if(count($subdistrict_ids) > 0){
            $collection_datas->whereIn('subdistrict_id',$subdistrict_ids);
        }
        if($groups_id == 2){
            $collection_datas->where('coordinator_id',$user_id);
        }
        $this->filter($collection_datas, request(), 'collection_data');
        $collection_datas = $collection_datas->get();

        $jobs = \Models\job_type::select(['*']);

        $sort_type = $sort_type > 2? 0 : $sort_type;
        $order_field = orders()[$sort_type] ?? null;
        if(in_array($sort_field,['verif','share','data']) && $order_field){
            $jobs->withCount(['collections_'.$sort_field  => function($query) use ($subdistrict_ids,$start_date,$end_date,$groups_id,$user_id){
                if(count($subdistrict_ids) > 0){
                    $query->whereIn('subdistrict_id',$subdistrict_ids);
                }
                if($start_date != ''){
                    $query->whereDate('created_at','>=',$start_date);
                }
                if($end_date != ''){
                    $query->whereDate('created_at','<=',$end_date);
                }
                if($groups_id == 2){
                    $query->where('coordinator_id',$user_id);
                }
            }]);
        }
        $jobs = $jobs->get();

        $collection_data = $collection_datas->where('job_type_id',null);
        $job_type = new \Models\job_type();
        $job_type->id = null;
        $job_type->code = 'NA';
        $job_type->name = 'NA';
        $job_type->collections_data_count = $collection_data->count();
        $job_type->collections_verif_count = $collection_data->where('status',2)->count();
        $job_type->collections_share_count = $collection_data->where('status_share',2)->count();

        $job_types[] = $job_type;
        $job_types = collect(array_merge($job_types,$jobs->all()));

        if(in_array($sort_field,['verif','share','data']) && $order_field){
            if(($order_field ?? 'desc') == 'asc'){
                $job_types = $job_types->sortBy('collections_'.$sort_field.'_count');
            }else{
                $job_types = $job_types->sortByDesc('collections_'.$sort_field.'_count');
            }
        }

        $this->filter_string = http_build_query(request()->all());
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListJobAsPdf?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-danger', 'icon' => 'fa-solid fa-file-pdf');
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListJobAsXls?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-success', 'icon' => 'fa-solid fa-file-excel');
        
        $with['subdistrict_ids'] = is_array(request()->subdistrict_ids)? request()->subdistrict_ids : [];
        $with['datas'] = $job_types;
        $with['model'] = request()->model;
        $with['start_date'] = request()->start_date;
        $with['end_date'] = request()->end_date;
        $with['param'] = request()->all();
        $with['collection_datas'] = $collection_datas;
        $with['actions'] = $actions;
        $with['sort_field'] = $sort_field;
        $with['sort_type'] = $sort_type;
        return $with;
    }

    public function getListByAge()
    {
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;

        $start_date = request()->start_date;
        $end_date = request()->end_date;
        $subdistrict_ids = is_array(request()->subdistrict_ids)? request()->subdistrict_ids : [];
        $sort_field = request()->sort_field;
        $sort_type = request()->sort_type;

        $ages = [
            [
                'name'=>'NA',
                'age_start'=>null,
                'age_end'=>null
            ],
            [
                'name'=>'17 - 30',
                'age_start'=>17,
                'age_end'=>30
            ],
            [
                'name'=>'31 - 45',
                'age_start'=>31,
                'age_end'=>45
            ],
            [
                'name'=>'46 - seterusnya',
                'age_start'=>46,
                'age_end'=>200
            ]
        ];

        $collection_datas = \Models\collection_data::select(['*', \DB::raw('TIMESTAMPDIFF (YEAR, dob , CURDATE()) AS age')]);
        if($start_date != ''){
            $collection_datas->whereDate('created_at','>=',$start_date);
        }
        if($end_date != ''){
            $collection_datas->whereDate('created_at','<=',$end_date);
        }
        if(count($subdistrict_ids) > 0){
            $collection_datas->whereIn('subdistrict_id',$subdistrict_ids);
        }
        if($groups_id == 2){
            $collection_datas->where('coordinator_id',$user_id);
        }
        $this->filter($collection_datas, request(), 'collection_data');
        $collection_datas = $collection_datas->orderBy('age','desc')->get();

        foreach($ages as $key => $data){
            $collection_data = $collection_datas->where('age','>=',$data['age_start'] ?? null)
                            ->where('age','<=',$data['age_end'] ?? null);
            $ages[$key]['verif'] = $collection_data->where('status',2)->count();
            $ages[$key]['share'] = $collection_data->where('status_share',2)->count();
            $ages[$key]['data'] = $collection_data->count();
        }

        $sort_type = $sort_type > 2? 0 : $sort_type;
        $order_field = orders()[$sort_type] ?? null;
        if($sort_field != '' && $order_field){
            $keys = array_column($ages, $sort_field);
            array_multisort($keys, ($order_field == 'asc'? SORT_ASC : SORT_DESC), $ages);
        }

        $this->filter_string = http_build_query(request()->all());
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListAgeAsPdf?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-danger', 'icon' => 'fa-solid fa-file-pdf');
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListAgeAsXls?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-success', 'icon' => 'fa-solid fa-file-excel');
        
        $with['subdistrict_ids'] = is_array(request()->subdistrict_ids)? request()->subdistrict_ids : [];
        $with['datas'] = $ages;
        $with['model'] = request()->model;
        $with['start_date'] = request()->start_date;
        $with['end_date'] = request()->end_date;
        $with['param'] = request()->all();
        $with['collection_datas'] = $collection_datas;
        $with['actions'] = $actions;
        $with['sort_field'] = $sort_field;
        $with['sort_type'] = $sort_type;
        return $with;
    }

    public function getListByVolunteer()
    {
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;

        $start_date = request()->start_date;
        $end_date = request()->end_date;
        $sort_field = request()->sort_field;
        $sort_type = request()->sort_type;

        $datas = \Models\volunteer_data::select(['*']);

        $this->filter($datas, request(), 'volunteer_data');
        $max_row = request()->input('max_row') ?? 50;

        $sort_type = $sort_type > 2? 0 : $sort_type;
        $order_field = orders()[$sort_type] ?? null;
        if(in_array($sort_field,['name']) && $order_field){
            $datas->orderBy($sort_field, $order_field ?? 'desc');
        }
        if(in_array($sort_field,['verif','share','data']) && $order_field){
            $datas->withCount(['collections_'.$sort_field  => function($query) use ($start_date,$end_date,$groups_id,$user_id){
                if($start_date != ''){
                    $query->whereDate('created_at','>=',$start_date);
                }
                if($end_date != ''){
                    $query->whereDate('created_at','<=',$end_date);
                }
                if($groups_id == 2){
                    $query->where('coordinator_id',$user_id);
                }
            }])->orderBy('collections_'.$sort_field.'_count', $order_field ?? 'desc');
        }

        $datas = $datas->orderBy('id','desc')->paginate($max_row);
        $datas->chunk(100);

        $volunteer_data_ids = $datas->pluck('id')->all();
        $volunteers = $datas->pluck('name')->all();

        $collection_datas = $this->model->select(['*'])
            ->whereIn('volunteer_data_id',$volunteer_data_ids);
        if($start_date != ''){
            $collection_datas->whereDate('created_at','>=',$start_date);
        }
        if($end_date != ''){
            $collection_datas->whereDate('created_at','<=',$end_date);
        }
        if($groups_id == 2){
            $collection_datas->where('coordinator_id',$user_id);
        }

        $collection_datas = $collection_datas->get();

        $this->filter_string = http_build_query(request()->all());
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListVolunteerAsPdf?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-danger', 'icon' => 'fa-solid fa-file-pdf');
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListVolunteerAsXls?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-success', 'icon' => 'fa-solid fa-file-excel');

        $with['model'] = request()->model;
        $with['start_date'] = request()->start_date;
        $with['end_date'] = request()->end_date;
        $with['datas'] = $datas;
        $with['volunteers'] = $volunteers;
        $with['param'] = request()->all();
        $with['collection_datas'] = $collection_datas;
        $with['actions'] = $actions;
        $with['sort_field'] = $sort_field;
        $with['sort_type'] = $sort_type;
        return $with;
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
            } elseif ($key == 'volunteer_name') {
                $data->whereHas('volunteer_data', function($builder) use($value){
                    $builder->where('name', 'LIKE', '%' . $value . '%');
                });
            }
        }
    }
    
    public function getListAsPdf()
    {
        $template = $this->controller_name . '::getListAsPdf';
        $data = $this->getListByCitizens();
        $data['title_head_export'] = 'Rekap Berdasarkan NIK';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('A4', 'landscape');

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return $pdf->download('Rekap Berdasarkan NIK ('.date('d-m-Y').').pdf');
    }

    public function getListAsXls()
    {
        $template = $this->controller_name . '::getListAsXls';
        $data = $this->getListByCitizens();
        $data['title_head_export'] = 'Rekap Berdasarkan NIK';
        $data['title_col_sum'] = 20;

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Rekap Berdasarkan NIK ('.date('d-m-Y').').xls"');
    }
    
    public function getListDistrictAsPdf()
    {
        $template = $this->controller_name . '::getListDistrictAsPdf';
        $data = $this->getListByDistrict();
        $data['title_head_export'] = 'Rekap Berdasarkan Kecamatan';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('A4', 'portrait');

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return $pdf->download('Rekap Berdasarkan Kecamatan ('.date('d-m-Y').').pdf');
    }

    public function getListDistrictAsXls()
    {
        $template = $this->controller_name . '::getListDistrictAsXls';
        $data = $this->getListByDistrict();
        $data['title_head_export'] = 'Rekap Berdasarkan Kecamatan';
        $data['title_col_sum'] = 5;

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Rekap Berdasarkan Kecamatan ('.date('d-m-Y').').xls"');
    }
    
    public function getListSubdistrictAsPdf()
    {
        $template = $this->controller_name . '::getListSubdistrictAsPdf';
        $data = $this->getListBySubdistrict();
        $data['title_head_export'] = 'Rekap Berdasarkan Kelurahan';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('A4', 'portrait');

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return $pdf->download('Rekap Berdasarkan Kelurahan ('.date('d-m-Y').').pdf');
    }

    public function getListSubdistrictAsXls()
    {
        $template = $this->controller_name . '::getListSubdistrictAsXls';
        $data = $this->getListBySubdistrict();
        $data['title_head_export'] = 'Rekap Berdasarkan Kelurahan';
        $data['title_col_sum'] = 5;

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Rekap Berdasarkan Kelurahan ('.date('d-m-Y').').xls"');
    }
    
    public function getListCoordinatorAsPdf()
    {
        $template = $this->controller_name . '::getListCoordinatorAsPdf';
        $data = $this->getListByCoordinator();
        $data['title_head_export'] = 'Rekap Berdasarkan Koordinator';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('A4', 'portrait');

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return $pdf->download('Rekap Berdasarkan Koordinator ('.date('d-m-Y').').pdf');
    }

    public function getListCoordinatorAsXls()
    {
        $template = $this->controller_name . '::getListCoordinatorAsXls';
        $data = $this->getListByCoordinator();
        $data['title_head_export'] = 'Rekap Berdasarkan Koordinator';
        $data['title_col_sum'] = 4;

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Rekap Berdasarkan Koordinator ('.date('d-m-Y').').xls"');
    }
    
    public function getListTPSAsPdf()
    {
        $template = $this->controller_name . '::getListTPSAsPdf';
        $data = $this->getListByTPS();
        $data['title_head_export'] = 'Rekap Berdasarkan TPS';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('A4', 'portrait');

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return $pdf->download('Rekap Berdasarkan TPS ('.date('d-m-Y').').pdf');
    }

    public function getListTPSAsXls()
    {
        $template = $this->controller_name . '::getListTPSAsXls';
        $data = $this->getListByTPS();
        $data['title_head_export'] = 'Rekap Berdasarkan TPS';
        $data['title_col_sum'] = 6;

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Rekap Berdasarkan TPS ('.date('d-m-Y').').xls"');
    }
    
    public function getListGenderAsPdf()
    {
        $template = $this->controller_name . '::getListGenderAsPdf';
        $data = $this->getListByGender();
        $data['title_head_export'] = 'Rekap Berdasarkan Jenis Kelamin';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('A4', 'portrait');

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return $pdf->download('Rekap Berdasarkan Jenis Kelamin ('.date('d-m-Y').').pdf');
    }

    public function getListGenderAsXls()
    {
        $template = $this->controller_name . '::getListGenderAsXls';
        $data = $this->getListByGender();
        $data['title_head_export'] = 'Rekap Berdasarkan Jenis Kelamin';
        $data['title_col_sum'] = 4;

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Rekap Berdasarkan Jenis Kelamin ('.date('d-m-Y').').xls"');
    }
    
    public function getListJobAsPdf()
    {
        $template = $this->controller_name . '::getListJobAsPdf';
        $data = $this->getListByJob();
        $data['title_head_export'] = 'Rekap Berdasarkan Pekerjaan';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('A4', 'portrait');

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return $pdf->download('Rekap Berdasarkan Pekerjaan ('.date('d-m-Y').').pdf');
    }

    public function getListJobAsXls()
    {
        $template = $this->controller_name . '::getListJobAsXls';
        $data = $this->getListByJob();
        $data['title_head_export'] = 'Rekap Berdasarkan Pekerjaan';
        $data['title_col_sum'] = 4;

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Rekap Berdasarkan Pekerjaan ('.date('d-m-Y').').xls"');
    }
    
    public function getListAgeAsPdf()
    {
        $template = $this->controller_name . '::getListAgeAsPdf';
        $data = $this->getListByAge();
        $data['title_head_export'] = 'Rekap Berdasarkan Umur';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('A4', 'portrait');

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return $pdf->download('Rekap Berdasarkan Umur ('.date('d-m-Y').').pdf');
    }

    public function getListAgeAsXls()
    {
        $template = $this->controller_name . '::getListAgeAsXls';
        $data = $this->getListByAge();
        $data['title_head_export'] = 'Rekap Berdasarkan Umur';
        $data['title_col_sum'] = 4;

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Rekap Berdasarkan Umur ('.date('d-m-Y').').xls"');
    }
    
    public function getListVolunteerAsPdf()
    {
        $template = $this->controller_name . '::getListVolunteerAsPdf';
        $data = $this->getListByVolunteer();
        $data['title_head_export'] = 'Rekap Berdasarkan Relawan Data';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('A4', 'portrait');

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return $pdf->download('Rekap Berdasarkan Relawan Data ('.date('d-m-Y').').pdf');
    }

    public function getListVolunteerAsXls()
    {
        $template = $this->controller_name . '::getListVolunteerAsXls';
        $data = $this->getListByVolunteer();
        $data['title_head_export'] = 'Rekap Berdasarkan Relawan Data';
        $data['title_col_sum'] = 4;

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Rekap Berdasarkan Relawan Data ('.date('d-m-Y').').xls"');
    }

    public function filterCoordinatorBySubdistrict()
    {
        $globalTools = new \Lib\core\globalTools();

        $q = request()->subdistrict_id;
        $id = request()->id;

        $target = 'coordinator_id';
        $blank = true;

        $datas = \App\Models\User::where('groups_id',2)
            ->whereHas('users_subdistrict', function($builder) use($q){
                $builder->where('subdistrict_id',$q);
            });
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;
        if($groups_id == 2){
            $datas->where('id',$user_id);
        }   
        $datas = $datas->orderBy('name','asc')->pluck('name','id')->all();

        return $globalTools->renderList($datas, $target, $id, $blank);
    }

    // public function filterVolunteerByCoordinator()
    // {
    //     $globalTools = new \Lib\core\globalTools();

    //     $q = request()->coordinator_id;
    //     $id = request()->id;

    //     $target = 'coordinator_id';
    //     $blank = true;

    //     $datas = \Models\volunteer_data::where('coordinator_id',$q)
    //         ->orderBy('name','asc')->pluck('name','id')->all();

    //     return $globalTools->renderList($datas, $target, $id, $blank);
    // }

    public function getListBySimpatisan()
    {
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;

        $start_date = request()->start_date;
        $end_date = request()->end_date;
        $subdistrict_id = request()->subdistrict_id;
        $coordinator_id = request()->coordinator_id;
        $no_tps = request()->no_tps;
        $sort_field = request()->sort_field;
        $sort_type = request()->sort_type;

        $subdistrict = \Models\subdistrict::find($subdistrict_id);
        $coordinator = \app\Models\User::find($coordinator_id);

        $datas = $this->model->select(['collection_data.*','subdistrict.name as subdistrict_name','users.name as coordinator_name', 'users.code as coordinator_code','volunteer_data.name as volunteer_name']);
        $datas->leftJoin('subdistrict', function ($join) {
            $join->on('subdistrict.id', '=', 'collection_data.subdistrict_id');
        })->leftJoin('users', function ($join) {
            $join->on('users.id', '=', 'collection_data.coordinator_id');
        })->leftJoin('volunteer_data', function ($join) {
            $join->on('volunteer_data.id', '=', 'collection_data.volunteer_data_id');
        });
        if($start_date != ''){
            $datas->whereDate('collection_data.created_at','>=',$start_date);
        }
        if($end_date != ''){
            $datas->whereDate('collection_data.created_at','<=',$end_date);
        }
        if($subdistrict_id != ''){
            $datas->where('collection_data.subdistrict_id',$subdistrict_id);
        }
        if($coordinator_id != ''){
            $datas->where('collection_data.coordinator_id',$coordinator_id);
        }
        if($no_tps != ''){
            $datas->where('collection_data.no_tps',$no_tps);
        }
        if($groups_id == 2){
            $datas->where('collection_data.coordinator_id',$user_id);
        }
        
        $sort_type = request()->sort_type > 2? 0 : request()->sort_type;
        $this->filter($datas, request(), 'collection_data');
        $this->order($datas, request());
        $max_row = request()->input('max_row') ?? 50;

        $datas = $datas->orderBy('id','desc')->paginate($max_row);
        $datas->chunk(100);

        $volunteer_groups = $datas->groupBy('volunteer_data_id')->map->count()->all();
        arsort($volunteer_groups);
        $coordinator_tps_id = array_key_first($volunteer_groups);

        $coordinator_tps = \Models\volunteer_data::find($coordinator_tps_id);

        if (request()->has('print_view')) {
            dd($datas,$datas->groupBy('volunteer_data_id'),$volunteer_groups,$coordinator_tps_id,$coordinator_tps);
        }

        $day = date('w');
        $start_date = new DateTime($start_date ?? date('Y-m-d', strtotime('-'.($day-1).' days')));
        $end_date = new DateTime($end_date ?? date('Y-m-d', strtotime('+'.(7-$day).' days')));

        $collection_datas = \Models\collection_data::select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(*) as total'));
        if($start_date != ''){
            $collection_datas->whereDate('collection_data.created_at','>=',$start_date);
        }
        if($end_date != ''){
            $collection_datas->whereDate('collection_data.created_at','<=',$end_date);
        }
        if($subdistrict_id != ''){
            $collection_datas->where('collection_data.subdistrict_id',$subdistrict_id);
        }
        if($coordinator_id != ''){
            $collection_datas->where('collection_data.coordinator_id',$coordinator_id);
        }
        if($no_tps != ''){
            $collection_datas->where('collection_data.no_tps',$no_tps);
        }
        if($groups_id == 2){
            $collection_datas->where('collection_data.coordinator_id',$user_id);
        }
        $collection_datas = $collection_datas->groupBy('date')->get()->pluck('total','date')->all();

        $interval = DateInterval::createFromDateString('1 day');
        // $end_week = new DateTime(date('Y-m-d', strtotime('+'.(8-$day).' days')));
        $end_week = request()->end_date ?? date('Y-m-d', strtotime('+'.(7-$day).' days'));
        $end_week = new DateTime(date('Y-m-d', strtotime($end_week . ' +1 day')));
        $date_range = new DatePeriod($start_date, $interval, $end_week);

        $dates = [];
        $dataByDates = [];
        foreach ($date_range as $dt) {
            $date = $dt->format('Y-m-d');
            $dates[] = dateToIndo($date);
            $dataByDates[] = $collection_datas[$date] ?? 0;
        }

        $this->filter_string = http_build_query(request()->all());
        
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListSimpatisanCanvasserAsPdf?' . $this->filter_string, 'name'=>'Rekap Koordinator Simpatisan TPS', 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-danger', 'icon' => 'fa-solid fa-file-pdf');
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListSimpatisanAsPdf?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-danger', 'icon' => 'fa-solid fa-file-pdf');
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListSimpatisanAsXls?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-success', 'icon' => 'fa-solid fa-file-excel');
        
        $with['datas'] = $datas;
        $with['model'] = request()->model;
        $with['start_date'] = request()->start_date;
        $with['end_date'] = request()->end_date;
        $with['subdistrict_id'] = request()->subdistrict_id;
        $with['coordinator_id'] = request()->coordinator_id;
        $with['no_tps'] = request()->no_tps;
        $with['param'] = request()->all();
        $with['dates'] = $dates;
        $with['dataByDates'] = $dataByDates;
        $with['actions'] = $actions;
        $with['sort_field'] = $sort_field;
        $with['sort_type'] = $sort_type;
        $with['subdistrict'] = $subdistrict;
        $with['coordinator'] = $coordinator;
        $with['coordinator_tps'] = $coordinator_tps;
        return $with;
    }

    public function getListSimpatisanAsPdf()
    {
        $template = $this->controller_name . '::getListSimpatisanAsPdf';
        $data = $this->getListBySimpatisan();
        $data['title_head_export'] = 'Rekap Data Simpatisan TPS';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('A4', 'landscape')
            ->setOptions(['isPhpEnabled' => true, 'enable_remote' => true]);

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return $pdf->download('Rekap Data Simpatisan TPS ('.date('d-m-Y').').pdf');
    }

    public function getListSimpatisanAsXls()
    {
        $template = $this->controller_name . '::getListSimpatisanAsXls';
        $data = $this->getListBySimpatisan();
        $data['title_head_export'] = 'Rekap Data Simpatisan TPS';
        $data['title_col_sum'] = ($data['coordinator']->name ?? null)? 8 : 9;

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Rekap Data Simpatisan TPS ('.date('d-m-Y').').xls"');
    }

    public function getListDistrictTPSAsPdf()
    {
        $template = $this->controller_name . '::getListDistrictTPSAsPdf';

        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;

        $start_date = request()->start_date;
        $end_date = request()->end_date;
        $sort_field = request()->sort_field;
        $sort_type = request()->sort_type;

        $data = $this->getListByDistrict();
        $data['title_head_export'] = 'Rekap Berdasarkan TPS tiap Kecamatan';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('A4', 'landscape');

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return $pdf->stream('Rekap Berdasarkan TPS tiap Kecamatan ('.date('d-m-Y').').pdf');
    }

    public function getListSimpatisanCanvasserAsPdf()
    {
        $template = $this->controller_name . '::getListSimpatisanCanvasserAsPdf';
        $data = $this->getListBySimpatisan();
        $data['title_head_export'] = 'Rekap Data Simpatisan TPS';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('A4', 'landscape')
            ->setOptions(['isPhpEnabled' => true, 'enable_remote' => true]);

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return $pdf->stream('Rekap Data Simpatisan TPS ('.date('d-m-Y').').pdf');
    }
}
