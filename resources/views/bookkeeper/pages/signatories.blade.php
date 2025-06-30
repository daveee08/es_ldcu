<div class="container-fluid">
    <div class="row">
        <!-- Left Panel -->
        <div class="col-md-6">
            <div class="list-group">
                @php
                    $categories = DB::table('bk_signatory_grp')->pluck('description', 'id')->toArray();
                @endphp

                @foreach ($categories as $id => $category)
                    <div class="d-flex justify-content-between align-items-center border rounded shadow-sm p-3 mb-2">
                        <span class="fw-semibold">• {{ $category }}</span>
                        <a href="#" class="text-primary text-decoration-underline view-details"
                            data-id="{{ $id }}" style="font-size: 12px;">View Details</a>
                    </div>
                @endforeach

            </div>
        </div>

        <!-- Right Panel -->
        <div class="col-md-6">
            <div class="container" style="background-color: #f1f1f1; padding: 20px;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Signatories</h5>
                    <button class="btn btn-sm btn-success" style="width: auto; height: auto;"><i
                            class="fas fa-plus-square add-signatory-row"></i></button>
                </div>

                <div id="signatoryContainer" style="width: 70%;">
                    <div class="signatory-row mb-3 border rounded p-2">
                        <input type="text" class="form-control form-control-sm mb-1 signatory-description"
                            placeholder="Description">
                        <input type="text"
                            class="form-control form-control-sm mb-1 signatory-name"placeholder="Name">
                        <hr>
                        <input type="text" class="form-control form-control-sm mb-1 signatory-title"
                            placeholder="Title">
                    </div>
                </div>

                {{-- <button class="btn btn-sm btn-success add-signatory-row"><i class="fas fa-plus-square"></i> Add
                    Row</button> --}}
                <button id="saveSignatoriesBtn" class="btn btn-success btn-sm">Save</button>

                {{-- </div> --}}
            </div>
        </div>
    </div>
</div>




<script>
    $(document).ready(function() {
        $(document).on('click', '.add-signatory-row', function() {
            var row = `
                    <div class="signatory-row mb-3 border rounded p-2">
                        <div class="d-flex align-items-start">
                            <input type="text" class="form-control form-control-sm mb-1 me-2 signatory-description" placeholder="Description" style="flex-grow:1;">
                            <button type="button" class="remove-signatory-row btn btn-sm text-danger fw-bold"
                                style="font-size: 1.25rem; line-height: 1; padding: 0.25rem 0.6rem; background: transparent; border: none;">
                                &times;
                            </button>
                        </div>
                        <input type="text" class="form-control form-control-sm mb-1 signatory-name" placeholder="Name">
                        <hr>
                        <input type="text" class="form-control form-control-sm mb-1 signatory-title" placeholder="Title">
                        <hr>
                    </div>
                `;
            $('#signatoryContainer').append(row);
        });

        $(document).on('click', '.remove-signatory-row', function() {
            $(this).closest('.signatory-row').remove();
        });

        $('#saveSignatoriesBtn').on('click', function() {

            var signatories = [];

            $('.signatory-row').each(function() {
                var name = $(this).find('.signatory-name').val();
                var description = $(this).find('.signatory-description').val();
                var title = $(this).find('.signatory-title').val();

                if (name && description && title) {
                    signatories.push({
                        name,
                        description,
                        title
                    });
                }
            });

            // Collect all category IDs from blade
            var categoryIds = @json(array_keys($categories));

            if (signatories.length === 0) {
                alert("Please fill out at least one signatory.");
                return;
            }

            $.ajax({
                url: '/bookkeeper/save-signatories',
                type: 'POST',
                data: {
                    category_ids: categoryIds,
                    signatories: signatories,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        type: 'success'
                    }).then(() => {
                        $('#signatoryContainer').empty();
                        $('#signatoryContainer .signatory-row input').val('');
                    });
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while saving.',
                        type: 'error'
                    });
                }
            });
        });

        function fetchSignatories(groupId) {
            $.ajax({
                url: '/bookkeeper/signatories',
                type: 'GET',
                success: function(signatories) {
                    console.log(signatories, ' eeee');

                    var filtered = signatories.filter(s => s.signatory_grp_id == groupId);

                    if (filtered.length === 0) {
                        $('#signatoryLists').html(
                            '<p class="text-muted">No signatories assigned to this group.</p>'
                        );
                    } else {
                        let html = '<ul class="list-group">';
                        filtered.forEach(s => {
                            html += `
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <strong>${s.description}</strong><br>
                                ${s.name} <em>(${s.title})</em>
                            </div>
                            <i class="fas fa-times text-danger remove-signatory" data-id="${s.id}" title="Remove" style="cursor: pointer;"></i>
                        </li>
                    `;
                        });
                        html += '</ul>';
                        $('#signatoryLists').html(html);
                    }

                    $('#signatoryListModal').modal('show');
                },
                error: function(err) {
                    console.error(err);
                    alert('Failed to fetch signatories.');
                }
            });
        }


        $(document).on('click', '.view-details', function(e) {
            e.preventDefault();

            var groupId = $(this).data('id');

            $('#addSignatory').attr('data-id', groupId);
            $('.remove-signatory').attr('data-id', groupId);

            fetchSignatories(groupId);
        });


        $(document).on('click', '#addSignatory', function() {
            var groupId = $(this).data('id');
            var signatories = [];

            $('#signatoryContainer .signatory-row').each(function() {
                var description = $(this).find('.signatory-description').val();
                var name = $(this).find('.signatory-name').val();
                var title = $(this).find('.signatory-title').val();

                if (description || name || title) {
                    signatories.push({
                        description: description,
                        name: name,
                        title: title
                    });
                }
            });

            if (signatories.length === 0) {
                alert('Please fill out at least one signatory.');
                return;
            }

            $.ajax({
                url: '/bookkeeper/add-signatory',
                type: 'POST',
                data: {
                    group_id: groupId,
                    signatories: signatories,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        type: 'success'
                    }).then(() => {
                        $('#signatoryContainer').empty();
                        $('#signatoryContainer .signatory-row input').val('');
                        fetchSignatories(groupId);
                    });
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert("Something went wrong.");
                }
            });
        });

        $(document).on('click', '.remove-signatory', function() {
            var signatoryId = $(this).data('id');
            var groupId = $('#addSignatory').data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This signatory will be marked as deleted.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/bookkeeper/delete-signatory',
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: signatoryId
                        },
                        success: function(response) {
                            Swal.fire('Deleted!', response.message, 'success');
                            fetchSignatories(groupId);
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            alert('Failed to delete signatory.');
                        }
                    });
                }
            });
        });
    });
</script>
