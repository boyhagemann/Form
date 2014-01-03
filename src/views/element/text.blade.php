<div class="form-group{{ $state }}">
	@if($element->getLabel())
	{{ Form::label($element->getName(), $element->getLabel(), array('class' => 'col-sm-2 col-lg-2 control-label')) }}
	@else
	<div class="col-lg-2"></div>
	@endif
	<div class="col-sm-10 col-lg-6">
		{{ Form::text($element->getName(), $element->getValue(), $element->getAttributes()) }}
		@if($element->getHelp())
		<p class="help-block">{{ $element->getHelp() }}</p>
		@endif
	</div>
</div>