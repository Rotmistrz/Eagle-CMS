		{% set i = 1 %}	
	
		<table class="items-table">
			<tr class="header-row">
				<th class="order-number">L. p.</th>
				<th class="title">Tytuł</th>
				<th class="operations">Operacje</th>
			</tr>

			{% for category in categoriesCollection %}
			<tr>
				<td class="order-number">{{ i }}</td>
				<td class="title">{{ category.header_1 }}</td>
				<td class="operations">
					<a href="index.php?module=category&amp;operation=edit&amp;type={{ category.type }}&amp;id={{ category.id }}" title="Edytuj"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edytuj</a>
					<a href="index.php?module=category&amp;operation=delete&amp;type={{ category.type }}&amp;id={{ category.id }}" title="Usuń"><i class="fa fa-times" aria-hidden="true"></i> Usuń</a>
					<a href="index.php?module=category&amp;operation=category-up&amp;id={{ category.id }}" title="W górę"><i class="fa fa-caret-square-o-up" aria-hidden="true"></i></a>
					<a href="index.php?module=category&amp;operation=category-down&amp;id={{ category.id }}" title="W dół""><i class="fa fa-caret-square-o-down" aria-hidden="true"></i></a>
				</td>

				{% set i = i + 1 %}
			</tr>
			{% endfor %}
		</table>