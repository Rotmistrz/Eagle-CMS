$(document).ready(function() {
    function Information(container) {
        this.container = container;

        this.setContent = function(content) {
            this.container.find('p').html(content);

            return this;
        }

        this.show = function() {
            var that = this;

            this.container.velocity("slideDown", {
                duration: 500,
                complete: function() {
                    that.container.velocity({ opacity: 1 }, {
                        duration: 500
                    });
                }
            });

            return this;
        }

        this.hide = function() {
            var that = this;

            if(this.container.is(":visible")) {
               this.container.velocity({ opacity: 0 }, {
                    duration: 500,
                    complete: function() {
                        that.container.velocity("slideUp", {
                            duration: 500
                        });
                    }
                }); 
            }
            

            return this;
        }

        this.run = function() {
            this.hide();
        }
    }

    function Layer(id) {
        this.id = id;
        this.layer = $('#' + this.id);
        this.classname = this.layer.attr('class');
        this.inner = this.layer.find('.' + this.classname + '__inner');
        this.contentContainer = this.layer.find('.' + this.classname + '__content');
        this.close = this.layer.find('.' + this.classname + '__close');

        this.show = function(callback) {
            var that = this;

            $('body').css({ overflow: 'hidden' });

            this.layer.velocity("fadeIn", {
                duration: 500,
                complete: function() {
                    that.contentContainer.velocity("slideDown", {
                        duration: 500,
                        complete: function() {
                            if(callback !== undefined) {
                                callback();
                            }
                        }
                    });
                }
            });

            return this;
        }

        this.hide = function(callback) {
            this.layer.velocity("fadeOut", {
                duration: 500,
                complete: function() {
                    $('body').css({ overflow: 'auto' });

                    if(callback !== undefined) {
                        callback();
                    }
                }
            });

            return this;
        }

        this.setContent = function(content) {
            this.contentContainer.hide().html('').html(content);
        }

        this.run = function() {
            var that = this;

            this.close.click(function() {
                that.hide();
            });

            this.layer.click(function() {
                that.hide();
            });

            this.inner.click(function(e) {
                e.stopPropagation();

                return;
            });

            return this;
        }
    }

    function AjaxRequestsManager(layer, simpleLayer) {
        this.layer = layer;
        this.simpleLayer = simpleLayer;

        this.requestLinks;
        this.requestForms;

        this.correctMessage = new Information($('#correct-message'));
        this.errorMessage = new Information($('#error-message'));

        this.sendRequest = function(request, item) {
            var that = this;

            that.correctMessage.hide();
            that.errorMessage.hide();

            $.ajax({
                type: "POST",
                url: "/eagle-cms/ajax.php",
                dataType: "json",
                data: { module: request.module, operation: request.operation, id: item.id, type: item.type, parent_id: item.parentId },
                success: function(result) {
                    console.log(result);

                    if(!result.error) {
                        if(result.module == 'item') {
                            switch(result.operation) {
                                case 'prepare-edit':
                                    that.layer.setContent(result.html);
                                    that.layer.show();
                                    that.refreshDependencies();
                                break;

                                case 'delete':
                                    var table = $('[data-table-type="' + result.item.type + '"]');
                                    var row = table.find('[data-item-id="' + result.item.id + '"]');
                                    
                                    row.velocity("slideUp");

                                    var nextRows = row.nextAll();
                                    nextRows.each(function() {
                                        var orderCell = $(this).find('.items-table__order-number');

                                        var orderNumber = parseInt(orderCell.html());

                                        orderCell.html(orderNumber - 1);
                                    });
                                break;

                                case 'prepare-delete':
                                    that.simpleLayer.setContent(result.html);
                                    that.simpleLayer.show();
                                    that.refreshDependencies();

                                    that.simpleLayer.layer.find('.choice-form__cancel').click(function() {
                                        that.simpleLayer.hide();
                                    });

                                    console.log(that.simpleLayer.layer.find('.choice-form'));

                                    var thisform = that.simpleLayer.layer.find('.choice-form').first();

                                    that.simpleLayer.layer.find('.choice-form').submit(function(e) {
                                        e.preventDefault();
                                        e.stopPropagation();

                                        that.simpleLayer.hide();

                                        that.sendRequest({ module: 'item', operation: 'delete' }, { id: item.id, parent_id: item.parent_id, type: item.type });
                                    });
                                break;

                                case 'hide':
                                    /**that.correctMessage.setContent(result.message);
                                    that.correctMessage.show();**/

                                    var table = $('[data-table-type="' + result.item.type + '"]');
                                    var row = table.find('[data-item-id="' + result.item.id + '"]');
                                    row.addClass('items-table__row--hidden');
                                break;

                                case 'show':
                                    /**that.correctMessage.setContent(result.message);
                                    that.correctMessage.show();**/

                                    var table = $('[data-table-type="' + result.item.type + '"]');
                                    var row = table.find('[data-item-id="' + result.item.id + '"]');
                                    row.removeClass('items-table__row--hidden');
                                break;

                                case 'item-up':
                                    console.log(result.message);

                                    if(result.item.earlier !== undefined) {
                                        var table = $('[data-table-type="' + result.item.type + '"]');
                                        var row = table.find('[data-item-id="' + result.item.id + '"]');

                                        var earlierRow = row.prev();

                                        var currentRowId = row.find('.items-table__order-number').html();
                                        var earlierRowId = earlierRow.find('.items-table__order-number').html();

                                        var height = row.outerHeight();

                                        row.velocity({ top: -height + "px"}, {
                                            duration: 200
                                        });

                                        earlierRow.velocity({ top: height + "px" }, {
                                            duration: 200
                                        });

                                        var rowCopy = row.clone();
                                        var earlierRowCopy = earlierRow.clone();

                                        rowCopy.find('.items-table__order-number').html(earlierRowId);
                                        earlierRowCopy.find('.items-table__order-number').html(currentRowId);

                                        setTimeout(function() {
                                            earlierRow.replaceWith(rowCopy);
                                            row.replaceWith(earlierRowCopy);

                                            that.refreshDependencies();
                                        }, 200);
                                    } else {
                                        that.correctMessage.setContent(result.message);
                                        that.correctMessage.show();
                                    }
                                break;

                                case 'item-down':
                                    console.log(result.message);

                                    if(result.item.later !== undefined) {
                                        var table = $('[data-table-type="' + result.item.type + '"]');
                                        var row = table.find('[data-item-id="' + result.item.id + '"]');

                                        var laterRow = row.next();

                                        var currentRowId = row.find('.items-table__order-number').html();
                                        var laterRowId = laterRow.find('.items-table__order-number').html();

                                        var height = row.outerHeight();

                                        row.velocity({ top: height + "px"}, {
                                            duration: 200
                                        });

                                        laterRow.velocity({ top: -height + "px" }, {
                                            duration: 200
                                        });

                                        var rowCopy = row.clone();
                                        var laterRowCopy = laterRow.clone();

                                        rowCopy.find('.items-table__order-number').html(laterRowId);
                                        laterRowCopy.find('.items-table__order-number').html(currentRowId);

                                        setTimeout(function() {
                                            laterRow.replaceWith(rowCopy);
                                            row.replaceWith(laterRowCopy);

                                            that.refreshDependencies();
                                        }, 200);
                                    } else {
                                        that.correctMessage.setContent(result.message);
                                        that.correctMessage.show();
                                    }
                                break;
                            }
                        }
                    } else {
                        that.errorMessage.setContent(result.message);
                        that.errorMessage.show();
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR + " " + textStatus + " " + errorThrown);
                },
                complete: function() {
                    //spinner.hide();
                }
            });

            return this;
        }

        this.sendForm = function(form) {
            var that = this;

            that.correctMessage.hide();
            that.errorMessage.hide();

            var formdata = new FormData(form);
            console.log($(form).find('[name="id"]').val());
            console.log($(form).find('[name="module"]').val());
            console.log($(form).find('[name="operation"]').val());
            console.log($(form).find('[name="parent_id"]').val());
            console.log($(form).find('[name="type"]').val());

            $.ajax({
                type: "POST",
                url: "/eagle-cms/ajax.php",
                dataType: "json",
                data: formdata,
                cache: false,
                processData: false, // Don't process the files
                contentType: false,
                success: function(result) {
                    console.log(result);

                    var callback = function() {
                        if(!result.error) {
                            if(result.module == 'item') {
                                switch(result.operation) {
                                    case 'prepare-add':
                                        that.layer.setContent(result.html);
                                        that.layer.show();
                                        that.refreshDependencies();
                                    break;

                                    case 'add':
                                        that.correctMessage.setContent(result.message);
                                        that.correctMessage.show();

                                        var table = $('[data-table-type="' + result.item.type + '"]');
                                        var row = $(result.item.row);
                                        row.hide();

                                        var existingRows = table.find('.items-table__row');
                                        var followingNumber = existingRows.length + 1;

                                        row.find('.items-table__order-number').html(followingNumber);

                                        table.append(row);
                                        row.velocity("slideDown");
                                    break;

                                    case 'edit':
                                        that.correctMessage.setContent(result.message);
                                        that.correctMessage.show();

                                        console.log(result.item.type);
                                        console.log(result.item.id);
                                        console.log($('[data-item-type=' + result.item.type + '][data-item-id=' + result.item.id + ']'));

                                        $('[data-item-type="' + result.item.type + '"][data-item-id="' + result.item.id + '"]')
                                            .find('.items-table__item-title')
                                            .velocity({ opacity: 0 }, {
                                                duration: 200,
                                                complete: function() {
                                                   $(this).html(result.item['header_1_pl']);
                                                   
                                                   $(this).velocity({ opacity: 1 }, {
                                                    duration: 200
                                                   }); 
                                                }
                                            });
                                            
                                    break;
                                }
                            }
                        } else {
                            that.errorMessage.setContent(result.message);
                            that.errorMessage.show();
                        }
                    }

                    that.layer.hide(callback);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR + " " + textStatus + " " + errorThrown);
                },
                complete: function() {
                    //spinner.hide();
                }
            });

            return this;
        }

        this.loadLayer = function(request, item) {
            var that = this;

            $.ajax({
                type: "POST",
                url: "/eagle-cms/ajax.php",
                dataType: "json",
                data: { 'operation': request.operation, 'module': request.module, 'id': item.id, 'parent_id': item.parentId, 'type': item.type },
                success: function(result) {
                    if(!result.error) {
                        that.layer.setContent(result.html);
                        that.layer.show();
                        that.refreshDependencies();
                    } else {
                        
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                },
                complete: function() {
                    //spinner.hide();
                }
            });

            return this;
        }

        this.refreshDependencies = function() {
            var that = this;

            this.requestLinks = $('.request-link');
            this.requestForms = $('.request-form');

            console.log(this.requestForms);

            this.requestLinks.unbind('click').click(function(e) {
                e.preventDefault();
                e.stopPropagation();

                var module = $(this).attr('data-module');
                var operation = $(this).attr('data-operation');
                var id = $(this).attr('data-id');
                var parent_id = $(this).attr('data-parent-id');
                var type = $(this).attr('data-type');

                that.sendRequest({ module: module, operation: operation }, { id: id, parentId: parent_id, type: type });
            });

            this.requestForms.unbind('submit').submit(function(e) {
                e.preventDefault();
                e.stopPropagation();

                that.sendForm(this);
            });
        }

        this.run = function() {
            this.correctMessage.hide();
            this.errorMessage.hide();

            this.refreshDependencies();
        }
    }

    var layer = new Layer('overlayer');
    layer.run();

    var simpleLayer = new Layer('simple-overlayer');
    simpleLayer.run();

    var manager = new AjaxRequestsManager(layer, simpleLayer);
    manager.run();
});