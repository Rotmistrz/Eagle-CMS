            <section class="table__row" data-page-id="{{ page.id }}">
                <div class="table__cell pages-table__order-number">{{ i }}</div>
                <div class="table__cell pages-table__slug">
                    {{ page.slug }}
                </div>
                <div class="table__cell pages-table__title">
                    {{ page.title }}
                </div>
                <div class="table__cell pages-table__operations">
                    <span class="request-link" data-module="page" data-operation="prepare-edit" data-id="{{ page.id }}" title="Edytuj"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edytuj</span>
                    <span class="request-link" data-module="page" data-operation="prepare-delete" data-id="{{ page.id }}" title="Usuń"><i class="fa fa-times" aria-hidden="true"></i> Usuń</span>
                </div>
            </section>