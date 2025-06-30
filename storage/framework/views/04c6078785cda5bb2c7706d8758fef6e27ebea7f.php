<?php
    $levelname = '';

    $level = db::table('gradelevel')->where('id', $levelid)->first();

    if ($level) {
        $levelname = $level->levelname;
    }

    $sinfo = db::table('schoolinfo')->first();

    $schoolname = $sinfo->schoolname;
    $schooladdress = $sinfo->address;
    $picurl = explode('?', $sinfo->picurl);

    $cursy = '';

    if ($levelid < 17) {
        $cursy = $selectedschoolyear;
    } else {
        $cursy = $selectedschoolyear . ' - ' . $selectedsemester;
    }

    $oldclassid = db::table('balforwardsetup')->first()->classid;
    $oldamount = 0;
    $totalcharges = 0;
    $totalpayment = 0;
    $totalbalance = 0;

?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        .roundtd {
            border: solid #6CC57EFF;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <table style="font-size: 14; font-weight: bold; font-family: Sans-serif; padding-top: 5px; width: 100%;">
        <tr>
            <td rowspan="2" style="text-align:right; width: 25%; ">
                <img src="<?php echo e($picurl[0]); ?>" style="width: 80px;">
                
            </td>
            <td style="text-align: center; width: 50%; padding-top: 20px;">
                <?php echo e($schoolname); ?>

            </td>
            <td rowspan="2" style="text-align: left; width: 25%;">
                <img src="assets/images/iso.png" style="width: 90px;"><br>
            </td>
        </tr>
        <tr>
            <td style="text-align: center; width: 100%; font-size: 8; font-weight: normal; padding-bottom: 30px;">
                <?php echo e($schooladdress); ?>

            </td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: right;font-weight: normal; padding-top: 0px; padding-right: 10px;">
                <?php
                    $schoolinfo = db::table('schoolinfo')->first();
                    $iso = $schoolinfo->iso ?? '_____________';
                ?>
                <span style="font-size: 10px; transform: translateY(-10px);">Registration No.
                    <b><?php echo e($iso); ?></b></span>
            </td>
        </tr>


        <tr>
            <td colspan="3" style="text-align: center;">
                STATEMENT OF ACCOUNT <br>
                <span style="font-size: 9; font-weight: normal;">
                    <?php echo e($cursy); ?>

                </span>
            </td>
        </tr>
    </table>


    <table style="font-family: Sans-serif; font-size: 11px; font-weight: bold; padding-top: 5px; width: 100%;">
        <tr>
            <td>Student ID: <?php echo e($studinfo->sid); ?></td>
            <td style="text-align: right;">
                Date: <?php echo e(date_format(date_create(\App\FinanceModel::getServerDateTime()), 'm/d/Y h:i A')); ?>

            </td>
        </tr>
        <tr>
            <td>Student Name:
                <?php echo e($studinfo->lastname . ', ' . $studinfo->firstname . ' ' . $studinfo->middlename . ' ' . $studinfo->suffix); ?></td>
        </tr>
        <?php if($levelid < 17): ?>
            <td>Level|Section: <?php echo e($levelname); ?> - <?php echo e($sectionname); ?>

            <?php else: ?>
            <td>Level|Course: <?php echo e($levelname); ?> - <?php echo e($courseabrv); ?>

        <?php endif; ?>
    </table>
    <br />
    <table cellspacing="0" cellpadding="1"
        style="font-size: 12px; width: 100%; font-family: Sans-serif; border-top: solid; border-bottom: solid;">
        <tr>
            <td style="width: 50%; text-align: center; border-right: solid; vertical-align: top;">
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <td colspan="3" class="roundtd"
                                style="width: 100%; text-align: center; font-weight: bold; padding: 5px; background-color: #6CC57EFF;">
                                TOTAL CHARGES
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left; width: 3em; font-weight: bold;">
                                DATE
                            </td>
                            <td style="text-align: center; width: 12em; font-weight: bold;">
                                DESCRIPTION
                            </td>
                            <td style="text-align: center; width: 3em; font-weight: bold;">
                                AMOUNT
                            </td>
                        </tr>
                    </thead>
                    <tbody style="font-size: 9px;">
                        <?php $__currentLoopData = $ledger; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($l->classid != $oldclassid): ?>
                                <?php
                                    $totalcharges += $l->amount;
                                ?>
                                <tr>
                                    <td><?php echo e(date_format(date_create($l->createddatetime), 'm-d-Y')); ?></td>
                                    <td><?php echo e(strtoupper($l->particulars)); ?></td>
                                    <td style="text-align: right;"><?php echo e(number_format($l->amount, 2)); ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td colspan="2" style="text-align: right; font-weight: bold;padding-top: 10px;">TOTAL:
                            </td>
                            <td style="text-align: right; font-weight: bold; border-top: solid; padding-top: 10px;">
                                <?php echo e(number_format($totalcharges, 2)); ?></td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td style="width: 50%; text-align: center; vertical-align: top;">
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <td colspan="3" class="roundtd"
                                style="width: 100%; text-align: center; font-weight: bold; padding: 5px; background-color: #6CC57EFF;">
                                OLD ACCOUNTS
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left; width: 100%; font-weight: bold;">
                                DATE
                            </td>
                            <td style="text-align: center; width: 100%; font-weight: bold;">
                                DESCRIPTION
                            </td>
                            <td style="text-align: center; width: 100%; font-weight: bold;">
                                AMOUNT
                            </td>
                        </tr>
                    </thead>
                    <tbody style="font-size: 9px;">

                        <?php $__currentLoopData = $ledger; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($l->classid == $oldclassid): ?>
                                <?php
                                    $oldamount += $l->amount;
                                ?>
                                <tr>
                                    <td><?php echo e(date_format(date_create($l->createddatetime), 'm-d-Y')); ?></td>
                                    <td><?php echo e(strtoupper($l->particulars)); ?></td>
                                    <td style="text-align: right;"><?php echo e(number_format($l->amount, 2)); ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td colspan="2" style="text-align: right; font-weight: bold; padding-top: 10px;">TOTAL:
                            </td>
                            <td style="text-align: right; font-weight: bold; border-top: solid; padding-top: 10px;">
                                <?php echo e(number_format($oldamount, 2)); ?></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    <br>
    <table cellspacing="0" cellpadding="1" style="font-size: 12px; width: 100%; font-family: Sans-serif;">
        <tr>
            <td style="width: 50%;">

            </td>
            <td style="width: 50%;">
                <table style="width: 100%;">
                    <tbody style="font-size: 13px;">
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                
                                ACTUAL ASSESSMENT: <br> <span style="font-size: 9px;">(Old Account + Total
                                    Charges)</span>

                            </td>
                            <td style="text-align: right; font-weight: bold; vertical-align: top;">
                                <?php
                                    $sumpay = collect($payments)->sum('payment');
                                    // $total = ($totalcharges + $oldamount) - $sumpay;
                                    $total = $totalcharges + $oldamount;
                                ?>
                                <?php echo e(number_format($total, 2)); ?>

                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center; font-size: 9px; padding-top: -10px;">

                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    <br />

    <table cellspacing="0" cellpadding="1" style="font-size: 12px; width: 100%; font-family: Sans-serif;">
        <tr>
            <td colspan="5" class="roundtd"
                style="width: 100%; text-align: center; font-weight: bold; padding: 5px; background-color: #6CC57EFF;">
                PAYMENT
            </td>
        </tr>
    </table>
    <table cellspacing="0" cellpadding="1"
        style="font-size: 12px; width: 100%; font-family: Sans-serif; border-top: solid; margin-top: 2px;">
        <thead>
            <tr>
                <td style="text-align: left; width: 10%; font-weight: bold;">
                    DATE
                </td>
                <td style="text-align: left; width: 10%; font-weight: bold;">
                    OR #
                </td>
                <td style="text-align: center; width: 50%; font-weight: bold;">
                    DESCRIPTION
                </td>
                <td style="text-align: center; width: 10%; font-weight: bold;">
                    PAYMENT
                </td>
            </tr>
        </thead>
        <tbody style="font-size: 9px;">
            <?php
                $totalbalance = $totalcharges + $oldamount;
            ?>
            <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $totalbalance -= $pay->payment;
                    $totalpayment += $pay->payment;
                ?>
                <tr>
                    <td><?php echo e(date_format(date_create($pay->createddatetime), 'm-d-Y')); ?></td>
                    <td><?php echo e(strtoupper($pay->ornum)); ?></td>
                    <td><?php echo e(strtoupper($pay->particulars)); ?></td>
                    <td style="text-align: right;"><?php echo e(number_format($pay->payment, 2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


        </tbody>
        <tfoot style="border-top: 1px solid black !important;">
            <tr>
                <td colspan="3" style="text-align: right; padding-top:2px">
                    TOTAL: <br> <span style="font-size: 9px;"></span>

                </td>
                <td style="text-align: right; font-weight: bold; vertical-align: top; padding-top: 2px;">
                    <?php echo e(number_format($totalpayment, 2)); ?>

                </td>
            </tr>
        </tfoot>
    </table>
    <br>

    <!--
    <table  cellspacing="0" cellpadding="1" style="font-size: 12px; width: 100%; font-family: Sans-serif;">
        <tr>
            <td width="50%"></td>
            <td style="width: 50%;">
                <table style="width: 100%;">
                    <tbody style="font-size: 13;">
                        
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                TOTAL PAYMENT: <br> <span style="font-size: 9px;"></span>

                            </td>
                            <td style="text-align: right; font-weight: bold; vertical-align: top;">
                                <?php echo e(number_format($totalpayment, 2)); ?>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table> -->

    <!--
    <table  cellspacing="0" cellpadding="1" style="font-size: 12px; width: 50%; font-family: Sans-serif;">
        <tbody style="font-size: 13;">
            
            <tr>
                <td colspan="2" style="text-align: center; background-color: #6CC57EFF; padding: 10px; width: 85%;">
                    TOTAL DUE FOR THE MONTH:<br>
                    <span style="font-size: 12px; font-weight: bold;">
                        (<?php echo e($monthinword); ?>)
                    </span>

                </td>
                <td colspan="2" style="text-align: right; background-color: #6CC57EFF; padding: 10px; width: 15%; vertical-align: top;">
                    <?php
                        $amountdue = 0;
                        $amountdue_not_tuition = 0;
                        foreach ($monthdue as $due) {
                            if (date('n', strtotime($due->duedate)) == $monthsetup['monthid']) {
                                $amountdue_not_tuition += str_replace(',', '', $due->paymentnotuition);
                            }
                            $amountdue += str_replace(',', '', $due->balance);
                        }
                    ?>
                    <b><?php echo e(number_format($amountdue + $amountdue_not_tuition, 2)); ?></b>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; font-size: 9px; padding-top: -10px;">

                </td>
            </tr>
        </tbody>
    </table>

-->



    <br>
    <table style="margin-bottom: 5px">
        <tr style="font-family: Sans-serif; font-size: 13px;">
            <td>
                RUNNING BALANCE: <b><?php echo e(number_format($totalbalance, 2)); ?></b>
            </td>
        </tr>
    </table>
    <table cellspacing="0" cellpadding="1" style="font-size: 12px; width: 100%; font-family: Sans-serif;">
        <tr>
            <td colspan="5" class="roundtd"
                style="width: 100%; text-align: center; font-weight: bold; padding: 5px; background-color: #6CC57EFF;">
                MONTHLY ASSESSMENT
            </td>
        </tr>
    </table>
    <table cellspacing="0" cellpadding="1"
        style="font-size: 12px; width: 100%; font-family: Sans-serif; border-top: solid; margin-top: 2px;">
        <thead>
            <tr>
                
                <td style="text-align: left; width: 30%; font-weight: bold;">
                    PARTICULARS
                </td>
                <td style="text-align: right; width: 15%; font-weight: bold;">
                    AMOUNT
                </td>
                <td style="text-align: right; width: 15%; font-weight: bold;">
                    BALANCE
                </td>
            </tr>
        </thead>
        <tbody style="font-size: 10px;">
            

            <?php
                use Carbon\Carbon;

                $currentMonth = Carbon::now()->format('m');
                $currentYear = Carbon::now()->format('Y');
                $payment = floatval($totalpayment); // Total payment available
                $carriedBalance = 0;
                $foundCurrentMonth = false;
                $runningbalance = 0;
                $updatedMonthDue = [];

                foreach ($monthdue as $due) {
                    $dueMonth = Carbon::parse($due->duedate)->format('m');
                    $dueYear = Carbon::parse($due->duedate)->format('Y');

                    $due = clone $due; // clone to avoid mutating original

                    if (!$foundCurrentMonth && $dueYear == $currentYear && $dueMonth == $currentMonth) {
                        // This is the current month: add carried balance
                        $due->balance = floatval($due->balance) + $carriedBalance;
                        $carriedBalance = 0;
                        $foundCurrentMonth = true;
                    } elseif (
                        !$foundCurrentMonth &&
                        Carbon::parse($due->duedate)->lt(Carbon::create($currentYear, $currentMonth, 1))
                    ) {
                        // This is a past due: zero its balance and carry forward
                        $carriedBalance += floatval($due->balance);
                        $due->balance = 0;
                    }

                    $updatedMonthDue[] = $due;
                }

                // Apply payment to updated dues
                foreach ($updatedMonthDue as $due) {
                    $balance = floatval($due->balance);

                    if ($payment >= $balance) {
                        $payment -= $balance;
                        $due->balance = 0;
                    } else {
                        $due->balance = $balance - $payment;
                        $payment = 0;
                    }

                    $runningbalance += $due->balance;
                }
            ?>


            <?php $__currentLoopData = $updatedMonthDue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $due): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e(strtoupper($due->particulars)); ?></td>
                    <td style="text-align: right;"><?php echo e(number_format($due->amount, 2)); ?></td>
                    <td style="text-align: right;"><?php echo e(number_format($due->balance, 2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td colspan="2" style="text-align: right; border-top: solid; font-size: 13px;">TOTAL DUE:</td>
                <td style="text-align: right; font-weight: bold; border-top: solid; font-size: 13px;">
                    <?php echo e(number_format($runningbalance, 2)); ?>

                </td>
            </tr>

        </tbody>

    </table>


    <footer style="position: fixed; bottom: 50px;">
        <table cellspacing="0" cellpadding="1" style="font-size: 12px; font-family: Sans-serif;" width="100%">
            <thead>
                <tr>
                    <td style="text-align: left;">Prepared By:</td>
                    <td style="text-align: left;">Received By:</td>
                </tr>
            </thead>
            <tr>
                <td>
                    <table style="width: 50%" cellpadding="5">
                        <tr>
                            <td style="border-bottom: 1px solid; text-align: center; font-weight: bold; ">
                                <?php
                                    $incharge = DB::table('users')
                                        ->select(
                                            'teacher.firstname',
                                            'teacher.lastname',
                                            'teacher.middlename',
                                            'teacher.suffix',
                                            'users.name',
                                        )
                                        ->leftJoin('teacher', 'users.id', '=', 'teacher.userid')
                                        ->where('users.deleted', 0)
                                        ->where('users.id', auth()->user()->id)
                                        ->first();

                                    $fullname = '';
                                    if ($incharge) {
                                        if ($incharge->firstname && $incharge->lastname) {
                                            $fullname = strtoupper(
                                                $incharge->firstname .
                                                    ' ' .
                                                    ($incharge->middlename ? $incharge->middlename[0] . '. ' : '') .
                                                    $incharge->lastname .
                                                    ' ' .
                                                    $incharge->suffix,
                                            );
                                        } else {
                                            $fullname = strtoupper($incharge->name);
                                        }
                                    }
                                    // dd($fullname);
                                ?>
                                <?php echo e($fullname); ?>

                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">
                                
                                Student Account In-Charge
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table style="width: 80%" cellpadding="5">
                        <tr>
                            <td style="border-bottom: 1px solid black;height: 20px;">

                            </td>
                        </tr>
                        <tr>
                            <td style="text-transform: uppercase; font-weight: bold; border-bottom: 1px solid black;">
                                Date:
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </footer>
</body>
<?php /**PATH C:\laragon\www\es_ldcu\resources\views/finance/reports/pdf/pdf_statementofacct_default_v2.blade.php ENDPATH**/ ?>