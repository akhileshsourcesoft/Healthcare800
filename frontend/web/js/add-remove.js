$(document).ready(function() {
    
    var inputs = 1; 
    
    $('#btnAdd').click(function() {
        $('.btnDel:disabled').removeAttr('disabled');
        var c = $('.clonedInput:first').clone(true);
            c.children(':text').attr('name','input'+ (++inputs) );
        $('.clonedInput:last').after(c);
    });
    
    $('.btnDel').click(function() {
        if (confirm('continue delete?')) {
            --inputs;
            $(this).closest('.clonedInput').remove();
            $('.btnDel').attr('disabled',($('.clonedInput').length  < 2));
        }
    });
    
    
});


