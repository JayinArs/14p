@extends('layouts.admin')

@section('top')
    {{ link_to_route('quiz.index', 'Cancel', [], ['class'=>'btn btn-danger']) }}
@endsection

@section('content')
    {!! Form::open(['route' => ['quiz.store'], 'id' => 'add-quiz-form', 'method' => 'POST', 'data-parsley-validate' => ' ', 'novalidate' => ' ', 'class' => '']) !!}
    <div class="panel panel-default">
        <div class="panel-body">
            <fieldset>
                <legend>Quiz Information</legend>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Title</label>
                    <div class="col-sm-10">
                        {!! Form::text('title', false, [ 'class' => 'form-control' ]) !!}
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10">
                        {!! Form::textarea('description', false, [ 'class' => 'form-control' ]) !!}
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10">
                        {!! Form::select('status', Config::get('constants.quiz.status'), 1, [ 'class' => 'form-control' ]) !!}
                    </div>
                </div>
            </fieldset>
            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@push('scripts')
<script>
    jQuery(function ($) {
        $("#add-quiz-form").submit(function (e) {
            var $form = $(this);

            $.ajax({
                type: $form.prop('method'),
                url: $form.prop('action'),
                data: $form.serialize(),
                success: function (data) {
                    $.notify(data);

                    if (data.status === 'success') {
                        document.location.href = '{{ route('quiz.edit', ['%%']) }}'.replace('%%', data.quiz.id);
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