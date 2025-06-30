@if(count($students) == 0)
<div class="col-md-12">
    <div class="alert alert-danger" role="alert">
        No students enrolled!
    </div>
</div>
@else
<style>
    table td, table th{
        padding: 1px !important;
    }
</style>
<div class="row">
    <div class="col-md-12 text-right mb-2"> 
        <div class="btn-group">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Export Form
            </button>
            <div class="dropdown-menu dropdown-menu-right" style="font-size: 14px;">
            <button class="btn-exportform dropdown-item" data-exporttype="pdf" data-template="1" data-layout="1"><i class="fa fa-file-pdf"></i> &nbsp;Report on Promotion & Level of Proficiency</button>
            <button class="btn-exportform dropdown-item" data-exporttype="pdf" data-template="1" data-layout="2"><i class="fa fa-file-pdf"></i> &nbsp;Report on Promotion & Level of Proficiency<br/><small>(with School Logo - PDF)</small></button>
            <button class="btn-exportform dropdown-item" data-exporttype="excel" data-template="1" data-layout="2"><i class="fa fa-file-excel"></i> &nbsp;Report on Promotion & Level of Proficiency<br/><small>(with School Logo - Excel)</small></button>
            <button class="btn-exportform dropdown-item" data-exporttype="pdf" data-template="2" data-layout="1"><i class="fa fa-file-pdf"></i> &nbsp;Report on Promotion and Learning Progress & Achievement</button>
            <button class="btn-exportform dropdown-item" data-exporttype="pdf" data-template="2" data-layout="2"><i class="fa fa-file-pdf"></i> &nbsp;Report on Promotion and Learning Progress & Achievement<br/><small>(with School Logo)</small></button>
            <button class="btn-exportform dropdown-item" data-exporttype="pdf" data-template="3" data-layout="1"><i class="fa fa-file-pdf"></i> &nbsp;Template 3</button>
            </div>
        </div>               
        {{-- <a href="/forms/form5?sectionid={{$gradeAndLevel[0]->sectionid}}&levelid={{$gradeAndLevel[0]->levelid}}&syid={{$sy->id}}&semid={{$sem->id}}&acadprogid={{$acadprogid}}&currentmonth={{\Carbon\Carbon::now()->month}}&action=export&exporttype=pdf" class="btn btn-sm btn-default" target="_blank">Export as PDF</a> --}}
    </div>
</div>
<div class="card" style="border: none; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th colspan="4">SUMMARY TABLE</th>
                        </tr>
                        <tr>
                            <th>STATUS</th>
                            <th>MALE</th>
                            <th>FEMALE</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>PROMOTED</th>
                            <td class="text-center">{{collect($students)->where('gender','male')->where('actiontaken','1')->count()}}</td>
                            <td class="text-center">{{collect($students)->where('gender','female')->where('actiontaken','1')->count()}}</td>
                            <td class="text-center">{{collect($students)->where('actiontaken','1')->count()}}</td>
                        </tr>
                        <tr>
                            <th>*Conditional
                            </th>
                            <td class="text-center">{{collect($students)->where('gender','male')->where('actiontaken','2')->count()}}</td>
                            <td class="text-center">{{collect($students)->where('gender','female')->where('actiontaken','2')->count()}}</td>
                            <td class="text-center">{{collect($students)->where('actiontaken','2')->count()}}</td>
                        </tr>
                        <tr>
                            <th>RETAINED</th>
                            <td class="text-center">{{collect($students)->where('gender','male')->where('actiontaken','3')->count()}}</td>
                            <td class="text-center">{{collect($students)->where('gender','female')->where('actiontaken','3')->count()}}</td>
                            <td class="text-center">{{collect($students)->where('actiontaken','3')->count()}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th colspan="4">LEARNING PROGRESS AND ACHIEVEMENT (Based on Learners' General Average)
                            </th>
                        </tr>
                        <tr>
                            <th>Descriptors & Grading
                                Scale</th>
                            <th>MALE</th>
                            <th>FEMALE</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Did Not Meet
                                Expectations
                                ( 74 and below)</th>
                            <td class="text-center">{{collect($students)->where('gender','male')->where('generalaverage','<=',74)->where('generalaverage','!=',0)->count()}}</td>
                            <td class="text-center">{{collect($students)->where('gender','female')->where('generalaverage','<=',74)->where('generalaverage','!=',0)->count()}}</td>
                            <td class="text-center">{{collect($students)->where('generalaverage','<=',74)->where('generalaverage','!=',0)->count()}}</td>
                        </tr>
                        <tr>
                            <th>Fairly Satisfactory
                                ( 75-79)
                            </th>
                            <td class="text-center">{{collect($students)->where('gender','male')->where('generalaverage','>=',75)->where('generalaverage','<=',79)->where('generalaverage','!=',0)->count()}}</td>
                            <td class="text-center">{{collect($students)->where('gender','female')->where('generalaverage','>=',75)->where('generalaverage','<=',79)->where('generalaverage','!=',0)->count()}}</td>
                            <td class="text-center">{{collect($students)->where('generalaverage','>=',75)->where('generalaverage','<=',79)->where('generalaverage','!=',0)->count()}}</td>
                        </tr>
                        <tr>
                            <th>Satisfactory
                                ( 80-84 )</th>
                            <td class="text-center">{{collect($students)->where('gender','male')->where('generalaverage','>=',80)->where('generalaverage','<=',84)->where('generalaverage','!=',0)->count()}}</td>
                            <td class="text-center">{{collect($students)->where('gender','female')->where('generalaverage','>=',80)->where('generalaverage','<=',84)->where('generalaverage','!=',0)->count()}}</td>
                            <td class="text-center">{{collect($students)->where('generalaverage','>=',80)->where('generalaverage','<=',84)->where('generalaverage','!=',0)->count()}}</td>
                        </tr>
                        <tr>
                            <th>Very Satisfactory
                                ( 85 -89)</th>
                            <td class="text-center">{{collect($students)->where('gender','male')->where('generalaverage','>=',85)->where('generalaverage','<=',89)->where('generalaverage','!=',0)->count()}}</td>
                            <td class="text-center">{{collect($students)->where('gender','female')->where('generalaverage','>=',85)->where('generalaverage','<=',89)->where('generalaverage','!=',0)->count()}}</td>
                            <td class="text-center">{{collect($students)->where('generalaverage','>=',85)->where('generalaverage','<=',89)->where('generalaverage','!=',0)->count()}}</td>
                        </tr>
                        <tr>
                            <th>Outstanding
                                ( 90 -100)</th>
                            <td class="text-center">{{collect($students)->where('gender','male')->where('generalaverage','>=',90)->where('generalaverage','!=',0)->count()}}</td>
                            <td class="text-center">{{collect($students)->where('gender','female')->where('generalaverage','>=',90)->where('generalaverage','!=',0)->count()}}</td>
                            <td class="text-center">{{collect($students)->where('generalaverage','>=',90)->where('generalaverage','!=',0)->count()}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-12 table-responsive p-0" style="height: 500px;">
                <table class="table table-bordered table-bordered table-head-fixed" style="font-size: 11px;">
                    <thead>
                        <tr class="text-center">
                            <th style="width: 15%;">LRN</th>
                            <th style="width: 35%;">LRN LEARNER'S NAME</th>
                            <th style="width: 10%;">GENERAL AVERAGE</th>
                            <th>ACTION TAKEN<br/>Promoted, Conditional, or Retained</th>
                            <th style="width: 25%;">Did Not Meet Expectations of the ff.
                                Learning Area/s as of end of
                                current School Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th colspan="5">MALE</th>
                        </tr>
                        @foreach($students as $student)
                            @if(strtolower($student->gender) == 'male')
                            <tr id="{{$student->id}}" class="eachstudent">
                                <td style="vertical-align: middle;">{{$student->lrn}}</td>
                                <td style="vertical-align: middle;">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</td>
                                <td style="vertical-align: middle;" class="text-center">{{$student->generalaverage > 0 ? $student->generalaverage : null}}</td>
                                <td>
                                    <div class="form-group clearfix m-0">
                                        <div class="icheck-primary d-inline">
                                          <input type="radio" id="radiopromoted{{$student->id}}" name="actiontaken{{$student->id}}" value="1" @if($student->actiontaken == 1) checked @endif>
                                          <label for="radiopromoted{{$student->id}}">Promoted
                                          </label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                          <input type="radio" id="radioconditional{{$student->id}}" name="actiontaken{{$student->id}}" value="2" @if($student->actiontaken == 2) checked @endif>
                                          <label for="radioconditional{{$student->id}}">
                                              Conditional
                                          </label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                          <input type="radio" id="radioretained{{$student->id}}" name="actiontaken{{$student->id}}" value="3" @if($student->actiontaken == 3) checked @endif>
                                          <label for="radioretained{{$student->id}}">
                                              Retained
                                          </label>
                                        </div>
                                      </div>
                                </td>
                                {{-- <td><input type="text" style="font-size: 11px;" class="form-control form-control-sm input-actiontaken" value="{{$student->actiontaken}}" data-id="{{$student->id}}"/></td> --}}
                                <td style="vertical-align: middle;"></td>
                            </tr>
                            @endif
                        @endforeach
                        <tr>
                            <th colspan="5">FEMALE</th>
                        </tr>
                        @foreach($students as $student)
                            @if(strtolower($student->gender) == 'female')
                            <tr id="{{$student->id}}" class="eachstudent">
                                <td style="vertical-align: middle;">{{$student->lrn}}</td>
                                <td style="vertical-align: middle;">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</td>
                                <td style="vertical-align: middle;" class="text-center">{{$student->generalaverage > 0 ? $student->generalaverage : null}}</td>
                                <td>
                                    <div class="form-group clearfix m-0">
                                        <div class="icheck-primary d-inline">
                                          <input type="radio" id="radiopromoted{{$student->id}}" name="actiontaken{{$student->id}}" value="1" @if($student->actiontaken == 1) checked @endif>
                                          <label for="radiopromoted{{$student->id}}">Promoted
                                          </label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                          <input type="radio" id="radioconditional{{$student->id}}" name="actiontaken{{$student->id}}" value="2" @if($student->actiontaken == 2) checked @endif>
                                          <label for="radioconditional{{$student->id}}">
                                              Conditional
                                          </label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                          <input type="radio" id="radioretained{{$student->id}}" name="actiontaken{{$student->id}}" value="3" @if($student->actiontaken == 3) checked @endif>
                                          <label for="radioretained{{$student->id}}">
                                              Retained
                                          </label>
                                        </div>
                                      </div>
                                </td>
                                {{-- <td><input type="text" style="font-size: 11px;" class="form-control form-control-sm input-actiontaken" value="{{$student->actiontaken}}" data-id="{{$student->id}}"/></td> --}}
                                <td style="vertical-align: middle;"></td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-12 text-right mb-2">
                <button type="button" class="btn btn-sm btn-success" id="btn-saveactiontaken"><i class="fa fa-share"></i> Save changes</button>
            </div>
        </div>
    </div>
</div>
<script>
    
    $(document).on('click','.btn-exportform', function(){
                var formexport = 1;
                var sectionid = '{{$gradeAndLevel[0]->sectionid}}';
                var levelid = '{{$gradeAndLevel[0]->levelid}}';
                var syid = '{{$sy->id}}';
                var semid = '{{$sem->id ?? 0}}';
                var acadprogid = '{{$acadprogid}}';
                var currentmonth = '{{\Carbon\Carbon::now()->month}}';
                var action = 'export';
                var exporttype = $(this).attr('data-exporttype');
                var template = $(this).attr('data-template')
                var layout = $(this).attr('data-layout')
                window.open('/forms/form5?export='+formexport+'&sectionid='+sectionid+'&levelid='+levelid+'&syid='+syid+'&semid='+semid+'&acadprogid='+acadprogid+'&currentmonth='+currentmonth+'&action='+action+'&exporttype='+exporttype+'&template='+template+'&layout='+layout,'_blank')
    })
</script>
@endif
