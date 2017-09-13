		{% set i = 1 %}	
	
		<section class="items-table" data-table-type="{{ type }}">
			<section class="items-table__header-row">
				<div class="items-table__cell items-table__order-number">L. p.</div>
				<div class="items-table__cell items-table__title">Tytuł</div>
				<div class="items-table__cell items-table__operations">Operacje</div>
			</section>

			{% for item in itemsCollection %}
				{% include('table-item-1.tpl') %}

				{% set i = i + 1 %}
			{% endfor %}
		</section>