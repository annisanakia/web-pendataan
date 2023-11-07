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
        }else{
            // Jenis Kelamin
            $with = $this->getListByGender();
            return view($this->controller_name . '::listByGender', $with);
        }
    }

    public function getListByCitizens($print = false)
    {
        $start_date = request()->start_date;
        $end_date = request()->end_date;
        $status = request()->status;
        $status_share = request()->status_share;

        $datas = $this->model->select(['*']);
        if($start_date != ''){
            $datas->whereDate('created_at','>=',$start_date);
        }
        if($end_date != ''){
            $datas->whereDate('created_at','<=',$end_date);
        }
        if($status != ''){
            $datas->where('status',$status);
        }
        if($status_share != ''){
            $datas->where('status_share',$status_share);
        }
        
        $this->filter($datas, request(), 'collection_data');
        $max_row = request()->input('max_row') ?? 50;
        $datas = $datas->orderBy('id','desc')->paginate($max_row);
        $datas->chunk(100);

        if(!$print){
            $day = date('w');
            $start_date = new DateTime($start_date ?? date('Y-m-d', strtotime('-'.($day-1).' days')));
            $end_date = new DateTime($end_date ?? date('Y-m-d', strtotime('+'.(7-$day).' days')));

            $collection_datas = \Models\collection_data::select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(*) as total'));
            if($start_date != ''){
                $collection_datas->whereDate('created_at','>=',$start_date);
            }
            if($end_date != ''){
                $collection_datas->whereDate('created_at','<=',$end_date);
            }
            if($status != ''){
                $collection_datas->where('status',$status);
            }
            if($status_share != ''){
                $collection_datas->where('status_share',$status_share);
            }
            $collection_datas = $collection_datas->groupBy('date')->get()->pluck('total','date')->all();
    
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
            
            $with['dates'] = $dates;
            $with['dataByDates'] = $dataByDates;
            $with['actions'] = $actions;
        }
        
        $with['datas'] = $datas;
        $with['model'] = request()->model;
        $with['start_date'] = request()->start_date;
        $with['end_date'] = request()->end_date;
        $with['param'] = request()->all();
        return $with;
    }

    public function getListByDistrict()
    {
        $start_date = request()->start_date;
        $end_date = request()->end_date;

        $datas = \Models\district::select(['*']);

        $this->filter($datas, request(), 'district');
        $max_row = request()->input('max_row') ?? 50;
        $datas = $datas->orderBy('id','desc')->paginate($max_row);
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
        $datas = $datas->orderBy('id','desc')->paginate($max_row);
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
        $datas = $datas->orderBy('id','desc')->paginate($max_row);
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

    public function getListByTPS()
    {
        $start_date = request()->start_date;
        $end_date = request()->end_date;
        $subdistrict_id = request()->subdistrict_id;

        $datas = $this->model->select('no_tps', \DB::raw('count(*) as total'));
        if($start_date != ''){
            $datas->whereDate('created_at','>=',$start_date);
        }
        if($end_date != ''){
            $datas->whereDate('created_at','<=',$end_date);
        }
        if($subdistrict_id != ''){
            $datas->where('subdistrict_id',$subdistrict_id);
        }
        
        $this->filter($datas, request(), 'collection_data');
        $max_row = request()->input('max_row') ?? 50;
        $datas = $datas->groupBy('no_tps')->orderBy('no_tps','asc')->paginate($max_row);
        $datas->chunk(100);

        $day = date('w');
        $start_date = new DateTime($start_date ?? date('Y-m-d', strtotime('-'.($day-1).' days')));
        $end_date = new DateTime($end_date ?? date('Y-m-d', strtotime('+'.(7-$day).' days')));

        $collection_datas = \Models\collection_data::select(['*']);
        if($start_date != ''){
            $collection_datas->whereDate('created_at','>=',$start_date);
        }
        if($end_date != ''){
            $collection_datas->whereDate('created_at','<=',$end_date);
        }
        if($subdistrict_id != ''){
            $collection_datas->where('subdistrict_id',$subdistrict_id);
        }
        $this->filter($collection_datas, request(), 'collection_data');
        $collection_datas = $collection_datas->get();

        $datas_tps = array_values($collection_datas->sortBy('no_tps')->pluck('no_tps','no_tps')->all());
        $dataByTPS = [];
        $no_tps = [];
        foreach($datas_tps as $tps){
            $dataByTPS[] = $collection_datas->where('no_tps',$tps)->count();
            $no_tps[] = 'TPS '.$tps;
        }

        $this->filter_string = http_build_query(request()->all());
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListTPSAsPdf?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-danger', 'icon' => 'fa-solid fa-file-pdf');
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListTPSAsXls?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-success', 'icon' => 'fa-solid fa-file-excel');
        
        $with['datas'] = $datas;
        $with['model'] = request()->model;
        $with['start_date'] = request()->start_date;
        $with['end_date'] = request()->end_date;
        $with['subdistrict_id'] = request()->subdistrict_id;
        $with['param'] = request()->all();
        $with['collection_datas'] = $collection_datas;
        $with['no_tps'] = $no_tps;
        $with['dataByTPS'] = $dataByTPS;
        $with['actions'] = $actions;
        return $with;
    }

    public function getListByGender()
    {
        $start_date = request()->start_date;
        $end_date = request()->end_date;
        $subdistrict_ids = is_array(request()->subdistrict_ids)? request()->subdistrict_ids : [];

        $datas = $this->model->select('gender', \DB::raw('count(*) as total'));
        if($start_date != ''){
            $datas->whereDate('created_at','>=',$start_date);
        }
        if($end_date != ''){
            $datas->whereDate('created_at','<=',$end_date);
        }
        if(count($subdistrict_ids) > 0){
            $datas->whereIn('subdistrict_id',$subdistrict_ids);
        }
        
        $this->filter($datas, request(), 'collection_data');
        $max_row = request()->input('max_row') ?? 50;
        $datas = $datas->groupBy('gender')->orderBy('gender','asc')->paginate($max_row);
        $datas->chunk(100);

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
        $this->filter($collection_datas, request(), 'collection_data');
        $collection_datas = $collection_datas->get();

        $dataByGender[] = $collection_datas->whereNull('gender')->count();
        $genders[] = 'NA';
        $dataByGender[] = $collection_datas->where('gender','L')->count();
        $genders[] = 'Laki-laki';
        $dataByGender[] = $collection_datas->where('gender','P')->count();
        $genders[] = 'Perempuan';

        $this->filter_string = http_build_query(request()->all());
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListGenderAsPdf?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-danger', 'icon' => 'fa-solid fa-file-pdf');
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListGenderAsXls?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-success', 'icon' => 'fa-solid fa-file-excel');
        
        $with['subdistrict_ids'] = is_array(request()->subdistrict_ids)? request()->subdistrict_ids : [];
        $with['datas'] = $datas;
        $with['model'] = request()->model;
        $with['start_date'] = request()->start_date;
        $with['end_date'] = request()->end_date;
        $with['param'] = request()->all();
        $with['collection_datas'] = $collection_datas;
        $with['genders'] = $genders;
        $with['dataByGender'] = $dataByGender;
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
        $data = $this->getListByCitizens(true);
        $data['title_head_export'] = 'Rekap Berdasarkan NIK';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('legal', 'landscape');

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return $pdf->download('Rekap Berdasarkan NIK ('.date('d-m-Y').').pdf');
    }

    public function getListAsXls()
    {
        $template = $this->controller_name . '::getListAsXls';
        $data = $this->getListByCitizens(true);
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
    
    public function getListTPSAsPdf()
    {
        $template = $this->controller_name . '::getListTPSAsPdf';
        $data = $this->getListByTPS();
        $data['title_head_export'] = 'Rekap Berdasarkan TPS';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('legal', 'portrait');

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
        $data['title_col_sum'] = 5;

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
            ->setPaper('legal', 'portrait');

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
        $data['title_col_sum'] = 5;

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Rekap Berdasarkan Jenis Kelamin ('.date('d-m-Y').').xls"');
    }
    
}
