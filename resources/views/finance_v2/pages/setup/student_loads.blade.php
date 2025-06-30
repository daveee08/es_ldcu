<!-- Student Loads Tab Content -->
@php
    $sy = DB::table('sy')->get();
    $semesters = DB::table('semester')->get();
@endphp

<hr style="border-color: #7d7d7d;">

<div class="row mb-3 p-3">
    <div class="col-md-6 d-flex align-items-center">
        <div class="form-row w-100">
            <div class="col" style="font-size: 13px;">
                <select class="form-control select2" id="filter_sy"
                    style="height: 32px; border-color: #a2a2a2 !important;">
                    @foreach ($sy as $item)
                        <option value="{{ $item->id }}" {{ $item->isactive ? 'selected' : '' }}>{{ $item->sydesc }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col" style="font-size: 13px;">
                <select class="form-control select2" id="filter_semester"
                    style="height: 32px; border-color: #a2a2a2 !important;">
                    @foreach ($semesters as $item)
                        <option value="{{ $item->id }}" {{ $item->isactive ? 'selected' : '' }}>{{ $item->semester }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-6 text-right">
        <button class="btn btn-sm py-1 px-3" style="background-color: #053473; color: white; font-size: 12px;">
            <i class="fas fa-print fa-fw mr-1"></i>Print SOA
        </button>
    </div>
</div>
<hr style="border-color: #7d7d7d;">


<div class="table-responsive mb-3 p-3">
    <table class="table table-bordered table-sm mb-0 w-100" id="studentLoadsTable">
        <thead style="background-color:#d9d9d9">
            <tr>
                <th style="font-weight: 600; border-color: #a2a2a2 !important;">
                    Section</th>
                <th style="font-weight: 600; border-color: #a2a2a2 !important;">
                    Subjects</th>
                <th style="font-weight: 600; border-color: #a2a2a2 !important;">
                    Class</th>
                <th style="font-weight: 600; border-color: #a2a2a2 !important;">
                    Lect</th>
                <th style="font-weight: 600; border-color: #a2a2a2 !important;">
                    Lab</th>
                <th style="font-weight: 600; border-color: #a2a2a2 !important;">
                    Time & Day</th>
                <th style="font-weight: 600; border-color: #a2a2a2 !important;">
                    Teacher</th>
                <th style="font-weight: 600; border-color: #a2a2a2 !important;">
                    Room</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        <tfoot>

        </tfoot>
    </table>
</div>

<div style="width: 50%; float: left;" class="table-responsive mb-3 p-3">
    <label>Assessment</label>
    <table class="table table-bordered table-sm w-100">
        <thead style="background-color:#d9d9d9">
            <tr>
                <th style="font-weight: 600; border-color: #a2a2a2 !important;">
                    Particulars</th>
                <th style="font-weight: 600; border-color: #a2a2a2 !important;">
                    Assess Units</th>
                <th style="font-weight: 600; border-color: #a2a2a2 !important;">
                    Per Unit</th>
                <th style="font-weight: 600; border-color: #a2a2a2 !important;">
                    Amount</th>
                <th style="font-weight: 600; border-color: #a2a2a2 !important;">
                    Lab</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border-color: #a2a2a2 !important;">Tuition Fee
                </td>
                <td style="border-color: #a2a2a2 !important;">12.0</td>
                <td style="border-color: #a2a2a2 !important;">1,000.00</td>
                <td style="border-color: #a2a2a2 !important;">12,000.00
                </td>
                <td style="border-color: #a2a2a2 !important;">3.0</td>
            </tr>
        </tbody>
    </table>
    <p style="font-weight: 600; text-align: right;">Total Assessment:
        <strong>
            12,000</strong>
    </p>
</div>

<div style="width: 45%; float: right;" class="table-responsive mb-3 p-3">
    <label>Tuition Fee</label>
    <table class="table table-bordered table-sm w-100">
        <thead style="background-color:#d9d9d9">
            <tr>
                <th style="font-weight: 600; border-color: #a2a2a2 !important;">
                    Particulars</th>
                <th style="font-weight: 600; border-color: #a2a2a2 !important;">
                    Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border-color: #a2a2a2 !important;">Tuition Fee
                </td>
                <td style="border-color: #a2a2a2 !important;">12,000.00
                </td>
            </tr>
        </tbody>
    </table>
    <p style="font-weight: 600; text-align: right;">Total Tuition Fee:
        <strong>
            12,000</strong>
    </p>
</div>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });

    function displayLoadedSubjects(response) {
        var tableBody = $('#studentLoadsTable tbody');
        tableBody.empty();

        if (!response.loadedSubjects || !response.studentLoading || response.loadedSubjects.length === 0) {
            tableBody.append('<tr><td colspan="8" style="text-align: center;">No student load</td></tr>');
            return;
        }

        var loadedSubjects = response.loadedSubjects;
        var studentLoading = response.studentLoading;

        loadedSubjects.forEach(subject => {
            var match = studentLoading.find(item => item.schedid === subject.schedid);

            if (match) {
                var row = `
                <tr style="font-size:10px">
                    <td>${match.sectionDesc || ''}</td>
                    <td>${match.subjDesc || ''} (${match.subjCode || ''})</td>
                    <td>${match.schedotherclass || ''}</td>
                    <td>${match.lecunits || ''}</td>
                    <td>${match.labunits || ''}</td>
                    <td>${match.schedule || ''}</td>
                    <td>${match.instructor || ''}</td>
                    <td>${match.roomname || ''}</td>
                </tr>
                `;
                tableBody.append(row);
            }
        });
    }

    function student_loads(studentId, sectionId, subjectID) {
        $.ajax({
            url: `/student/loading/get-added-student-loading/${studentId}/${sectionId}/${$('#filter_sy').val()}/${$('#filter_semester').val()}/${subjectID}`,
            method: 'GET',
            success: function(response) {
                console.log(response);
                displayLoadedSubjects(response);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching student loads:", error);
            }
        });
    }
</script>
