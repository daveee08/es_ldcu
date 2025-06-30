<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <button class="btn btn-sm btn-warning"><strong>{{ count($students) }}</strong> Students</button>
            </div>
            <div class="col-md-6 text-right">
                <td class="text-right">
                    <button type="button" class="btn btn-default printAllSOA bg-primary" style="font-size: 12px;">
                        <i class="fa fa-print"></i> Print All
                    </button>
                </td>
                {{-- <button type="button" class="btn btn-default btn-export-all" exporttype="pdf">
                                <i class="fa fa-file-pdf"></i> Export to PDF
                            </button> --}}
                {{-- <button type="button" class="btn btn-default btn-export-all" exporttype="excel">
                                <i class="fa fa-file-excel"></i> Export to Excel
                            </button> --}}
            </div>
        </div>
        <div class="row">
            <div class="col-12" id="selectedoptionscontainer"></div>
        </div>
        <div class="row mt-2">
            <div class="col-12" id="resultscontainer">
                <table id="example1" class="table">
                    <thead>
                        <tr>
                            <th style="width: 3%;">
                                <input type="checkbox" id="select_all_students" checked>
                            </th>
                            <th style="width: 5%;">SID</th>
                            {{-- <th>Grade Level</th> --}}

                            <th>
                                <div style="display: flex; justify-content: space-between;">
                                    <span>Student Grade</span>
                                    <span class="mr-3 text-success">Level/Section</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="soa_body">
                        @if (count($students) > 0)
                            @foreach ($students as $student)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="student_checkbox" value="{{ $student->id }}"
                                            checked>
                                    </td>
                                    <td>{{ $student->sid }}</td>
                                    {{-- <td>{{ $student->levelname }}</td>
                                    <td>{{ $student->sectionname }}</td> --}}
                                    <td class="p-0">
                                        <div class="card collapsed-card p-0 mb-0"
                                            style="border: none !important; background-color: unset !important;box-shadow: none !important;">
                                            <div class="card-header">
                                                <div
                                                    style="display: flex; justify-content: space-between; align-items: center;">
                                                    <h3 class="card-title">{{ $student->lastname }},
                                                        {{ $student->firstname }} {{ $student->middlename }}
                                                        {{ $student->suffix }}</h3>

                                                    <h3 class="card-title">{{ $student->levelname }},
                                                        {{ $student->sectionname }}</h3>
                                                </div>

                                                <br>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool viewdetails"
                                                        data-card-widget="collapse" id="{{ $student->id }}">View
                                                    </button>
                                                    @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xai' ||
                                                            strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc' ||
                                                            strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
                                                    @else
                                                        {{-- <button type="button" class="btn btn-tool printstatementofacct"  exporttype="excel" studid="{{$student->id}}">Excel
                                                        </button> --}}
                                                    @endif
                                                    <button type="button" class="btn btn-tool printstatementofacct"
                                                        exporttype="pdf" studid="{{ $student->id }}">PDF
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: none;" id="stud{{ $student->id }}">
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    // $('.printAllSOA').on('click', function () {
    //     window.open('/printallSOA', '_blank');
    // });
    document.getElementById('select_all_students').addEventListener('change', function() {
        let checked = this.checked;
        document.querySelectorAll('.student_checkbox').forEach(function(cb) {
            cb.checked = checked;
        });
    });
</script>
