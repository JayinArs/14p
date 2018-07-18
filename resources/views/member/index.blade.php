@extends('layouts.admin')

@section('content')
    <div class="panel panel-default">
        <div class="panel-body">
            <table class="table table table-striped table-hover table-bordered table-bordered-force"
                   id="members-table">
                <thead>
                <tr>
                    <th style="text-align: left;">First Name</th>
                    <th style="text-align: left;">Last Name</th>
                    <th style="text-align: left;">Email Address</th>
                    <th style="text-align: left;">Country</th>
                    <th style="text-align: left;">City</th>
                    <th style="text-align: left;">State</th>
                    <th style="max-width: 100px; min-width: 100px;" nowrap>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>

    $(function () {
        var $table = $('#members-table');
        var $datatable = $table.DataTable({
            responsive: true,
            errMode: 'throw',
            ajax: '{{ route("member.data") }}',
            columns: [
                {
                    name: 'first_name',
                    data: 'first_name'
                },
                {
                    name: 'last_name',
                    data: 'last_name'
                },
                {
                    name: 'email',
                    data: 'email'
                },
                {
                    name: 'country',
                    data: 'country'
                },
                {
                    name: 'city',
                    data: 'city'
                },
                {
                    name: 'state',
                    data: 'state'
                },
                {
                    bSortable: false,
                    mRender: function (o) {
                        return "<div class='btn-group'><button class='btn btn-primary btn-sm' role='edit'><i class='fa fa-edit'></i></button></div>";
                    }
                }
            ],
            dom: '<"html5buttons"B <"ml pull-right"C>>lTfgitp',
            colVis: {
                buttonText: "Column Visibility",
                activate: "click",
                sButtonClass: 'btn btn-primary'
            },
            buttons: [
                {
                    extend: 'pdf',
                    text: 'PDF',
                }, {
                    extend: 'csv',
                    text: 'CSV'
                }, {
                    extend: 'print',
                    text: 'Print'
                }
            ]
        });

        $table.find('tbody').on('click', '.btn-group button', function () {
            var $button = $(this);
            var role = $button.attr('role');
            var data = $datatable.row($button.parents('tr')).data();

            if (role === 'edit') {
                document.location.href = '{{ route('member.edit', ['%%']) }}'.replace('%%', data['id']);
            }
        });

    });
</script>
@endpush