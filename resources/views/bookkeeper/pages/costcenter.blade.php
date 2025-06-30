<div class="container-fluid">
    <div class="row">
        <div class="mb-1 float-right">

        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-12 text-right">
            <button id="costcenter_create" class="btn btn-sm btn-primary">Create</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table width="100%" id="level_data"
                class="table table-striped table-sm text-sm display nowrap" style="table-layout: fixed; font-size: 14px;">
                <thead>
                    <th width="30%">Account</th>
                    <th width="60%">Description</th>
                    <th width="10%" class="text-center">Action</th>

                </thead>
                <tbody id="costcenter_list" style="">

                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade show" id="modal-costcenter_detail" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-md" style="margin-top: 3em">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">Cost Center</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <label for="">Description</label>
                            <input id="costcenter_description" class="form-control text-sm form-control-sm"
                                type="text">
                        </div>
                        <div class="col-md-4">
                            <label for="">Code</label>
                            <input id="costcenter_acc_code" class="form-control text-sm form-control-sm"
                                type="text">
                        </div>
                    </div>
                </div>
                <div class="modal-footer float-right">
                    <div class="">
                        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">
                            Close
                        </button>
                    </div>
                    <div>
                        <button id="costcenter_save" class="btn btn-primary btn-sm" data-id="0">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<script>
    $(document).ready(function() {

        
        $('#modal-costcenter_detail').on('shown.bs.modal', function() {
            $('#costcenter_description').focus()
        })

        costcenter_read()
        
        $(document).on('click', '#costcenter_create', function() {
            costcenter_clearfields()
            $('#modal-costcenter_detail').modal('show')
        })

        $(document).on('click', '#costcenter_save', function() {
            let description = $('#costcenter_description').val()
            let code = $('#costcenter_acc_code').val()
            let id = $(this).attr('data-id')

            $.ajax({
                type: "GET",
                url: '/bookkeeper/costcenter_create',
                data: {
                    description: description,
                    code: code,
                    id: id
                },
                success: function(data) {

                    costcenter_read()
                    costcenter_clearfields()

                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });
                    Toast.fire({
                        type: "success",
                        title: "Cost Center has been added"
                    });
                }
            });
        })

        function costcenter_clearfields() {
            $('#costcenter_description').val('')
            $('#costcenter_acc_code').val('')
        }

        function costcenter_read() {
            $.ajax({
                type: "GET",
                url: '/bookkeeper/costcenter_read',
                // data: "data",
                // dataType: "dataType",
                success: function(data) {
                    $('#costcenter_list').empty();

                    $.each(data, function(indexInArray, val) {
                        $('#costcenter_list').append(`
                        <tr data-id="` + val.id + `">
                            <td>` + val.acc_code + `</td>
                            <td>` + val.description + `</td>
                            <td class="text-center">
                                <i class="fas fa-edit text-primary" data-id="` + val.id +
                            `" style="cursor:pointer"></i> <i class="fas fa-trash text-danger costcenter_delete" data-id="` +
                            val.id + `" style="cursor:pointer"></i>
                            </td>
                        </tr>
                        `)
                    });
                }
            });
        }
    });
</script>
