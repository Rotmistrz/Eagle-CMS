            <section class="table__row" data-data-defined-id="{{ data.id }}">
                <div class="table__cell data-defined-table__order-number">{{ i }}</div>
                <div class="table__cell data-defined-table__code">
                    {{ data.code }}
                </div>
                <div class="table__cell data-defined-table__value">
                    {{ data.value }}
                </div>
                <div class="table__cell data-defined-table__operations">
                    <span class="request-link" data-module="data-defined" data-operation="prepare-edit" data-id="{{ data.id }}" title="Edytuj"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edytuj</span>
                    <span class="request-link" data-module="data-defined" data-operation="prepare-delete" data-id="{{ data.id }}" title="Usuń"><i class="fa fa-times" aria-hidden="true"></i> Usuń</span>
                </div>
            </section>