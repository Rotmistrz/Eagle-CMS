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
					<a href="/index.php?module=edit&amp;type={{ item.type }}&amp;id={{ item.id }}">Edytuj</a>
					<a href="/index.php?module=delete&amp;type={{ item.type }}&amp;id={{ item.id }}">Usuń</a>
				</td>

				{% set i = i + 1 %}
			</tr>
			{% endfor %}
		</table>