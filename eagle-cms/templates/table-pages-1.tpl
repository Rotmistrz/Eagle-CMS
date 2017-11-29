        {% set i = 1 %} 
    
        <section class="table pages-table">
            <section class="table__header-row">
                <div class="table__cell pages-table__order-number">L. p.</div>
                <div class="table__cell pages-table__slug">Slug</div>
                <div class="table__cell pages-table__title">Tytuł</div>
                <div class="table__cell pages-table__operations">Operacje</div>
            </section>

            {% for page in pagesCollection %}
                {% include('table-page-1.tpl') %}

                {% set i = i + 1 %}
            {% endfor %}
        </section>