            <section class="table__row{% if item.visible %}{% else %} table__row--hidden{% endif %}" data-item-id="{{ item.id }}" data-item-type="{{ item.type }}">
                <div class="table__cell items-table__order-number">{{ i }}</div>
                <div class="table__cell items-table__title">
                    <i class="fa fa-toggle-on item-visibility item-visibility--visible" aria-hidden="true"></i>
                    <i class="fa fa-toggle-off item-visibility item-visibility--hidden" aria-hidden="true"></i>

                    <a class="items-table__item-title" href="index.php?module=item&amp;operation=showcase&amp;type={{ item.type }}&amp;id={{ item.id }}" title="Wyświetl">{{ item.header_1 }}</a>
                </div>
                <div class="table__cell items-table__operations">
                    <span class="request-link" data-module="item" data-operation="prepare-edit" data-id="{{ item.id }}" data-parent-id="{{ item.parentId }}" data-type="{{ item.type }}" title="Edytuj"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edytuj</span>

                    <span class="request-link" data-module="item" data-operation="prepare-delete" data-id="{{ item.id }}" data-parent-id="{{ item.parentId }}" data-type="{{ item.type }}" title="Usuń"><i class="fa fa-times" aria-hidden="true"></i> Usuń</span>

                    <span class="request-link" data-module="item" data-operation="item-up" data-id="{{ item.id }}" data-parent-id="{{ item.parentId }}" data-type="{{ item.type }}" title="W górę"><i class="fa fa-caret-square-o-up" aria-hidden="true"></i></span>

                    <span class="request-link" data-module="item" data-operation="item-down" data-id="{{ item.id }}" data-parent-id="{{ item.parentId }}" data-type="{{ item.type }}" title="W dół"><i class="fa fa-caret-square-o-down" aria-hidden="true"></i></span>

                    <span class="request-link item-visibility-toggler--hide" data-module="item" data-operation="hide" data-id="{{ item.id }}" data-parent-id="{{ item.parentId }}" data-type="{{ item.type }}" title="Ukryj"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
                    
                    <span class="request-link item-visibility-toggler--show" data-module="item" data-operation="show" data-id="{{ item.id }}" data-parent-id="{{ item.parentId }}" data-type="{{ item.type }}" title="Uwidocznij"><i class="fa fa-eye" aria-hidden="true"></i></span>
                </div>
            </section>