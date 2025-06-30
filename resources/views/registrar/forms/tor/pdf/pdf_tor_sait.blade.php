<html>

<head>
    <title>Transcript of Records</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        table {
            border-collapse: collapse;
        }

        @page {
            margin: 0.25in 0.5in !important;
            size: 8.5in 13in
        }

        #watermark1 {
            opacity: 0.1;
            position: absolute;
            /* bottom:   0px; */
            /* left:     -70px; */
            /** The width and height may change
                    according to the dimensions of your letterhead
                **/
            /* width:    21.5cm; */
            /* height:   28cm; */

            /** Your watermark should be behind every content**/
            z-index: -2000;
        }

        header {
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0px;
            height: 250px;
        }

        footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 50px;
        }
    </style>
</head>

<body style="padding-top: 170px; padding-bottom: 100px;">
    @php
        $initialschool = null;
        $initialcourse = null;

        $firstcountrows = 0;
        $firstrowsperpage = 20;
        $countrows = 0;
        $rowsperpage = 60;
        // dd($remarks);
        $schoolfrom = null;

        $avatar = 'assets/images/avatars/unknown.png';
    @endphp
    <div id="watermark1" style="width: 100%; padding-top: 250px; text-align: center; position: fixed;">
        <img src="{{ base_path() }}/public/{{ $schoolinfo->picurl }}" height="700px" width="700px" />
    </div>

    <footer style=" font-family: Arial, Helvetica, sans-serif !important;">
        <table style="width: 100%; font-size: 11px;margin-bottom: 2px;">
            <tr>
                <td width="10%">Date printed:</td>
                <td width="20%" style="text-align: center; border-bottom: 1px solid black;">{{ date('m/d/Y h:i A') }}
                </td>
                <td></td>
                <td></td>

            </tr>
        </table>
    </footer>

    <header style="position: fixed; top: 0px; left: 0px; right: 0px; height: 250px;">
        <table style="width: 100%; table-layout: fixed;">
            <tr>
                <td rowspan="3" style="width: 25%; vertical-align: top;">
                    {{-- {{ base_path() }}/public/assets/images/otherlogo.jpg --}}
                    <img src="#" alt="school" width="100px" />
                    <img src="{{ base_path() }}/public/{{ $schoolinfo->picurl }}" alt="school" width="100px" />
                </td>
                <td style="text-align: center; font-weight: bold; vertical-align: bottom; font-size: 18px;">
                    {{ str_replace('Incorporated', 'Inc.', DB::table('schoolinfo')->first()->schoolname) }}</td>
                <td rowspan="5" style="width: 15%;">
                    @if ($getphoto && file_exists(public_path($getphoto->picurl)))
                        <img src="{{ URL::asset($getphoto->picurl . '?random="' . \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss')) }}"
                            style="width: 100%; margin: 0px; position: absolute;" />
                    @else
                        @php
                            if (strtoupper($studentinfo->gender) == 'FEMALE') {
                                $avatar = 'avatar/S(F) 1.png';
                            } else {
                                $avatar = 'avatar/S(M) 1.png';
                            }
                            $picExists = !empty($studentinfo->picurl) && file_exists(public_path($studentinfo->picurl));
                        @endphp

                        @if ($picExists)
                            <img src="{{ asset($studentinfo->picurl) }}" alt="student"
                                style="width: 100%; margin: 0px; position: absolute;">

                            <img id="student-pic" src="{{ asset($studentinfo->picurl) }}"
                                onerror="this.style.display='none'; document.getElementById('fallback-div').style.display='block';"
                                onload="this.style.border='none';" alt="student pic"
                                style="width: 1.2in; height: 1.2in; margin: 0px; position: absolute; background-size: cover !important; background-position: center; background-repeat: no-repeat;">

                            <div id="fallback-div"
                                style="display: none; width: 1.2in; height: 1.2in; position: absolute; border: 1px solid black; background: gray; text-align: center; line-height: 1.5in;">
                                No Image
                            </div>
                        @else
                            <div
                                style="width: 1.2in; height: 1.2in; margin: 0px; position: absolute; border: 1px solid black; 
                        background-size: cover !important; background-position: center; background-repeat: no-repeat;">
                            </div>
                        @endif

                    @endif
                </td>
            </tr>
            <tr>
                <td style="text-align: center; font-size: 10.5px;">
                    @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait')
                        {{ DB::table('schoolinfo')->first()->address }}<br />Tel. no. 828-6058 Email Add: <u
                            style="color:blue;">saitvalencia1960@gmail.com</u> <br />Website: https://www.sait.edu.ph
                    @else
                        {{ DB::table('schoolinfo')->first()->address }}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 12px;">
                    <span>OFFICE OF THE REGISTRAR</span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: center; font-weight: bold; ">OFFICIAL TRANSCRIPT OF
                    RECORD</td>
            </tr>
        </table>
        <div style="border-bottom: 4px solid #4a7ebb;"></div>
    </header>

    <table
        style="width: 100%; font-size: 12px;border-collapse: collapse; table-layout: fixed; border: 1px solid black; margin: 0px">
        <tr>
            <td width="20%" style="background-color: black; color: white; text-align: center;padding:2px;">
                <b>PERSONAL DATA</b>
            </td>
            <td width="30%"> </td>
            <td width="5%"></td>
            <td width="15%"> </td>
            <td width="30%"></td>
        </tr>
        <tr>
            <td width="20%" style="padding-left: 5px; padding-top: 10px"><b>Name:</b></td>
            <td width="30%"> {{ $studentinfo->firstname }} {{ $studentinfo->middlename }}.
                {{ $studentinfo->lastname }}, {{ $studentinfo->suffix }}</td>
            <td width="5%"></td>
            <td width="15%" style="text-align: left;"> <b>Student ID No. :</b> </td>
            <td width="30%"> {{ $studentinfo->sid }} </td>
        </tr>
        <tr>
            <td style="padding-left: 5px"><b>Date of Birth:</b></td>
            <td> {{ $studentinfo->dob != null ? date('j F Y', strtotime($studentinfo->dob)) : '' }} </td>
            <td></td>
            <td> <b>Special Order No.:</b> </td>
            <td> </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2"><b>NSTP Serial Number:  {{$details->nstpserialno ?? ''}}</b></td>
        </tr>
        <tr>
            <td style="padding-left: 5px"><b>Place of Birth:</b></td>
            <td> {{ $studentinfo->pob }} </td>
            <td></td>
            <td> <b>Course:</b> </td>
            <td style="font-size:7pt!important"> {{ $records[0]->coursename }} </td>
        </tr>
        <tr>
            <td style="padding-left: 5px"><b>Permanent Address:</b></td>
            <td colspan="4">{{ $studentinfo->street }}, {{ $studentinfo->barangay }},
                {{ $studentinfo->city }},
                {{ $studentinfo->province }}
            </td>
        </tr>
        <tr>
            <td style="padding-left: 5px; padding-bottom: 5px"><b>Nationality:</b></td>
            <td> {{ $studentinfo->nationality }} </td>
            <td></td>
            <td> <b>Date Graduated:</b> </td>
            <td> {{ $details->graduationdate }} </td>
        </tr>
    </table>

    <table
        style="width: 100%; font-size: 12px;border-collapse: collapse; table-layout: fixed; border: 1px solid black;margin: 0px; transform: translateY(-2px);">
        <tr>
            <td width="20%" style="background-color: black; color: white; text-align: center;padding:2px;">
                <b>ENTRANCE</b>
            </td>
            <td width="30%"> </td>
            <td width="5%"></td>
            <td width="15%"> </td>
            <td width="30%"></td>
        </tr>
        <tr>
            <td colspan="5" style="padding-left: 5px; padding-top: 10px"><b>Date / Term and School Year Admitted:</b>
                @if ($enrollment_his && $enrollment_his->created_at != null)
                    {{ date('F j, Y, g:i A', strtotime($enrollment_his->created_at)) }}
            </td>
            @endif
        </tr>
        <tr>
            <td colspan="5">
                <table style="table-layout: fixed;" width="100%">
                    <tr>
                        <td width="15%" style="padding-left: 5px; "><b>Category: </b></td>
                        <td width="5%" style="text-align: right !important;">
                            <input type="checkbox" name="category" @if ($details->glits == 14 || $details->glits == 15) checked @endif>
                        </td>
                        <td width="10%"> SHS </td>
                        <td width="5%">
                            <input type="checkbox" name="category" @if ($details->glits == 26) checked @endif>
                        </td>
                        <td width="25%" style="white-space: nowrap;">Vocation Level/Graduate</td>
                        <td width="5%">
                            <input type="checkbox" name="category" @if ($details->glits >= 17 && $details->glits <= 25) checked @endif>
                        </td>
                        <td width="15%" style="white-space: nowrap;">College Level</td>
                        <td width="5%">
                            <input type="checkbox" name="category" @if ($iscollege_grad) checked @endif>
                        </td>
                        <td width="15%" style="white-space: nowrap;">College Graduate</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="5" style="padding-left: 5px;"><b>Junior High School Completed at:</b> {{ $details->juniorschoolname ?? '' }}</td>
        </tr>
        <tr>
            <td colspan="5" style="padding-left: 5px;"><b>Senior High School Completed at:</b> {{ $details->seniorschoolname ?? '' }}</td>
        </tr>
        <tr>
            <td colspan="5" style="padding-left: 5px;"><b>Last School Attended:</b>
                {{ $studentinfo->lastschoolatt }} </td>
        </tr>
        <tr>
            <td colspan="5" style="padding-left: 5px;padding-bottom: 5px"><b>Date Graduated / Last Term and School
                    Year Attended:</b> {{ $lastterm }} </td>
        </tr>

    </table>


    <table style="width: 100%; font-size: 12px; margin-top: 5px; page-break-inside: always; page-break-before: avoid;" border="1">
        <thead>
            <tr>
                <th style="width: 13%;">SUBJECTS<br />& NUMBERS</th>
                <th>DESCRIPTIVE TITLE</th>
                <th style="width: 12%;">FINAL<br />GRADE</th>
                <th style="width: 12%;">CREDITS</th>
            </tr>
        </thead>
        @if (count($records) > 0)
            @php
                $lastSchool = null;
            @endphp

            @foreach ($records as $record)
                @php
                    $record->subjdata = collect($record->subjdata)->unique('subjcode');
                @endphp

                @if (count($record->subjdata) > 0)
                    {{-- Semester Header --}}
                    <tr style="font-size: 12px;">
                        <td colspan="4" style="background-color: #ddd; font-weight: bold; text-align: center;">
                            {{-- Show school name only if it has changed --}}
                            @if (strtolower($lastSchool) != strtolower($record->schoolname))
                                {{ strtoupper($record->schoolname) }}<br>
                                @php
                                    $lastSchool = $record->schoolname;
                                @endphp
                            @endif

                            AY {{ $record->sydesc }}
                            @if ($record->semid == 1)
                                1st Semester
                            @elseif($record->semid == 2)
                                2nd Semester
                            @else
                                Summer
                            @endif
                        </td>
                    </tr>

                    @php
                        $firstcountrows += 1;
                    @endphp

                    {{-- Subject Rows --}}
                    @foreach ($record->subjdata as $subj)
                        <tr style="font-size: 12px;">
                            <td style="padding-left: 5px; border-top: hidden; border-bottom: hidden;">
                                {{ $subj->subjcode }}</td>
                            <td style="border-top: hidden; border-bottom: hidden;">{{ $subj->subjdesc }}</td>
                            <td style="border-top: hidden; border-bottom: hidden; text-align: center;">
                                {{ $record->type != "manual" && isset($subj->subjgrade) ? number_format($subj->subjgrade, 1) : $subj->subjgrade ?? '' }}</td>
                            <td style="border-top: hidden; border-bottom: hidden; text-align: center;">
                                {{ $subj->subjcredit > 0 ? $subj->subjcredit : $subj->subjunit }}</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
            

            @if ($schoolfrom)
                <tr>
                    <td style="text-align: center;font-size: 10px; padding-top: 5px;padding-bottom: 5px;"
                        colspan="4">PLEASE SEE ATTACHED CERTIFIED TRUE COPY OF TRANSCRIPT OF RECORDS FROM
                        {{ $schoolfrom }} </td>
                </tr>
            @endif

            <tr>
                <td style="text-align: center; font-size: 10px;">
                    REMARKS
                </td>
                <td colspan="3" style="font-size: 11px;">
                    <div style="padding: 5px">
                        @if ($details->graduationdate)
                            <span>GRADUATED {{ $details->degree }} LAST {{ $details->graduationdate }}
                                <br></span>
                        @endif
                        <span>{{ $remarks ?? '' }} <br> </span>
                        <span>{{ $purpose ?? '' }}</span>
                    </div>
                </td>
            </tr>


        @endif
    </table>


    <table style="table-layout: fixed; width: 100%;">
        <tr>
            <td style="text-align: center; font-size: 12px; padding: 2px;">
                ANY ALTERATIONS / MODIFICATIONS MADE IN THIS DOCUMENT NULLIFY THE ENTIRE TRANSCRIPT OF RECORDS
            </td>
        </tr>
    </table>

    <table style="width: 100%; font-size: 12px; margin-bottom: 2px; margin-top: 10px;">
        <tr>
            <th style="vertical-align: top; width: 20%;">Grading System:</th>
            <td>
                <strong>Grade - Equivalent:</strong><br>
                100 - 1.0, 99 - 1.1, 98 - 1.2, 97 - 1.3, 96 - 1.4, 95 - 1.5, 94 - 1.6, 93 - 1.7, 92 - 1.8, 91 - 1.9 <br>
                90 - 2.0, 89 - 2.1, 88 - 2.2, 87 - 2.3, 86 - 2.4, 85 - 2.5, 84 - 2.6, 83 - 2.7, 82 - 2.8, 81 - 2.9 <br>
                80 - 3.0, 79 - 3.1, 78 - 3.2, 77 - 3.3, 76 - 3.4, 75 - 3.5, 74 - 5.0 <br>
                <strong>Special Grades:</strong><br>
                Dropped - 9.0, Withdrawn (W)
            </td>
        </tr>
        <tr>
            <td style="text-align: center; font-size: 14px; padding-top: 10px;" colspan="2">
                <u>CERTIFICATION</u>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                I hereby certify that the foregoing record of
                <u style="font-weight: bold;">{{ $studentinfo->firstname }} {{ $studentinfo->middlename }}
                    {{ $studentinfo->lastname }} {{ $studentinfo->suffix }}</u>
                has been verified by me and that true copies of the official records substantiating the same are kept in
                the files of the school.
            </td>
        </tr>
    </table>

    <table style="width: 100%; font-size: 12px; margin-bottom: 2px; margin-top: 5px;">
        <tr>
            <td style="width: 10%;">Prepared by:</td>
            <td style="width: 30%; text-align: center; border-bottom: 1px solid black;">{{ $assistantreg }}</td>
            <td style="width: 5%;"></td>
            <td style="text-align: center; border-bottom: 1px solid black;">
                @if ($dateissued != null)
                    <span style="font-weight: bold;">{{ date('m/d/Y', strtotime($dateissued)) }}</span>
                @endif
            </td>
            <td style="width: 5%;"></td>
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'stii')
                <td style="width: 30%; text-align: center; border-bottom: 1px solid black; font-weight: bold;">ERWIN B. CENAS</td>
            @else
                <td style="width: 30%; text-align: center; border-bottom: 1px solid black; font-weight: bold;">{{ $registrar }}</td>
            @endif
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center;">Assistant Registrar</td>
            <td></td>
            <td style="text-align: center;">Date of Issuance</td>
            <td></td>
            <td style="text-align: center;">School Registrar</td>
        </tr>
        <tr>
            <td colspan="6" style="white-space: nowrap;">Not valid without school seal</td>
        </tr>
    </table>


</body>

</html>
