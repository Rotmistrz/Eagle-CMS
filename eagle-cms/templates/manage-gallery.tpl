        {% set i = 1 %} 
    
        <section class="pictures-list" data-item-id="{{ itemId }}">
            <section class="pictures-list__header">
                <button class="request-link" data-id="0" data-module="gallery-picture" data-operation="prepare-add" data-item-id="{{ itemId }}">Dodaj zdjęcie</button>

                <h2 class="pictures-list__title">Galeria</h2>
            </section>

            <section class="pictures-list__inner">
                <div class="container">
                    {% for picture in picturesCollection %}
                        {% include('manage-gallery-item.tpl') %}

                        {% set i = i + 1 %}
                    {% endfor %}
                </div>
            </section>
        </section>