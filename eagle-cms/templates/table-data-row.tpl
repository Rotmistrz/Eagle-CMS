            <section class="table__row" data-item-id="{{ item.id }}" data-item-type="{{ item.type }}">
                <div class="table__cell data-table__order-number">{{ i }}</div>
                <div class="table__cell data-table__title">
                    {{ item.code }}
                </div>
                <div class="table__cell data-table__operations">
                    <span class="request-link" data-module="data" data-operation="prepare-edit" data-id="{{ item.id }}" title="Edytuj"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edytuj</span>
                    <span class="request-link" data-module="data" data-operation="prepare-delete" data-id="{{ item.id }}" data-type="{{ item.type }}" title="Usuń"><i class="fa fa-times" aria-hidden="true"></i> Usuń</span>
                </div>
            </section>