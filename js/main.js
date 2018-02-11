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
                        $('.data').html("");
                        // for demo purposes only there is no need to create fallbacks for obsolete browsers w/o support of 'outerHTML'
                        var depthTemplate = $('#depth-indicator-template').outerHTML;
                        var nodeTemplate = $('#node-template').outerHTML;
                        $.each(this.data, function (index, depth) {
                            console.log(index);
                            var html = depthTemplate;
                            // just a simplification for demo purposes.
                            // All the changes needed could be also defined in data returned in response
                            // or key => value pairs can be defined in array for update
                            html.replace('{depth}', index-1);
                            html.replace('{notADepth}', index);
                            // this one could be also updated one line later altering created jQuery object property:
                            html.replace('depth-indicator-template', index);
                            var $depthLevel = $(html);
                            $.each(depth, function (index2, node) {
                                var nodeHTML = nodeTemplate;
                                nodeHTML.replace('{parent}', node.parent);
                                nodeHTML.replace('{id}', node.id);
                                nodeHTML.replace('node-template', node.id);
                                $depthLevel.append(nodeHTML);
                            });
                            $('.data').append($depthLevel);
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
