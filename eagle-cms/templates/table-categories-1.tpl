		{% set i = 1 %}	
	
		<section class="table categories-table" data-table-type="{{ type }}">
			<section class="table__header-row">
				<div class="table__cell categories-table__order-number">L. p.</div>
				<div class="table__cell categories-table__name">Nazwa</div>
				<div class="table__cell categories-table__operations">Operacje</div>
			</section>

			{% for item in categoriesCollection %}
				{% include('table-category-1.tpl') %}

				{% set i = i + 1 %}
			{% endfor %}
		</section>