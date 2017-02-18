<form id="{{ id }}" class="form choice-form" action="{{ action }}" method="post">
	<fieldset>
		<input id="accepted" name="accepted" type="hidden" value="1" />

		<h1 class="form__title">{{ title }}</h1>

		<div class="button-group button-group--horizontal">
			<button class="button" type="submit">Tak</button>
			<a class="button" href="{{ back }}">Nie</a>
		</div>
	</fieldset>
</form>