<style>
    .scrollable-content {
        max-height: calc(100vh - 240px);
        overflow-y: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .scrollable-content::-webkit-scrollbar {
        display: none;
    }
</style>
<div class="scrollable-content p-3" style=" overflow-y: auto;">
    <!-- Student Information content -->
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="mb-3 mt-2">Student Details</h5>
        <button class="btn btn-primary btn-sm mr-2" style="background-color: #053473; color: white; font-size: 12px;" data-id="" id="printStudInfo"><i class="fa fa-print" aria-hidden="true"></i> Print Student Info</button>
    </div>
    <hr style="border-color: #7d7d7d;">
    <form style="font-size: 12px;">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="studentIdNo">Student ID No.</label>
                <input type="text" class="form-control" id="studentIdNo" value="1120003221" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="firstName">First Name</label>
                <input type="text" class="form-control" id="firstName" value="Raymund Jake" readonly>
            </div>
            <div class="col-md-4">
                <label for="middleName">Middle Name</label>
                <input type="text" class="form-control" id="middleName" value="Tagana" readonly>
            </div>
            <div class="col-md-3">
                <label for="lastName">Last Name</label>
                <input type="text" class="form-control" id="lastName" value="Abcede" readonly>
            </div>
            <div class="col-md-1">
                <label for="suffix">Suffix</label>
                <input type="text" class="form-control" id="suffix" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="dateOfBirth">Date of Birth</label>
                <input type="text" class="form-control" id="dateOfBirth" value="2002-09-11" readonly>
            </div>
            <div class="col-md-4">
                <label for="placeOfBirth">Place of Birth</label>
                <input type="text" class="form-control" id="placeOfBirth" value="Iligan City" readonly>
            </div>
            <div class="col-md-4">
                <label for="gender">Gender</label>
                <input type="text" class="form-control" id="gender" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="mobileNumber">Mobile Number</label>
                <input type="tel" class="form-control" id="mobileNumber" value="09760856671" readonly>
            </div>
            <div class="col-md-8">
                <label for="emailAddress">Email Address</label>
                <input type="email" class="form-control" id="emailAddress" value="abcederj@gmail.com" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="religion">Religion</label>
                <input type="text" class="form-control" id="religion" value="Catholic" readonly>
            </div>
            <div class="col-md-4">
                <label for="nationality">Nationality</label>
                <input type="text" class="form-control" id="nationality" value="Filipino" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="permanentAddress">Permanent Address</label>
                <input type="text" class="form-control" id="permanentAddress" value="Ditucalan, Iligan City"
                    readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="presentAddress">Present Address</label>
                <input type="text" class="form-control" id="presentAddress"
                    value="Mountain View Homes, Balulong Cagayan De Oro City" readonly>
            </div>
        </div>
        <h5 class="mb-4 mt-3">Parents / Guardian Information</h5>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="fatherFirstName">Father's First Name</label>
                <input type="text" class="form-control" id="fatherFirstName" value="Jake" readonly>
            </div>
            <div class="col-md-4">
                <label for="fatherMiddleName">Father's Middle Name</label>
                <input type="text" class="form-control" id="fatherMiddleName" value="Tual" readonly>
            </div>
            <div class="col-md-3">
                <label for="fatherLastName">Father's Last Name</label>
                <input type="text" class="form-control" id="fatherLastName" value="Abcede" readonly>
            </div>
            <div class="col-md-1">
                <label for="fatherSuffix">Suffix</label>
                <input type="text" class="form-control" id="fatherSuffix" readonly>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-3">
                <label for="fContactNumber">Contact Number</label>
                <input type="text" class="form-control" id="fContactNumber" value="Jake" readonly>
            </div>
            <div class="col-md-2">
                <label for="fOccupation">Occupation</label>
                <input type="text" class="form-control" id="fOccupation" value="Tual" readonly>
            </div>
            <div class="col-md-5">
                <label for="fHAE">High Educational Attainment</label>
                <input type="text" class="form-control" id="fHAE" value="Abcede" readonly>
            </div>
            <div class="col-md-2">
                <label for="fEthnicity">Ethnicity</label>
                <input type="text" class="form-control" id="fEthnicity" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="motherFirstName">Mother's First Name</label>
                <input type="text" class="form-control" id="motherFirstName" value="Jane" readonly>
            </div>
            <div class="col-md-4">
                <label for="motherMiddleName">Mother's Middle Name</label>
                <input type="text" class="form-control" id="motherMiddleName" value="Doe" readonly>
            </div>
            <div class="col-md-3">
                <label for="motherLastName">Mother's Last Name</label>
                <input type="text" class="form-control" id="motherLastName" value="Smith" readonly>
            </div>
            <div class="col-md-1">
                <label for="motherSuffix">Suffix</label>
                <input type="text" class="form-control" id="motherSuffix" readonly>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-3">
                <label for="mContactNumber">Contact Number</label>
                <input type="text" class="form-control" id="mContactNumber" value="1234567890" readonly>
            </div>
            <div class="col-md-2">
                <label for="mOccupation">Occupation</label>
                <input type="text" class="form-control" id="mOccupation" value="Teacher" readonly>
            </div>
            <div class="col-md-5">
                <label for="mHAE">High Educational Attainment</label>
                <input type="text" class="form-control" id="mHAE" value="Master's Degree" readonly>
            </div>
            <div class="col-md-2">
                <label for="mEthnicity">Ethnicity</label>
                <input type="text" class="form-control" id="mEthnicity" readonly>
            </div>
        </div>
        <h5 class="mb-4 mt-3">Guardian Information</h5>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="guardianFirstName">Guardian's First Name</label>
                <input type="text" class="form-control" id="guardianFirstName" value="John" readonly>
            </div>
            <div class="col-md-4">
                <label for="guardianMiddleName">Guardian's Middle Name</label>
                <input type="text" class="form-control" id="guardianMiddleName" value="Doe" readonly>
            </div>
            <div class="col-md-3">
                <label for="guardianLastName">Guardian's Last Name</label>
                <input type="text" class="form-control" id="guardianLastName" value="Smith" readonly>
            </div>
            <div class="col-md-1">
                <label for="guardianSuffix">Suffix</label>
                <input type="text" class="form-control" id="guardianSuffix" readonly>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-3">
                <label for="gContactNumber">Contact Number</label>
                <input type="text" class="form-control" id="gContactNumber" value="0987654321" readonly>
            </div>
            <div class="col-md-2">
                <label for="gOccupation">Occupation</label>
                <input type="text" class="form-control" id="gOccupation" value="Engineer" readonly>
            </div>
            <div class="col-md-5">
                <label for="gHAE">High Educational Attainment</label>
                <input type="text" class="form-control" id="gHAE" value="Bachelor's Degree" readonly>
            </div>
            <div class="col-md-2">
                <label for="gEthnicity">Ethnicity</label>
                <input type="text" class="form-control" id="gEthnicity" readonly>
            </div>
        </div>
        <h5 class="mb-4 mt-3">Educational Information</h5>
        <div class="row mb-3" style="font-size: 12px">
            <div class="col-md-3">
                <label for="lastSchoolAttended">Last School Attended</label>
                <input type="text" class="form-control" id="lastSchoolAttended" value="John" readonly>
            </div>
            <div class="col-md-3">
                <label for="lastGradeLevelCompleted">Last Grade Level Completed</label>
                <input type="text" class="form-control" id="lastGradeLevelCompleted" value="Doe" readonly>
            </div>
            <div class="col-md-3">
                <label for="schoolContactNo">School's Contact No.</label>
                <input type="text" class="form-control" id="schoolContactNo" value="Smith" readonly>
            </div>
            <div class="col-md-3">
                <label for="schoolMailingAddress">Last School Mailing Address</label>
                <input type="text" class="form-control" id="schoolMailingAddress" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="schoolTypeLabel"></label>
            </div>
            <div class="col-md-3">
                <label for="schoolName">School Name</label>
            </div>
            <div class="col-md-3">
                <label for="schoolYearGraduated">School Year Graduated</label>
            </div>
            <div class="col-md-3">
                <label for="schoolType">School Type</label>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="preSchool">Pre-School</label>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="preSchoolName" value="Doe" readonly>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="preSchoolYearGraduated" value="Smith" readonly>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="preSchoolType" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="gradeSchool">Grade School</label>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="gradeSchoolName" value="Doe" readonly>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="gradeSchoolYearGraduated" value="Smith" readonly>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="gradeSchoolType" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="juniorHighSchool">Junior High School</label>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="juniorHighSchoolName" value="Doe" readonly>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="juniorHighSchoolYearGraduated" value="Smith"
                    readonly>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="juniorHighSchoolType" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="seniorHighSchool">Senior High School</label>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="seniorHighSchoolName" value="Doe" readonly>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="seniorHighSchoolYearGraduated" value="Smith"
                    readonly>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="seniorHighSchoolType" readonly>
            </div>
        </div>
        <h5 class="mb-4 mt-3">Medical Information</h5>
        <div class="row mb-3">
            <div class="col-md-2">
                <label for="Height">Height (Meters)</label>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="Height" value="" readonly>
            </div>
            <div class="col-md-2">
                <label for="Weight">Weight (kgs)</label>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="Weight" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="otherMed">Any Current Medications, Specify </label>
            </div>
            <div class="col-md-8">
                <input type="text" class="form-control" id="otherMed" value="" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-2">
                <label for="anyAllergies">Any Allergies</label>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="anyAllergies" value="" readonly>
            </div>
            <div class="col-md-2">
                <label for="medAllergies"> Medications to allergies</label>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="medAllergies" value="" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-2">
                <label for="medHistory">Medical History</label>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="medHistory" value="" readonly>
            </div>
            <div class="col-md-2">
                <label for="otherMedInfo">Other Medical Information</label>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="otherMedInfo" value="" readonly>
            </div>
        </div>
    </form>
</div>

<script>
    function openStudentInformationModal(studentId) {
        $.ajax({
            url: '/student/loading/Student-Information',
            // async:true,
            method: 'GET',
            data: {
                studentId: studentId,
                syid: $('#filter_sy').val(),
                semid: $('#filter_semester').val()
            },
            dataType: 'json',
            success: function(response) {
                console.log(response, 'response');
                eval_name = response.firstname + ' ' + response.lastname;
                eval_course = response.courseabrv;
                eval_section = response.sectionDesc || '';
                eval_curriculum = response.curriculumname || '';
                eval_gradelevel = response.levelname;
                curr_id = response.curriculumID;
                levelid = response.levelid;
                eval_courseID = response.courseid;
                $('#studentLoadingModalLabel').text(response.firstname + ' ' +
                    response.middlename + ' ' + response.lastname);
                // Populate form fields with student data
                $('#printStudInfo').attr('data-id', studentId);

                $('#studentIdNo').val(response.sid || '');
                $('#firstName').val(response.firstname || '');
                $('#middleName').val(response.middlename || '');
                $('#lastName').val(response.lastname || '');
                $('#suffix').val(response.suffix || '');
                $('#dateOfBirth').val(response.dob || '');
                $('#placeOfBirth').val(response.pob || '');
                $('#gender').val(response.gender || '');
                $('#mobileNumber').val(response.contactno || '');
                $('#emailAddress').val(response.semail || '');
                $('#religion').val(response.religionname || '');
                $('#nationality').val(response.nationality || '');
                $('#permanentAddress').val(response.fulladdress || '');
                $('#presentAddress').val(response.fulladdress || '');
                $('#studenAcadSection').attr('data-section', response.sectionid)

                // Populate parent information father information
                $('#fatherFirstName').val(response.ffname || '');
                $('#fatherMiddleName').val(response.mmname || '');
                $('#fatherLastName').val(response.flname || '');
                $('#fatherSuffix').val(response.fsuffix || '');
                $('#fContactNumber').val(response.fcontactno || '');
                $('#fOccupation').val(response.foccupation || '');
                $('#fHAE').val(response.fea || '');
                $('#mEthnicity').val(response.fethnicity || '');

                // Populate parent information mother information
                $('#motherFirstName').val(response.mfname || '');
                $('#motherMiddleName').val(response.mmname || '');
                $('#motherLastName').val(response.mlname || '');
                $('#motherSuffix').val(response.msuffix || '');
                $('#mContactNumber').val(response.mcontactno || '');
                $('#mOccupation').val(response.moccupation || '');
                $('#mHAE').val(response.mea || '');
                $('#mEthnicity').val(response.methnicity || '');

                // Populate parent information guardian information
                $('#guardianFirstName').val(response.gfname || '');
                $('#guardianMiddleName').val(response.gmname || '');
                $('#guardianLastName').val(response.glname || '');
                $('#guardianSuffix').val(response.gsuffix || '');
                $('#gContactNumber').val(response.gcontactno || '');
                $('#gOccupation').val(response.goccupation || '');
                $('#gHAE').val(response.gea || '');
                $('#gEthnicity').val(response.gethnicity || '');

                //Last School Attended Information
                $('#lastSchoolAttended').val(response.lastschoolatt || '');
                $('#lastGradeLevelCompleted').val(response.lastschoolsy || '');
                $('#schoolContactNo').val(response.scn || '');
                $('#schoolMailingAddress').val(response.cmaosla || '');

                //Pre-school School Attended Information
                $('#preSchoolName').val(response.psschoolname || '');
                $('#preSchoolYearGraduated').val(response.pssy || '');
                $('#preSchoolType').val(response.psschooltype || '');

                //Grade School Attended Information
                $('#gradeSchoolName').val(response.gsschoolname || '');
                $('#gradeSchoolYearGraduated').val(response.gssy || '');
                $('#gradeSchoolType').val(response.gsschooltype || '');

                //Junior School Attended Information
                $('#juniorHighSchoolName').val(response.jhsschoolname || '');
                $('#juniorHighSchoolYearGraduated').val(response.jhssy || '');
                $('#juniorHighSchoolType').val(response.jhsschooltype || '');

                //Senior School Attended Information
                $('#seniorHighSchoolName').val(response.shsschoolname || '');
                $('#seniorHighSchoolYearGraduated').val(response.shssy || '');
                $('#seniorHighSchoolType').val(response.shsschooltype || '');

                //Medical Information of Student
                // $('#Height').val(response.gsschoolname || '');
                // $('#Weight').val(response.shssy || '');
                // $('#otherMed').val(response.shsschooltype || '');
                // $('#anyAllergies').val(response.gsschoolname || '');
                // $('#medAllergies').val(response.shssy || '');
                // $('#medHistory').val(response.shsschooltype || '');
                // $('#otherMedInfo').val(response.shsschooltype || '');

                // Populate the additional table information
                // $('#studentPhoto').img(response.picurl || '');

                $('#studentIdNumber').text(response.sid || '');
                $('#studentLevel').text(response.levelname || '');
                $('#studenAcadSection').text(response.sectionDesc || '');
                $('#studentCourse').text(response.courseDesc || '');
                $('#studentStatus').text(response.studstatus || '');

                $('#sectionScheduleModalLabel').data('data-id-student', studentId);

                $('#studentLoadingModal').data('student-id', studentId);
                // Show the modal
                var studentLoadingModal = new bootstrap.Modal(document
                    .getElementById(
                        'studentLoadingModal'));
                studentLoadingModal.show();
            },
            error: function(xhr, status, error) {
                console.error("Error fetching student data:", error);
                alert("Failed to load student data. Please try again later.");
            }
        });
    }

    $('#printStudInfo').on('click', function() {
        var studid = $(this).attr('data-id');
        window.open('/registrar/studentinfo/print?studid=' + studid, '_blank');
    });
</script>
