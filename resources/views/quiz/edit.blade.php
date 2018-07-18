@extends('layouts.admin')

@section('top')
    {!! Form::open(['route' => ['quiz.destroy', $quiz->id], 'method' => 'DELETE', 'style' => 'display: inline;']) !!}
    <button class="btn btn-danger">Delete</button>
    {!! Form::close() !!}
    {{ link_to_route('quiz.index', 'Cancel', [], ['class'=>'btn btn-danger']) }}
@endsection

@section('content')
    {!! Form::open(['route' => ['quiz.update', $quiz->id], 'id' => 'edit-quiz-form', 'method' => 'PUT', 'data-parsley-validate' => ' ', 'novalidate' => ' ', 'class' => '']) !!}
    <div class="panel panel-default">
        <div class="panel-body">
            <fieldset>
                <legend>Quiz Information</legend>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Title</label>
                    <div class="col-sm-10">
                        {!! Form::text('title', $quiz->title, [ 'class' => 'form-control' ]) !!}
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10">
                        {!! Form::textarea('description', $quiz->description, [ 'class' => 'form-control' ]) !!}
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10">
                        {!! Form::select('status', Config::get('constants.quiz.status'), $quiz->status, [ 'class' => 'form-control' ]) !!}
                    </div>
                </div>
            </fieldset>
            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
        </div>
    </div>
    {!! Form::close() !!}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Rounds</h3>
        </div>
        <div class="panel-body">
            <table class="table table table-striped table-hover table-bordered table-bordered-force"
                   id="rounds-table">
                <thead>
                <tr>
                    <th style="text-align: left;">Title</th>
                    <th style="text-align: left;">Type</th>
                    <th style="text-align: left;">Start Date</th>
                    <th style="text-align: left;">Expiration Date</th>
                    <th style="text-align: left;">Questions Limit</th>
                    <th style="text-align: left;">Number of Questions</th>
                    <th style="max-width: 100px; min-width: 100px;" nowrap>Action</th>
                </tr>
                </thead>
            </table>
            {!! Form::open(['route' => ['round.store'], 'id' => 'add-quiz-round-form', 'method' => 'POST', 'data-parsley-validate' => ' ', 'novalidate' => ' ', 'class' => '']) !!}
            <fieldset>
                <legend>Add New Round</legend>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Title</label>
                    <div class="col-sm-10">
                        {!! Form::text('title', false, [ 'class' => 'form-control' ]) !!}
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Questions Limit</label>
                    <div class="col-sm-10">
                        {!! Form::number('limit', false, [ 'class' => 'form-control' ]) !!}
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Type</label>
                    <div class="col-sm-10">
                        {!! Form::select('type', Config::get('constants.quiz.type'), 0, [ 'class' => 'form-control' ]) !!}
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Start Date</label>
                    <div class="col-sm-10">
                        {!! Form::text('start_date', false, [ 'class' => 'form-control', 'placeholder' => 'YYYY-MM-DD', 'pattern' => '[0-9]{4}-[0-9]{2}-[0-9]{2}' ]) !!}
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Expiration Date</label>
                    <div class="col-sm-10">
                        {!! Form::text('expiration_date', false, [ 'class' => 'form-control', 'placeholder' => 'YYYY-MM-DD', 'pattern' => '[0-9]{4}-[0-9]{2}-[0-9]{2}' ]) !!}
                    </div>
                </div>
            </fieldset>
            {!! Form::hidden('quiz_id', $quiz->id) !!}
            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@push('scripts')
<script>
    jQuery(function ($) {
        var $table = $('#rounds-table');
        var round_types = {!! json_encode(Config::get('constants.quiz.type')) !!};
        var $datatable = $table.DataTable({
            responsive: true,
            errMode: 'throw',
            ajax: '{{ route("round.data", $quiz->id) }}',
            columns: [
                {
                    name: 'title',
                    data: 'title'
                },
                {
                    name: 'type',
                    data: function (row) {
                        return round_types[row.type];
                    }
                },
                {
                    name: 'start_date',
                    data: 'start_date'
                },
                {
                    name: 'expiration_date',
                    data: 'expiration_date'
                },
                {
                    name: 'limit',
                    data: 'limit'
                },
                {
                    name: 'number_of_questions',
                    data: 'number_of_questions'
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
                document.location.href = '{{ route('round.edit', '%%') }}'.replace('%%', data.id);
            }
        });
        $("#edit-quiz-form").submit(function (e) {
            var $form = $(this);

            $.ajax({
                type: $form.prop('method'),
                url: $form.prop('action'),
                data: $form.serialize(),
                success: function (data) {
                    $.notify(data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    try {
                        var data = $.parseJSON(jqXHR.responseText);
                        if (data.hasOwnProperty('errors')) {
                            var errors = data.errors;
                            for (var key in errors) {
                                var errorMessages = errors[key];
                                var fieldInstance = $form.find('[name="' + key + '"]').parsley(),
                                    errorName = key + '-validation-response';
                                fieldInstance.removeError(errorName);
                                fieldInstance.addError(errorName, {message: errorMessages[0]});
                            }
                        }
                    } catch (e) {
                        $.notify({
                            status: 'danger',
                            message: 'Internel Server Error.'
                        });
                    }
                }
            });
            e.preventDefault();
        });
        $("#add-quiz-round-form").submit(function (e) {
            var $form = $(this);

            $.ajax({
                type: $form.prop('method'),
                url: $form.prop('action'),
                data: $form.serialize(),
                success: function (data) {
                    $.notify(data);

                    if (data.status === 'success') {
                        $datatable.ajax.reload();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    try {
                        var data = $.parseJSON(jqXHR.responseText);
                        if (data.hasOwnProperty('errors')) {
                            var errors = data.errors;
                            for (var key in errors) {
                                var errorMessages = errors[key];
                                var fieldInstance = $form.find('[name="' + key + '"]').parsley(),
                                    errorName = key + '-validation-response';
                                fieldInstance.removeError(errorName);
                                fieldInstance.addError(errorName, {message: errorMessages[0]});
                            }
                        }
                    } catch (e) {
                        $.notify({
                            status: 'danger',
                            message: 'Internel Server Error.'
                        });
                    }
                }
            });
            e.preventDefault();
        });
    });
</script>
@endpush