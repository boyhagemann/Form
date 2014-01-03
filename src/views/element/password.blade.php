<div class="form-group{{ $state }}">
	@if($element->getLabel())
	{{ Form::label($element->getName(), $element->getLabel(), array('class' => 'col-sm-2 col-lg-2 control-label')) }}
	@else
	<div class="col-lg-2"></div>
	@endif
	<div class="col-sm-10 col-lg-6">
		{{ Form::password($element->getName(), $element->getAttributes()) }}
		@if($element->getHelp())
		<span class="help-block">{{ $element->getHelp() }}</span>
		@endif
	</div>
</div>