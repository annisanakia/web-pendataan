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
        // }elseif($model == 4){
            // TPS
        }else{
            $with = $this->getListByCoordinator();
            return view($this->controller_name . '::listByCoordinator', $with);
        }
    }

    public function getListByCitizens()
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
        return $with;
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

        $this->filter_string = http_build_query(request()->all());
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListDistrictAsPdf?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-danger', 'icon' => 'fa-solid fa-file-pdf');
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListDistrictAsXls?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-success', 'icon' => 'fa-solid fa-file-excel');

        $with['model'] = request()->model;
        $with['start_date'] = request()->start_date;
        $with['end_date'] = request()->end_date;
        $with['datas'] = $datas;
        $with['districts'] = $districts;
        $with['param'] = request()->all();
        $with['collection_datas'] = $collection_datas;
        $with['actions'] = $actions;
        return $with;
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
        $with['collection_datas'] = $collection_datas;
        $with['actions'] = $actions;
        return $with;
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

        $this->filter_string = http_build_query(request()->all());
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListCoordinatorAsPdf?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-danger', 'icon' => 'fa-solid fa-file-pdf');
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListCoordinatorAsXls?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-success', 'icon' => 'fa-solid fa-file-excel');

        $with['model'] = request()->model;
        $with['start_date'] = request()->start_date;
        $with['end_date'] = request()->end_date;
        $with['datas'] = $datas;
        $with['coordinators'] = $coordinators;
        $with['param'] = request()->all();
        $with['collection_datas'] = $collection_datas;
        $with['actions'] = $actions;
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
            }
        }
    }
    
    public function getListAsPdf()
    {
        $template = $this->controller_name . '::getListAsPdf';
        $data = $this->getListByCitizens();
        $data['title_head_export'] = 'Rekap Berdasarkan NIK';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('legal', 'portrait');

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
        $data['title_col_sum'] = 17;

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
            ->setPaper('legal', 'portrait');

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
        $data['title_col_sum'] = 6;

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
            ->setPaper('legal', 'portrait');

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return $pdf->download('Rekap Berdasarkan Kelurahan ('.date('d-m-Y').').pdf');
    }

    public function getListSubdistrictAsXls()
    {
        $template = $this->controller_name . '::getListSubdistrictAsXls';
        $data = $this->getListBySubdistrict();
        $data['title_head_export'] = 'Rekap Berdasarkan Kecamatan';
        $data['title_col_sum'] = 6;

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Rekap Berdasarkan Kecamatan ('.date('d-m-Y').').xls"');
    }
    
    public function getListCoordinatorAsPdf()
    {
        $template = $this->controller_name . '::getListCoordinatorAsPdf';
        $data = $this->getListByCoordinator();
        $data['title_head_export'] = 'Rekap Berdasarkan Koordinator';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('legal', 'portrait');

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
        $data['title_col_sum'] = 5;

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Rekap Berdasarkan Koordinator ('.date('d-m-Y').').xls"');
    }
    
}
