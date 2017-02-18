<select id="{{ id }}" name="{{ id }}">
	{% for option in optionsCollection %}
	<option value="{{ option.value }}"{% if option.value == default %} selected="selected"{% endif %}>{{ option.name }}</option>
	{% endfor %}
</select>