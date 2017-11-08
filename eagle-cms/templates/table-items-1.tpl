		{% set i = 1 %}	
	
		<section class="table items-table" data-table-type="{{ type }}">
			<section class="table__header-row">
				<div class="table__cell items-table__order-number">L. p.</div>
				<div class="table__cell items-table__title">Tytuł</div>
				<div class="table__cell items-table__operations">Operacje</div>
			</section>

			{% for item in itemsCollection %}
				{% include('table-item-1.tpl') %}

				{% set i = i + 1 %}
			{% endfor %}
		</section>