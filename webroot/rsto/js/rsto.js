const RSTORegex = {
    'email': /^[a-z0-9](\.?[a-z0-9_-]){0,}@[a-z0-9-]+\.([a-z]{1,6}\.)?[a-z]{2,6}$/g,
    'phoneNumber': /^((\+(1\-)?\d{1,3})|0)\d{4,14}$/g,
    'numeric': /^\d{1,3}(,\d{3})*(\.\d+)?$/g,
    'integer': /^\d{1,3}(,\d{3})*$/g,
};


const RSTOMessages = {
    'Added': "The item was successfully added!",
    'Updated': "The item was successfully updated!",
    'Deleted': "The item was successfully deleted!",
    'Error': "An error occured, please retry or contact an Administrator",
    'Validation': "Do you really want to validate this item ?",
    'Validated': "The item was successfully validated!",
    'ConfirmDelete': "Do you really want to delete this item ?"
};

// Perform a translation on a string
const __ = function (value) {
    return value;
};

/**
 * Retrieve JSON from server via post request
 * @param {String} url
 * @param {Mixed} data
 * @param {String} xCSRFToken
 * @param {Callback} success
 * @returns {jqXHR}
 */
const RSTOGetJSON = function (url, data, xCSRFToken, success) {
    return $.ajax({
        'type': 'post',
        'dataType': 'json',
        'url': url,
        'data': data,
        'headers': {'x-csrf-token': xCSRFToken},
        'success': success,
        'error': function (xhr) {
            var _response = JSON.parse(xhr.responseText);
            var _message = '<b>{0}</b><br>File :{1}<br>Line: {2}'.format(_response.message, _response.file, _response.line);
            alert(_message, 'danger');
        }
    });
};

const RSTOCRUD = function (config){
    var _me = this;
    _me.xCSRFToken = _me.form.attr('data-x-csrf-token');
    // Create
    _me.buttons.add.click(function(){
            _me.modal.modal('show');
    });
    // After form submission
    _me.form.on('submitted.rsto', function(e, response){
        if(response.success){
            var _editMode = _me.attr('data-edit') === 'true';
            _me.modal.modal('hide');
            _me.datatable.ajax.reload();
            alert(RSTOMessages.Added);
        } else {
            alert(RSTOMessages.Error);
        }
    });
    // When an item is selected on the datatable
    _me.table.on('selectionChanged.rsto', function(e, data){
        // Enable buttons
        for (var _button in _me.buttons){
            if(_button !== 'add'){
                _me.buttons[_button].RSTOEnable();
            }
        }
        // Update data-edit-url
        var _requestQuery = {};
        _requestQuery[config.primaryKey];
        _me.form.RSTODataURLQuery(_requestQuery, 'data-edit-url');
    });
    // When datatable is redrawn
    _me.table.on('draw.dt', function(){
        // Disblae buttons
        for (var _button in _me.buttons){
            if(_button !== 'add'){
                _me.buttons[_button].RSTODisable();
            }
        }
    });
    // Edit item
    _me.buttons.edit.click(function(){
        _me.form.attr('data-edit', 'true');
        // Fill the form
        _me.fillForm();
        // Show modal
        _me.modal.modal('show');
    });

    // Detele item
    _me.buttons.delete.click(function(){
        confirm(RSTOMessages.ConfirmDelete, function(response){
            if(response === true){
                var _requestData = {};
                _requestData[config.primaryKey] = _me.table.RSTODatatableSelectedData().id;
                RSTOGetJSON(_me.buttons.delete.attr('data-url'), _requestData, _me.xCSRFToken, function(response){
                    if(response === true){
                        _me.datatable.ajax.reload();
                        alert(RSTOMessages.Deleted);
                    } else {
                        alert(RSTOMessages.Error);
                    }
                });
            }
        });
    });
}

/**
 * Show a message box
 * @param {String} title : Title of the modal
 * @param {String} content : Content HTML
 * @param {Array} buttons : Array of button configurations
 * @param {String} type : danger | warning | success | information
 * @returns {void}
 */
const RSTOMessageBox = function (title, content, buttons, type) {
    var _type = type || '';
    var _class = _type === '' ? '' : 'modal-' + _type;
    var _messageBox = $('<div class="modal"/>').addClass(_class);
    var _messageBoxDialog = $('<div class="modal-dialog"/>').appendTo(_messageBox);
    var _messageBoxContent = $('<div class="modal-content"/>').appendTo(_messageBoxDialog);
    var _messageBoxHeader = $('<div class="modal-header"/>').appendTo(_messageBoxContent);
    $('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>').appendTo(_messageBoxHeader);
    $('<h4 class="modal-title"/>').text(title).appendTo(_messageBoxHeader);
    var _messageBoxBody = $('<div class="modal-body"/>').appendTo(_messageBoxContent);
    _messageBoxBody.append(content);
    var _messageBoxFooter = $('<div class="modal-footer"/>').appendTo(_messageBoxContent);
    if (buttons.length === 0) {
        buttons.push({
            'text': 'OK',
            'class': _type === '' ? 'primary' : 'outline',
            click: function (e) {
                e.modal('hide');
            }
        });
    }
    buttons.reverse();
    buttons.forEach(function (_messageBoxButton) {
        _button = $('<button type="button" class="btn"/>').text(_messageBoxButton.text);
        _button.addClass('btn-' + _messageBoxButton.class);
        _messageBoxFooter.append(_button);
        _button.click(function () {
            _messageBoxButton.click(_messageBox);
        });
    });
    $(document.body).append(_messageBox);
    _messageBox.on('hidden.bs.modal', function (e) {
        _messageBox.remove();
    });
    _messageBox.modal('show');
};

const RSTOChangeURLQuery = function (url, query) {
    var _urlParts = url.split('?');
    var _url = _urlParts[0];
    var _i = 0;
    for (var _query in query) {
        _url += (_i++ === 0 ? "?" : "&") + "{0}={1}".format(_query, query[_query]);
    }
    return _url;
};

/**
 * Prompt message
 * @param {String} message : le message
 * @param {String} type : danger | warning | information | success
 * @returns {void}
 */
window.alert = function (message, type) {
    var _title;
    switch (type || '') {
        case 'danger':
            _title = 'Error';
            break;
        case '':
            _title = 'Information';
            break;
        default:
            _title = type.ucfirst();
            break;
    }
    RSTOMessageBox(__(_title), message, [], type);
};

/**
 * Ask confirmation
 * @param {String} message : Question
 * @returns {void}
 */
window.confirm = function (message, callback) {
    RSTOMessageBox(__('Confirmation'), message, [
        {
            text: __('Yes'),
            'class': 'primary',
            'click': function (e) {
                e.modal('hide');
                callback(true);
            }
        },
        {
            text: __('No'),
            'class': 'default',
            'click': function (e) {
                e.modal('hide');
                callback(false);
            }
        }
    ]);
};

// First letter uppercase function on String
if (!String.prototype.ucfirst) {
    String.prototype.ucfirst = function () {
        if (this.length > 0) {
            return this.charAt(0).toUpperCase() + this.slice(1);
        }
        return this;
    };
}

// Check if a string is a valid mail
if (!String.prototype.isEmail) {
    String.prototype.isEmail = function () {
        var _matches = this.trim().match(RSTORegex.email);
        return _matches !== null && _matches.length === 1;
    };
}

// Check if a string is a valid numeric
if (!String.prototype.isNumeric) {
    String.prototype.isNumeric = function () {
        var _matches = this.trim().match(RSTORegex.numeric);
        return _matches !== null && _matches.length === 1;
    };
}

// Check if a string is a valid numeric
if (!String.prototype.isInteger) {
    String.prototype.isInteger = function () {
        var _matches = this.trim().match(RSTORegex.integer);
        return _matches !== null && _matches.length === 1;
    };
}

// Check if a string is a valid phone number
if (!String.prototype.isPhoneNumber) {
    String.prototype.isPhoneNumber = function () {
        var _matches = this.replace(/ /g, '').match(RSTORegex.phoneNumber);
        return _matches !== null && _matches.length === 1;
    };
}

// Check if a string is a valid text
if (!String.prototype.isText) {
    String.prototype.isText = function () {
        return this.trim().length > 0;
    };
}

// Create String.format function if it doesnt yet exists
if (!String.prototype.format) {
    String.prototype.format = function () {
        var args = arguments;
        return this.replace(/{(\d+)}/g, function (match, number) {
            return typeof args[number] !== undefined ? args[number] : match;
        });
    };
}

$.fn.extend({
    /**
     * Add .has-error to form-group
     * @returns {jQuery}
     */
    RSTOAddError: function () {
        return this.each(function () {
            $(this).closest('div.form-group')
                    .removeClass('has-success')
                    .removeClass('has-warning')
                    .addClass('has-error');
        });
    },
    /**
     * Add .has-success to form-group
     * @returns {jQuery}
     */
    RSTOAddSuccess: function () {
        return this.each(function () {
            $(this).closest('div.form-group')
                    .removeClass('has-error')
                    .removeClass('has-warning')
                    .addClass('has-success');
        });
    },
    /**
     * Add .has-warning to form-group
     * @returns {jQuery}
     */
    RSTOAddWarning: function () {
        return this.each(function () {
            $(this).closest('div.form-group')
                    .removeClass('has-success')
                    .removeClass('has-error')
                    .addClass('has-warning');
        });
    },
    /**
     * Add remote validation to inputs
     * @returns {jQuery}
     */
    RSTOAddRemoteValidation: function () {
        return this.each(function () {
            var _this = $(this);
            //var _ajaxQueue = [];
            var _validationFunction = function(){
                clearTimeout(_this.data('_RSTOValidationTimer'));
                var _timer = setTimeout(function () {
                    var _form = _this.closest('form');
                    // If form is reseting then avoid validation
                    if (_form[0]._RSTOReseting === true) {
                        return;
                    }
                    // Abort preview ajax
                    if (_this[0]._RSTORemoteValidationAjax !== undefined) {
                        _this[0]._RSTORemoteValidationAjax.abort();
                    }
                    var _validationURL = _this.attr('data-validation-url');
                    var _x_csrf_token = _this.closest('form').attr('data-x-csrf-token');
                    var _value = _this.val().trim();
                    // Run new Ajax
                    this._RSTORemoteValidationAjax = RSTOGetJSON(_validationURL, {"value": _value}, _x_csrf_token, function (response) {
                        var _isOriginal = _value === _this.RSTOOriginalValue();
                        var _isValid = _this[0]._RSTOIsValid = _isOriginal === true ? 'original' : (response === true || response === 1);
                        _isValid === 'original' ? _this.RSTORemoveValidationClass() : (response ? _this.RSTOAddSuccess() : _this.RSTOAddError());
                        // Trigger validation event
                        _this.trigger('afterValidation.rsto', [_isValid]);
                        _form.RSTOValidate();
                    });
                }, 100);
                _this.data('_RSTOValidationTimer', _timer);
            };
            this._RSTOHasValidation = true;
            this._RSTOOriginalValue = null;
            this._RSTOIsRequired = _this.attr('data-required') === 'true';
            this._RSTOIsValid = !this._RSTOIsRequired;
            this._RSTOStopValidation = false;
            _this.keyup(_validationFunction);
            _this.on('paste', _validationFunction);
        });
    },
    /**
     * Add validation process on an input<br>
     * The validation is base on regex<br>
     * If you want to add more validation type, extend string prototype by adding a validation function<br>
     * Eg : phone-number validation is base on String.prototype.isPhoneNumber
     * @param {String} type : email | integer | numeric | phone-number
     * @event afterValidation.rsto
     * @returns jQuery
     */
    RSTOAddValidation: function (type) {
        return this.each(function () {
            var _this = $(this);
            var _function = "is" + type.split('-').map(x => x.ucfirst()).join('');
            if (typeof String.prototype[_function] === 'function') {
                var _validationFunction = function(){
                    clearTimeout(_this.data('_RSTOValidationTimer'));
                    var _timer = setTimeout(function () {
                        var _form = _this.closest('form');
                        // If form is reseting then avoid validation
                        if (_form[0]._RSTOReseting === true) {
                            return;
                        }
                        var _val = _this.val();
                        // Check if input is empty
                        var _isEmpty = _val.trim().length === 0;
                        // Retrievve the original value, in case the form is on edit mode
                        var _original = _this.RSTOOriginalValue();
                        // Check if value matches
                        var _match = _val[_function]();
                        // Determine validation status true | false | original
                        var _isValid = _this[0]._RSTOIsValid = _val === _original ? 'original' : (_match || (_isEmpty && !_this[0]._RSTOIsRequired));
                        // Raise validation event
                        _this.trigger('afterValidation.rsto', [_isValid]);
                        // Add css class depend on validation status
                        (_isValid === 'original' || (_isEmpty && !_this[0]._RSTOIsRequired)) ? _this.RSTORemoveValidationClass() : (_isValid ? _this.RSTOAddSuccess() : _this.RSTOAddError());
                        // Form validation
                        _form.RSTOValidate();
                    }, 100);
                    _this.data('_RSTOValidationTimer', _timer);
                };
                // Add this property to tell that the input has a validation process
                this._RSTOHasValidation = true;
                this._RSTOOriginalValue = null;
                this._RSTOIsRequired = _this.attr('data-required') === 'true';
                this._RSTOIsValid = !this._RSTOIsRequired
                _this.keyup(_validationFunction);
                _this.on('paste', _validationFunction);
            }
        });
    },
    RSTODataURLQuery: function (query, dataURLAttribute) {
        return this.each(function () {
            if (query !== undefined) {
                var _this = $(this);
                var _dataURLAttribute = dataURLAttribute || 'data-url';
                if (_this.attr(_dataURLAttribute)) {
                    _this.attr(_dataURLAttribute, RSTOChangeURLQuery(_this.attr(_dataURLAttribute), query));
                } else {
                    throw "Atribute {0} doesn't exist on this element!".format(_dataURLAttribute);
                }

            }
        });
    },
    RSTORemoveDataURLQuery: function (dataURLAttribute) {
        return this.each(function () {
            var _this = $(this);
            var _dataURLAttribute = dataURLAttribute || 'data-url';
            if (_this.attr(_dataURLAttribute)) {
                _this.attr(_dataURLAttribute, RSTOChangeURLQuery(_this.attr(_dataURLAttribute), {}));
            }
        });
    },
    RSTODatatable: function (columns) {
        if (this.length === 1 && this[0].tagName.toLowerCase() === 'table') {
            var _this = this;
            _this.on('draw.dt', function () {
                _this[0]._RSTODatatableSelectedData = null;
            });
            _this.on('selectionChanged.rsto', function (e, data) {
                _this[0]._RSTODatatableSelectedData = data;
            });
            return _this.DataTable({
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "ajax": {
                    "url": _this.attr('data-url'),
                    "type": 'post',
                    "dataType": 'json',
                    "headers": {
                        'x-csrf-token': _this.attr('data-x-csrf-token')
                    },
                    'error': function (xhr) {
                        var _response = JSON.parse(xhr.responseText);
                        var _message = '<b>{0}</b><br>File :{1}<br>Line: {2}'.format(_response.message, _response.file, _response.line);
                        alert(_message, 'danger');
                    }
                },
                "columns": columns,
                "createdRow": function (row, data) {
                    $(row).RSTOSelectableDatatableRow(_this, data);
                }
            });
        } else {
            throw "RSTODatable works only on table DOM!";
        }
    },
    RSTODatatableSelectedData: function () {
        if (this.length === 1 && this[0].tagName.toLowerCase() === 'table') {
            return this[0]._RSTODatatableSelectedData;
        } else {
            throw "RSTODatatableSelected works only on table DOM";
        }
    },
    RSTODisable: function () {
        return this.each(function () {
            var _this = $(this);
            if (this.tagName.toLowerCase() === 'form') {
                $(this.elements).RSTODisable();
            } else {
                this.disabled = true;
                _this.attr('disabled', 'disabled');
                _this.removeClass('disabled');
            }
        });
    },
    RSTOEnable: function () {
        return this.each(function () {
            var _this = $(this);
            if (this.tagName.toLowerCase() === 'form') {
                $(this.elements).RSTOEnable();
            } else {
                this.disabled = false;
                _this.removeAttr('disabled');
                _this.removeClass('disabled');
            }
        });
    },
    /**
     * Customize form, prevent default submit and perform ajax instead
     * @param {callback} additionalValidation : Additional validation
     * @returns {jQuery}
     */
    RSTOForm: function (additionalValidation) {
        return this.each(function () {
            var _this = $(this);
            this._RSTOExternalValidation = additionalValidation || function () {
                return true;
            };
            _this.submit(function (e) {
                e.preventDefault();

                if (_this.RSTOIsValid()) {
                    var _editMode = _this.attr('data-edit') === 'true';
                    $.ajax({
                        'type': 'post',
                        'dataType': 'json',
                        'data': _this.serialize(),
                        'url': _editMode ? _this.attr('data-edit-url') : _this.attr('data-action-url'),
                        'headers': {'x-csrf-token': _this.attr('data-x-csrf-token')},
                        'success': function (response) {
                            _this.trigger('submitted.rsto', [response]);
                        },
                        'error': function (xhr) {
                            try{
                                var _response = JSON.parse(xhr.responseText);
                            } catch(e)
                            {
                                alert(xhr.responseText, 'error');
                            }
                            var _message = '<b>{0}</b><br>File :{1}<br>Line: {2}'.format(_response.message, _response.file, _response.line);
                            alert(_message, 'danger');
                        }
                    });
                }
            });
            // After validation, we enable submit button if form is valid
            _this.on('afterValidation.rsto', function (e, isValid) {
                var _submitBtn = _this.find('button[type="submit"]');
                isValid ? _submitBtn.RSTOEnable() : _submitBtn.RSTODisable();
            });
            // Prevent submission by all button except submit button
            _this.find('button[type!="submit"]').click(function (e) {
                e.preventDefault();
            });

            // Add validation to checkbox
            _this.find('input[type=checkbox]').each(function () {
                this._RSTOHasValidation = true;
                this._RSTOIsRequired = false;
                this._RSTOOriginalValue = false;
                this._RSTOIsValid = 'original';
            });

            // Checkbox validation
            _this.find('input[type=checkbox]').change(function () {
                this._RSTOIsValid = this.checked !== (this._RSTOOriginalValue || false) ? true : 'original';
                _this.RSTOValidate();
            });
        });
    },
    RSTOIsValid: function () {
        return this[0]._RSTOIsValid;
    },
    RSTOModal: function () {
        return this.each(function () {
            var _me = $(this);
            _me.on('hide.bs.modal', function () {
                // Reset form
                $(this).find('form').RSTOReset();
            });
        });
    },
    /**
     * Get or set the title of a modal
     * @param {String} title : Title to set, left empty if you want to get the actual title
     * @return {jQuery} or {String}
     */
    RSTOModalTitle: function (title) {
        var _title = title || false;
        var _selector = 'h4.modal-title'
        if (title) {
            return this.each(function () {
                var _h4 = $(this).find(_selector).html(_title);
            });
        } else if (this.length === 1) {
            return this.find(_selector).html();
        }
    },
    /**
     * Get or set, element's original value
     * @param {String} value : Original value, if null function will return the original value
     * @param {String} option : In case of select element, this parameter is the option label
     * @returns {mixed}
     */
    RSTOOriginalValue: function (value, option) {
        if (value === undefined) {
            return this[0]._RSTOOriginalValue;
        } else {
            return this.each(function () {
                var _this = $(this);
                var _tagName = this.tagName.toLowerCase();
                this._RSTOOriginalValue = value;
                this._RSTOIsValid = 'original';
                // Add temporary validation, will be removed on form reset.
                // We need this to check if value equals to original or not
                // So we will be able to enable or disable submit button depend on validation status
                this._RSTOHasValidation = true;
                switch (_tagName) {
                    case 'select':
                        var _option = _this.find('option[value="{0}"]'.format(value));
                        if (_option.length === 0) {
                            _this.append(new Option(option, value, true, true)).trigger('change.select2');
                        } else {
                            _option.prop('selected', true).trigger('change.select2');
                        }
                        break;
                    case 'input':
                        if (_this.attr('type').toLowerCase() === 'checkbox') {
                            _this.prop('checked', value);
                        } else {
                            _this.val(value);
                        }
                        break;
                    default:
                        _this.val(value);
                        break;
                }
            });
        }
    },
    /**
     * Removes .has-error, .has-success, .has-warning css class from .from-group
     * @returns {jQuery}
     */
    RSTORemoveValidationClass: function () {
        return this.each(function () {
            $(this).closest('div.form-group')
                    .removeClass('has-success')
                    .removeClass('has-error')
                    .removeClass('has-warning');
        });
    },
    /**
     * Reset form
     * @returns {jQuery}
     */
    RSTOReset: function () {
        return this.each(function () {
            var _this = $(this);
            if (_this.prop('tagName').toLowerCase() === 'form') {
                //indicate to fields that form is reseting so avoid validation
                this._RSTOReseting = true;
                _this.trigger('reset');
                _this.removeAttr('data-edit');
                _this.find('.rsto-select2').val(null).trigger('change.select2');
                // Remove temoporary validation
                _this.find('input[type="checkbox"]').each(function () {
                    this._RSTOIsValid = 'original';
                    this._RSTOOriginalValue = false;
                });
                // Reset validation tools
                $.each(_this[0].elements, function (index, element) {
                    if (element._RSTOHasValidation) {
                        element._RSTOOriginalValue = null;
                        element._RSTOIsValid = !element._RSTOIsRequired;
                    }
                    $(element).RSTORemoveValidationClass();
                });
                // Disable submit button
                _this.find('button[type="submit"]').RSTODisable();
                //indicate to fields that form reseting sis finished
                this._RSTOReseting = false;
            }
        });
    },
    /**
     * Build select2 with validation process
     * @returns {jQuery}
     */
    RSTOSelect2: function () {
        return this.each(function () {
            var _this = $(this);
            var _dataURL = _this.attr('data-url');

            // Init validation tool
            this._RSTOHasValidation = true;
            this._RSTOOriginalValue = null;
            this._RSTOIsRequired = _this.attr('data-required') === 'true';
            this._RSTOIsValid = !this._RSTOIsRequired;

            // Build select2
            if (_dataURL) {
                _this.select2({
                    'width': '100%',
                    'allowClear': !this._RSTOIsRequired,
                    'ajax': {
                        'type': 'post',
                        'dataType': 'json',
                        'url': function () {
                            return _this.attr('data-url');
                        },
                        'headers': {
                            'x-csrf-token': _this.closest('form').attr('data-x-csrf-token')
                        },
                        'error': function (xhr) {
                            var _response = JSON.parse(xhr.responseText);
                            var _message = '<b>{0}</b><br>File :{1}<br>Line: {2}'.format(_response.message, _response.file, _response.line);
                            alert(_message, 'danger');
                        }
                    }
                });
            } else {
                _this.select2({
                    'width': '100%',
                    'allowClear': !this._RSTOIsRequired
                });
            }

            // Add event
            _this.on('change.select2', function (e) {
                var _form = _this.closest('form');
                //If form is reseting then avoid validation
                if (_form[0]._RSTOReseting === true) {
                    return;
                }
                var _val = parseInt(_this.val());
                var _isEmpty = _this.val() === null;
                // Determine validation status true | false | original
                var _isValid = _this[0]._RSTOIsValid = _val === _this.RSTOOriginalValue() ? 'original' : (_val !== null || (_isEmpty && !this._RSTOIsRequired));
                // Raise validation event
                _this.trigger('afterValidation.rsto', [_isValid]);
                // Add css class depend on validation status
                (_isValid === 'original' || (_isEmpty && !this._RSTOIsRequired)) ? _this.RSTORemoveValidationClass() : (_isValid ? _this.RSTOAddSuccess() : _this.RSTOAddError);
                // Form validation
                _form.RSTOValidate();
            });
        });
    },
    /**
     * Make datatable row selectable
     * @param {jQuery} datatableDOM : Table
     * @param {object} data : Row's data
     * @param {boolean} multiple : Multiple selection
     * @returns {jQuery}
     */
    RSTOSelectableDatatableRow: function (datatableDOM, data, multiple) {
        return this.each(function () {
            var _multiple = multiple || false;
            var _this = $(this);
            _this.click(function () {
                if (!_multiple && !_this.hasClass('selected')) {
                    datatableDOM.find('tr.selected').removeClass('selected');
                    _this.addClass('selected');
                    datatableDOM.trigger('selectionChanged.rsto', [data]);
                    datatableDOM[0]._RSTODatatableSelectedData = data;
                } else if (_multiple) {
                    _this.toggleClass('selected');
                    if (_this.hasClass('selected')) {
                        datatableDOM[0]._RSTODatatableSelectedData = data;
                    }
                    datatableDOM.trigger('selectionChanged.rsto', [data]);
                }
            });
        });
    },
    /**
     * Validate a form<br>
     * It determines his validations status by checking his element's validation status
     * @event afterValidation.rsto
     * @returns {void}
     */
    RSTOValidate: function () {
        return this.each(function () {
            var _this = $(this);
            if (_this[0].tagName.toLowerCase() === 'form') {
                var _isValid = true;
                var _isNotOriginal = false;
                $.each(_this.find('input,select,textarea'), function (i, e) {
                    var _element = $(e)[0];
                    if (_element._RSTOHasValidation) {
                        _isValid &= _element._RSTOIsValid === true || _element._RSTOIsValid === 'original';
                        _isNotOriginal |= _element._RSTOIsValid !== 'original';
                        //console.log('field:{0}, value:{1}, field is valid:{2}, form is not original:{3}, form is valid:{4}'.format(this.name, e.checked || $(e).val(), _element._RSTOIsValid, _isNotOriginal, _isValid));
                    }
                });
                var __isValid = _isValid && _isNotOriginal && _this[0]._RSTOExternalValidation();
                _this.trigger('afterValidation.rsto', [__isValid]);
                this._RSTOIsValid = __isValid;
            } else {
                throw "RSTOValidate works only on form DOM!";
            }
        });
    }
});

$(window).on('load', function () {
    // Initialize inputs with remote validation
    $('.remote-validation').RSTOAddRemoteValidation();
    // Initialize inputs with numeric validation
    $('.numeric-validation').RSTOAddValidation('numeric');
    // Initialize inputs with integer validation
    $('.integer-validation').RSTOAddValidation('integer');
    // Initialize inputs with integer validation
    $('.phone-number-validation').RSTOAddValidation('phone-number');
    // Initialize inputs with integer validation
    $('.email-validation').RSTOAddValidation('email');
    // Initialize inputs type text with no validation
    $('input[type="text"],textarea')
            .not('.remote-validation')
            .not('.numeric-validation')
            .not('.integer-validation')
            .not('.phone-number-validation')
            .not('.email-validation')
            .not('.date-validation')
            .RSTOAddValidation('text');
    // Initialize selects with remode data
    $('select').RSTOSelect2();
    // Initialize forms with custom submit
    $('form.rsto-form').RSTOForm().RSTOReset();
    // hide other modal when the actual modal is shown. It avoids modals superposition
    $('div.modal').attr('data-backdrop', 'static')
            .attr('data-keyboard', 'false')
            .on('show.bs.modal', function () {
                $('div.modal.in').not($(this)).each(function(){
                    $(this).addClass('hidden-temporarily').removeClass('in').css('display', 'none');
                    $(this).data('bs.modal').$backdrop.removeClass('in').css('display', 'none');
                })
            })
            .on('hide.bs.modal', function () {
                $('div.modal.hidden-temporarily').not($(this)).last().each(function(){
                    $(this).removeClass('hidden-temporarily').addClass('in').css('display', 'block').css('overflow', 'scroll');
                    $(this).data('bs.modal').$backdrop.addClass('in').css('display', 'block');
                })
            });

    // Initialize modals
    $('div.rsto-modal').RSTOModal();
});
