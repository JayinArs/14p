@extends('layouts.admin')

@section('top')
    {!! Form::open(['route' => ['round.destroy', $round->id], 'method' => 'DELETE', 'style' => 'display: inline;']) !!}
    <button class="btn btn-danger">Delete</button>
    <input type="hidden" name="redirect" value="{{ route('quiz.edit', $round->quiz->id) }}"/>
    {!! Form::close() !!}
    {{ link_to_route('quiz.edit', 'Cancel', [$round->quiz->id], ['class'=>'btn btn-danger']) }}
@endsection

@section('content')
    {!! Form::open(['route' => ['round.update', $round->id], 'id' => 'edit-round-form', 'method' => 'PUT', 'data-parsley-validate' => ' ', 'novalidate' => ' ', 'class' => '']) !!}
    <div class="panel panel-default">
        <div class="panel-body">
            <fieldset>
                <legend>Quiz Round Information</legend>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Title</label>
                    <div class="col-sm-10">
                        {!! Form::text('title', $round->title, [ 'class' => 'form-control' ]) !!}
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Questions Limit</label>
                    <div class="col-sm-10">
                        {!! Form::number('limit', $round->limit, [ 'class' => 'form-control' ]) !!}
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Type</label>
                    <div class="col-sm-10">
                        {!! Form::select('type', Config::get('constants.quiz.type'), $round->type, [ 'class' => 'form-control' ]) !!}
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Start Date</label>
                    <div class="col-sm-10">
                        {!! Form::text('start_date', \Carbon\Carbon::parse($round->start_date)->toDateString(), [ 'class' => 'form-control', 'placeholder' => 'YYYY-MM-DD', 'pattern' => '[0-9]{4}-[0-9]{2}-[0-9]{2}' ]) !!}
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Expiration Date</label>
                    <div class="col-sm-10">
                        {!! Form::text('expiration_date', \Carbon\Carbon::parse($round->expiration_date)->toDateString(), [ 'class' => 'form-control', 'placeholder' => 'YYYY-MM-DD', 'pattern' => '[0-9]{4}-[0-9]{2}-[0-9]{2}' ]) !!}
                    </div>
                </div>
            </fieldset>
            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
        </div>
    </div>
    {!! Form::close() !!}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Questions</h3>
        </div>
        <div class="panel-body">
            <table class="table table table-striped table-hover table-bordered table-bordered-force"
                   id="questions-table">
                <thead>
                <tr>
                    <th style="text-align: left;">Question</th>
                    <th style="text-align: left;">Type</th>
                    <th style="text-align: left;">Time</th>
                    <th style="max-width: 100px; min-width: 100px;" nowrap>Action</th>
                </tr>
                </thead>
            </table>
            {!! Form::open(['route' => ['question.store'], 'id' => 'add-question-form', 'method' => 'POST', 'data-parsley-validate' => ' ', 'novalidate' => ' ', 'class' => '']) !!}
            <fieldset>
                <legend>Add New Question</legend>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Question</label>
                    <div class="col-sm-10">
                        {!! Form::text('question', false, [ 'class' => 'form-control' ]) !!}
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Time</label>
                    <div class="col-sm-10">
                        {!! Form::text('time', '00:00:45', [ 'class' => 'form-control', 'placeholder' => '00:00:00', 'pattern' => '[0-9]{2}:[0-9]{2}:[0-9]{2}' ]) !!}
                        <small>Format: hh:mm:ss</small>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Type</label>
                    <div class="col-sm-10">
                        {!! Form::select('type', Config::get('constants.question.type'), false, [ 'id' => 'select-question-type', 'class' => 'form-control' ]) !!}
                    </div>
                </div>
            </fieldset>
            <div id="choice-fields">
                <fieldset>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Choices</label>
                        <div class="col-sm-10">
                            <div id="choices">
                                {!! Form::text('choices[]', false, [ 'class' => 'form-control choice-field mb' ]) !!}
                            </div>
                            <a href="#" id="btn-add-choice" class="btn btn-primary">Add Choice</a>
                        </div>
                    </div>
                </fieldset>
            </div>
            {!! Form::hidden('quiz_round_id', $round->id) !!}
            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@push('scripts')
<script>
    jQuery(function ($) {
        var $table = $('#questions-table');
        var types = {!! json_encode(Config::get('constants.question.type')) !!};
        var $datatable = $table.DataTable({
            responsive: true,
            errMode: 'throw',
            ajax: '{{ route("question.data", $round->id) }}',
            columns: [
                {
                    name: 'question',
                    data: 'question'
                },
                {
                    name: 'type',
                    data: function (row) {
                        return types[row.type];
                    }
                },
                {
                    name: 'time',
                    data: 'time'
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
                window.location.href = '{{ route('question.edit', '%%') }}'.replace('%%', data.id);
            }
        });

        $("#select-question-type").on("change", function () {
            if ($(this).val() === '2')
                $("#choice-fields").hide();
            else
                $("#choice-fields").show();
        });
        $("#btn-add-choice").on("click", function (e) {
            e.preventDefault();

            var $new_input = $("#choices input").first().clone();
            $new_input.val("");

            $("#choices").append($new_input);
            $new_input.focus();
        });
        $("#edit-round-form").submit(function (e) {
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
        $("#add-question-form").submit(function (e) {
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