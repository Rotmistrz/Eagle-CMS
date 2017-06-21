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
				<td class="title">
					{% if item.visible %}
					<i class="fa fa-toggle-on" aria-hidden="true"></i>
					{% else %}
					<i class="fa fa-toggle-off" aria-hidden="true"></i>
					{% endif %}

					<a href="index.php?module=item&amp;operation=showcase&amp;type={{ item.type }}&amp;parent_id={{ item.id }}" title="Wyświetl">{{ item.header_1 }}</a>
				</td>
				<td class="operations">
					<a href="index.php?module=item&amp;operation=edit&amp;type={{ item.type }}&amp;id={{ item.id }}&amp;parent_id={{ item.parentId }}" title="Edytuj"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edytuj</a>
					<a href="index.php?module=item&amp;operation=delete&amp;type={{ item.type }}&amp;id={{ item.id }}&amp;parent_id={{ item.parentId }}" title="Usuń"><i class="fa fa-times" aria-hidden="true"></i> Usuń</a>
					<a href="index.php?module=item&amp;operation=item-up&amp;id={{ item.id }}&amp;parent_id={{ item.parentId }}" title="W górę"><i class="fa fa-caret-square-o-up" aria-hidden="true"></i></a>
					<a href="index.php?module=item&amp;operation=item-down&amp;id={{ item.id }}&amp;parent_id={{ item.parentId }}" title="W dół"><i class="fa fa-caret-square-o-down" aria-hidden="true"></i></a>
					{% if item.visible %}<a href="index.php?module=item&amp;operation=hide&amp;id={{ item.id }}&amp;parent_id={{ item.parentId }}" title="Ukryj"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
					{% else %}<a href="index.php?module=item&amp;operation=show&amp;id={{ item.id }}&amp;parent_id={{ item.parentId }}" title="Uwidocznij"><i class="fa fa-eye" aria-hidden="true"></i></a>{% endif %}
				</td>

				{% set i = i + 1 %}
			</tr>
			{% endfor %}
		</table>