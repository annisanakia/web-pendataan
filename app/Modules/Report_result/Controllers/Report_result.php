<?php

namespace App\Modules\Report_result\Controllers;

use Models\election_results as election_resultsModel;
use Lib\core\RESTful;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use DateInterval;
use DateTime;
use DatePeriod;

class Report_result extends RESTful {

    public function __construct() {
        $model = new election_resultsModel;
        $controller_name = 'Report_result';
        
        $this->setExceptMiddleware(['quickCount']);
        
        $this->table_name = 'election_results';
        parent::__construct($model, $controller_name);
    }

    public function index()
    {
        $user_id = \Auth::user()->id;
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
        $model = request()->model ?? null;
        if($model == 1){
            // Kecamatan
            $with = $this->getListByDistrict();
            return view($this->controller_name . '::listByDistrict', $with);
        }elseif($model == 2){
            // Kelurahan
            $with = $this->getListBySubdistrict();
            return view($this->controller_name . '::listBySubdistrict', $with);
        }elseif($model == 3){
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
            // koordinator TPS
            $with = $this->getListByCoordinator();
            return view($this->controller_name . '::listByCoordinator', $with);
        }
    }

    public function getListByDistrict()
    {
        $groups_id = \Auth::user()->groups_id ?? null;

        $sort_field = request()->sort_field;
        $sort_type = request()->sort_type;

        $datas = \Models\district::select(['*'])->with('election_results_data');
        if($groups_id == 2){
            $subdistrict_ids = session()->get('subdistrict_ids');
            $datas->whereHas('subdistrict', function($builder) use($subdistrict_ids){
                $builder->whereIn('id',$subdistrict_ids);
            });
        }

        $this->filter($datas, request(), 'district');
        $max_row = request()->input('max_row') ?? 50;
        
        $sort_type = $sort_type > 2? 0 : $sort_type;
        $order_field = orders()[$sort_type] ?? null;
        if(in_array($sort_field,['code','name']) && $order_field){
            $datas->orderBy($sort_field, $order_field ?? 'desc');
        }
        if(in_array($sort_field,['election_results_data']) && $order_field){
            $datas->withSum('election_results_data','total_result')->orderBy('election_results_data_sum_total_result', $order_field ?? 'desc');
        }

        $datas = $datas->orderBy('id','desc')->paginate($max_row);
        $datas->chunk(100);

        $districts = $datas->pluck('name')->all();

        $this->filter_string = http_build_query(request()->all());
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListDistrictAsPdf?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-danger', 'icon' => 'fa-solid fa-file-pdf');
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListDistrictAsXls?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-success', 'icon' => 'fa-solid fa-file-excel');

        $with['model'] = request()->model;
        $with['datas'] = $datas;
        $with['districts'] = $districts;
        $with['param'] = request()->all();
        $with['actions'] = $actions;
        $with['sort_field'] = $sort_field;
        $with['sort_type'] = $sort_type;
        return $with;
    }

    public function getListBySubdistrict()
    {
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;

        $subdistrict_ids = is_array(request()->subdistrict_ids)? request()->subdistrict_ids : [];
        $sort_field = request()->sort_field;
        $sort_type = request()->sort_type;

        $datas = \Models\subdistrict::select(['*'])->with('election_results_data');
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
        if(in_array($sort_field,['election_results_data']) && $order_field){
            $datas->withSum('election_results_data','total_result')->orderBy('election_results_data_sum_total_result', $order_field ?? 'desc');
        }
        
        $datas = $datas->orderBy('id','desc')->paginate($max_row);
        $datas->chunk(100);

        $subdistricts = $datas->pluck('name')->all();

        $this->filter_string = http_build_query(request()->all());
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListSubdistrictAsPdf?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-danger', 'icon' => 'fa-solid fa-file-pdf');
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListSubdistrictAsXls?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-success', 'icon' => 'fa-solid fa-file-excel');

        $with['subdistrict_ids'] = is_array(request()->subdistrict_ids)? request()->subdistrict_ids : [];
        $with['model'] = request()->model;
        $with['datas'] = $datas;
        $with['subdistricts'] = $subdistricts;
        $with['param'] = request()->all();
        $with['actions'] = $actions;
        $with['sort_field'] = $sort_field;
        $with['sort_type'] = $sort_type;
        return $with;
    }

    public function getListByTPS()
    {
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;

        $subdistrict_id = request()->subdistrict_id;
        $sort_field = request()->sort_field;
        $sort_type = request()->sort_type;

        $datas = $this->model->select('collection_data.no_tps', 'election_results.total_result')
            ->rightJoin('collection_data', function ($join) {
                $join->on('collection_data.no_tps', '=', 'election_results.no_tps')
                    ->on('collection_data.subdistrict_id', '=', 'election_results.subdistrict_id');
            });
        
        if($subdistrict_id != ''){
            $datas->where('collection_data.subdistrict_id',$subdistrict_id);
        }
        
        $this->filter($datas, request(), 'collection_data');
        $max_row = request()->input('max_row') ?? 50;
        
        $sort_type = $sort_type > 2? 0 : $sort_type;
        $order_field = orders()[$sort_type] ?? null;
        if(in_array($sort_field,['no_tps','total_result']) && $order_field){
            if($sort_field == 'no_tps'){
                $datas->orderBy('collection_data.'.$sort_field, $order_field ?? 'desc');
            }else{
                $datas->orderBy('election_results.'.$sort_field, $order_field ?? 'desc');
            }
        }

        $datas = $datas->groupBy('collection_data.no_tps')
            ->groupBy('election_results.total_result')
            ->orderBy('collection_data.no_tps','asc')->paginate($max_row);
        $datas->chunk(100);

        $this->filter_string = http_build_query(request()->all());
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListTPSAsPdf?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-danger', 'icon' => 'fa-solid fa-file-pdf');
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListTPSAsXls?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-success', 'icon' => 'fa-solid fa-file-excel');
        
        $with['datas'] = $datas;
        $with['model'] = request()->model;
        $with['subdistrict_id'] = request()->subdistrict_id;
        $with['param'] = request()->all();
        $with['actions'] = $actions;
        $with['sort_field'] = $sort_field;
        $with['sort_type'] = $sort_type;
        return $with;
    }

    public function getListByCoordinator()
    {
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;

        $sort_field = request()->sort_field;
        $sort_type = request()->sort_type;

        $datas = $this->model->select(
                [
                    'user_id','total_result','users.name as user_name',
                    'election_results.no_tps','district.name as district_name',
                    'subdistrict.name as subdistrict_name'
                ]
            )
            ->leftJoin('users', function ($join) {
                $join->on('users.id', '=', 'election_results.user_id');
            })
            ->leftJoin('district', function ($join) {
                $join->on('district.id', '=', 'election_results.district_id');
            })
            ->leftJoin('subdistrict', function ($join) {
                $join->on('subdistrict.id', '=', 'election_results.subdistrict_id');
            })
            ->where('users.deleted_at',NULL);
        
        $this->filter($datas, request(), 'election_results');
        $max_row = request()->input('max_row') ?? 50;
        
        $sort_type = $sort_type > 2? 0 : $sort_type;
        $order_field = orders()[$sort_type] ?? null;
        if(in_array($sort_field,['user_name','district_name','subdistrict_name','no_tps','total_result']) && $order_field){
            $datas->orderBy($sort_field, $order_field ?? 'desc');
        }

        $datas = $datas->orderBy('user_name','asc')->paginate($max_row);
        $datas->chunk(100);

        $this->filter_string = http_build_query(request()->all());
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListCoordinatorAsPdf?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-danger', 'icon' => 'fa-solid fa-file-pdf');
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListCoordinatorAsXls?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-success', 'icon' => 'fa-solid fa-file-excel');
        
        $with['datas'] = $datas;
        $with['model'] = request()->model;
        $with['subdistrict_id'] = request()->subdistrict_id;
        $with['param'] = request()->all();
        $with['actions'] = $actions;
        $with['sort_field'] = $sort_field;
        $with['sort_type'] = $sort_type;
        return $with;
    }

    public function customFilter($data, $newFilters)
    {
        foreach ($newFilters as $key => $value) {
            if ($key == 'user_name') {
                $data->whereHas('user', function ($builder) use ($value){
                    $builder->where('name', 'like', '%' . $value . '%');
                });
            }elseif ($key == 'district_name') {
                $data->whereHas('district', function ($builder) use ($value){
                    $builder->where('name', 'like', '%' . $value . '%');
                });
            }elseif ($key == 'subdistrict_name') {
                $data->whereHas('subdistrict', function ($builder) use ($value){
                    $builder->where('name', 'like', '%' . $value . '%');
                });
            }
        }
    }

    public function getListDistrictAsPdf()
    {
        $template = $this->controller_name . '::getListDistrictAsPdf';
        $data = $this->getListByDistrict();
        $data['title_head_export'] = 'Hasil Pemilu Berdasarkan Kecamatan';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('A4', 'portrait');

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return $pdf->download('Hasil Pemilu Berdasarkan Kecamatan ('.date('d-m-Y').').pdf');
    }

    public function getListDistrictAsXls()
    {
        $template = $this->controller_name . '::getListDistrictAsXls';
        $data = $this->getListByDistrict();
        $data['title_head_export'] = 'Hasil Pemilu Berdasarkan Kecamatan';
        $data['title_col_sum'] = 4;

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Hasil Pemilu Berdasarkan Kecamatan ('.date('d-m-Y').').xls"');
    }

    public function getListSubdistrictAsPdf()
    {
        $template = $this->controller_name . '::getListSubdistrictAsPdf';
        $data = $this->getListBySubdistrict();
        $data['title_head_export'] = 'Hasil Pemilu Berdasarkan Kelurahan';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('A4', 'portrait');

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return $pdf->download('Hasil Pemilu Berdasarkan Kelurahan ('.date('d-m-Y').').pdf');
    }

    public function getListSubdistrictAsXls()
    {
        $template = $this->controller_name . '::getListSubdistrictAsXls';
        $data = $this->getListBySubdistrict();
        $data['title_head_export'] = 'Hasil Pemilu Berdasarkan Kelurahan';
        $data['title_col_sum'] = 4;

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Hasil Pemilu Berdasarkan Kelurahan ('.date('d-m-Y').').xls"');
    }

    public function getListTPSAsPdf()
    {
        $template = $this->controller_name . '::getListTPSAsPdf';
        $data = $this->getListByTPS();
        $data['title_head_export'] = 'Hasil Pemilu Berdasarkan TPS';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('A4', 'portrait');

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return $pdf->download('Hasil Pemilu Berdasarkan TPS ('.date('d-m-Y').').pdf');
    }

    public function getListTPSAsXls()
    {
        $template = $this->controller_name . '::getListTPSAsXls';
        $data = $this->getListByTPS();
        $data['title_head_export'] = 'Hasil Pemilu Berdasarkan TPS';
        $data['title_col_sum'] = 3;

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Hasil Pemilu Berdasarkan TPS ('.date('d-m-Y').').xls"');
    }

    public function getListCoordinatorAsPdf()
    {
        $template = $this->controller_name . '::getListCoordinatorAsPdf';
        $data = $this->getListByCoordinator();
        $data['title_head_export'] = 'Hasil Pemilu Berdasarkan Koordinator';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('A4', 'portrait');

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return $pdf->download('Hasil Pemilu Berdasarkan Koordinator ('.date('d-m-Y').').pdf');
    }

    public function getListCoordinatorAsXls()
    {
        $template = $this->controller_name . '::getListCoordinatorAsXls';
        $data = $this->getListByCoordinator();
        $data['title_head_export'] = 'Hasil Pemilu Berdasarkan Koordinator';
        $data['title_col_sum'] = 6;

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Hasil Pemilu Berdasarkan Koordinator ('.date('d-m-Y').').xls"');
    }

    public function quickCount()
    {
        $data = \Models\election_results::select('total_result');
        
        $groups_id = \Auth::user()->groups_id ?? null;
        if($groups_id == 2){
            $subdistrict_ids = session()->get('subdistrict_ids');
            $data->whereIn('subdistrict_id',$subdistrict_ids);
        }elseif($groups_id == 3){
            $subdistrict_id = \Auth::user()->subdistrict_id;
            $no_tps = \Auth::user()->no_tps;
            $data->where('subdistrict_id',$subdistrict_id)->where('no_tps',$no_tps);
        }

        $total_result = $data->sum('total_result');

        return response()->json(['status' => 'success', 'count' => $total_result], 200);
    }
}