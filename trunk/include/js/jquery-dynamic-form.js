(function($){
    var methods = {
        // init
        // options is an object, containing any of the following keys:
        //   - plusSelector: Selector that can be used to find the "plus" element
        //   - minusSelector: Selector that can be used to find the "minus" element
        //   - createColor: Color to hilight the new clone with
        //   - duration: Duration the "create" animation should last
        //
        // Note: plus and minus selectors are looked for in the "template" element first,
        //       if they are not found the scope is broadened to the whole document.
        init: function( options ) {
            return this.each(function() {
                var $this = $(this);

                var template = $this.cloneWithAttribut(true);

                var data = $.extend({
	            formFields: "input, checkbox, select, textarea, fieldset",
                    externalPlus: false,
                    externalMinus: false,
                    source: this,
                    template: template,
                    clones: []
                }, options);

                $this.data('dynamicForm', data);

                $this.attr('dynamicFormCloneIndex', 0);

                var plus = $this.find(data.plusSelector)
                var minus = $this.find(data.minusSelector)

                if(plus.get(0) == undefined) {
                    plus = $(data.plusSelector);
                    data.externalPlus = true;
                }

                if(minus.get(0) == undefined) {
                    minus = $(data.minusSelector);
                    data.externalMinus = true;
                }
                // XXX: This section looks pretty nasty, but I don't really know the best way to fix it.
                minus.hide();
                minus.click({master: $this}, function(event) {
                    event.preventDefault();

                    var master = event.data.master;
                    master.dynamicForm('remove');

                    return false;
                })
                plus.click({master: $this}, function(event) {
                    event.preventDefault();

                    var master = event.data.master;
                    var addAfter = master;

                    if(master.data('dynamicForm').externalPlus) {
                        addAfter = null; // If this is an external button, we want new clones added at the end
                    }
                    master.dynamicForm('add', {addAfter: addAfter});

                    return false;
                })

                $this.dynamicForm('reflowFormNames');
            });
        },

        // add: Add a new field
        // options is an object, containing any of the following keys:
        //   - disableEffect: Skip performing any kind of animation (boolean)
        //   - addAfter: DOM element to add our new clone after
        add: function(options) {
            options = options || {};

            var $this = $(this);
            var disableEffect = options.disableEffect;
            var addAfter = options.addAfter;

            var data = $this.data('dynamicForm');

            var source = data.source;
            var clones = data.clones;
            var template = data.template;

            var clone = template.cloneWithAttribut(true);
            var unwrappedClone = $(clone).get(0)
            var callbackReturn;

            if (typeof data.afterClone === "function") {
                callbackReturn = data.afterClone(clone);
            }

            if(callbackReturn || typeof callbackReturn == "undefined") {
                clone.insertAfter(addAfter || clones[clones.length - 1] || source);
            }

            /* Normalize template id attribute */
            if (clone.attr("id")) {
                clone.attr("id", clone.attr("id") + clones.length);
            }

            if (clone.effect && data.createColor && !disableEffect) {
                clone.effect("highlight", {color: data.createColor}, data.duration);
            }

            // XXX: This can probably be cleaned up
            var unwrappedAddAfter = $(addAfter).get(0);
            if(addAfter) {
                if(unwrappedAddAfter == source) {
                    clones.splice(0, 0, unwrappedClone); // Add the new clone at the beginning of the array
                } else {
                    var addAfterIndex = $.inArray(unwrappedAddAfter, clones);
                    if(addAfterIndex != -1) { // Add new clone at the index after the index of "addAfter" element
                        clones.splice(addAfterIndex + 1, 0, unwrappedClone);
                    } else {
                        clones.push(unwrappedClone); // Unable to find addAfter element, just stick it on the end
                    }
                }
            } else {
                clones.push(unwrappedClone); // "addAfter" was not specified, stick the new clone at the end
            }

            $this.dynamicForm('reflowCloneIndexes')

            clone.find(data.plusSelector).click({clone: clone, master: $this}, function(event) {
		event.preventDefault();

                var master = event.data.master;
                var clone = event.data.clone;

                master.dynamicForm('add', {addAfter: clone});

                return false;
            })

            clone.find(data.minusSelector).click({clone: clone, master: $this}, function(event) {
                event.preventDefault();

                var master = event.data.master;
                var clone = event.data.clone;

                master.dynamicForm('remove', {clone: clone});

                return false;
            })

            if(data.externalMinus) {
                $(data.minusSelector).show();
            }

            $this.dynamicForm('reflowFormNames'); // FIXME, performance: This was added as an attempt to standardize the name attribute for all elements in a form.
            return clone;
        },

        // remove: Remove a clone from a managed dynamicForm
        // options is an object, containing any of the following keys:
        //   - clone: Clone to remove. If this field is not present, the last element is removed.
        remove: function(options) {
            options = options || {};

            var $this = $(this);
            var data = $this.data('dynamicForm');

            // I'm keeping data.clones broken out here to be explicit
            var clone = options.clone || data.clones[data.clones.length - 1]
            if(clone == undefined) {
                return false;
            }

            // We need to make sure we're assigning clones back into data
            data.clones = $.grep(data.clones, function(value) {
                // XXX: Is there a better way to do this?
                var unwrappedClone = $(clone).get(0);
                var unwrappedValue = $(value).get(0);
                return unwrappedClone != unwrappedValue;
            })
            clone.remove();

            if(data.clones.length == 0 && data.externalMinus) {
                $(data.minusSelector).hide();
            }

            $this.dynamicForm('reflowCloneIndexes');

            $this.dynamicForm('reflowFormNames'); // FIXME, performance: This was added as an attempt to standardize the name attribute for all elements in a form.
            return true;
        },

        reflowCloneIndexes: function() {
            var $this = $(this);
            var data = $this.data('dynamicForm');

            $(data.clones).each(function(i, elem) {
                $(elem).attr('dynamicFormCloneIndex', i + 1) // Offset by one to include the source element
            })
        },

        // reflowFormNames: Iterate through containing form, overwriting all name attributes.
        // Note: Given the current state of the world, this is the only way I could think of to build the appropriate form structure.
        //       I'd much rather a similar method to this, except non-destructive.
        reflowFormNames: function() {
            var $this = $(this);
            var data = $this.data('dynamicForm');
            var $form = $this.parents('form'); // FIXME: What happens if there are nested forms?

            var recurseSettingNames = function(startElement, prefix) {
                $(startElement).children().each(function(i, elem) {
                    var $elem = $(elem);
                    // Ensure that dynamicFormName is preserved before we overwrite name
                    // ignoreDynamicFormName is for elements that don't have names of their own, they're just string values in an array or something like that.
                    if($elem.attr('ignoreDynamicFormName') == undefined && $elem.attr('dynamicFormName') == undefined) {
                        $elem.attr('dynamicFormName', $elem.attr('name'));
                    }

                    // If this has a dynamicFormName, let's use it to set name
                    if($elem.attr('dynamicFormName')) {
                        var dynamicFormCloneIndex = $elem.attr('dynamicFormCloneIndex')
                        var index = ']'
                        var _prefix = prefix + '[';
                        if(prefix == undefined || prefix == '') {
                            _prefix = '';
							index = '';
                        }

                        if(dynamicFormCloneIndex != undefined) {
                            index = '[' + dynamicFormCloneIndex + ']';
                        }
                        $elem.attr('name', _prefix + $elem.attr('dynamicFormName') + index);

                    // If there's no dynamicFormName and the tagName is in the listed tags, let's give this element a name.
                    // In order for this to not get out of control, we also need to set ignoreDynamicFormName on the element.
                    } else if($.inArray($elem.get(0).tagName, ['INPUT']) != -1) {
                        if($elem.attr('dynamicFormName') == undefined) {
                            $elem.attr('ignoreDynamicFormName', 'true');
                        }
                        $elem.attr('name', prefix);
                    }

                    var _prefix = prefix;
                    if($elem.attr('name') != null) {
                        _prefix = $elem.attr('name');
                    }

                    recurseSettingNames($elem, _prefix);
                })
            }

            recurseSettingNames($form, "");
        },

        // inject: Fill form with data
        // data is an object, corresponding to the structure of the form.
        // The form is traversed, requesting values from the "data" object as needed.
        // Arguments:
        //   - data: Object or array containing values. If array, attempt to create enough clones to house the data.
        //   - target: Which element to start traversal from. If not specified, the containing form is selected.
        inject: function(data, target) {
            var recurseFillingData = function(elem, data) {
                if(data == undefined) {
                    return false;
                }

                $(elem).children().each(function(i, elem) {
                    var $elem = $(elem);
                    var dynamicFormName = $elem.attr('dynamicFormName')
                    var value = data[dynamicFormName];

                    if(Array.isArray(value) && $elem.data('dynamicForm') != undefined) {
                        $elem.dynamicForm('inject', value);

                    } else if(value != null && (Array.isArray(value) || $.isPlainObject(value))) {
                        recurseFillingData($elem, value);

                    } else if(value != null) {
                        if($elem.attr('type') == 'checkbox') {
                            $elem.attr('checked', value?'checked':'');

                        } else if($elem.attr('type') == 'radio' && $elem.val() == value) {
                            $elem.attr('checked', 'checked');

                        } else {
                            $elem.val(value);
                        }
                    } else if($elem.get(0).tagName == "INPUT" && typeof(data) == "string") {
                        $elem.val(data);
                    } else {
                        recurseFillingData($elem, data);
                    }
                })
            }

            if(Array.isArray(data)) {
                var $this = $(this);
                var clones = $this.dynamicForm('getAllClones');
                for(var i = 0; i < data.length - clones.length; i++) {
                    $this.dynamicForm('add', {disableEffect: true});
                }
                clones = $this.dynamicForm('getAllClones');
                $(data).each(function(i, value) {
                    recurseFillingData(clones[i], value);
                })
            } else {
                if(target == undefined) {
                    target = $(this).parents('form'); // FIXME: What happens if there are nested forms?
                }
                recurseFillingData(target, data);
            }
        },

        // destroy: This is a stub, all it does right now is clear out the data object.
        //          It is unclear what this function should do, or if it should exist at all.
        destroy: function() {
            var $this = $(this);
            $this.data('dynamicForm', null);
        },

        getAllClones: function() {
            return [$(this).get(0)].concat($(this).data('dynamicForm').clones);
        }
    };

$.fn.dynamicForm = function( method ) {
    // Method calling logic
    if ( methods[method] ) {
        return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
        return methods.init.apply( this, arguments );
    } else {
        $.error( 'Method ' +  method + ' does not exist on jQuery.dynamicForm' );
    }

    return items;
};

/**
 * jQuery original clone method decorated in order to fix an IE < 8 issue
 * where attributs especially name are not copied
 */
jQuery.fn.cloneWithAttribut = function( withDataAndEvents ){
	if ( jQuery.support.noCloneEvent ){
		return $(this).clone(withDataAndEvents);
	}else{
		$(this).find("*").each(function(){
			$(this).data("name", $(this).attr("name"));
		});
		var clone = $(this).clone(withDataAndEvents);
		
		clone.find("*").each(function(){
			$(this).attr("name", $(this).data("name"));
		});
		
		return clone;
	}
};

})(jQuery);
