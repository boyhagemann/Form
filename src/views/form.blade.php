
@if($fb->getModel())
{{ Form::model($fb->getModel(), $fb->getOptions() + $fb->getAttributes()) }}
@else
{{ Form::open($fb->getOptions() + $fb->getAttributes()) }}
@endif

@foreach($fb->getElements() as $element)
{{ $fb->buildElement($element) }}
@endforeach

<div class="form-group">
	<hr>
	<div class="col-sm-offset-2 col-lg-offset-2 col-sm-10  col-lg-10">
		{{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
		{{ Form::reset('Reset', array('class' => 'btn')) }}
	</div>
</div>

{{ Form::close() }}
