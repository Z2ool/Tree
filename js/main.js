/**
 * @author Petr Klezla
 * @date 10.2.2018
 */
$(document).ready(function () {
    "use-strict";
    $.extend({
        Tree: {
            data: [],
            render: function(){
                    if(Object.keys(this.data).length > 0) {
                        console.log('mazu a znovuvytvarim _cely_ strom');
                        $('.data').html("");
                        $.each(this.data, function (index, depth) {
                            console.log(index);
                            var html = '<div class="row border d-flex flex-nowrap" data-depth="' + index + '" id="' + index + '">';
                            html += '<div class="node w-5 font-weight-bold text-center">' + (index - 1) + '</div>';
                            $.each(depth, function (index2, node) {
                                html += '<div class="node text-center w-auto" data-parent="' + node.parent + '" data-id="' + node.id + '"><span class="fa fa-minus" aria-hidden="true"></span> [P:' + node.parent + ' ID:' + node.id + '] <span class="fa fa-plus" aria-hidden="true"></span> </div>';
                            });
                            html += '</div>';
                            $('.data').append(html);
                        })
                    }
                //}
            },
            init: function(){
                $.ajax({
                    url: $('.tree').data('data'),
                    dataType: 'json',
                    beforeSend: function (xhr) {
                    },
                    success: function(data)
                    {
                        this.data = data;
                        this.render();
                    }.bind(this)
                });
            },
            insert: function(id){
                $.ajax({
                    url: $('.tree').data('insert') + id + '/',
                    dataType: "json",
                    success: function(data)
                    {
                        if(data.error)
                        {
                            this.message(data.error,'alert-danger');
                        }
                        else
                        {
                            this.data = data;
                            this.render();
                            this.message('Do větve s ID: ' + id + " bylo přidáno dítě.");
                        }
                    }.bind(this)
                });
            },
            delete: function(id, parent){

                if(parent) {
                    $.ajax({
                        url: $('.tree').data('delete') + id + '/',
                        dataType: "json",
                        success: function (data) {
                            this.data = data;
                            this.render();
                            this.message('Nodes s ID: ' + id + ' bylo vymazáno.');
                        }.bind(this)
                    });
                }
                else
                {
                    this.message('Hlavní větev nejde vymazat.','alert-danger');
                }
            },
            message: function(text, type){
                type =  type || 'alert-success';
                $('.alert').html(text);
                $('.alert').addClass(type);
                setTimeout(function(){this.nomessage(type)}.bind(this),3000);
            },
            nomessage: function(type){
                $('.alert').removeClass(type);
                //$('.alert').html("");
            }
        }

    });

    $.Tree.init();

    $('.tree').on('click', '.fa-minus', function () {
        $.Tree.delete($(this).parent('div').data('id'),$(this).parent('div').data('parent'));
    }).on('click', '.fa-plus', function () {
        $.Tree.insert($(this).parent('div').data('id'));
    });


    $('.tree').on('submit','.ajax',function (e) {
        e.preventDefault();
        if((Number($('.ajax input').val()) > 0 && $('#' + $('.ajax input').val())) || $('.ajax input').val() == "")
        {
        //console.log($('.ajax input').val()=="");
            //console.log('insert');
            $.Tree.insert($('.ajax input').val());
        }
        else
        {
            $.Tree.message('Rodičovské ID: ' + $('.ajax input').val() + ' neexistuje','alert-danger');
        }

    });
});
