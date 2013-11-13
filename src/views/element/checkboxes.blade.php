<div class="form-group{{ $state }}">
	{{ Form::label($element->getName(), $element->getLabel(), array('class' => 'col-sm-2 col-lg-2 control-label')) }}
	<div class="col-sm-10 col-lg-10">
		@foreach($element->getChoices() as $key => $value)
			<label class="choice">{{ Form::checkbox($element->getName(), $key, in_array($key, $element->getValue()), $element->getAttributes()) }} {{ $value }}</label>
		@endforeach
		@if($element->getHelp())
		<span class="help-block">{{ $element->getHelp() }}</span>
		@endif
	</div>
</div>