<html>

<head>
    <title>Certificate of Enrollment 
    </title>
    <style>
        @page {
            margin: 0.5in 0.5in 0.2in 0.5in;
        }

        td,
        th {
            padding: 0px;
        }

        #watermark1 {
            position: absolute;
            /* bottom:   0px; */
            /* left:     20px; */
            /** The width and height may change
                    according to the dimensions of your letterhead
                **/
            /* width:    100%; */
            height: 19cm;
            opacity: 0.2;
            /** Your watermark should be behind every content**/
            z-index: -2000;
        }

        table {
            border-collapse: collapse;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 40px;
            margin-bottom: 30px;
        }

        .office {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 30px;
            outline: 1px solid #000;
            outline-offset: -1px;
            box-sizing: border-box;
            width: 70%;
            margin: 0 auto;
            font-family: 'Times New Roman', Times, serif;
        }

    </style>
</head>

<body>

     <div id="watermark1" style="padding-top: 170px; text-align: center;">
                    <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" height="95%" width="95%" style="padding-left: 20px;" />
            </div>

    <table style="width: 100%; table-layout: fixed;">
        <tr>
            <th style="width: 20%;">
                <img src="{{ base_path() }}/public/{{ DB::table('schoolinfo')->first()->picurl }}"
                    style="width: 120px;" />
            </th>
            <td style="text-align: center; font-size: 14px;">
                <span
                    style="font-size: 20px; font-weight: bold;">{{ ucwords(strtoupper(DB::table('schoolinfo')->first()->schoolname)) }}</span>
                <br />
                <span
                     >{{ ucwords(strtolower(DB::table('schoolinfo')->first()->address)) }}</span>
                    <br>
                <span >Tel. Fax No. 062-333-2469</span><br>
                <span >Registered: Commission on Higher Education (CHED)</span><br>
                <span >Department of Education (DEPED)</span><br>
                <span >Technical Education and Skills Development Authority (TESDA)</span><br>
            </td>
            <th style="width: 20%;">
                <img src="{{ base_path() }}/public/assets/images/gcl.png"
                    style="width: 120px; position: absolute; transform:translateY(-40px);" />
            </th>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
              <td colspan="3">
                <div class="office">OFFICE OF THE SCHOOL REGISTRAR</div>
                <br>
                <div class="title">CERTIFICATION</div>
              </td>
        </tr>
    </table>
    <br />
    <table style="width: 100%; margin: 0px 20px;">
        <tr>
            <td>To Whom It May Concern:</td>
        </tr>
        <tr>
            @php
                // dd($studentinfo);
                // $studentinfo->levelid = 14;
            @endphp
            @if ($studentinfo->levelid == 14 || $studentinfo->levelid == 15)
                <td style="text-align: justify; padding-top: 5px; line-height: 25px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;THIS IS TO CERTIFY that
                    <strong>{{ $studentinfo->lastname }}, {{ $studentinfo->firstname }} @if ($studentinfo->middlename != null)
                            {{ $studentinfo->middlename }}
                        @endif  {{ $studentinfo->suffix }}</strong>a
                        <strong>{{ strtolower($studentinfo->levelname) }}</strong> student is officially enrolled in this Institution with <strong>LRN # {{ $studentinfo->lrn }}</strong>, under the K-12 Program of the Department of Education this <strong>{{ $semesterinfo->semester }}</strong>, S.Y.<strong>{{ $syinfo->sydesc }}</strong>.
                </td>
                
            @elseif($studentinfo->levelid >= 17 && $studentinfo->levelid <= 21)
                <td style="text-align: justify; padding-top: 5px; line-height: 25px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;THIS IS TO CERTIFY that
                    <strong>{{ $studentinfo->lastname }}, {{ $studentinfo->firstname }} @if ($studentinfo->middlename != null)
                        {{ $studentinfo->middlename }}
                        @endif  {{ $studentinfo->suffix }}</strong>a
                        <strong>{{ strtolower($studentinfo->yearlevel) }}</strong> student officially enrolled in <strong>{{ $studentinfo->strandname}}</strong> this
                        <strong>{{$semesterinfo->semester}}</strong> S.Y.<strong>{{ $syinfo->sydesc }}</strong>.
                </td>

            @else
                <td style="text-align: justify; padding-top: 5px; line-height: 25px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;THIS IS TO CERTIFY that
                    <strong>{{ $studentinfo->lastname }}, {{ $studentinfo->firstname }} @if ($studentinfo->middlename != null)
                            {{ $studentinfo->middlename }}
                        @endif  {{ $studentinfo->suffix }}</strong>a
                        <strong>{{ strtolower($studentinfo->levelname) }}</strong> student officially enrolled in this Institution under the K-12 Program of the Department of Education this Academic Year, <strong>{{ $syinfo->sydesc }}</strong>.
                </td>
            @endif
            
             
        </tr>
    </table><br />

    <br />
    <p style="text-align: justify; margin: 0px 20px;">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued upon the
        request of the above-named student for whatever legal purpose(s) that may serve him/her best. </p>
    <br />

    <p style="text-align: justify; margin: 0px 20px;">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GIVEN this <strong>{{ date('jS', strtotime($givendate)) }} day of {{ date('F Y', strtotime($givendate)) }}</strong> at {{ $schoolinfo->schoolname }}
    </p>
        <br />
        <br />
        <br />
    <table style="width: 100%; table-layout: fixed; font-size: 15px; margin: 0px 20px;">
        <tr>
            <td rowspan="2" style="font-size: 11px; font-style: italic;">Not valid without<br />School Dry Seal.</td>
            <td rowspan="2"></td>
            <td style="text-align: center; vertical-align: bottom; font-weight: bold; font-family: 'Times New Roman', Times, serif;">{{ $signatoryinfo->name }}</td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top; font-size: 13px !important; font-style: italic; border-top: 1px solid black;">{{ $signatoryinfo->title ? $signatoryinfo->title : 'Registrar' }}</td>
            </td>
        </tr>
    </table>
</body>

</html>
