<form id="{{ id }}" class="form choice-form" action="{{ action }}" method="post">
	<fieldset>
		<input id="accepted" name="accepted" type="hidden" value="1" />

		<h1 class="form__title">{{ title }}</h1>

		<div class="button-group button-group--horizontal">
			<button class="button choice-form__accept" type="submit">Tak</button>
			<span class="button choice-form__cancel">Nie</span>
		</div>
	</fieldset>
</form>