 <!-- Nav tabs -->
<ul class="nav nav-tabs" id="importTabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="paliativos-tab" data-toggle="tab" href="#paliativos" role="tab" aria-controls="paliativos" aria-selected="true">
            Paliativos
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="fidem-tab" data-toggle="tab" href="#fidem" role="tab" aria-controls="fidem" aria-selected="false">
            Fidem Contigo
        </a>
    </li>
</ul>
<!-- Tab panes -->
<div class="tab-content">
    <div class="tab-pane fade show active" id="paliativos" role="tabpanel" aria-labelledby="paliativos-tab">
        @include('admin.import.div_paliativos.div_paliativos')
    </div>
    <div class="tab-pane fade" id="fidem" role="tabpanel" aria-labelledby="fidem-tab">
        @include('admin.import.div_paliativos.div_fidemcontigo')
    </div>
</div>