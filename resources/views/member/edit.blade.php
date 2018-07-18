@extends('layouts.admin')

@section('top')
    {!! Form::open(['route' => ['member.destroy', $member->id], 'method' => 'DELETE', 'style' => 'display: inline;']) !!}
    <button class="btn btn-danger">Delete</button>
    {!! Form::close() !!}
    {{ link_to_route('member.index', 'Cancel', [], ['class'=>'btn btn-danger']) }}
@endsection

@section('content')
    {!! Form::open(['route' => ['member.update', $member->id], 'id' => 'edit-member-form', 'method' => 'PUT', 'data-parsley-validate' => ' ', 'novalidate' => ' ', 'class' => '']) !!}
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-9">
                    <fieldset>
                        <legend>Member Information</legend>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">First Name</label>
                            <div class="col-sm-10">
                                {!! Form::text('first_name', $member->first_name, [ 'class' => 'form-control' ]) !!}
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Last Name</label>
                            <div class="col-sm-10">
                                {!! Form::text('last_name', $member->last_name, [ 'class' => 'form-control' ]) !!}
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                {!! Form::email('email', $member->email, [ 'class' => 'form-control' ]) !!}
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Phone</label>
                            <div class="col-sm-10">
                                {!! Form::text('phone', $member->phone, [ 'class' => 'form-control' ]) !!}
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Age Group</label>
                            <div class="col-sm-10">
                                {!! Form::select('age_group', Config::get('constants.member.age_groups'), $member->age_group, [ 'class' => 'form-control' ]) !!}
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Gender</label>
                            <div class="col-sm-10">
                                {!! Form::select('gender', Config::get('constants.member.genders'), $member->gender, [ 'class' => 'form-control' ]) !!}
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Country</label>
                            <div class="col-sm-10">
                                {!! Form::text('country', $member->country, [ 'class' => 'form-control' ]) !!}
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">City</label>
                            <div class="col-sm-10">
                                {!! Form::text('city', $member->city, [ 'class' => 'form-control' ]) !!}
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">State</label>
                            <div class="col-sm-10">
                                {!! Form::text('state', $member->state, [ 'class' => 'form-control' ]) !!}
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Street</label>
                            <div class="col-sm-10">
                                {!! Form::text('street', $member->street, [ 'class' => 'form-control' ]) !!}
                            </div>
                        </div>
                    </fieldset>
                    <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                </div>
                <div class="col-md-3">
                    @if($member->photo)
                        <fieldset>
                            <legend>Member Photo</legend>
                            <img src="{{ url($member->photo) }}" style="max-width: 100%;"/>
                        </fieldset>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@push('scripts')
<script>
    jQuery(function ($) {
        $("#edit-member-form").submit(function (e) {
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