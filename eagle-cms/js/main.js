$(document).ready(function() {
    function mergeObjects(mainObject, object) {
        var result = {};

        if(object === undefined) {
            return mainObject;
        }

        for (var property in object) {
            if (object.hasOwnProperty(property)) {
                result[property] = object[property];
            }
        }

        for (var property in mainObject) {
            if (mainObject.hasOwnProperty(property)) {
                result[property] = (object.hasOwnProperty(property)) ? object[property] : mainObject[property];
            }
        }

        return result;
    }

    function scrollTo(options) {
        var defaultOptions = {
            container: window,
            anchor: '#top-anchor',
            callback: function() {},
            delay: 0,
            duration: 800,
            offset: 0
        };

        options = mergeObjects(defaultOptions, options);

        setTimeout(function(){
            $(options.container).scrollTo(options.anchor, options.duration, {'axis': 'y', 'offset': options.offset, onAfter: function() { options.callback(); } });
        }, options.delay);

        return this;
    }

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

        this.spinnerLayer = $('#spinner-overlayer');

        this.requestLinks;
        this.requestForms;

        this.correctMessage = new Information($('#correct-message'));
        this.errorMessage = new Information($('#error-message'));
        this.overlayerErrorMessage = new Information($('#overlayer-error-message'));

        this.modules = {
            item: "item",
            galleryPicture: "gallery-picture",
            data: "data",
            page: "page",
            dataDefined: "data-defined"
        };

        this.sendRequest = function(generalData, moduleData) {
            var that = this;

            that.correctMessage.hide();
            that.errorMessage.hide();
            that.overlayerErrorMessage.container.hide();

            var data = mergeObjects(generalData, moduleData);

            console.log(data);

            $.ajax({
                type: "POST",
                url: "/eagle-cms/ajax.php",
                dataType: "json",
                data: data,
                success: function(result) {
                    console.log(result);

                    if(!result.error) {
                        if(result.module == that.modules.item) {
                            if(result.operation == 'prepare-edit') {
                                that.layer.setContent(result.html);
                                that.layer.show();
                                that.refreshDependencies();

                                $('.trumbowyg').trumbowyg();
                            } else if(result.operation == 'delete') {
                                var table = $('[data-table-type="' + result.item.type + '"]');
                                var row = table.find('[data-item-id="' + result.item.id + '"]');
                                
                                row.velocity("slideUp");

                                var nextRows = row.nextAll();
                                nextRows.each(function() {
                                    var orderCell = $(this).find('.items-table__order-number');

                                    var orderNumber = parseInt(orderCell.html());

                                    orderCell.html(orderNumber - 1);
                                });
                            } else if(result.operation == 'prepare-delete') {
                                that.simpleLayer.setContent(result.html);
                                that.simpleLayer.show();
                                that.refreshDependencies();

                                that.simpleLayer.layer.find('.choice-form__cancel').click(function() {
                                    that.simpleLayer.hide();
                                });

                                var thisform = that.simpleLayer.layer.find('.choice-form').first();

                                that.simpleLayer.layer.find('.choice-form').submit(function(e) {
                                    e.preventDefault();
                                    e.stopPropagation();

                                    that.simpleLayer.hide();

                                    that.sendRequest({ module: that.modules.item, operation: 'delete' }, moduleData);
                                });
                            } else if(result.operation == 'hide') {
                                var table = $('[data-table-type="' + result.item.type + '"]');
                                var row = table.find('[data-item-id="' + result.item.id + '"]');
                                row.addClass('table__row--hidden');
                            } else if(result.operation == 'show') {
                                var table = $('[data-table-type="' + result.item.type + '"]');
                                var row = table.find('[data-item-id="' + result.item.id + '"]');
                                row.removeClass('table__row--hidden');
                            } else if(result.operation == 'item-up') {
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
                            } else if(result.operation == 'item-down') {
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
                            }
                        } else if(result.module == that.modules.galleryPicture) {
                            if(result.operation == 'prepare-add' || result.operation == 'prepare-edit') {
                                that.layer.setContent(result.html);
                                that.layer.show();
                                that.refreshDependencies();
                            } else if(result.operation == 'delete') {
                                var container = $('[data-item-id="' + result.picture.item_id + '"] .pictures-list__inner .container');
                                var row = container.find('[data-gallery-picture-id="' + result.picture.id + '"]');

                                console.log(container.find('[data-gallery-picture-id="' + result.picture.id + '"]').length);
                                console.log(result.picture.id);
                                
                                row.velocity("fadeOut");
                            } else if(result.operation == 'prepare-delete') {
                                that.simpleLayer.setContent(result.html);
                                that.simpleLayer.show();
                                that.refreshDependencies();

                                that.simpleLayer.layer.find('.choice-form__cancel').click(function() {
                                    that.simpleLayer.hide();
                                });

                                that.simpleLayer.layer.find('.choice-form').submit(function(e) {
                                    e.preventDefault();
                                    e.stopPropagation();

                                    that.simpleLayer.hide();

                                    that.sendRequest({ module: that.modules.galleryPicture, operation: 'delete' }, moduleData);
                                });
                            } else if(result.operation == 'gallery-picture-up') {
                                if(result.picture.earlier !== undefined) {
                                    var container = $('[data-item-id="' + result.picture.item_id + '"] .pictures-list__inner .container');
                                    var picture = container.find('[data-gallery-picture-id="' + result.picture.id + '"]');

                                    var earlierPicture = container.find('[data-gallery-picture-id="' + result.picture.earlier + '"]');

                                    var pictureCopy = picture.clone();
                                    var earlierPictureCopy = earlierPicture.clone();

                                    pictureCopy.add(earlierPictureCopy).css({ opacity: 0 });

                                    picture.add(earlierPicture).velocity({ opacity: 0 }, {
                                        duration: 200,
                                        complete: function() {
                                            picture.replaceWith(earlierPictureCopy);
                                            earlierPicture.replaceWith(pictureCopy);

                                            pictureCopy.add(earlierPictureCopy).velocity({ opacity: 1 }, {
                                                duration: 200,
                                                complete: function() {
                                                    that.refreshDependencies();
                                                }
                                            });
                                        }
                                    });
                                } else {
                                    that.correctMessage.setContent(result.message);
                                    that.correctMessage.show();
                                }
                            } else if(result.operation == 'gallery-picture-down') {
                                if(result.picture.later !== undefined) {
                                    var container = $('[data-item-id="' + result.picture.item_id + '"] .pictures-list__inner .container');
                                    var picture = container.find('[data-gallery-picture-id="' + result.picture.id + '"]');

                                    var laterPicture = container.find('[data-gallery-picture-id="' + result.picture.later + '"]');

                                    var pictureCopy = picture.clone();
                                    var laterPictureCopy = laterPicture.clone();

                                    pictureCopy.add(laterPictureCopy).css({ opacity: 0 });

                                    picture.add(laterPicture).velocity({ opacity: 0 }, {
                                        duration: 200,
                                        complete: function() {
                                            picture.replaceWith(laterPictureCopy);
                                            laterPicture.replaceWith(pictureCopy);

                                            pictureCopy.add(laterPictureCopy).velocity({ opacity: 1 }, {
                                                duration: 200,
                                                complete: function() {
                                                    that.refreshDependencies();
                                                }
                                            });
                                        }
                                    });
                                } else {
                                    that.correctMessage.setContent(result.message);
                                    that.correctMessage.show();
                                }
                            }
                        } else if(result.module == that.modules.page) {
                            if(result.operation == 'prepare-add' || result.operation == 'prepare-edit') {
                                that.layer.setContent(result.html);
                                that.layer.show();
                                that.refreshDependencies();
                            } else if(result.operation == 'delete') {
                                var table = $('.pages-table');
                                var row = table.find('[data-page-id="' + result.page.id + '"]');
                                
                                row.velocity("slideUp");

                                var nextRows = row.nextAll();
                                nextRows.each(function() {
                                    var orderCell = $(this).find('.pages-table__order-number');

                                    var orderNumber = parseInt(orderCell.html());

                                    orderCell.html(orderNumber - 1);
                                });
                            } else if(result.operation == 'prepare-delete') {
                                that.simpleLayer.setContent(result.html);
                                that.simpleLayer.show();
                                that.refreshDependencies();

                                that.simpleLayer.layer.find('.choice-form__cancel').click(function() {
                                    that.simpleLayer.hide();
                                });

                                var thisform = that.simpleLayer.layer.find('.choice-form').first();

                                that.simpleLayer.layer.find('.choice-form').submit(function(e) {
                                    e.preventDefault();
                                    e.stopPropagation();

                                    that.simpleLayer.hide();

                                    that.sendRequest({ module: that.modules.page, operation: 'delete' }, moduleData);
                                });
                            }
                        } else if(result.module == that.modules.dataDefined) {
                            if(result.operation == 'prepare-add' || result.operation == 'prepare-edit') {
                                that.layer.setContent(result.html);
                                that.layer.show();
                                that.refreshDependencies();
                            } else if(result.operation == 'delete') {
                                var table = $('.data-defined-table');
                                var row = table.find('[data-data-defined-id="' + result.dataDefined.id + '"]');
                                
                                row.velocity("slideUp");

                                var nextRows = row.nextAll();
                                nextRows.each(function() {
                                    var orderCell = $(this).find('.data-defined-table__order-number');

                                    var orderNumber = parseInt(orderCell.html());

                                    orderCell.html(orderNumber - 1);
                                });
                            } else if(result.operation == 'prepare-delete') {
                                that.simpleLayer.setContent(result.html);
                                that.simpleLayer.show();
                                that.refreshDependencies();

                                that.simpleLayer.layer.find('.choice-form__cancel').click(function() {
                                    that.simpleLayer.hide();
                                });

                                var thisform = that.simpleLayer.layer.find('.choice-form').first();

                                that.simpleLayer.layer.find('.choice-form').submit(function(e) {
                                    e.preventDefault();
                                    e.stopPropagation();

                                    that.simpleLayer.hide();

                                    that.sendRequest({ module: that.modules.dataDefined, operation: 'delete' }, moduleData);
                                });
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
            that.overlayerErrorMessage.container.hide();

            var formdata = new FormData(form);

            that.spinnerLayer.show();

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

                    if(!result.error) {
                        if(result.module == that.modules.item) {
                            if(result.operation == 'prepare-add') {
                                that.layer.setContent(result.html);
                                that.layer.show();
                                that.refreshDependencies();

                                $('.trumbowyg').trumbowyg();
                            } else if(result.operation == 'add') {
                                var callback = function() {
                                    that.correctMessage.setContent(result.message);
                                    that.correctMessage.show();

                                    var table = $('[data-table-type="' + result.item.type + '"]');
                                    var row = $(result.item.row);
                                    row.hide();

                                    var existingRows = table.find('.table__row').filter(':visible');
                                    var followingNumber = existingRows.length + 1;

                                    row.find('.items-table__order-number').html(followingNumber);

                                    table.append(row);
                                    row.velocity("slideDown");

                                    that.refreshDependencies();
                                }
                                
                                that.layer.hide(callback);
                            } else if(result.operation == 'edit') {
                                var callback = function() {
                                    that.correctMessage.setContent(result.message);
                                    that.correctMessage.show();

                                    $('[data-item-type="' + result.item.type + '"][data-item-id="' + result.item.id + '"]')
                                        .find('.items-table__item-title')
                                        .velocity({ opacity: 0 }, {
                                            duration: 200,
                                            complete: function() {
                                               $(this).html(result.item['header_1_pl']);
                                               
                                               $(this).velocity({ opacity: 1 }, {
                                                duration: 200
                                               });

                                               that.refreshDependencies();
                                            }
                                        }); 
                                }
                                
                                that.layer.hide(callback);
                            }
                        } else if(result.module == that.modules.galleryPicture) {
                            if(result.operation == 'add') {
                                var callback = function() {
                                    var container = $('[data-item-id="' + result.picture.item_id + '"] .pictures-list__inner .container');
                                    var row = $(result.picture.row);
                                    row.hide();

                                    container.append(row);
                                    row.velocity("fadeIn");

                                    that.correctMessage.setContent(result.message);
                                    that.correctMessage.show();

                                    that.refreshDependencies();
                                }
                                
                                that.layer.hide(callback);
                            } else if(result.operation == 'edit') {
                                var callback = function() {
                                    var current = $('[data-gallery-picture-id="' + result.picture.id + '"]');
                                    
                                    var row = $(result.picture.row);
                                    row.css({ opacity: 0 });

                                    current.velocity({ opacity: 0 }, {
                                        duration: 200,
                                        complete: function() {
                                            current.replaceWith(row);
                                            row.velocity({ opacity: 1 });

                                            that.refreshDependencies();
                                        }
                                    });

                                    that.correctMessage.setContent(result.message);
                                    that.correctMessage.show();
                                }

                                that.layer.hide(callback);
                            }
                        } else if(result.module == that.modules.page) {
                            if(result.operation == 'add') {
                                var callback = function() {
                                    that.correctMessage.setContent(result.message);
                                    that.correctMessage.show();

                                    var table = $('.pages-table');
                                    var row = $(result.page.row);
                                    row.hide();

                                    var existingRows = table.find('.table__row').filter(':visible');
                                    var followingNumber = existingRows.length + 1;

                                    row.find('.pages-table__order-number').html(followingNumber);

                                    table.append(row);
                                    row.velocity("slideDown");

                                    that.refreshDependencies();
                                }
                                
                                that.layer.hide(callback);
                            } else if(result.operation == 'edit') {
                                var callback = function() {
                                    var current = $('[data-page-id="' + result.page.id + '"]');
                                    
                                    current
                                        .find('.pages-table__slug')
                                        .velocity({ opacity: 0 }, {
                                            duration: 200,
                                            complete: function() {
                                               $(this).html(result.page['slug']);
                                               
                                               $(this).velocity({ opacity: 1 }, {
                                                duration: 200
                                               });
                                            }
                                        });

                                    current
                                        .find('.pages-table__title')
                                        .velocity({ opacity: 0 }, {
                                            duration: 200,
                                            complete: function() {
                                               $(this).html(result.page['title_pl']);
                                               
                                               $(this).velocity({ opacity: 1 }, {
                                                duration: 200
                                               });
                                            }
                                        });

                                    that.correctMessage.setContent(result.message);
                                    that.correctMessage.show();
                                }

                                that.layer.hide(callback);
                            }
                        } else if(result.module == that.modules.dataDefined) {
                            if(result.operation == 'add') {
                                var callback = function() {
                                    that.correctMessage.setContent(result.message);
                                    that.correctMessage.show();

                                    var table = $('.data-defined-table');
                                    var row = $(result.dataDefined.row);
                                    row.hide();

                                    var existingRows = table.find('.table__row').filter(':visible');
                                    var followingNumber = existingRows.length + 1;

                                    row.find('.data-defined-table__order-number').html(followingNumber);

                                    table.append(row);
                                    row.velocity("slideDown");

                                    that.refreshDependencies();
                                }
                                
                                that.layer.hide(callback);
                            } else if(result.operation == 'edit') {
                                var callback = function() {
                                    var current = $('[data-data-defined-id="' + result.dataDefined.id + '"]');
                                    
                                    current
                                        .find('.data-defined-table__code')
                                        .velocity({ opacity: 0 }, {
                                            duration: 200,
                                            complete: function() {
                                               $(this).html(result.dataDefined['code']);
                                               
                                               $(this).velocity({ opacity: 1 }, {
                                                duration: 200
                                               });
                                            }
                                        });

                                    current
                                        .find('.data-defined-table__value')
                                        .velocity({ opacity: 0 }, {
                                            duration: 200,
                                            complete: function() {
                                               $(this).html(result.dataDefined['value_pl']);
                                               
                                               $(this).velocity({ opacity: 1 }, {
                                                duration: 200
                                               });
                                            }
                                        });

                                    that.correctMessage.setContent(result.message);
                                    that.correctMessage.show();
                                }

                                that.layer.hide(callback);
                            }
                        }
                    } else {
                        that.overlayerErrorMessage.setContent(result.message);
                        that.overlayerErrorMessage.show();
                        scrollTo({ container: that.layer.layer, anchor: '#overlayer-inner-anchor' });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR + " " + textStatus + " " + errorThrown);

                    that.overlayerErrorMessage.setContent(jqXHR + " " + textStatus + " " + errorThrown);
                    that.overlayerErrorMessage.show();
                },
                complete: function() {
                    that.spinnerLayer.hide();
                }
            });

            return this;
        }

        /**this.loadLayer = function(request, item) {
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
        }**/

        this.refreshDependencies = function() {
            var that = this;

            this.requestLinks = $('.request-link');
            this.requestForms = $('.request-form');

            this.requestLinks.unbind('click').click(function(e) {
                e.preventDefault();
                e.stopPropagation();

                var module = $(this).attr('data-module');
                var operation = $(this).attr('data-operation');

                var id = $(this).attr('data-id');
                var parent_id = $(this).attr('data-parent-id');
                var type = $(this).attr('data-type');
                var item_id = $(this).attr('data-item-id');

                var moduleData = {};

                if(module == that.modules.item) {
                    moduleData = {
                        id: id,
                        parent_id: parent_id,
                        type: type
                    }
                } else if(module == that.modules.galleryPicture) {
                    moduleData = {
                        id: id,
                        item_id: item_id
                    }
                } else if(module == that.modules.page) {
                    moduleData = {
                        id: id
                    }
                } else if(module == that.modules.dataDefined) {
                    moduleData = {
                        id: id
                    }
                }

                that.sendRequest({ module: module, operation: operation }, moduleData);
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
            this.overlayerErrorMessage.hide();

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