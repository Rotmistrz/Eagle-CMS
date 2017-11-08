        {% set i = 1 %} 
    
        <section class="table data-table" data-table-type="{{ type }}">
            <section class="table__header-row">
                <div class="table__cell data-table__order-number">L. p.</div>
                <div class="table__cell data-table__title">Nazwa</div>
                <div class="table__cell data-table__operations">Operacje</div>
            </section>

            {% for item in itemsCollection %}
                {% include('table-data-row.tpl') %}

                {% set i = i + 1 %}
            {% endfor %}
        </section>