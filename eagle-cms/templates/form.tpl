<form id="{{ id }}" class="{{ class }}" action="{{ action }}" method="{{ method }}" enctype="multipart/form-data">
	<fieldset>
		{% if title|length > 0 %}
		<header class="form__header">
			<h1 class="form__title">{{ title }}</h1>
		</header>
		{% endif %}

		{{ content }}
	</fieldset>
</form>