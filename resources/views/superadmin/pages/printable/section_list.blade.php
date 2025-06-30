<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample</title>
    <style>
        .table {
            background-color: transparent;
            vertical-align: middle;
            table-layout: fixed;
            width: 100%;
        }

        table {
            border-collapse: collapse;

        }

        .table thead th {
            vertical-align: bottom;
        }

        .table td,
        .table th {
            /* padding-top: 5px; */
            padding-bottom: 2px;
            /* vertical-align: top; */
        }

        .table-bordered {
            border: 1px solid #00000;
        }

        .table-borderedx {
            border: 1px solid #00000;
        }


        .table-bordered td,
        .table-bordered th {
            border: 1px solid #00000;
        }

        .table-sm td,
        .table-sm th {
            padding: 5px !important;
        }

        .u {
            text-decoration: underline;
        }

        .text-center {
            text-align: center !important;
        }

        .cell-center td:nth-child(3) {
            text-align: center !important;
        }

        .cell-center td:nth-child(4) {
            text-align: center !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-left {
            text-align: left !important;
        }

        .p-0 {
            padding-top: 0;
            padding-bottom: 0;
            padding-right: 0;
            padding-left: 0;
        }

        .p-0 td {
            padding-top: 0;
            padding-bottom: 0;
            padding-right: 0;
            padding-left: 0;
        }

        .p-2 {
            padding-right: 2px !important;
            padding-left: 2px !important;
        }

        .pb-0 {
            padding-bottom: 0 !important;
        }

        .pl-1 {
            padding-left: 15px !important;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .mt-0 {
            margin-top: 0 !important;
        }

        .mb-1,
        .my-1 {
            margin-bottom: .25rem !important;
        }

        .mx-2 {
            margin-left: .4in;
            margin-right: .4in;
        }

        body {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 7pt;
        }

        p {
            margin: 0;
        }

        .align-top {
            vertical-align: top !important;
        }

        .align-middle {
            vertical-align: middle !important;
        }

        .align-bottom {
            vertical-align: bottom !important;
        }

        .grades td {
            padding-top: 5px;
            padding-bottom: 5px;
            font-size: 9pt;
        }

        .studentinfo td {
            padding-top: .1rem;
            padding-bottom: .1rem;

        }

        .bold {
            font-weight: bold !important;
        }

        .bg-red {
            color: red;
            border: solid 1px black !important;
        }

        td {
            padding-left: 10px;
            padding-right: 10px;
        }

        .aside {
            /* background: #48b4e0; */
            color: #000;
            line-height: 15px;
            height: 35px;
            border: 1px solid #000 !important;

        }

        .aside span {
            /* Abs positioning makes it not take up vert space */
            /* position: absolute; */
            top: 0;
            left: 0;

            /* Border is the new background */
            background: none;

            /* Rotate from top left corner (not default) */
            transform-origin: 10 10;
            transform: rotate(-90deg);
        }

        .trhead {
            background-color: rgb(167, 223, 167);
            color: #000;
            font-size;
        }

        .trhead td {
            border: 1px solid #000;
        }

        /* @page {
            margin:20px 20px;
            
        } */
        body {
            /* margin:0px 10px; */
        }

        .thead-border th {
            border: 2px solid black;
            text-transform: uppercase;
            padding-top: 10px !important;
            padding-bottom: 10px !important;
        }

        .tfoot-border td {
            border: 1px solid black;
            text-transform: uppercase;
            padding-top: 20px !important;
            padding-bottom: 20px !important;
        }


        .small-font {
            font-size: 9pt !important;
        }

        .smaller-font {
            font-size: 8pt !important;
        }

        .smallest-font {
            font-size: 6.5pt !important;
        }

        .smallestest-font {
            font-size: 5pt !important;
        }

        h4 {
            margin-bottom: 10px;
        }

        .suptitle {
            font-size: 15pt !important;
        }

        .round-bordered {
            border: 2px solid black;
            border-radius: 10px !important;
        }

        .rounded-bordered {
            border: 2px solid black;
            border-radius: 30px !important;
        }

        .title {
            font-size: 11pt !important;
        }

        .subtitle {
            font-size: 10pt !important;
        }

        .space {
            margin-top: 2px !important
        }

        .spacing {
            margin-top: 4px !important;
        }

        .superspace {
            margin-top: .8in !important;
        }

        .underline {
            border-bottom: 1px solid black !important;
        }

        .timesnew {
            font-family: 'Times New Roman', Times, serif;
        }

        #space {
            margin-top: .2in !important
        }

        .right-margin {
            margin-right: .5in !important;
        }

        .left-margin {
            margin-left: .3in !important;
        }

        .smallcompressed {
            margin-right: .15in !important;
            margin-left: .15in !important;
        }

        .compressed {
            margin-right: .3in !important;
            margin-left: .3in !important;
        }

        .supcompressed {
            margin-right: 1in !important;
            margin-left: 1in !important;
        }

        .border-top-bottom {
            border-top: 1px solid black;
            border-bottom: 1px solid black;

        }

        .border {
            border: 1px solid black;
        }

        .indent {
            text-indent: .8in;
        }

        .small-indent {
            text-indent: .4in
        }

        .new-page {
            page-break-after: always;
        }

        .padding {
            padding-bottom: 12px !important;
            padding-top: 12px !important;
        }

        .pad td {
            padding-left: 5px !important;
        }

        .no-border {
            border: none !important;
        }

        .no-border td {
            border: none !important;
        }

        .no-right {
            border-right: 1px solid #FFF89E !important;
        }

        .no-left {
            border-left: 1px solid #FFF89E !important;
        }

        .no-top {
            border-top: 1px solid #FFF89E !important;
        }

        .no-bottom {
            border-bottom: 1px solid #FFF89E !important;
        }

        .no-bottom td {
            border-bottom: 1px solid #FFF89E !important;
        }

        .relative {
            position: relative;
        }

        .absolute {
            position: absolute;
            top: 11.5in;
        }

        .word-space {
            word-spacing: 11px;
            letter-spacing: 1px;
        }

        .mr-0 {
            margin-right: 0 !important;
        }

        .ml-0 {
            margin-left: 0 !important;
        }

        .gray {
            background-color: rgb(225, 225, 225);
        }

        .blue {
            color: rgb(0, 32, 96);
        }

        .red {
            color: red;
        }

        .blue-bg {
            background-color: rgb(189, 214, 238);
        }

        .uppercase {
            text-transform: capitalize'

        }

        .justify {
            text-align: justify !important;
        }

        .gigaspace {
            margin-top: 1.7in;
        }

        .paragraph {
            font-size: 8.5pt;
        }

        .italic {
            font-style: italic;
        }

        .watermark2 {
            position: fixed;
            text-align: center !important;
            opacity: .32;
            z-index: -999;
            margin: -.2in;
            top: 1.9in;
            left: .3in
        }

        .watermark1 {
            position: fixed;
            text-align: center !important;
            opacity: .32;
            z-index: -999;
            margin: -.2in;
            top: 1.9in;
            right: .5in
        }

        .uppercase {
            text-transform: uppercase;
        }

        .pad-5 {
            padding-left: 5px !important;
        }

        .no-border {
            border: none !important;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000000 !important;
            ;
        }

        .td-sm {
            padding: 2px !important;
            font-size: 12px;
        }

        @page {
            size: 8.5in 13in;
            margin: 0.1in .1in;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>


<body class="p-5">
    <table width="100%" class="spacing title " style="font-size: 10px!important;">
        <tr>
            <th colspan="3" style="font-size: 12px !important; text-align: start; ">
                <div style="display: flex; align-items: center; justify-content: start;">

                    <span>SIBUGAY TECHNOLOGY INSTITUTE INC.</span>
                </div>
            </th>

        </tr>
        <tr colspan="3" style="font-size: 8px !important; text-align: center; ">
            <th colspan="3" style="font-size: 8px !important; text-align: start; ">
                <div style="display: flex; align-items: center; justify-content: start;">
                    <span>Lower Taway, Ipil, Zamboanga Sibugay, Philippines</span>
                </div>
            </th>

        </tr>
        <tr colspan="3" style="font-size: 8px !important; text-align: center; ">
            <th colspan="3" style="font-size: 8px !important; text-align: start; ">
                <div style="display: flex; align-items: center; justify-content: start;">
                    <span>Email Address: sibugaytech07@gmail.com</span>
                </div>
            </th>
        </tr>
    </table>
    <table width="100%" class="spacing title " style="font-size: 10px!important;">
        <tr>
            <th colspan="3" style="font-size: 15px !important; text-align: center; padding: 10px;">
                <div style="display: flex; align-items: center; justify-content: center;">
                    <span>BLOCK SCHEDULE LISTING</span>
                </div>
            </th>
        </tr>
    </table>



    <table width="100%" class="table table-bordered smaller-font pr-3" style="border: 1px solid black; color: black;">
        <tr class=" p-0">
            <th width="5%" class="text-center bold">NO</th>
            <th width="25%" class="text-start bold">STRAND</th>
            <th width="20%" class="text-start bold">LEVEL</th>
            <th width="20%" class="text-start bold">SECTION</th>
            <th width="15%" class="text-center bold">ENROLLED</th>
        </tr>
        @php
                $counter = 1;
        @endphp
        @foreach ($sections as $section)
            
            <tr class="align-start p-0">
                <td class="text-center  " style="font-size: 11px;">{{$counter}}</td>
                <td class="text-start pl-1" style="font-size: 11px;">@if(isset($section->strand)) {{$section->strand[0]->blockname}} @endif</td>
                <td class="text-start pl-1" style="font-size: 11px;">{{$section->levelname}} </td>
                <td class="text-start pl-1" style="font-size: 11px;">{{$section->sectionname}}</td>
                <td class="text-center bold" style="font-size: 11px;">{{$section->enrolled}}</td>
            </tr>
            @php
                $counter++;
            @endphp
        @endforeach

    </table>


</body>

</html>
