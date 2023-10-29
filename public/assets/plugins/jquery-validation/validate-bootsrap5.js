$.validator.setDefaults({
    errorElement: 'span',
    errorPlacement: function (error, element) {
        if (element.parents('.input-group').length > 0) {
            error.addClass('invalid-feedback');
            element.parents('.input-group').after(error);
        } else if (element.parents('.bootstrap-select').length > 0) {
            error.addClass('invalid-feedback');
            element.parents('.bootstrap-select').after(error);
        } else if (element.parents('.dropzone-wrapper').length > 0) {
            error.addClass('invalid-feedback');
            element.parents('.dropzone-wrapper').after(error);
        } else if (element.hasClass('select2-hidden-accessible')) {
            error.addClass('invalid-feedback');
            element.parent().find('.select2').after(error);
        } else if (element.parents('.datetimepicker-container').length > 0) {
            console.log(element.parents('.datetimepicker-container'));
            error.addClass('invalid-feedback');
            element.parents('.datetimepicker-container').after(error);
        } else if (element.parents('.tinymce-container').length > 0) {
            error.addClass('invalid-feedback');
            element.parent().find('.tox-tinymce').after(error);
        } else {
            error.addClass('invalid-feedback');
            element.after(error);
        }
    },
    highlight: function (element, errorClass, validClass) {        
        if ($(element).parents('.input-group').length > 0) {
            $(element).parents('.input-group').addClass('is-invalid');
        } else if ($(element).parents('.bootstrap-select').length > 0) {
            $(element).parents('.bootstrap-select').addClass('is-invalid');
        } else if ($(element).parents('.dropzone-wrapper').length > 0) {
            $(element).parents('.dropzone-wrapper').addClass('is-invalid');
        } else if ($(element).parents('.datetimepicker-container').length > 0) {
            $(element).parents('.datetimepicker-container').addClass('is-invalid');
        } else if ($(element).parents('.tinymce-container').length > 0) {
            $(element).parents('.tinymce-container').addClass('is-invalid');
        } else {
            $(element).addClass('is-invalid');
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        if ($(element).parents('.input-group').length > 0) {
            $(element).parents('.input-group').removeClass('is-invalid');
        } else if ($(element).parents('.bootstrap-select').length > 0) {
            $(element).parents('.bootstrap-select').removeClass('is-invalid');
        } else if ($(element).parents('.dropzone-wrapper').length > 0) {
            $(element).parents('.dropzone-wrapper').removeClass('is-invalid');
        } else if ($(element).parents('.datetimepicker-container').length > 0) {
            $(element).parents('.datetimepicker-container').removeClass('is-invalid');
        } else if ($(element).parents('.tinymce-container').length > 0) {
            $(element).parents('.tinymce-container').removeClass('is-invalid');
        } else {
            $(element).removeClass('is-invalid');
        }
    }
});

$.validator.addMethod("lessThan", function(value, element, params) {
    var startDate = new Date(value);
    var endDate = new Date($(params).val());
    
    // Compare the dates
    if (startDate < endDate) {
      return true; // Start date is less than end date
    }
    
    return false; // Start date is greater than or equal to end date
}, "Periode mulai harus lebih kecil dari periode selesai.");