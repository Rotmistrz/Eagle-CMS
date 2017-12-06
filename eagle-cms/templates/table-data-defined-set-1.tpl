        {% set i = 1 %} 
    
        <section class="table data-defined-table">
            <section class="table__header-row">
                <div class="table__cell data-defined-table__order-number">L. p.</div>
                <div class="table__cell data-defined-table__code">Kod</div>
                <div class="table__cell data-defined-table__value">Wartość</div>
                <div class="table__cell data-defined-table__operations">Operacje</div>
            </section>

            {% for data in dataCollection %}
                {% include('table-data-defined-1.tpl') %}

                {% set i = i + 1 %}
            {% endfor %}
        </section>