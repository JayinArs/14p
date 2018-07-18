@extends('layouts.admin')

@section('top')
    {{ link_to_route('quiz.create', 'New Quiz', [], ['class'=>'btn btn-primary']) }}
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-body">
            <table class="table table table-striped table-hover table-bordered table-bordered-force"
                   id="quiz-table">
                <thead>
                <tr>
                    <th style="text-align: left;">Title</th>
                    <th style="text-align: left;">Rounds</th>
                    <th style="text-align: left;">Participants</th>
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
        var $table = $('#quiz-table');
        var $datatable = $table.DataTable({
            responsive: true,
            errMode: 'throw',
            ajax: '{{ route("quiz.data") }}',
            columns: [
                {
                    name: 'title',
                    data: 'title'
                },
                {
                    name: 'number_of_rounds',
                    data: 'number_of_rounds'
                },
                {
                    name: 'number_of_participants',
                    data: 'number_of_participants'
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
                document.location.href = '{{ route('quiz.edit', ['%%']) }}'.replace('%%', data['id']);
            }
        });

    });
</script>
@endpush