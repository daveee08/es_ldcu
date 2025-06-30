<?php
    $layout = session('currentPortal') == 19 
        ? 'bookkeeper.layouts.app' 
        : 'finance.layouts.app';
?>



<?php $__env->startSection('content'); ?>
	<style>
  .studledger {
    font-size: 14px;
  }
  .card-body {
    /* margin:auto; */
    text-align: center;
  }
  .card-body .btn-app{
    width: 22%;
    height: 100px;
    border: none;
    padding-top:20px;
    transition: .3s;
    background-color: transparent!important;
  }
  .card-body .btn-app .fas{
    font-size: 22px;
    color: #ffc107;
    transition: .3s;
    -webkit-text-stroke: .1px #0d4019;
  }
  .card-body .btn-app:hover {
    background-color: #81f99c52;
    transition: .3s;
  }
  .card-body .btn-app:hover .fas {
    font-size: 40px;
    transition: .3s;
    /* color: green; */
  }
  .card-body .btn-app span{
    color: #fff;
    font-size: 13px;
    font-weight: 600;
  }
  .btn-app:hover{
    background-color: yellow;
  }
  </style>
  <section class="content">
  <div class="container-fluid">
 
  <div class="row">

    <div class="col-lg-6">
      <div class="col-md-12 ap">
        <div class="card bg-success">
          <div class="card-header bg-success">
            <h3 class="card-title"><i style="color: #ffc107" class="fas fa-user-tag"></i> <b>Accounts</b></h3>
          </div>
          <div class="card-body" style="overflow-y: auto">
            <li class="nav-item has-treeview 
                <?php echo e((Request::Is('finance/discounts')) ? 'menu-open' : ''); ?>

                <?php echo e((Request::Is('finance/allowdp')) ? 'menu-open' : ''); ?>

                <?php echo e((Request::Is('finance/modeofpayment')) ? 'menu-open' : ''); ?>

                <?php echo e((Request::Is('finance/mopnew')) ? 'menu-open' : ''); ?>

                <?php echo e((Request::Is('finance/mopedit/*')) ? 'menu-open' : ''); ?>

                <?php echo e((Request::Is('finance/fees')) ? 'menu-open' : ''); ?>

                <?php echo e((Request::Is('finance/feesnew')) ? 'menu-open' : ''); ?>

                <?php echo e((Request::Is('finance/feesedit/*')) ? 'menu-open' : ''); ?>

                ">
              <a href="<?php echo route('discounts'); ?>" class="btn btn-app">
                <i class="fas fa-percent"></i> <b><span>Discounts</span></b>
              </a>
              <a href="<?php echo route('adjustment'); ?>" class="btn btn-app">
                <i class="fas fa-adjust"></i> <b><span>Adjustments</span></b>
              </a>
              <a href="<?php echo route('oldaccounts'); ?>" class="btn btn-app">
                <i class="fas fa-balance-scale-right"></i> <b><span>Old Accounts</span></b>
              </a>
              <a href="<?php echo route('allowdp'); ?>" class="btn btn-app">
                <i class="fas fa-exchange-alt"></i> <b><span>Allow no DP</span></b>
              </a>
            </li>
          </div>
        </div>
      </div>

      <!--  -->

      <div class="col-md-12">
        <div class="card bg-primary">
          <div class="card-header bg-primary">
            <h3 class="card-title"><i style="color: #ffc107" class="fas fa-coins"></i> <b>Payment Setup</b></h3>
          </div>
          <div class="card-body" style="overflow-y: auto">
            <li class="nav-item has-treeview 
              <?php echo e((Request::Is('finance/itemclassification')) ? 'menu-open' : ''); ?>

              <?php echo e((Request::Is('finance/payitems')) ? 'menu-open' : ''); ?>

              <?php echo e((Request::Is('finance/modeofpayment')) ? 'menu-open' : ''); ?>

              <?php echo e((Request::Is('finance/mopnew')) ? 'menu-open' : ''); ?>

              <?php echo e((Request::Is('finance/mopedit/*')) ? 'menu-open' : ''); ?>

              <?php echo e((Request::Is('finance/fees')) ? 'menu-open' : ''); ?>

              <?php echo e((Request::Is('finance/feesnew')) ? 'menu-open' : ''); ?>

              <?php echo e((Request::Is('finance/feesedit/*')) ? 'menu-open' : ''); ?>

            ">
              <a href="<?php echo route('itemclassification'); ?>" class="btn btn-app">
                <i class="fas fa-cubes"></i> <b><span>Item Classification</span></b>
              </a>
              <a href="<?php echo route('payitems'); ?>" class="btn btn-app">
                <i class="fas fa-list-ul"></i> <b><span>Payments Items</span></b>
              </a>
              <a href="<?php echo route('modeofpayment'); ?>" class="btn btn-app">
                <i class="fas fa-money-bill-wave"></i> <b><span>Mode of Payment</span></b>
              </a>
              <a href="<?php echo route('fees'); ?>" class="btn btn-app">
                <i class="fas fa-map"></i> <b><span>Fees and Collection</span></b>
              </a>
            </li>
          </div>
        </div>
      <a href="/finance/onlinepay">
        <div class="info-box mb-3 bg-warning valpayment">
          <span class="info-box-icon"><i class="fas fa-gem"></i></span>
          
            <div class="info-box-content" style="cursor: pointer;">
              <span class="info-box-text text-lg">Unvalidated Online Payment</span>
              <span class="info-box-number text-lg viewolpaycount"><?php echo e(App\FinanceModel::countOnlinePayment()); ?></span>
            </div>
          
          <!-- /.info-box-content -->
        </div>    
      </a>

      <?php if(auth()->user()->type == 15 || auth()->user()->email == 'ckgroup'): ?>
        <a href="/finance/setup">
          <div class="info-box mb-3 bg-info valpayment">
            <span class="info-box-icon"><i class="fa fa-cogs"></i></span>
            
              <div class="info-box-content" style="cursor: pointer;">
                <span class="info-box-text text-lg">Finance Setup</span>
                
              </div>
            
            <!-- /.info-box-content -->
          </div>    
        </a>
      <?php endif; ?>

      
    </div>
  </div>
  <div class="col-lg-6">
    <div class="col-md-12 glevelindex ">
      <div class="card" style="height: 411px;">
        <div class="card-header bg-info">
			<div class="row form-group">
            <div class="col-md-12">
              <h3 class="card-title"><i style="color: #ffc107" class="fas fa-layer-group"></i> <b> ACTIVE GRADE LEVEL</b></h3>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <select id="sy" class="form-control sysem" style="width: 100%">
                <?php $__currentLoopData = db::table('sy')->orderBy('sydesc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php if($sy->isactive == 1): ?>
                    <option value="<?php echo e($sy->id); ?>" selected><?php echo e($sy->sydesc); ?></option>
                  <?php else: ?>
                    <option value="<?php echo e($sy->id); ?>"><?php echo e($sy->sydesc); ?></option>
                  <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>
            <div class="col-md-6">
              <select id="sem" class="form-control sysem" style="width: 100%">
                <?php $__currentLoopData = db::table('semester')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php if($sem->isactive == 1): ?>
                    <option value="<?php echo e($sem->id); ?>" selected><?php echo e($sem->semester); ?></option>
                  <?php else: ?>
                  <option value="<?php echo e($sem->id); ?>"><?php echo e($sem->semester); ?></option>
                  <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body pt-0" style="overflow-y: scroll">
        <table class="table table-hover table-sm text-sm p-0" style="table-layout:fixed;">
          <thead>
            <tr>
              <th class="text-left">Grade Level</th>
              <th class="text-center">Status</th>
              <th class="text-center">Enrolled Students</th>
            </tr>
          </thead>
          <tbody id="levellist">
            
          </tbody>
        </table>
      </div>
        <!-- /.card-body -->
        
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="info-box mb-3 bg-pink">
            <span class="info-box-icon"><i class="fas fa-user-check"></i></span>
            
            <div class="info-box-content" style="cursor: pointer;">
              <span class="info-box-text text-lg">Ready to Enroll</span>
              <span id="stud_readytoenroll" class="info-box-number text-lg">
                0
              </span>
            </div>
          </div>    
        </div>

        <div class="col-md-6">
          <div class="info-box mb-3 bg-olive">
            <span class="info-box-icon"><i class="fas fa-users"></i></span>
            
            <div class="info-box-content" style="cursor: pointer;">
              <span class="info-box-text text-lg">Enrolled Students</span>
              <span class="info-box-number text-lg">
                <?php echo e(App\FinanceModel::enrolledstudcount(App\FinanceModel::getSYID(), App\FinanceModel::getSemID())); ?>

              </span>
            </div>
          </div>    
        </div>
      </div>
    </div>
  </div>

  
  </div>
  </div>
  	
  </section>

  <?php $__env->stopSection(); ?>
  <?php $__env->startSection('modal'); ?>

  <div class="modal fade" id="modal-readytoenroll" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-pink">
                <h5 class="modal-title" id="exportmodalLabel">Ready to Enroll</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row form-group">
                    <div class="col-md-12 table-responsive" style="height: 18em">
                        <table class="table table-sm text-sm table-striped">
                            <thead>
                                <tr>
                                    <th>NAME</th>
                                    <th>DATE</th>
                                    <th>OR #</th>
                                    <th class="text-center">AMOUNT</th>
                                </tr>
                            </thead>
                            <tbody id="stud_readylist"></tbody>
                        </table>
                    </div>
                </div>  
            </div>
            <div class="modal-footer">
              <div class="col-md-6">
                  <button class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
              </div>
              <div class="col-md-6 text-right">
                  
              </div>
            </div>
        </div>
    </div>
  </div>

  <?php $__env->stopSection(); ?>
  <?php $__env->startSection('js'); ?>
    <script>
        $(document).ready(function(){
            actvglvlload()
            readytoenroll()

            function actvglvlload()
            {
                var syid = $('#sy').val()
                var semid = $('#sem').val()
                $.ajax({
                    type: "GET",
                    url: "<?php echo e(route('actvglvlload')); ?>",
                    data: {
                        syid:syid,
                        semid:semid
                    },
                    // dataType: "dataType",
                    success: function (data) {
                        // console.log(data)  
                        $('#levellist').html(data)
                    }
                })
            }

            function readytoenroll()
            {
                $.ajax({
                    type: "GET",
                    url: "<?php echo e(route('readytoenroll_load')); ?>",
                    // data: "data",
                    // dataType: "dataType",
                    success: function (data) {
						var counter = 0;
                        $('#stud_readylist').empty()
                        $.each(data, function (index, value) { 
							counter +=1;
                            $('#stud_readylist').append(`
                                <tr>
                                    '<td>`+value.lastname+`, `+value.firstname+` `+value.middlename+` `+value.suffix+`</td>'
                                    '<td>`+value.transdate+`</td>'
                                    '<td>`+value.ornum+`</td>'
                                    '<td class="text-right">`+value.amount+`</td>'
                                </tr>
                            `)
                        });

						$('#stud_readytoenroll').text(counter)
                    }
                });
            }

            $(document).on('change', '.sysem', function(){
                actvglvlload()
            })

            $(document).on('click', '#stud_readytoenroll', function(){
                $('#modal-readytoenroll').modal('show')
            })
        });

    </script>
  <?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\es_ldcu\resources\views/finance/index.blade.php ENDPATH**/ ?>