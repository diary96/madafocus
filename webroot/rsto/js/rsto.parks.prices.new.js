const RSTOParkSellingEntranceFee = {
    modal: $('#rsto-park-selling-entrance-fee-modal'),
    form: $('#rsto-park-selling-entrance-fee-form'),
    fields: {
        park: $('#rsto-park-selling-entrance-fee-park'),
        currency: $('#rsto-park-selling-entrance-fee-currency'),
        adult: $('#rsto-park-adult-selling-entrance-fee'),
        children: $('#rsto-park-children-selling-entrance-fee')
    },
    edit: function(data){
        this.form.attr('data-edit', 'true');
        this.form.RSTODataURLQuery({'id_park_selling_entrance_fee': data.id}, 'data-edit-url');
        this.fields.park.val(data.id_park);
        this.fields.currency.RSTOOriginalValue(data.id_currency, data.currency_name);
        this.fields.adult.RSTOOriginalValue(data.adult);
        this.fields.children.RSTOOriginalValue(data.children);
        this.modal.modal('show');
    },
    init: function(){
        var _me = this;
        _me.form.on('submitted.rsto', function(e, response){
            if(response.success){
                var _editMode = _me.form.attr('data-edit', 'true') === 'true';
                _me.modal.modal('hide');
                alert(_editMode ? RSTOMessages.Updated : RSTOMessages.Added);
            } else {
                alert(RSTOMessages.Error);
            }
        });
    }
};