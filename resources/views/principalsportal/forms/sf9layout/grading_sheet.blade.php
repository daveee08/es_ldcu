<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Summary</title>
    <style>
        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            font-size: 11px;
        }

        table {
            border-collapse: collapse;
        }

        .table thead th {
            vertical-align: bottom;
        }

        .table td,
        .table th {
            padding: .75rem;
            vertical-align: top;
        }

        .table-bordered {
            border: 1px solid #00000;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #00000;
        }

        .table-sm td,
        .table-sm th {
            padding: .3rem;
        }

        .text-center {
            text-align: center !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-left {
            text-align: left !important;
        }

        .p-0 {
            padding: 0 !important;
        }

        .p-1 {
            padding: .25rem !important;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .border-bottom {
            border-bottom: 1px solid black;
        }

        .mb-1,
        .my-1 {
            margin-bottom: .25rem !important;
        }

        body {
            font-family: Calibri, sans-serif;
        }

        .align-middle {
            vertical-align: middle !important;
        }

        .grades td {
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: 11px !important;
            font-family: Arial, sans-serif;
        }

        .grades-header td {
            font-size: .6rem !important;
        }

        .studentinfo td {
            padding-top: .1rem;
            padding-bottom: .1rem;
        }

        .text-red {
            color: red;
            border: solid 1px black;
        }

        .page_break {
            page-break-before: always;
        }

        @page {
            size: 8.5in 11in;
            margin: .25in;
        }
    </style>
</head>

<body>

    <table class="table grades " width="100%">
        <tr>
            <td style="text-align: right !important; vertical-align: top;" width="15%">
                <img src="{{ base_path() }}/public/{{ $scinfo->picurl }}" alt="school" width="70px">
            </td>
            <td style="width: 70%; text-align: center;" class="align-middle">
                <div style="width: 100%; font-weight: bold; font-size: 19px !important;">{{ $scinfo->schoolname }}</div>
                <div style="width: 100%; font-size: 12px;">{{ $scinfo->address }}</div>
                <div style="width: 100%; font-size: 12px;"></div>
            </td>
            <td width="15%">

            </td>
        </tr>
    </table>
    <table class="table grades" width="100%">
        <tr>
            <td class="text-center p-0">OFFICIAL GRADE SHEET</td>
        </tr>
        <tr>
            <td class="text-center p-0">[ {{ $schedinfo->subjCode ?? 'N/A' }} - {{ $schedinfo->subjDesc ?? 'N/A' }} ]
            </td>
        </tr>
    </table>
    <table class="table grades" width="100%">
        <tr>
            <td width="15%">Instructor:</td>
            <td width="35%">{{ $instructor }}</td>
            <td width="35%" class="text-right">School Year:</td>
            <td width="15%" class="text-right">{{ $syinfo->sydesc }}</td>
        </tr>
        <tr>
            <td>Schedule:</td>
            <td>
                @foreach ($time_list as $item)
                    [{{ $item->day }}] {{ $item->curtime }}
                    @if (!$loop->last)
                        <br>
                    @endif
                @endforeach
            </td>
            <td class="text-right">Semester:</td>
            <td class="text-right">{{ $seminfo->semester }}</td>
        </tr>
    </table>
    <table class="table grades table-bordered mb-0" width="100%">

        @php
            $col_count = count($gradesetup);
        @endphp

        <tr class="grades-header">
            <td width="2%" class="p-0 align-middle text-center"><b>#</b></td>
            <td width="19%" class=" p-1"><b>STUDENT</b></td>
            <td width="15%" class=" p-1"><b>COURSE</b></td>

            @foreach ($gradesetup as $grade)
                <td width="6%" class="p-0 align-middle text-center"><b>{{ strtoupper($grade->description) }}</b>
                </td>
            @endforeach

            <td width="9%" style="font-size:.55rem !important" class="p-0 align-middle text-center"><b>
                    REMARKS
                </b></td>
        </tr>
        @php
            $count = 1;
        @endphp
        @php
            $maleStudents = $students->filter(fn($student) => $student->gender === 'MALE');
            $femaleStudents = $students->filter(fn($student) => $student->gender === 'FEMALE');
            $count = 1;
        @endphp

        @foreach (['MALE' => $maleStudents, 'FEMALE' => $femaleStudents] as $gender => $studentsByGender)
            <tr>
                <td colspan="{{ $col_count + 4 }}" class="text-center"><b>{{ $gender }}</b></td>
            </tr>
            @foreach ($studentsByGender as $item)
                <tr>
                    <td class="align-middle">{{ $count }}</td>
                    <td class="align-middle p-1">{{ $item->lastname }}, {{ $item->firstname }}</td>
                    <td class="align-middle p-1">{{ $item->courseabrv }} -
                        {{ $item->levelid <= 21 ? $item->levelid - 16 : $item->levelid - 21 }}</td>

                    @foreach ($gradesetup as $grade_setup)
                        @php
                            $gradeValue = null;
                            if ($grade_setup->quarter == 1) {
                                $gradeValue = $grades->where('studid', $item->studid)->first()->prelim_transmuted ?? null;
                            } elseif ($grade_setup->quarter == 2) {
                                $gradeValue = $grades->where('studid', $item->studid)->first()->midterm_transmuted ?? null;
                            } elseif ($grade_setup->quarter == 3) {
                                $gradeValue = $grades->where('studid', $item->studid)->first()->prefinal_transmuted ?? null;
                            } elseif ($grade_setup->quarter == 4) {
                                $gradeValue = $grades->where('studid', $item->studid)->first()->final_transmuted ?? null;
                            }
                        @endphp

                        @php
                            $studentGrades = $grades->where('studid', $item->studid)->first();
                            $statuses = [
                                $studentGrades->prelim_transmuted ?? '',
                                $studentGrades->midterm_transmuted ?? '',
                                $studentGrades->prefinal_transmuted ?? '',
                                $studentGrades->final_transmuted ?? '',
                            ];
                        @endphp
                        @if (in_array('DROPPED', $statuses))
                            <td class="p-0 align-middle text-center" style="font-size:.75rem !important">DROPPED</td>
                        @else
                            <td class="p-0 align-middle text-center" style="font-size:.75rem !important">
                                {{ $gradeValue }}
                            </td>
                        @endif
                    @endforeach

                    <td class="p-0 align-middle text-center" style="font-size:.75rem !important">
                        @php
                            $studentGrades = $grades->where('studid', $item->studid)->first();
                            $statuses = [
                                $studentGrades->prelim_transmuted ?? '',
                                $studentGrades->midterm_transmuted ?? '',
                                $studentGrades->prefinal_transmuted ?? '',
                                $studentGrades->final_transmuted ?? '',
                            ];
                        @endphp

                        @if (in_array('DROPPED', $statuses))
                            DROPPED
                        @elseif (in_array('INC', $statuses))
                            INC
                        @else
                            {{ $studentGrades->final_remarks ?? null }}
                        @endif
                    </td>
                </tr>
                @php
                    $count += 1;
                @endphp
            @endforeach
        @endforeach

    </table>
    <table class="table grades" width="100%">
        <tr>
            <td width="100%" class="text-right"> <i style="font-size:.5rem !important">Date Generated:
                    {{ \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM DD, YYYY hh:mm a') }}</i></td>
        </tr>
    </table>

    <br>
    <br>
    <br>
    <table class="table grades" width="100%">
        <tr>
            <td width="5%"></td>
            <td width="40%" class="text-center border-bottom">{{ $instructor }}</td>
            <td width="10%"></td>
            <td width="40%" class="text-center border-bottom">{{ $dean_text }}</td>
            <td width="5%"></td>
        </tr>
        <tr>
            <td></td>
            <td class="text-center"><b>Instructor</b></td>
            <td></td>
            <td class="text-center"><b>Dean</b></td>
            <td></td>
        </tr>
    </table>
    </div>

</body>

</html>
