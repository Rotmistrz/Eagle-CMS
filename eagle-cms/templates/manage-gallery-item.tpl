            <div class="pictures-list__item" data-picture-id="{{ picture.id }}">
                <section class="picture-item">
                    <div class="picture-item__inner">
                        <section class="picture-item__picture-container">
                            <img class="picture-item__picture" src="/uploads/galleries/{{ picture.id }}-square.{{ picture.extension }}?id={{ time }}" alt="" />
                        </section>

                        <section class="picture-item__operations">
                            <div class="picture-item__operation-row">
                                <span class="request-link" data-module="item" data-operation="prepare-edit-gallery-picture" data-id="{{ picture.id }}" data-parent-id="0" data-type="0" title="Edytuj"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edytuj</span>
                            </div>

                            <div class="picture-item__operation-row">
                                <span class="request-link" data-module="item" data-operation="prepare-delete-gallery-picture" data-item-id="{{ picture.itemId }}" data-id="{{ picture.id }}" data-parent-id="0" data-type="0" title="Usuń"><i class="fa fa-times" aria-hidden="true"></i> Usuń</span>
                            </div>

                            <div class="picture-item__operation-row">
                                <span class="request-link" data-module="item" data-operation="gallery-picture-up" data-item-id="{{ picture.itemId }}" data-id="{{ picture.id }}" data-parent-id="0" data-type="0" title="W górę">&lt;&lt;</span>

                                <span class="request-link" data-module="item" data-operation="gallery-picture-down" data-item-id="{{ picture.itemId }}" data-id="{{ picture.id }}" data-parent-id="0" data-type="0" title="W dół">&gt;&gt;</span>
                            </div>
                        </section>
                    </div>
                </section>
            </div>