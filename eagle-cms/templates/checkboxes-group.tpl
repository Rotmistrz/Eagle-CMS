<div class="form__checkboxes-group">
	{{ title }}

	{% for checkbox in checkboxes %}
	<label class="form__field-container">
		<div class="form__checkbox">
			<input class="form__checkbox-field" name="{{ name }}[]" type="checkbox" value="{{ checkbox.value }}" {% if checkbox.checked %} checked="checked"{% endif %} />
			<div class="form__checkbox-description">
			{{ checkbox.description }}
			</div>
		</div>
	</label>
	{% endfor %}
</div>