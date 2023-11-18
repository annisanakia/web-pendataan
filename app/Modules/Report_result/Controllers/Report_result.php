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
        
        $this->table_name = 'election_results';
        parent::__construct($model, $controller_name);
    }

    public function index()
    {
        $groups_id = \Auth::user()->groups_id;
        $subdistrict_ids = \Models\subdistrict::pluck('id')->all();
        if($groups_id == 2){
            $subdistrict_ids = \Models\users_subdistrict::where('user_id',$user->id ?? null)->pluck('subdistrict_id')->all();
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
        }else{
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
        }
    }

    public function getListByDistrict()
    {
        $groups_id = \Auth::user()->groups_id ?? null;

        $start_date = request()->start_date;
        $end_date = request()->end_date;
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
            $datas->withCount('election_results_data')->orderBy('election_results_data_count', $order_field ?? 'desc');
        }

        $datas = $datas->orderBy('id','desc')->paginate($max_row);
        $datas->chunk(100);

        $districts = $datas->pluck('name')->all();

        $this->filter_string = http_build_query(request()->all());
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListDistrictAsPdf?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-danger', 'icon' => 'fa-solid fa-file-pdf');
        $actions[] = array('name' => '', 'url' => strtolower($this->controller_name) . '/getListDistrictAsXls?' . $this->filter_string, 'attr' => 'target="_blank"', 'class' => 'btn btn-outline-success', 'icon' => 'fa-solid fa-file-excel');

        $with['model'] = request()->model;
        $with['start_date'] = request()->start_date;
        $with['end_date'] = request()->end_date;
        $with['datas'] = $datas;
        $with['districts'] = $districts;
        $with['param'] = request()->all();
        $with['actions'] = $actions;
        $with['sort_field'] = $sort_field;
        $with['sort_type'] = $sort_type;
        return $with;
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
        $data['title_col_sum'] = 4;

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Rekap Berdasarkan Kecamatan ('.date('d-m-Y').').xls"');
    }
}