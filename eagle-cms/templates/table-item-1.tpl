            <section class="items-table__row {% if item.visible %}{% else %}items-table__row--hidden{% endif %}" data-item-id="{{ item.id }}" data-item-type="{{ item.type }}">
                <div class="items-table__cell items-table__order-number">{{ i }}</div>
                <div class="items-table__cell items-table__title">
                    <i class="fa fa-toggle-on item-visibility item-visibility--visible" aria-hidden="true"></i>
                    <i class="fa fa-toggle-off item-visibility item-visibility--hidden" aria-hidden="true"></i>

                    <a class="items-table__item-title" href="index.php?module=item&amp;operation=showcase&amp;type={{ item.type }}&amp;parent_id={{ item.id }}" title="Wyświetl">{{ item.header_1 }}</a>
                </div>
                <div class="items-table__cell items-table__operations">
                    <span class="request-link" data-module="item" data-operation="prepare-edit" data-id="{{ item.id }}" data-parent-id="{{ item.parentId }}" data-type="{{ item.type }}" href="index.php?module=item&amp;operation=edit&amp;type={{ item.type }}&amp;id={{ item.id }}&amp;parent_id={{ item.parentId }}" title="Edytuj"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edytuj</span>
                    <a href="index.php?module=item&amp;operation=delete&amp;type={{ item.type }}&amp;id={{ item.id }}&amp;parent_id={{ item.parentId }}" title="Usuń"><i class="fa fa-times" aria-hidden="true"></i> Usuń</a>
                    <a href="index.php?module=item&amp;operation=item-up&amp;id={{ item.id }}&amp;parent_id={{ item.parentId }}" title="W górę"><i class="fa fa-caret-square-o-up" aria-hidden="true"></i></a>
                    <a href="index.php?module=item&amp;operation=item-down&amp;id={{ item.id }}&amp;parent_id={{ item.parentId }}" title="W dół"><i class="fa fa-caret-square-o-down" aria-hidden="true"></i></a>
                    {% if item.visible %}<a href="index.php?module=item&amp;operation=hide&amp;id={{ item.id }}&amp;parent_id={{ item.parentId }}" title="Ukryj"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                    {% else %}<a href="index.php?module=item&amp;operation=show&amp;id={{ item.id }}&amp;parent_id={{ item.parentId }}" title="Uwidocznij"><i class="fa fa-eye" aria-hidden="true"></i></a>{% endif %}
                </div>
            </section>