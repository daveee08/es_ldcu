<link rel="stylesheet" href="<?php echo e(asset('plugins/fontawesome-free/css/all.min.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('dist/css/adminlte.min.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('plugins/fullcalendar/main.min.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('plugins/fullcalendar-daygrid/main.min.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('plugins/fullcalendar-timegrid/main.min.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('plugins/fullcalendar-bootstrap/main.min.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('plugins/fullcalendar-interaction/main.min.css')); ?>">



<style>
    .dataTable                  { font-size:80%; }
    .tschoolschedule .card-body { height:250px; }
    .tschoolcalendar            { font-size: 12px; }
    /* .tschoolcalendar .card-body { height: 250px; overflow-x: scroll; } */
    .teacherd ul li a           { color: #fff; -webkit-transition: .3s; }
    .teacherd ul li             { -webkit-transition: .3s; border-radius: 5px; background: rgba(173, 177, 173, 0.3); margin-left: 2px; }
    .sf5                        { background: rgba(173, 177, 173, 0.3)!important; border: none!important; }
    .sf5menu a:hover            { background-color: rgba(173, 177, 173, 0.3)!important; }
    .teacherd ul li:hover       { transition: .3s; border-radius: 5px; padding: none; margin: none; }

    .small-box                  { box-shadow: 1px 2px 2px #001831c9; overflow-y: auto scroll; }

    .small-box h5               { text-shadow: 1px 1px 2px gray; }
</style>
<?php $__env->startSection('content'); ?>
<?php
    use \Carbon\Carbon;
    $now = Carbon::now();
    $comparedDate = $now->toDateString();
?>
<div class="row">
  <div class="col-md-4">
      <div class="small-box bg-info">
        <div class="inner">
          <h3>LEAVES</h3>

          <p>Apply Leave</p>
        </div>
        <div class="icon">
          <i class="ion ion-bag"></i>
        </div>
        <a href="/leaves/apply/index" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
  </div>
  <div class="col-md-4">
      <div class="small-box bg-success">
        <div class="inner">
          <h3>Overtime</h3>

          <p>Apply Overtime</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
        <a href="/overtime/apply/index" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
  </div>
  <div class="col-md-4">
      <div class="small-box bg-warning">
        <div class="inner">
          <h3>DTR</h3>

          <p>Daily Time Record</p>
        </div>
        <div class="icon">
          <i class="ion ion-person-add"></i>
        </div>
        <a href="/dtr/attendance/index" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
      
  </div>
    <div class="col-md-12">
        <div class="card card-primary tschoolcalendar h-100">
            <div class="card-header bg-success">
                <h3 class="card-title">School Calendar</h3>
            </div>
            <div class="card-body p-1">
                <div class="calendarHolder">
                    <div id='newcal'></div>
                </div>
            </div>
        </div>
    </div>
    </div>
    
  </div>


<script src="<?php echo e(asset('plugins/moment/moment.min.js')); ?>"></script>
<script src="<?php echo e(asset('plugins/fullcalendar/main.min.js')); ?>"></script>
<script src="<?php echo e(asset('plugins/fullcalendar-daygrid/main.min.js')); ?>"></script>
<script src="<?php echo e(asset('plugins/fullcalendar-timegrid/main.min.js')); ?>"></script>
<script src="<?php echo e(asset('plugins/fullcalendar-interaction/main.min.js')); ?>"></script>
<script src="<?php echo e(asset('plugins/fullcalendar-bootstrap/main.min.js')); ?>"></script>
<script src="<?php echo e(asset('plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<script src="<?php echo e(asset('plugins/datatables/jquery.dataTables.js')); ?>"></script>
<script src="<?php echo e(asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')); ?>"></script>
<script>

$( document ).ready(function() {

if($(window).width()<500){

$('.fc-prev-button').addClass('btn-sm')
$('.fc-next-button').addClass('btn-sm')
$('.fc-today-button').addClass('btn-sm')

$('.fc-left').css('font-size','13px')
$('.fc-toolbar').css('margin','0')
$('.fc-toolbar').css('padding-top','0')

var header = {
    left:   'title',
    center: '',
    right:  'today prev,next'
}
console.log(header)


}
else{
var header = {
    left  : 'prev,next today',
    center: 'title',
    right : 'dayGridMonth,timeGridWeek,timeGridDay'
}
console.log(header)
}

var date = new Date()
var d    = date.getDate(),
m    = date.getMonth(),
y    = date.getFullYear()

var schedule = [];

<?php $__currentLoopData = $schoolcalendar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <?php if($item->noclass == 1): ?>
        var backgroundcolor = '#dc3545';
    <?php else: ?>
        var backgroundcolor = '#00a65a';
    <?php endif; ?>

    schedule.push({
        title          : '<?php echo e($item->description); ?>',
        start          : '<?php echo e($item->datefrom); ?>',
        end            : '<?php echo e($item->dateto); ?>',
        backgroundColor: backgroundcolor,
        borderColor    : backgroundcolor,
        allDay         : true,
        id: '<?php echo e($item->id); ?>'
    })

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


var Calendar = FullCalendar.Calendar;

console.log(schedule);

var calendarEl = document.getElementById('newcal');

var calendar = new Calendar(calendarEl, {
    plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
    header    : header,
    events    : schedule,
    height : 'auto',
    themeSystem: 'bootstrap',
    eventStartEditable: false
});

calendar.render();

$('.fc-dayGridMonth-button').css('text-transform', 'capitalize')
$('.fc-timeGridWeek-button').css('text-transform', 'capitalize')
$('.fc-timeGridDay-button').css('text-transform', 'capitalize')
$('.fc-today-button').css('text-transform', 'capitalize')
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('general.defaultportal.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\es_ldcu\resources\views/general/defaultportal/pages/index.blade.php ENDPATH**/ ?>