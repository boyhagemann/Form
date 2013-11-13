<div class="form-group{{ $state }}">
	{{ Form::label($element->getName(), $element->getLabel(), array('class' => 'col-sm-2 col-lg-2 control-label')) }}
	<div class="col-sm-10 col-lg-10">
		{{ Form::select($element->getName(), $element->getChoices(), $element->getValue(), $element->getAttributes()) }}
		@if($element->getHelp())
		<span class="help-block">{{ $element->getHelp() }}</span>
		@endif
	</div>
</div>