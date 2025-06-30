<style>
    .container-fluid * {
        /* font-family: Arial, sans-serif !important; */
        font-size: 12px !important;
    }
</style>
@php
    $coa = DB::table('acc_coa')->get();
@endphp
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-success" id="addItemBtn">
            <i class="fas fa-plus"></i> Add Expenses Item
        </button>

        <div class="form-inline">
            <input type="text" class="form-control mr-2" id="searchBar" placeholder="Search...">
            <i class="fas fa-search" id="searchIcon"></i>
        </div>
    </div>

    <div class="table-responsive" style="max-height: 600px; overflow-y: scroll;">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>QTY</th>
                    <th>Amount</th>
                    <th>Inventory Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="expenseTableBody">

            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->

<script>
    $(document).ready(function() {
        loadExpenseItems();

        $('#addItemModal').on('hidden.bs.modal', function() {
            $('#expensesModal').modal('show');
        });


        $('#searchBar').on('keyup', function() {
            var value = $(this).val().toLowerCase();

            $('#expenseTableBody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        $(document).on('click', '#addExpenseItem', function(e) {
            e.preventDefault();

            var itemName = $('#itemName').val().trim();
            var itemCode = $('#itemCode').val().trim();
            var quantity = $('#quantity').val().trim();
            var amount = $('#amount').val().trim();
            var itemType = $('input[name="itemType"]:checked').attr('id');
            var debitAccount = $('#cashier_debit_account').val();

            if (!itemName || !itemCode || !quantity || !amount) {
                alert("Please fill out all fields.");
                return;
            }

            $.ajax({
                url: '/bookkeeper/add-expense-item',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    itemName: itemName,
                    itemCode: itemCode,
                    quantity: quantity,
                    amount: amount,
                    itemType: itemType,
                    debitAccount: debitAccount
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Expense item added successfully.',
                        type: 'success'
                    }).then(() => {
                        $('#addItemModal').modal('hide');
                        loadExpenseItems();
                    });
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire('Error', 'Failed to add expense item.', 'error');
                }
            });
        });


        $(document).on('click', '.delete-item', function() {
            var itemId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This item will be marked as deleted.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/bookkeeper/delete-expense-item',
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: itemId
                        },
                        success: function(response) {
                            Swal.fire('Deleted!', response.message, 'success');
                            loadExpenseItems();
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            Swal.fire('Error', 'Failed to delete the item.',
                                'error');
                        }
                    });
                }
            });
        });

        // $(document).on('click', '.edit-item', function() {
        //     var itemId = $(this).data('id');

        //     $.ajax({
        //         url: '/bookkeeper/edit-expense-item',
        //         type: 'GET',
        //         data: {
        //             id: itemId
        //         },
        //         success: function(item) {
        //             $('#itemName').val(item.description);
        //             $('#itemCode').val(item.itemcode);
        //             $('#quantity').val(item.qty);
        //             $('#amount').val(item.amount);

        //             if (item.itemtype === 'Inventory') {
        //                 $('#inventory').prop('checked', true);
        //             } else {
        //                 $('#nonInventory').prop('checked', true);
        //             }

        //             if ($('#cashier_debit_account option[value="' + item.account_name + '"]')
        //                 .length === 0) {
        //                 $('#cashier_debit_account').append(
        //                     $('<option>', {
        //                         value: item.account_name,
        //                         text: item.account_name || item.account
        //                     })
        //                 );
        //             }
        //             setTimeout(() => {
        //                 $('#cashier_debit_account').val(item.account_name).trigger(
        //                     'change');
        //             }, 500);

        //             $('#addItemModalLabel').text('Edit Expense Item');

        //             $('#updateExpenseItem').data('id', item.id);

        //             $('#addExpenseItem').hide();
        //             $('#updateExpenseItem').show();

        //             $('#addItemModal').modal('show');
        //         },
        //         error: function(xhr) {
        //             console.error(xhr.responseText);
        //             Swal.fire('Error', 'Unable to fetch item details.', 'error');
        //         }
        //     });
        // });

        $(document).on('click', '.edit-item', function() {
            var itemId = $(this).data('id');

            $.ajax({
                url: '/bookkeeper/edit-expense-item',
                type: 'GET',
                data: {
                    id: itemId
                },
                success: function(item) {
                    $('#itemName').val(item.description);
                    $('#itemCode').val(item.itemcode);
                    $('#quantity').val(item.qty);
                    $('#amount').val(item.amount);

                    if (item.itemtype === 'Inventory') {
                        $('#inventory').prop('checked', true);
                    } else {
                        $('#nonInventory').prop('checked', true);
                    }

                    var accountOption = $('<option>', {
                        value: item.coaid,
                        text: item.account_code + ' - ' + item.account_name
                    });

                    if ($('#cashier_debit_account option[value="' + accountOption.val() + '"]').length === 0) {
                        $('#cashier_debit_account').append(accountOption);
                    }

                    setTimeout(() => {
                        $('#cashier_debit_account').val(accountOption.val()).trigger('change');
                    }, 500);

                    $('#addItemModalLabel').text('Edit Expense Item');

                    $('#updateExpenseItem').data('id', item.id);

                    $('#addExpenseItem').hide();
                    $('#updateExpenseItem').show();

                    $('#addItemModal').modal('show');
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire('Error', 'Unable to fetch item details.', 'error');
                }
            });
        });


        $(document).on('click', '#updateExpenseItem', function(e) {
            e.preventDefault();

            var itemId = $(this).data('id');

            $.ajax({
                url: '/bookkeeper/update-expense-item',
                type: 'PUT',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: itemId,
                    itemCode: $('#itemCode').val(),
                    itemName: $('#itemName').val(),
                    quantity: $('#quantity').val(),
                    amount: $('#amount').val(),
                    itemType: $('input[name="itemType"]:checked').attr('id') === 'inventory' ?
                        'Inventory' : 'Non Inventory Item',
                    debitAccount: $('#cashier_debit_account').val()
                },
                success: function(response) {
                    Swal.fire('Success', response.message, 'success');
                    $('#addItemModal').modal('hide');
                    $('#itemForm')[0].reset();
                    $('#addExpenseItem').show();
                    $('#updateExpenseItem').hide().removeData('id');
                    loadExpenseItems();
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire('Error', 'Failed to update item.', 'error');
                }
            });
        });


        // $('#addItemBtn').on('click', function() {
        //     $('#itemForm')[0].reset();

        //     $('#addItemModalLabel').text('New Item');
        //     $('#addExpenseItem').show();
        //     $('#updateExpenseItem').hide();

        //     $('#addExpenseItem').removeData('id');
        // });
    });

    function loadExpenseItems() {
        $.ajax({
            url: '/bookkeeper/expense-items',
            type: 'GET',
            success: function(items) {
                var tbody = $('#expenseTableBody');
                tbody.empty();

                if (items.length === 0) {
                    tbody.append(`
                    <tr>
                        <td colspan="6" class="text-center text-muted">No expense items found.</td>
                    </tr>
                `);
                    return;
                }

                items.forEach((item, index) => {
                    tbody.append(`
                    <tr>
                        <td>${item.itemcode || ''}</td>
                        <td>${item.description}</td>
                        <td>${item.qty || '0'}</td>
                        <td>${item.amount || '0.00'}</td>
                        <td>${item.itemtype}</td>
                        <td style="text-align: center;">
                            <i class="fas fa-edit edit-item text-primary" data-id="${item.id}" style="cursor: pointer; margin-right: 5px;"></i>
                            <i class="fas fa-trash-alt delete-item text-danger" data-id="${item.id}" style="cursor: pointer;"></i>
                        </td>
                    </tr>
                `);
                });
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert('Failed to load expense items.');
            }
        });
    }
</script>
