@extends('layouts.admin')

@section('top')
    {!! Form::open(['route' => ['question.destroy', $question->id], 'method' => 'DELETE', 'style' => 'display: inline;']) !!}
    <button class="btn btn-danger">Delete</button>
    <input type="hidden" name="redirect" value="{{ route('round.edit', $question->quiz_round_id) }}"/>
    {!! Form::close() !!}
    {{ link_to_route('round.edit', 'Cancel', [$question->quiz_round_id], ['class'=>'btn btn-danger']) }}
@endsection

@section('content')
    {!! Form::open(['route' => ['question.update', $question->id], 'id' => 'edit-question-form', 'method' => 'PUT', 'data-parsley-validate' => ' ', 'novalidate' => ' ', 'class' => '']) !!}
    <div class="panel panel-default">
        <div class="panel-body">
            <fieldset>
                <legend>Question Information</legend>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Question</label>
                    <div class="col-sm-10">
                        {!! Form::text('question', $question->question, [ 'class' => 'form-control' ]) !!}
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Time</label>
                    <div class="col-sm-10">
                        {!! Form::text('time', $question->time, [ 'class' => 'form-control', 'placeholder' => '00:00:00', 'pattern' => '[0-9]{2}:[0-9]{2}:[0-9]{2}' ]) !!}
                        <small>Format: hh:mm:ss</small>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Type</label>
                    <div class="col-sm-10">
                        {!! Form::select('type', Config::get('constants.question.type'), $question->type, [ 'id' => 'select-question-type', 'class' => 'form-control' ]) !!}
                    </div>
                </div>
            </fieldset>
            <div id="choice-fields" style="display: {{ ($question->type != '2' ? 'block' : 'none') }}">
                <fieldset>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Choices</label>
                        <div class="col-sm-10">
                            <div id="choices">
                                @if(!empty($question->answers->toArray()))
                                    @foreach($question->answers as $answer)
                                        {!! Form::text('choices[]', $answer->answer, [ 'class' => 'form-control choice-field mb' ]) !!}
                                    @endforeach
                                @else
                                    {!! Form::text('choices[]', false, [ 'class' => 'form-control choice-field mb' ]) !!}
                                @endif
                            </div>
                            <a href="#" id="btn-add-choice" class="btn btn-primary">Add Choice</a>
                        </div>
                    </div>
                </fieldset>
            </div>
            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@push('scripts')
<script>
    jQuery(function ($) {
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
        $("#edit-question-form").submit(function (e) {
            var $form = $(this);

            $.ajax({
                type: $form.prop('method'),
                url: $form.prop('action'),
                data: $form.serialize(),
                success: function (data) {
                    $.notify(data);

                    window.location.reload();
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