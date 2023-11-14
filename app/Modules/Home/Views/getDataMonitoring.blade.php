<div class="row">
    <?php
        $groups_id = \Auth::user()->groups_id ?? null;
    ?>
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-area me-1"></i>
                Grafik Mingguan
            </div>
            <div class="card-body">
                @include(ucwords($controller_name).'::getDataGraph')
            </div>
        </div>
    </div>
    @if($groups_id != 2)
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-bar me-1"></i>
                Grafik Mingguan Koordinator
            </div>
            <div class="card-body">
                @include(ucwords($controller_name).'::getDataCoorGraph')
            </div>
        </div>
    </div>
    @else
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-bar me-1"></i>
                Grafik Mingguan Status
            </div>
            <div class="card-body">
                @include(ucwords($controller_name).'::getDataStatusGraph')
            </div>
        </div>
    </div>
    @endif
</div>