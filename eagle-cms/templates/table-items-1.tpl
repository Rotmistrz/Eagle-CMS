		{% set i = 1 %}	
	
		<table class="items-table">
			<tr class="header-row">
				<th class="order-number">L. p.</th>
				<th class="title">Tytuł</th>
				<th class="operations">Operacje</th>
			</tr>

			{% for item in itemsCollection %}
			<tr>
				<td class="order-number">{{ i }}</td>
				<td class="title">{{ item.header_1 }}</td>
				<td class="operations">
					<a href="index.php?module=item&amp;operation=edit&amp;type={{ item.type }}&amp;id={{ item.id }}">Edytuj</a>
					<a href="index.php?module=item&amp;operation=delete&amp;type={{ item.type }}&amp;id={{ item.id }}">Usuń</a><br />
					<a href="index.php?module=item&amp;operation=item-up&amp;id={{ item.id }}">W górę</a>
					<a href="index.php?module=item&amp;operation=item-down&amp;id={{ item.id }}">W dół</a>
					{% if item.visible %}<a href="index.php?module=item&amp;operation=hide&amp;id={{ item.id }}">Ukryj</a>
					{% else %}<a href="index.php?module=item&amp;operation=show&amp;id={{ item.id }}">Uwidocznij</a>{% endif %}
				</td>

				{% set i = i + 1 %}
			</tr>
			{% endfor %}
		</table>